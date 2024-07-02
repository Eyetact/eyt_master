<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MailboxSetting;
use App\Repositories\ImapRepository;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendReplay;
use Illuminate\Support\Facades\Auth;
use App\Models\Mailbox;
use Config;
use Illuminate\Support\Str;


class MailsController extends Controller
{
    protected $checkConnection, $imapRepository, $flashRepository, $emailSetting, $method_demo, $user_id, $mail_data;
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user_id = auth()->user();
            $this->imapRepository = new ImapRepository;

            $id = $request->route('id');
            if ($id) {
                $user_role = $this->user_id->roles()->get()[0]->id;
                $mail_data = Mailbox::with(['smtps', 'imaps'])
                    ->where(['id' => $id])->first();
                $this->mail_data = $mail_data;
                // dd($this->mail_data);
                $this->checkConnection = $this->imapRepository->makeAccountAndConnection($mail_data);
            }
            return $next($request);
        });
    }

    public function index(Request $request)
    {
        $allMailbox = Mailbox::with('smtps')
            // ->where(['role_id' => $this->user_id->roles()->get()[0]->id])
            // ->orWhere('group_id', '!=', null)
            ->latest('id')->get();
        // dd($allMailbox,$this->user_id->roles()->get()[0]->id);
        return view('mails.all_mailbox', [
            'allMailbox' => $allMailbox
        ]);
    }

    public function mails(Request $request, $id)
    {

        //    return view('mails.mailbox-list',['total' => 10]);

        $folders = $this->checkConnection->getFolders($hierarchical = false);
        // dd($folders);
        $folders_array = array();
        if ($this->checkConnection->isConnected() == false) {
            $this->flashRepository->setFlashSession('alert-danger', 'Check the connection.');
            return false;
        }
        foreach ($folders as $key_folder => $folder) {
            $folder_data[$key_folder]['name'] = $folder->full_name;
        }
        $folder_inbox = $this->checkConnection->getFolderByPath('INBOX');

        $folder_inbox = $folder_inbox->query()->setFetchBody(false)->all();
        // dd($folder_inbox->count());
        $paginator_data = $folder_inbox->setFetchOrder("desc")->paginate($per_page = 10, $page = null, $page_name = 'imap_page');
        // dd($paginator_data);
        return view('mails.mailbox-list', [
            'messages' => $folders_array,
            'folder' => $folder_data,
            'inbox_data' => $paginator_data,
            'mail_data' => $this->mail_data,
            'total' => $folder_inbox->count()
        ]);
    }

    public function getDataByUID($id, $mail_id)
    {
        if ($this->checkConnection->isConnected() == false) {
            if (empty($this->emailSetting)) {
                return false;
            }
        }
        $folder = $this->checkConnection->getFolderByPath('INBOX');
        $message = $folder->query()->getMessageByUid($mail_id);
        $message->setFlag(['Seen', 'Flagged']);
        return view('mails/replay-mail', [
            'messages' => $message
        ]);
    }

    public function sendReply(Request $request, $id = '')
    {
        try {

            $file = '';
            if ($request->has('attachment')) {
                $file = $request->file('attachment');
                $file_name =  Str::random(7) . '.' . $file->getClientOriginalExtension();
                $destinationPath = "/mails";
                $file->move(public_path($destinationPath), $file_name);
                $file = 'mails/' . $file_name;
            }
            // dd($request->all());
            Mail::to($request->reply_email)->send(new SendReplay($request->body ,$request->subject, $file));

            return redirect('mails/'.$id);//->back();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function trashMails(Request $request, $id)
    {
        if ($this->checkConnection->isConnected() == false) {
            return false;
        }

        foreach ($request->check_mails as $key => $value) {
            $folder = $this->checkConnection->getFolderByPath('INBOX');
            $message = $folder->query()->getMessageByUid($value);
            // $trash_msg = $message->delete(true, "INBOX.Trash", true);
            $trash_msg = $message->move('[Gmail]/Trash');
            if (!empty($trash_msg)) {
                return response()->json([
                    'status' => 'success',
                ]);
            }
        }
    }

    public function spamMails(Request $request, $id)
    {
        if ($this->checkConnection->isConnected() == false) {
            return false;
        }

        foreach ($request->check_mails as $key => $value) {
            $folder = $this->checkConnection->getFolderByPath('INBOX');
            $message = $folder->query()->getMessageByUid($value);
            $trash_msg = $message->move('[Gmail]/Spam');
            if (!empty($trash_msg)) {
                return response()->json([
                    'status' => 'success',
                ]);
            }
        }
    }

    public function markAsunRead(Request $request, $id)
    {
        if ($this->checkConnection->isConnected() == false) {
            return false;
        }

        foreach ($request->check_mails as $key => $value) {
            $folder = $this->checkConnection->getFolderByPath('INBOX');
            $message = $folder->query()->getMessageByUid($value);
            $message->unsetFlag('Flagged');
        }
        return response()->json([
            'status' => 'success',
        ]);
    }

    public function markAsStarredsingle(Request $request, $id)
    {
        if ($this->checkConnection->isConnected() == false) {
            return false;
        }

        $folder = $this->checkConnection->getFolderByPath('INBOX');
        $message = $folder->query()->getMessageByUid($request->selected_val);
        $message->setFlag('flagged');

        return response()->json([
            'status' => 'success',
        ]);
    }

    public function markAsStarred(Request $request, $id)
    {
        if ($this->checkConnection->isConnected() == false) {
            return false;
        }

        foreach ($request->check_mails as $key => $value) {
            $folder = $this->checkConnection->getFolderByPath('INBOX');
            $message = $folder->query()->getMessageByUid($value);
            $message->setFlag('flagged');
        }
        return response()->json([
            'status' => 'success',
        ]);
    }

    public function markAsRead(Request $request, $id)
    {
        if ($this->checkConnection->isConnected() == false) {
            return false;
        }

        foreach ($request->check_mails as $key => $value) {
            $folder = $this->checkConnection->getFolderByPath('INBOX');
            $message = $folder->query()->getMessageByUid($value);
            $message->setFlag('seen');
        }
        return response()->json([
            'status' => 'success',
        ]);
    }

    public function fetchmails(Request $request, $id)
    {
        if ($this->checkConnection->isConnected() == false) {
            return false;
        }
        $folder_inbox = $this->checkConnection->getFolderByPath($request->mail_folder);
        if ($folder_inbox != null) {
            $paginator_data = $folder_inbox->query()->setFetchBody(false)->setFetchOrder("asc")->all()->paginate($per_page = 10, $page = null, $page_name = 'imap_page');
            $html_data = '';
            $html_data .= '<div class="list-group">';
            if ($paginator_data->count() > 0) {
                foreach ($paginator_data as $key => $value) {
                    // $html_data .= '<a href="'.url("/fetch") . '/' . $value->getUid().'" class="list-group-item">
                    //     <div class="checkbox">
                    //         <label>
                    //             <input type="checkbox" class="child ">
                    //         </label>
                    //     </div>
                    //     <span class="glyphicon glyphicon-star-empty"></span>
                    //     <span class="text-muted" style="font-size: 16px;">
                    //     - ';
                    //     $html_data .= $value->getSubject()[0];
                    //     $html_data .= '</span>
                    //     <span class="" style="font-size: 12px;">Attached ';
                    //     $html_data .= $value->getAttachments()->count();
                    //     $html_data .= ' files</span>
                    // </a>';

                    $html_data .= '<li class="media mail-read">
                            <div class="user-action">
                                <div class="checkbox-con ">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input child" id="' . $value->getUid() . '" data-id="' . $value->getUid() . '">
                                        <label class="custom-control-label" for="' . $value->getUid() . '"></label>
                                    </div>
                                </div>
                                <span class="favorite warning">
                                    <i class="feather icon-star"></i>
                                </span>
                            </div>
                            <div class="media-body">
                                <div class="user-details">
                                    <div class="mail-items">
                                        <a href="' . url('/fetch') . '/' . request()->id . '/' . $value->getUid() . '" class="">
                                            <span class="list-group-item-text text-truncate">
                                                <span class="text-muted" style="font-size: 16px;">
                                                    - ' . $value->getSubject()[0] . '
                                                </span>
                                                <span class="" style="font-size: 12px;">Attached ' . $value->getAttachments()->count() . '
                                                    files</span>
                                            </span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </li>';
                }
                $html_data .= '</div>';
            } else {
                $html_data = 'No records found.';
            }
            return response()->json([
                'data' => $html_data,
                'status' => 'success',
            ]);
        } else {
            return response()->json([
                'data' => 'No records found.',
                'status' => 'success'
            ]);
        }
    }
}
