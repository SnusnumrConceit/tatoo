<?php

namespace App\Http\Resources\Admin\Appointment;

use Illuminate\Http\Resources\Json\JsonResource;

class Appointment extends JsonResource
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
			'name' => $this->name,
			'inUsed' => $this->hasEmployees()
		];
	}
}
