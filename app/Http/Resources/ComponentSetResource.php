<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ComponentSetResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
           // Decode the 'set_component' JSON data
           $decodedComponents = json_decode($this->set_component, true);

        //    Extract the 'name' and 'index' attributes from the JSON
           $components = collect($decodedComponents)->map(function ($item) {

               $name = isset($item['name']) ? $item['name'] : null;

               return [

                   'name' => $name,
               ];
           });

           return [
               'id' => $this->id,
               'name' => $this->compo_set_name,
               'main_part_id' => $this->main_part_id,
               'components' => $components,
           ];




    }


}
