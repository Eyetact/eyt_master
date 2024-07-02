<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ConfigResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [


            'current_subscription' => $this->subscriptions()->where('status','active')->orderBy('created_at', 'desc')->first(),
            // 'permissions' => PermissionResource::collection($this->subscriptions()->where('status','active')->orderBy('created_at','desc')->first()?->plan->permissions),
            'permissions'=> $this->subscriptions()->where('status','active')->orderBy('created_at', 'desc')->first()?->plan?->permissions->pluck('name')->toArray(),
            'app_version' =>config('app.app_version')




        ];
    }
}
