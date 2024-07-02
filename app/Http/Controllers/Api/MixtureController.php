<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\MixtureDataResource;
use App\Models\Admin\ComponentsSet;
use App\Models\Admin\Mixture;
use App\Models\Admin\Software;
use App\Models\Order;
use App\Models\SoftMixture;
use Illuminate\Http\Request;
use App\Repositories\Repository;
use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Http\Controllers\ApiController;
use App\Http\Resources\MixtureResource;

class MixtureController extends ApiController
{
    public function __construct()
    {
        $this->resource = MixtureResource::class;
        $this->model = app(Mixture::class);
        $this->repositry = new Repository($this->model);
    }

    public function save(Request $request)
    {

        $machine = Software::find($request->machine_id);
        $model = new Mixture();
        $model->components_set_id = $machine->components_set_id;
        $model->mix_name = $request->name;
        $model->category_id = $request->category_id ?? NULL;

        $mixComponenets = [];
        $index = 1;
        foreach ($request->mix_component as $item) {
            if (!isset ($item['name'])) {
                return $this->returnError(__('Invalid component name.'));
            }

            $mixComponenets[$index] = [
                'id'=> $item['id'],
                'name' => $item['name'],
                'value' => $item['value'],

            ];
            $index++;
        }


        $model->mix_component = $mixComponenets;
        $model->user_id = auth()->user()->id;
        $model->save();

        if ($model) {


            $softMix= new SoftMixture();
            $softMix->software_id =  $machine->id;
            $softMix->mixture_id = $model->id;
            $softMix->save();



            return $this->returnData('data', new MixtureDataResource($model), __('Succesfully'));
        }

        return $this->returnError(__('Sorry! Failed to create !'));




    }

    public function edit($id, Request $request)
    {


        $machine = Software::find($request->machine_id);
        $model = Mixture::find($id);
        $model->components_set_id = $machine->components_set_id;
        $model->mix_name = $request->name;
        $model->category_id = $request->category_id ?? NULL;


        if (isset ($request->mix_component)) {

            $mixComponenets = [];
            $index = 1;
            foreach ($request->mix_component as $item) {
                if (!isset ($item['name'])) {
                    return $this->returnError(__('Invalid component name.'));
                }

                $mixComponenets[$index] = [
                    'id' => $item['id'],
                    'name' => $item['name'],
                    'value' => $item['value'],

                ];
                $index++;
            }



            $model->mix_component = $mixComponenets;

        }


        $model->user_id = auth()->user()->id;
        $model->save();

        if ($model) {
            return $this->returnData('data', new MixtureDataResource($model), __('Succesfully'));
        }

        return $this->returnError(__('Sorry! Failed to create !'));


    }

    public function mixtures($id)
    {
        $machine = Software::find($id);
        $component_id = ComponentsSet::where('main_part_id', $machine->main_part_id)
            ->first()->id;
        // dd($component_id);


        $mixtures = Mixture::where('components_set_id', $component_id)
            ->where(function ($query) use ($machine) {
                $query->where('customer_id', auth()->user()->id)
                    ->orWhere('user_id', auth()->user()->id)
                    ->orWhere('assign_id', auth()->user()->id);

                if ($machine->customer_group_id !== NULL) {
                    $query->orWhere('customer_group_id', $machine->customer_group_id);
                }
            })
            ->get();


        // dd($mixtures);




        return $this->returnData('data', MixtureDataResource::collection($mixtures), __('Get successfully'));

    }


    public function view($id)
    {
        $model = $this->repositry->getByID($id);

        if ($model) {
            return $this->returnData('data', new MixtureDataResource( $model ), __('Get  succesfully'));
        }

        return $this->returnError(__('Sorry! Failed to get !'));
    }



    public function getMixByCategory(Request $request)
    {


        $machine = Software::find($request->machine_id);




        $mixtures = $machine->mixtures()->where('components_set_id', $machine->components_set_id)
                                      ->where('category_id',$request->category_id)
                                      ->get();
                                    //   ->unique();





        return $this->returnData('data', MixtureDataResource::collection($mixtures), __('Get successfully'));

    }

    public function makeOrder(Request $request)
    {


       $order= new Order();
       $order->software_id = $request->machine_id;
       $order->mixture_id = $request->mixture_id;
       $order->save();


       return $this->returnSuccessMessage('Done');

    }


}
