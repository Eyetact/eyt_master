<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\CategoryDataResource;
use App\Http\Resources\ComponentResource;
use App\Http\Resources\MixtureDataResource;
use App\Http\Resources\MixtureResource;
use App\Http\Resources\MyComponentsResource;
use App\Models\Admin\Category;
use App\Models\Admin\Classification;
use App\Models\Admin\Component;
use App\Models\Admin\ComponentsSet;
use App\Models\Admin\Mixture;
use App\Models\Admin\Software;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use App\Repositories\Repository;
use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Http\Controllers\ApiController;
use App\Http\Resources\CategoryResource;



class CategoryController extends ApiController
{
    public function __construct()
    {
        $this->resource = CategoryResource::class;
        $this->model = app(Category::class);
        $this->repositry = new Repository($this->model);
    }

    public function save(Request $request)
    {

        $classification = new Classification();
        $classification->class_child = $request->name;
        $classification->save();

        $model = $this->repositry->save($request->all());
        $model->classification_id = $classification->id;
        $model->user_id = auth()->user()->id;

        $model->save();



        if ($model) {
            return $this->returnData('data', new $this->resource($model), __('Succesfully'));
        }

        return $this->returnError(__('Sorry! Failed to create !'));
    }

    public function edit($id, Request $request)
    {


        $model = Category::find($id);


        if ($model) {

            $classification = Classification::find($model->classification_id);
            $classification->class_child = $request->name;
            $classification->save();

            return $this->returnData('data', new $this->resource($model), __('Succesfully'));
        }

        return $this->returnError(__('Sorry! Category Not Found !'));
    }


    public function categories($id)
{
    $machine = Software::find($id);

    $categories = Category::where(function ($query) use ($machine) {
        $query->where('customer_id', auth()->user()->id)
            ->orWhere('user_id', auth()->user()->id)
            ->orWhere('assign_id', auth()->user()->id)
            ->orWhere(function ($query) {
                $query->where('global', 1)
                    ->where('status', 'active');
            });

        if ($machine->customer_group_id !== null) {
            $query->orWhere('customer_group_id', $machine->customer_group_id);
        }
    })
        ->get();

    return $this->returnData('data', CategoryResource::collection($categories), __('Get successfully'));
}

    // public function categories($id)
    // {
    //     $machine = Software::find($id);

    //     $categories = Category::where(function ($query) use ($machine) {
    //         $query->where('customer_id', auth()->user()->id)
    //             ->orWhere('user_id', auth()->user()->id)
    //             ->orWhere('assign_id', auth()->user()->id)
    //         ->orWhere(('global', 1)&&('status','active'));

    //         if ($machine->customer_group_id !== null) {
    //             $query->orWhere('customer_group_id', $machine->customer_group_id);
    //         }
    //     })
    //         ->get();
    //         // dd($categories);


    //     return $this->returnData('data', CategoryResource::collection($categories), __('Get successfully'));

    // }



