<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Admin\Aaa;
use App\Http\Requests\Admin\{StoreAaaRequest, UpdateAaaRequest};
use App\Models\UCGroup;
use App\Models\Attribute;
use Yajra\DataTables\Facades\DataTables;
use App\Services\GeneratorService;
use App\Models\Module;
use App\Generators\GeneratorUtils;




class AaaController extends Controller
{
    private $generatorService;

    public function __construct()
    {
        $this->generatorService = new GeneratorService();

    }

    /**
     * Display a listing of the resource.
     *
     * @return
     */
    public function index()
    {



        if (request()->ajax()) {
            $aaas = Aaa::query();

               if (auth()->user()->access_table == "Group") {
                $group_ids = auth()->user()->groups()->pluck('group_id');

                $userids= UCGroup::whereIn('group_id', $group_ids)
                ->pluck('user_id');



                $aaas = Aaa::whereIn('user_id', $userids)->get();
            }

            if (auth()->user()->access_table == "Individual") {

                $aaas = Aaa::where('user_id', auth()->user()->id)->get();

            }

            return DataTables::of($aaas)
                ->addColumn('action', 'admin.aaas.include.action')
                ->toJson();
        }

        return view('admin.aaas.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return
     */
    public function create()
    {

        return view('admin.aaas.create');
    }


    public function createLess()
    {

        return view('admin.aaas.create-less');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return
     */
    public function store(StoreAaaRequest $request)
    {
        $insert = Aaa::create($request->validated());

        if( !empty($request->module) ){

            $module = Module::find($request->module);
            $modelName = "App\Models\Admin\\".GeneratorUtils::setModelName($module->code);

            $data = new $modelName();
            foreach ($module->fields as $value) {

                $attr = $value->code;

                $data->$attr = $request[$attr];
            }
            $data->save();

             $insert->sub_id = $request->module;
              $insert->data_id = $data->id;
            $insert->save();

        }

        $parent_id = Module::where('code','LIKE', '%' . 'Aaa' . '%' )->first()->parent_id;
        if($parent_id == 0){
            return redirect()
            ->route('aaas.index')
            ->with('success', __('The aaa was created successfully.'));
        }else{
            $parent = Module::find($parent_id);
            $parent = GeneratorUtils::cleanPluralLowerCase($parent->code);
            // dd($parent);
            return redirect()
                ->route($parent.'.index')
                ->with('success', __('The aaa  was created successfully.'));

        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Aaa  $aaa
     * @return
     */


     public function show(Aaa $aaa )
    {


        $module = Module::find($aaa->sub_id);
        $modelName = "App\Models\Admin\\".GeneratorUtils::setModelName($module->code);
        $child =  $modelName::find($aaa->data_id);


        return view('admin.aaas.show', compact('aaa','module','child'));
    }





    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Aaa  $aaa
     * @return
     */
    public function edit(Aaa $aaa)
    {
        return view('admin.aaas.edit', compact('aaa'));
    }

     public function editLess($id)
    {

        $aaa = Aaa::find($id);
        return view('admin.aaas.edit-less', compact('aaa'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Aaa  $aaa
     * @return
     */
    public function update(UpdateAaaRequest $request, Aaa $aaa)
    {
        $aaa->update($request->validated());


        if( !empty($request->module) ){

            $module = Module::find($request->module);
            $modelName = "App\Models\Admin\\".GeneratorUtils::setModelName($module->code);

                      if($request->module == $aaa->sub_id)
                      {

            $data =  $modelName::find($aaa->data_id);
            foreach ($module->fields as $value) {

                $attr = $value->code;

                $data->$attr = $request[$attr];
            }
            $data->save();
                      }

                      else{

                           $data = new $modelName();
            foreach ($module->fields as $value) {

                $attr = $value->code;

                $data->$attr = $request[$attr];
            }
            $data->save();

             $aaa->sub_id = $request->module;
              $aaa->data_id = $data->id;
            $aaa->save();

                      }


        }

             $parent_id = Module::where('code','LIKE', '%' . 'Aaa' . '%' )->first()->parent_id;
        if($parent_id == 0){
            return redirect()
            ->route('aaas.index')
            ->with('success', __('The aaa was updated successfully.'));
        }else{
            $parent = Module::find($parent_id);
            $parent = GeneratorUtils::cleanPluralLowerCase($parent->code);
            // dd($parent);
         return redirect()
            ->route('aaas.index')
            ->with('success', __('The aaa was updated successfully.'));

        }



    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Aaa  $aaa
     * @return
     */
    public function destroy(Aaa $aaa)
    {
        try {


            if($aaa->sub_id)
            {

            $module = Module::find($aaa->sub_id);
            $modelName = "App\Models\Admin\\".GeneratorUtils::setModelName($module->code);
            $child =  $modelName::find($aaa->data_id);
            $child->delete();
            }

            $aaa->delete();


            return response()->json(['msg' => 'Item deleted successfully!'], 200);
        } catch (\Throwable $th) {
            return response()->json(['msg' => 'Not deleted'], 500);
        }
    }
}
