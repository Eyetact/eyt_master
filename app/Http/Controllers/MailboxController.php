<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mailbox;
use Illuminate\Support\Facades\Auth;
use App\Repositories\ImapRepository;
use App\Models\Smtp;
//use Spatie\Permission\Models\Role;
use App\Models\Group;

use Mail;
use Config;

class MailboxController extends Controller
{
    protected $checkConnection, $imapRepository, $flashRepository, $emailSetting, $method_demo, $user_id;
    public function __construct() {
        $this->middleware(function ($request, $next) {
            $this->user_id = auth()->user();
            $this->imapRepository = new ImapRepository;

//            $user_role = $this->user_id->roles()->get()[0]->id;
            $mail_data = Mailbox::with('smtps')
//                ->where(['role_id' => $user_role])
                ->latest('id')->first();
            $data = $mail_data;

            $this->checkConnection = $this->imapRepository->makeAccountAndConnection($data,'crud');
            return $next($request);
        });
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $emailSetting = Mailbox::with('smtps')->latest('id')->first();

        // Mail::raw('bonjour ', function($message) {
        //     $message->subject('Email de test')
        //             ->to('pragnab.itpath@gmail.com');
        // });

        if(request()->ajax()) {
            $mailboxData=Mailbox::select('id','mailer_id','mailbox_name','smtp','imap')->with(['smtps','imaps']);
            // dd($mailboxData);
            return datatables()->of($mailboxData)
                ->addColumn('smtp', function($row){
                    return $row->smtps->mailer_id;
                })
                ->addColumn('imap', function($row){
                    return $row->imaps->mailer_id;
                })
                ->addColumn('action', function($row){
                    $btn = '';
//                    if(Auth::user()->can('edit.mainmailbox')){
                        // $btn = '<a class="btn-default  edit-mailbox edit_form" data-path="'.route('main_mailbox.edit', ['main_mailbox' => $row->id]).'"> <button><i class="fa fa-edit"></i></button> </a>';
                        $btn = '<a class="edit-mailbox edit_form btn btn-icon btn-success mr-1 white" href="'.route('main_mailbox.edit', ['main_mailbox' => $row->id]).'" data-path="'.route('main_mailbox.edit', ['main_mailbox' => $row->id]).'" data-name="'.$row->name.'" data-id='.$row->id.' title="Edit"> <i class="fa fa-edit"></i> </a>';
//                    }
//                    if(Auth::user()->can('delete.mainmailbox')){
                        // $btn = $btn.'<button type="submit" class=" btn-danger delete-mailbox" data-id="'.$row->id.'"><i class="fa fa-trash-o"></i>';
                        $btn = $btn.'<a class="btn btn-icon btn-danger mr-1 white delete-mailbox" data-id="'.$row->id.'" title="Delete"> <i class="fa fa-trash-o"></i> </a>';
//                    }

                    return $btn;
                })
                ->addIndexColumn()
                ->make(true);
        }
        $smtps=Smtp::All();
//        $groups=Group::All();
        return view('mailbox.index', ['mailbox' => new Mailbox(),'smtps'=>$smtps]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $smtps=Smtp::All();
        // dd($smtps);
//        $roles = Role::all();
        $groups=Group::All();
        return view('mailbox.create', ['mailbox' => new Mailbox(),'smtps'=>$smtps]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $group = Mailbox::create([
            'mailer_id' => $request->mailer_id,
            'mailbox_name' => $request->mailbox_name,
            'transport_type' => $request->transport_type,
            // 'mail_server' => $request->mail_server,
            // 'email' => $request->email,
            // 'password' => $request->password,
            // 'port' => $request->port,
            // 'encryption_mode' => $request->encryption_mode,
            // 'authentication_mode' => $request->authentication_mode ?? 'login',
            // 'sender_address' => $request->sender_address,
            // 'delivery_address' => $request->delivery_address,
            'created_by' => auth()->user()->id,
            'smtp' => $request->smtp,
            'imap' => $request->imap,
           'role_id'=>1,
//            'group_id'=>$request->group_id
        ]);
        return redirect()->route('main_mailbox.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Mailbox $mailbox,$id)
    {
        $mailbox=Mailbox::find($id);
        $smtps=Smtp::All();
//        $roles = Role::all();
//        $groups=Group::All();
        return view('mailbox.create', ['mailbox' => $mailbox,'smtps'=>$smtps]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Mailbox $main_mailbox)
    {
        // $request->validate([
        //     'name' => 'required|unique:groups,name,'.$group->id
        // ]);
        $main_mailbox->fill($request->all())->save();
        // dd($request->all(),$main_mailbox);
        return redirect()->route('main_mailbox.index');
    }

    public function destroy(Request $request)
    {
        $mailbox = Mailbox::find($request->id)->delete();
        if($mailbox)
            return response()->json(['msg' => 'Mailbox deleted successfully!']);

        return response()->json(['msg' => 'Something went wrong, Please try again'],500);
    }
}