    public function myLists(Request $request)
    {

        // if ($request->name == "categories") {

        //     $machine = Software::find($request->machine_id);
        //     $categories = collect([]);


        //     if( $machine->components_set_id != NULL){

        //     $components_set = ComponentsSet::find($machine->components_set_id);
        //     if( $components_set){

        //         $set_component = json_decode($components_set->set_component);
        //         $categoryIds = collect($set_component)->pluck('id');
        //         // dd($categoryIds);

        //         foreach ($categoryIds as $categoryId) {
        //             $component = Component::find($categoryId);
        //             $compo_category = json_decode($component->compo_category, true);
        //             $compo_category_collection = collect($compo_category)->pluck('id');

        //             foreach ($compo_category_collection as $categoryId) {
        //                 $category = Category::find($categoryId);
        //                 if ($category && !$categories->contains('id', $category->id)) {
        //                     $categories->push($category);
        //                 }
        //             }

        //         }

        //     }

        //     }




        //     return $this->returnData('data',  CategoryResource::collection($categories), __('Get successfully'));
        // }

        if ($request->name == "categories") {
            $machine = Software::find($request->machine_id);
            $components_set = ComponentsSet::find($machine->components_set_id);

            $categories = [];

            if ($components_set) {
                $set_component = json_decode($components_set->set_component);
                $componentIds = collect($set_component)->pluck("id");

                foreach ($componentIds as $componentId) {
                    $component = Component::find($componentId);
                    if ($component) {
                        $category = json_decode($component->compo_category, true);
                        if (is_array($category)) {
                            $categories = array_merge($categories, $category);
                        } elseif ($category) {
                            $categories[] = $category;
                        }
                    }
                }
            }

            $categoriesWithImages = [];

            foreach ($categories as $categoryId) {
                $categoryObject = Category::find($categoryId['id']);

                if ($categoryObject) {
                    $categoryId['category_image'] = $categoryObject->category_image;
                }

                $categoriesWithImages[] = $categoryId;
            }

            // dd($categoriesWithImages);


            return $this->returnData('data', ['categories' => $categoriesWithImages], __('Get successfully'));
        }
        if ($request->name == "components") {


            $machine = Software::find($request->machine_id);
            $components_set = ComponentsSet::find($machine->components_set_id);

            $descriptions = [];
            $results = [];
            $delays = [];
            $components = collect([]);

            if( $components_set){
            $set_component = json_decode($components_set->set_component);
            $componentIds = collect($set_component)->pluck("id");
            $main_pumps = json_decode($machine->main_part->main_pump);



        $main_pumps_array = get_object_vars($main_pumps);

            $indexMap = []; // To store the index for each component

            // foreach ($componentIds as $componentId) {
                foreach ($componentIds as $index => $componentId) {

                $component = Component::find($componentId);
                if ($component) {


                      $description = collect($set_component)->firstWhere('id', $componentId)->describtion;
                      $descriptions[$componentId] = $description;


                        $indexMap[$componentId] = $index + 1;



       $flowRate = $main_pumps_array[$index + 1]->pump_flow;


                if ($machine->main_part->main_type != 'Mixing With Carrier') {
                    $result = 1000 / $component->compo_concentration * $component->compo_value;
                } else {

                    if ($index == 0) {

                        $result = null;
                    } else {
                        $result = 1000 / $component->compo_concentration * $component->compo_value;
                    }
                }
                $results[$componentId] = $result;


                    // Calculate delay
                    if ($result !== null) {
                        $delay = ($result * 60 / $flowRate) * 1000;
                        $delays[$componentId] = $delay;
                    }

                    $components->push($component);
                }
            }



        if ($machine->main_part->main_type == 'Mixing With Carrier') {
            $sumOfOtherResults = collect($results)->filter()->sum();
            $firstComponentId = $components->first()->id;
            $results[$firstComponentId] = 1000 - $sumOfOtherResults;



                    $flowRate = $main_pumps_array[1]->pump_flow;
                    $delays[$firstComponentId] = ($results[$firstComponentId] * 60 / $flowRate) * 1000;
        }

        }



    $components = $components->map(function ($component) use ($descriptions, $indexMap, $results, $delays) {
        $component->description = $descriptions[$component->id] ?? null;
        $component->json_index = $indexMap[$component->id] ?? null;
        $component->result = isset($results[$component->id]) ? number_format($results[$component->id], 3) : null; // Format to 3 decimal places
        $component->delay = isset($delays[$component->id]) ? number_format($delays[$component->id], 3) : null; // Format to 3 decimal place
        return $component;
    });

            return $this->returnData('data', MyComponentsResource::collection($components), __('Get successfully'));
        }

        if ($request->name == "mixtures") {


            $machine = Software::find($request->machine_id);


            $mixtures =$machine->mixtures;



            return $this->returnData('data', MixtureDataResource::collection($mixtures), __('Get successfully'));



        }

    }

}

