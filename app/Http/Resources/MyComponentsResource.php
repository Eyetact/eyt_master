<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MyComponentsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [

            'id' => $this->id,
            'name' => $this->compo_name,
            'element_id' => $this->element?->id,
            'element_name'=> $this->element?->element_name,
            'unit_id' => $this->unit?->id,
            'unit_name'=> $this->unit?->unit_code,
            'component_carrier' => $this->compo_carrier,
            'categories'=>json_decode($this->compo_category, true),
            'concentration' => $this->compo_concentration,
            'description' => $this->description, // Include the description field
            'pin_number' => $this->json_index, // Include the index field from JSON structure
            'result' => $this->result, // Include the calculated result field
            'delay' => $this->delay, // Include the calculated delay field


        ];
    }
}



