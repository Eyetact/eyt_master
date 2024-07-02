<?php

namespace App\Http\Resources;

use App\Models\Admin\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryDataResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {


        return[
        'categories' => $this->resource,
        ];



    }

}
