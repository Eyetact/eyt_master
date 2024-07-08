<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\MyComponentsResource;
use App\Models\Admin\Component;
use App\Models\Admin\ComponentsSet;
use App\Models\Admin\Element;
use App\Models\Admin\Software;
use Illuminate\Http\Request;
use App\Repositories\Repository;
use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Http\Controllers\ApiController;
use App\Http\Resources\ComponentResource;
use App\Http\Resources\ElementResource;
use App\Http\Resources\ComponentDataResource;


class ComponentController extends ApiController
{
    public function __construct()
    {
        $this->resource = ComponentResource::class;
        $this->model = app(Component::class);
        $this->repositry = new Repository($this->model);
    }

    public function save(Request $request)
    {

        $machine = Software::find($request->machine_id);

        // dd($machine->main_part_id);

        $model = new Component();
        $model->compo_name = $request->name;
        $model->element_id = $request->element_id;
        $model->compo_concentration = $request->concentration;
        // $model->unit_id = $request->unit_id;
        $unit_id = Element::find($request->element_id)->unit_id;
        $model->unit_id = $unit_id;
        $model->compo_carrier = $request->compo_carrier;
        $model->main_part_id = $machine->main_part_id;



        // $decodedCategories = is_array($request->compo_category) ? $request->compo_category : json_decode($request->compo_category, true);
        // if (!is_array($decodedCategories)) {
        //     return $this->returnError(__('Invalid compo_category data.'));
        // }

        $setCategories = [];
        $index = 1;
        foreach ($request->compo_category as $item) {
            if (!isset($item['name'])) {
                return $this->returnError(__('Invalid category name.'));
            }

            $setCategories[$index] = [
                'id' => $item['id'],
                'name' => $item['name'],
                'minimum' => $item['minimum'],
                'maximum' => $item['maximum'],
                'default' => $item['default'],
            ];
            $index++;
        }



        $model->compo_category = $setCategories;
        $model->user_id = auth()->user()->id;
        $model->save();


        if ($model) {


            $component_set = ComponentsSet::where('main_part_id', $machine->main_part_id)
                ->where('user_id', auth()->user()->id)
                ->latest()
                ->first();

            if ($component_set) {
                $existingComponents = json_decode($component_set->set_component, true);

                if (!is_array($existingComponents)) {
                    $existingComponents = [];
                }

                $newComponent = [
                    'id' => $model->id,
                    'name' => $model->compo_name,
                ];

                if (!is_array($newComponent) || empty($newComponent['name'])) {
                    return $this->returnError(__('Invalid component data.'));
                }

                // Find the highest numeric key in the existing components
                $maxNumericKey = max(array_keys($existingComponents));

                // Assign the next sequential numeric key to the new component
                $newNumericKey = $maxNumericKey + 1;

                // Add the new component to the existing components with the new numeric key
                $existingComponents[$newNumericKey] = $newComponent;

                // Encode the JSON with the JSON_UNESCAPED_UNICODE option to remove backslashes
                // $encodedJson = json_encode($existingComponents, JSON_UNESCAPED_UNICODE);

                $component_set->set_component = $existingComponents;
                $component_set->save();


            }

            if (!$component_set) {

                $set = new ComponentsSet();
                $set->compo_set_name = "user selection";
                $set->main_part_id = $machine->main_part_id;



                $setComponents = []; // Initialize the array

                // Populate the array with the desired data
                $index = 1;
                $setComponents[$index] = [

                    'id' => $model->id,
                    'name' => $model->compo_name,
                ];

                $set->set_component = $setComponents;
                $set->user_id = auth()->user()->id;
                $set->save();

                $machine->components_set_id = $set->id;
                $machine->save();


            }





            return $this->returnData('data', new ComponentDataResource($model), __('Succesfully'));
        }

        return $this->returnError(__('Sorry! Failed to create !'));

    }

    public function edit($id, Request $request)
    {



        $model = Component::find($id);
        $machine = Software::find($request->machine_id);


        if ($model) {
            // dd($machine->main_part_id);

            $model->compo_name = $request->name;
            $model->element_id = $request->element_id;
            $model->compo_concentration = $request->concentration;
            $model->main_part_id = $machine->main_part_id;


            // $model->unit_id = $request->unit_id;
            $unit_id = Element::find($request->element_id)->unit_id;
            $model->unit_id = $unit_id;
            $model->compo_carrier = $request->compo_carrier;


            if (isset($request->compo_category)) {

                $setCategories = [];
                $index = 1;
                foreach ($request->compo_category as $item) {
                    if (!isset($item['name'])) {
                        return $this->returnError(__('Invalid category name.'));
                    }

                    $setCategories[$index] = [
                        'id' => $item['id'],
                        'name' => $item['name'],
                        'minimum' => $item['minimum'],
                        'maximum' => $item['maximum'],
                        'default' => $item['default'],
                    ];
                    $index++;
                }



                $model->compo_category = $setCategories;

            }



            if (isset($request['name'])) {

                $component_set = ComponentsSet::where('main_part_id', $model->main_part_id)
                    ->where('user_id', auth()->user()->id)
                    ->latest()
                    ->first();

                if ($component_set) {
                    $existingComponents = json_decode($component_set->set_component, true);

                    if (!is_array($existingComponents)) {
                        $existingComponents = [];
                    }

                    $collection = collect($existingComponents);


                    $collection = $collection->map(function ($component) use ($request, $model) {
                        if ($component['id'] == $model->id) {


                            $component['name'] = $request->name;


                        }
                        return $component;
                    });

                    // $encodedJson = $collection->toJson();

                    $component_set->set_component = $collection;
                    $component_set->save();
                }
            }

            $model->save();

            return $this->returnData('data', new ComponentDataResource($model), __('Succesfully'));
        }


        return $this->returnError(__('Sorry! Component not found !'));

    }

