<?php

namespace App\Http\Resources\Admin\User;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class User extends JsonResource
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
            'id' => $this->id,
            'name' => $this->getFullName($this),
            'email' => $this->email,
            'birthday' => $this->convert($this->birthday),
            'created_at' => $this->convert($this->created_at),
        ];
    }

    public function convert($date)
    {
        if (Carbon::now()->diffInDays($date) > 0) {
            return Carbon::parse($date)->format('d.m.Y');
        } else {
            return Carbon::parse($date)->diffForHumans();
        }

    }

    public function getFullName($user)
    {
        return $user->last_name.' '.$user->first_name;
    }
}
