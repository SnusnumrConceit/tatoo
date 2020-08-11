<?php

namespace App\Http\Resources\Admin\Role;

use Illuminate\Http\Resources\Json\JsonResource;

class RoleDetail extends JsonResource
{
    public static $wrap = 'role';
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'name' => $this->name,
            'slug' => $this->slug,
            'is_protected' => $this->is_protected
        ];
    }
    
    public function withResponse($request, $response)
    {
        $response->setStatusCode(200);
    }
}
