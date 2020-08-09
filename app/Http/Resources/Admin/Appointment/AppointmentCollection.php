<?php

namespace App\Http\Resources\Admin\Appointment;

use Illuminate\Http\Resources\Json\ResourceCollection;

class AppointmentCollection extends ResourceCollection
{
	public static $wrap = 'appointments';
	
	/**
	 * Transform the resource collection into an array.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return array
	 */
	public function toArray($request)
	{
		return $this->collection->toArray();
	}
}
