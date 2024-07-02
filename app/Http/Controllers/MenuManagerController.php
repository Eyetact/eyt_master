<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MenuManager;
use App\Models\Module;
use App\Http\Requests\ModulePostRequest;
use App\Repositories\FlashRepository;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class MenuManagerController extends Controller
{
    private $flashRepository;

    public function __construct()
    {
        $this->flashRepository = new FlashRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data=array();
        $moduleData=Module::get();
        return view('module.menu', ['menu' => new MenuManager(), 'data' => $data,'moduleData'=>$moduleData]);
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
    public function store(ModulePostRequest $request)
    {
        $request->validated();
        $requestData=$request->all();
        $lastSequenceData=MenuManager::where('parent','0')->where('menu_type',$requestData['menu_type'])->where('include_in_menu',1)->where('status',1)->orderBy('id','desc')->first();
        $sequence=0;
        if($lastSequenceData){
            $sequence=$lastSequenceData->sequence+1;
        }

        $createData=array(
            'name' => $requestData['name'],
            'module_id' => (isset($requestData['module']) ?? ''),
            'status'=> (isset($requestData['is_enable']) ?? 0),
            'include_in_menu'=> (isset($requestData['include_in_menu']) ?? 0),
            'menu_type' => $requestData['menu_type'],
            'code' => $requestData['code'],
            'path' => $requestData['path'],
            'meta_title' => (isset($requestData['meta_title']) ?? ''),
            'meta_description' => (isset($requestData['meta_description']) ?? ''),
            'assigned_attributes' => $requestData['assigned_attributes'],
            'sequence' => $sequence,
            'parent' => 0,
            'created_date' => date('Y-m-d',strtotime($requestData['created_date']))
        );
        // dd($createData);
        $menuManager = MenuManager::create($createData);

        // if (!$menuManager) {
        //     $this->flashRepository->setFlashSession('alert-danger', 'Something went wrong!.');
        //     return redirect()->route('menu.index');
        // }
        // $this->flashRepository->setFlashSession('alert-success', 'Menu Item created successfully.');
        // return redirect()->route('menu.index');
        echo 1;exit;
    }

    public function menu_update(Request $request){
        if($request->type=='storfront'){
            $dataArray = json_decode($request['storfront_json'], true);
        }else{
            $dataArray = json_decode($request['admin_json'], true);
        }
        // dd($request->all(),$dataArray);
        $data=$this->processArray($dataArray);
        return redirect()->route('menu.index');

    }

    public function processArray($dataArray) {
        foreach ($dataArray as $item) {
            $data=MenuManager::find($item['id']);
            $data->sequence=$item['sequence'];
            $data->parent=$item['parent'];
            $data->save();
            // Check if there are children and recursively process them
            if (isset($item['children']) && is_array($item['children']) && count($item['children']) > 0) {
                $this->processArray($item['children']);
            }
        }
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
    public function destroy($id)
    {
        //
    }
}
