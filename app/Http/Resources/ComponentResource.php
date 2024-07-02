<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ComponentResource extends JsonResource
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
            'categories'=>json_decode($this->compo_category, true),
            'concentration' => $this->compo_concentration,
            'description' => $this->description, // Include the description field


        ];
    }
}
