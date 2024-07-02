<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Smtp;
use App\Models\GlobalMail;
use Illuminate\Support\Facades\Auth;

class SmtpController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(request()->ajax()) {
            return datatables()->of(Smtp::select('id','mailer_id'))
                ->addColumn('action', function($row){
                    $btn = '';
//                    if(Auth::user()->can('edit.smtp')){
                        $btn = '<a class="edit-smtp edit_form btn btn-icon btn-success mr-1 white" href="'.route('smtp.edit', ['smtp' => $row->id]).'" data-path="'.route('smtp.edit', ['smtp' => $row->id]).'" data-name="'.$row->name.'" data-id='.$row->id.' title="Edit"> <i class="fa fa-edit"></i> </a>';

//                    }
//                    if(Auth::user()->can('delete.smtp')){
                        $btn = $btn.'<a class="btn btn-icon btn-danger mr-1 white delete-smtp" data-id="'.$row->id.'" title="Delete"> <i class="fa fa-trash-o"></i> </a>';
//                    }
                    return $btn;
                })
                ->addIndexColumn()
                ->make(true);
        }
        $globalmail=GlobalMail::all();
        return view('smtp.index', ['smtp' => new Smtp(),'globalmail'=>$globalmail]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $globalmail=GlobalMail::all();
        return view('smtp.create', ['smtp' => new Smtp(),'globalmail'=>$globalmail]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $group = Smtp::create([
            'mailer_id' => $request->mailer_id,
            'transport_type' => $request->transport_type,
            'email' => $request->email,
            'password' => $request->password,
            'mail_server' => $request->mail_server,
            'port' => $request->port,
            'encryption_mode' => $request->encryption_mode,
            'imap_mail_server' => $request->imap_mail_server,
            'imap_port' => $request->imap_port,
            'imap_encryption_mode' => $request->imap_encryption_mode,
            'authentication_mode' => $request->authentication_mode ?? 'login',
            'sender_address' => $request->sender_address,
            'delivery_address' => $request->delivery_address,
            'created_by' => auth()->user()->id
        ]);
        return redirect()->route('smtp.index');
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
    public function edit(Smtp $smtp)
    {
        return view('smtp.create', ['smtp' => $smtp]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Smtp $smtp)
    {
        // $request->validate([
        //     'name' => 'required|unique:groups,name,'.$group->id
        // ]);
        $smtp->fill($request->all())->save();

        return redirect()->route('smtp.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $smtp = Smtp::find($request->id)->delete();
        if($smtp)
            return response()->json(['msg' => 'Smtp deleted successfully!']);

        return response()->json(['msg' => 'Something went wrong, Please try again'],500);
    }
}
