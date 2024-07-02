<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ComponentDataResource extends JsonResource
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
            'concentration' => $this->compo_concentration,
            'unit_id' => $this->unit?->id,
            'unit_name'=> $this->unit?->unit_code,
            'component_carrier' => $this->compo_carrier,
            'main_part_id' => $this->main_part_id,
            'categories'=>json_decode($this->compo_category, true),

        ];
    }
}
