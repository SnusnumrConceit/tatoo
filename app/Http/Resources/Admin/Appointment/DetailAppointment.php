<?php

namespace App\Http\Resources\Admin\Appointment;

use Illuminate\Http\Resources\Json\JsonResource;

class DetailAppointment extends JsonResource
{
	public static $wrap = '';
	
	/**
	 * Transform the resource into an array.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return array
	 */
	public function toArray($request)
	{
		return [
			'appointment' => [
				'id' => $this->id,
				'name' => $this->name
			]
		];
	}
}