    public function components($id)
    {
        $machine = Software::find($id);
        $components = Component::where('main_part_id', $machine->main_part_id)
            ->where(function ($query) use ($machine) {
                $query->where('customer_id', auth()->user()->id)
                    ->orWhere('global', 1)
                    ->orWhere('user_id', auth()->user()->id)
                    ->orWhere('assign_id', auth()->user()->id);

                if ($machine->customer_group_id !== null) {
                    $query->orWhere('customer_group_id', $machine->customer_group_id);
                }
            })
            ->get();



        return $this->returnData('data', ComponentResource::collection($components), __('Get successfully'));

    }

    public function elements()
    {

        $elements = Element::all();



        return $this->returnData('data', ElementResource::collection($elements), __('Get successfully'));

    }

    public function view($id)
    {
        $model = $this->repositry->getByID($id);

        if ($model) {
            return $this->returnData('data', new ComponentDataResource($model), __('Get  succesfully'));
        }

        return $this->returnError(__('Sorry! Failed to get !'));
    }


    public function getComponentsByCategory(Request $request)
    {

        $machine = Software::find($request->machine_id);

        $componentSetId = $machine->components_set_id;
        $catId = $request->category_id;
        $components = collect([]);

        if ($componentSetId) {
            $components_set = ComponentsSet::find($componentSetId);

            if ($components_set) {
                $set_component = json_decode($components_set->set_component);
                $componentsId = collect($set_component)->pluck('id');

                foreach ($componentsId as $componentId) {
                    $component = Component::find($componentId);
                    $compo_category = json_decode($component->compo_category, true);
                    $compo_category_collection = collect($compo_category)->pluck('id');

                    if ($compo_category_collection->contains($catId)) {
                        // if ($compo_category_collection->contains($catId) && !$components->contains('id', $componentId)) {
                        $components->push($component);
                    }
                }
                return $this->returnData('data', ComponentResource::collection($components), __('Get successfully'));

            }
        }

    }



    public function show(Request $request)
    {
        $machine = Software::find($request->machine_id);
        $components_set = ComponentsSet::find($machine->components_set_id);

        $descriptions = [];
        $results = [];
        $delays = [];
        $components = collect([]);
        $matchedComponent = null;

        if ($components_set) {
            $set_component = json_decode($components_set->set_component);
            $componentIds = collect($set_component)->pluck("id");
            $main_pumps = json_decode($machine->main_part->main_pump);

            // Convert main_pumps to an array
            $main_pumps_array = get_object_vars($main_pumps);

            $indexMap = []; // To store the index for each component

            foreach ($componentIds as $index => $componentId) {
                $component = Component::find($componentId);
                if ($component) {
                    // Access the description from the JSON object
                    $description = collect($set_component)->firstWhere('id', $componentId)->describtion;
                    $descriptions[$componentId] = $description;

                    // Store the index for the component
                    $indexMap[$componentId] = $index + 1;

                    // Extract the flow rate for the current component
                    $flowRate = $main_pumps_array[$index + 1]->pump_flow;

                    // Calculate the result based on machine's main part type
                    if ($machine->main_part->main_type != 'Mixing With Carrier') {
                        $result = 1000 / $component->compo_concentration * $component->compo_value;
                    } else {
                        // For 'mixing with carrier', calculate for all except the first component
                        if ($index == 0) {
                            $result = null; // Placeholder for the first component
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

                    // Check if this is the component we're interested in
                    if ($componentId == $request->id) {
                        $matchedComponent = $component;
                    }
                }
            }

            // Calculate the result for the first component if main_type is 'mixing with carrier'
            if ($machine->main_part->main_type == 'Mixing With Carrier' && $components->isNotEmpty()) {
                $sumOfOtherResults = collect($results)->filter()->sum();
                $firstComponentId = $components->first()->id;
                $results[$firstComponentId] = 1000 - $sumOfOtherResults;

                // Calculate delay for the first component
                $flowRate = $main_pumps_array[1]->pump_flow; // The first pump in the original object
                $delays[$firstComponentId] = ($results[$firstComponentId] * 60 / $flowRate) * 1000;

                // Check if the first component is the one we're interested in
                if ($firstComponentId == $request->id) {
                    $matchedComponent = $components->first();
                }
            }
        }

        // Add descriptions to the matched component object
        if ($matchedComponent) {
            $matchedComponent->description = $descriptions[$matchedComponent->id] ?? null;
            $matchedComponent->json_index = $indexMap[$matchedComponent->id] ?? null;
            $matchedComponent->result = isset($results[$matchedComponent->id]) ? number_format($results[$matchedComponent->id], 3) : null; // Format to 3 decimal places
            $matchedComponent->delay = isset($delays[$matchedComponent->id]) ? number_format($delays[$matchedComponent->id], 3) : null; // Format to 3 decimal places
        }

        return $this->returnData('data', new MyComponentsResource($matchedComponent), __('Get successfully'));
    }
}
