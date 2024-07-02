<?php

namespace App\Http\Controllers;

use App\Http\Requests\UrlRequest;
use App\Models\Setting;
use Auth;
use Illuminate\Http\Request;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use App\Models\SocialMediaSetting;
use App\Models\ContactSetting;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data=Setting::where('created_by',auth()->id())->first();
        if(empty($data)){
            $data=new Setting();
        }

        $socialMediaData=SocialMediaSetting::where('created_by',auth()->id())->get();
        if(empty($socialMediaData)){
            $socialMediaData=new SocialMediaSetting();
        }

        $contactSettingData=ContactSetting::where('created_by',auth()->id())->first();
        if(empty($contactSettingData)){
            $contactSettingData=new ContactSetting();
        }

        return view('setting.index',['data'=>$data,'socialMediaData'=>$socialMediaData,'contactSettingData'=>$contactSettingData]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
//         dd($request->all());
        $ContactSetting=ContactSetting::where('created_by',auth()->id())->first();
        if(!empty($ContactSetting)){
            $sData=$ContactSetting;
            $sData->email=$request->contact_email;
            $sData->phone=$request->contact_phone;
            $sData->address=$request->adress;
            $sData->latitude=$request->latitude;
            $sData->longitude=$request->longitude;
            $sData->save();
        }else{
            ContactSetting::create([
                'email'=>$request->contact_email,
                'phone'=>$request->contact_phone,
                'address'=>$request->adress,
                'latitude'=>$request->latitude,
                'longitude'=>$request->longitude,
                'created_by'=>auth()->id()
            ]);
        }

        $socialReq=$request->social;

        // dd( $socialReq );
        for($i=0;$i<count($socialReq);$i++){
            if(!isset($socialReq[$i]['id']) || $socialReq[$i]['id']==null){

                //image refactor icon will be social nam

                // $icon_file = $socialReq[$i]['icon'];
                // $icon = 'photo-' . time() . '.' . $icon_file->getClientOriginalExtension();
                // $destinationiconPath = public_path('uploads/socialmedia');
                // $icon_file->move($destinationiconPath, $icon);

                SocialMediaSetting::create([
                    'icon'=>$socialReq[$i]['icon'],
                    'title'=>$socialReq[$i]['icon'],
                    'url'=>$socialReq[$i]['url'],
                    'active'=>!empty( $socialReq[$i]['active'] ) ? 1 : 0,
                    'created_by'=>auth()->id()
                ]);
            }else{
                $updateArray=array(
                    'title'=>$socialReq[$i]['icon'],
                    'url'=>$socialReq[$i]['url'],
                    'icon'=>$socialReq[$i]['icon'],
                    'active'=>!empty( $socialReq[$i]['active'] ) ? 1 : 0,
                );
                // if(isset($socialReq[$i]['icon'])){
                //     $icon_file = $socialReq[$i]['icon'];
                //     $icon = 'photo-' . time() . '.' . $icon_file->getClientOriginalExtension();
                //     $destinationiconPath = public_path('uploads/socialmedia');
                //     $icon_file->move($destinationiconPath, $icon);
                //     $updateArray['icon']=$icon;
                // }
                SocialMediaSetting::where('id',$socialReq[$i]['id'])->update($updateArray);
            }
        }

        if($request->file('logo')){
            $logo_file = $request->file('logo');
            $logo = 'photo-' . time() . '.' . $request->file('logo')->getClientOriginalExtension();
            $destinationLogoPath = public_path('uploads/logo');
            $logo_file->move($destinationLogoPath, $logo);
        }

        if($request->file('dark_logo')){
            $dark_logo_file = $request->file('dark_logo');
            $dark_logo = 'photo-' . time() .time() . '.' . $request->file('dark_logo')->getClientOriginalExtension();
            $destinationdark_logoPath = public_path('uploads/logo');
            $dark_logo_file->move($destinationdark_logoPath, $dark_logo);
        }

        if($request->file('web_icon')){
            $web_icon_file = $request->file('web_icon');
            $web_icon = 'photo-' . time() . '.' . $request->file('web_icon')->getClientOriginalExtension();
            $destinationweb_icon = public_path('uploads/web_icon');
            $web_icon_file->move($destinationweb_icon, $web_icon);
        }

        if($request->file('footer_logo')){
            $footer_logo_file = $request->file('footer_logo');
            $footer_logo = 'photo-' . time() . '.' . $request->file('footer_logo')->getClientOriginalExtension();
            $destinationfooter_logo = public_path('uploads/footer_logo');
            $footer_logo_file->move($destinationfooter_logo, $footer_logo);
        }

        $Setting=Setting::where('created_by',auth()->id())->first();
        if(!empty($Setting)){
            $data=$Setting;
            $data->copyright_text = $request->copyright_text;
            $data->footer_text = $request->footer_text;
            $data->meta_keywords = $request->meta_keywords;
            $data->meta_description = $request->meta_description;
            $data->logo_text = $request->logo_text;
            $data->email = $request->email;
            $data->password = $request->password;
            $data->server = $request->mail_server;
            $data->port = $request->port;
            $data->domain = $request->domain;
            $data->encryption = $request->encryption_mode;
            if($request->file('logo')){
                $data->logo=$logo;
            }
            if($request->file('dark_logo')){
                $data->dark_logo=$dark_logo;
            }
            if($request->file('footer_logo')){
                $data->footer_logo=$footer_logo;
            }
            if($request->file('web_icon')){
                $data->web_icon=$web_icon;
            }

            $data->save();
        } else{
            Setting::create([
                'copyright_text' => $request->copyright_text,
                'footer_text' => $request->footer_text,
                'meta_keywords' => $request->meta_keywords,
                'meta_description' => $request->meta_description,
                'logo_text' => $request->logo_text,
                'email' => $request->email,
                'password' => $request->password,
                'server' => $request->mail_server,
                'port' => $request->port,
                'encryption' => $request->encryption_mode,
                'created_by'=>auth()->id(),
                'logo'=>$logo,
                'footer_logo'=>$footer_logo,
                'domain'=>$request->domain,
                'web_icon'=>$web_icon
            ]);
        }
        return redirect()->back();
    }

    public function countries()
    {
        if(request()->ajax()) {
            return datatables()->of(Country::select('*'))
                ->addColumn('DT_RowIndex', function($row){
                    // $image="https://ipdata.co/flags/".strtolower($row->sort).".png";
                    $image="https://flagcdn.com/48x36/".strtolower($row->sort).".png";
                    return "<img src='".$image."'>";
                })
                ->addColumn('status', function($row){
                    $checked=$row->status ? 'checked' : '';
                    $status="<input type='checkbox' class='status-".$row->id." statusCol' ".$checked." data-id='".$row->id."' data-status='".$row->status."'>";
                    return $status;
                })
                ->rawColumns(['DT_RowIndex','status'])
                // ->addIndexColumn()
                ->make(true);
        }
        return view('setting.countries', ['country' => new Country()]);
    }

    public function settingCountry($id,$status)
    {
        Country::where('id',$id)->update(['status'=>($status ? 0 : 1)]);
    }

    public function states()
    {
        if(request()->ajax()) {
            return datatables()->of(State::select('*'))
                ->addIndexColumn()
                ->make(true);
        }
        return view('setting.states', ['state' => new State()]);
    }

    public function cities()
    {
        if(request()->ajax()) {
            return datatables()->of(City::select('*'))
                ->addIndexColumn()
                ->make(true);
        }
        return view('setting.cities', ['city' => new City()]);
    }

    public function storeUrl( UrlRequest $request ){
        $setting = Setting::updateOrCreate([
            'created_by'   => Auth::user()->id,
        ],[
            'url'     => $request->main_url,
            'path'     => $request->path,
            'footer_logo' => '',
            'email' => '',
            'password' => '',
            'server' => '',
            'port' => 0,
            'encryption' => '',
        ]);

        return redirect()->back();
    }

}
