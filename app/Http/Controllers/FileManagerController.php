<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\Folder;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
// use Illuminate\Support\Facades\File;

class FileManagerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index( Request $request)
    {

        if (request()->ajax()) {
            return view('files.index-content');

        }

        if ($request->isMethod('post')) {
            $file = new File();

            $value = $request->file;
            $ext = $value->getClientOriginalExtension();
            $file->type = $ext;

            $file_name = time() . mt_rand(1000, 9000) . '.' . $ext;
            $file->name = $file_name;

            $value->move(public_path('uploads/users/'), $file_name);
            $file->path = 'uploads/users/' . $file_name;
            $file->folder_id = $request->folder_id;
            $file->user_id = Auth::user()->id;
            $file->save();

            if ($request->folder_id != 0) {
                $folder = Folder::find($request->folder_id);
                return view('files.index',compact('folder'));
            }

            return redirect()->route('files')
                ->with('success', 'File has been added successfully');

        }
        return view('files.index');
    }

    public function viewfolder($id)
    {
        $folder = Folder::find($id);
        return view('files.folder', compact('folder'));
    }

    public function newFolder(Request $request)
    {
        if ($request->isMethod('post')) {
            $folder = new Folder();
            $folder->name = $request->name;
            $folder->user_id = Auth::user()->id;
            $folder->save();

            return redirect()->route('files')
                ->with('success', 'Folder has been added successfully');

        }
        return view('files.new-folder');
    }

    public function newFile(Request $request)
    {
        if ($request->isMethod('post')) {
            $file = new File();

            $value = $request->file;
            $ext = $value->getClientOriginalExtension();
            $file->type = $ext;

            $file_name = time() . mt_rand(1000, 9000) . '.' . $ext;
            $file->name = $file_name;

            $value->move(public_path('uploads/users/'), $file_name);
            $file->path = 'uploads/users/' . $file_name;
            $file->folder_id = $request->folder_id;
            $file->user_id = Auth::user()->id;
            $file->save();

            if ($request->folder_id != 0) {
                $folder = Folder::find($request->folder_id);
                return view('files.index',compact('folder'));
            }

            return redirect()->route('files')
                ->with('success', 'File has been added successfully');

        }
        return view('files.new-file');
    }

    public function showFolder($id)
    {
        $folder = Folder::find($id);
        return view('files.show-folder', compact('folder'));
    }

    public function updateFolder(Request $request, string $id)
    {
        $folder = Folder::findOrFail($id);

        $folder->update($request->all());

        return redirect()->route('files')
            ->with('success', 'File has been updated successfully');
    }

    public function showFile($id)
    {
        $file = File::find($id);
        return view('files.show-file', compact('file'));
    }

    public function updateFile(Request $request, string $id)
    {
        $file = File::findOrFail($id);

        $value = $request->file;
        $ext = $value->getClientOriginalExtension();
        $file->type = $ext;

        $file_name = time() . mt_rand(1000, 9000) . '.' . $ext;
        $file->name = $file_name;

        $value->move(public_path('uploads/users/'), $file_name);
        $file->path = 'uploads/users/' . $file_name;
        $file->folder_id = 0;
        $file->user_id = Auth::user()->id;
        $file->save();

        return redirect()->route('files')
            ->with('success', 'File has been updated successfully');
    }

    public function destroyFolder($id)
    {
        if (Folder::find($id)->delete()) {
            return response()->json(['msg' => 'Folder deleted successfully!'], 200);
        } else {
            return response()->json(['msg' => 'Something went wrong, please try again.'], 200);
        }
    }

    public function destroyFile($id)
    {
        if (File::find($id)->delete()) {
            return response()->json(['msg' => 'File deleted successfully!'], 200);
        } else {
            return response()->json(['msg' => 'Something went wrong, please try again.'], 200);
        }
    }

    public function downloadFile($id)
    {
        $file = File::findOrFail($id);

        $filePath = public_path($file->path);

        return response()->download($filePath, $file->name);
    }

    public function shareFile($id)
    {
        $file = File::findOrFail($id);

        return view('files.share', compact('file'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function images($id)
    {
        $files = File::where('type', 'png')
            ->orWhere('type', 'gif')
            ->orWhere('type', 'jpeg')
            ->orWhere('type', 'jpg')
            ->where('user_id', $id)
            ->get();

        return view('files.files', compact('files'));
    }

    public function videos($id)
    {
        $files = File::where('type', 'mp4')

            ->get();

        return view('files.files', compact('files'));
    }

    public function search($key)
    {
        $files = File::where('name', 'like', '%' . $key . '%')->get();
        // dd($files);
        return view('files.files', compact('files'));
    }

    public function docs($id)
    {
        $files = File::where('type', 'pdf')
            ->orWhere('type', 'xlsx')
            ->orWhere('type', 'xls')
            ->orWhere('type', 'docx')
            ->orWhere('type', 'doc')
            ->orWhere('type', 'ppt')
            ->orWhere('type', 'pptx')
            ->get();

        return view('files.files', compact('files'));
    }

    public function music($id)
    {
        $files = File::where('type', 'mp3')
            ->orWhere('type', 'wav')
            ->orWhere('type', 'flac')
            ->get();

        return view('files.files', compact('files'));
    }

    public function openFile($id)
    {
        $file = File::findOrFail($id);
        return view('files.view', compact('file'));
    }

}
