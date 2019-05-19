<?php

namespace App\Http\Resources\Admin\User;

use Illuminate\Http\Resources\Json\JsonResource;

class UserVuex extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'full_name' => $this->last_name.' '.$this->first_name,
            'role' => $this->role[0]->slug,
            'csrf_token' => $this->getCSRF($this->role[0]->slug),
        ];
    }

    public function getCSRF($slug)
    {
        switch ($slug) {
            case 'superadmin': return csrf_token(); break;
            case 'admin': return csrf_token(); break;
            default: return ''; break;
        }
    }
}
