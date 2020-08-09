<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Exports\AppointmentExport;

use App\Http\Requests\Admin\Appointment\IndexAppointment;
use App\Http\Requests\Admin\Appointment\StoreAppointment;
use App\Http\Requests\Admin\Appointment\UpdateAppointment;
use App\Http\Requests\Admin\Appointment\DeleteAppointment;

use App\Http\Resources\Admin\Appointment\DetailAppointment;
use App\Http\Resources\Admin\Appointment\AppointmentCollection;
use Illuminate\Http\JsonResponse;

class AppointmentController extends Controller
{
	public function __construct()
	{
		$this->authorizeResource('App\Models\Appointment');
	}
	
	/**
	 * Display a listing of the appointments
	 *
	 * @param IndexAppointment $request
	 *
	 * @return JsonResponse
	 *
	 * @throws \Illuminate\Auth\Access\AuthorizationException
	 */
	public function index(IndexAppointment $request)
	{
		$this->authorize('index', Appointment::class);
		
		$appointments = Appointment::query();
		
		$appointments->when($request->keyword, function ($q, $keyword) {
			return $q->where('name', 'LIKE', "%{$keyword}%");
		});
		
		$appointments->when($request->filter, function ($q, $filter) {
			$filter = json_decode($filter);
			
			return $q->orderBy($filter->name, $filter->type);
		});
		
		$appointments = $appointments->paginate();
		
		return (new AppointmentCollection($appointments))
			->response();
	}
	
	/**
	 * Store a newly created appointment in storage
	 *
	 * @param StoreAppointment $request
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function store(StoreAppointment $request)
	{
		Appointment::create($request->validated());
	
		return response()->json([
			'message' => __('appointments.created')
		], 200);
	}
	
	/**
	 * Display the appointment resource.
	 *
	 * @param Appointment $appointment
	 *
	 * @return JsonResponse
	 */
	public function show(Appointment $appointment)
	{
		return (new DetailAppointment($appointment))
			->response();
	}

	/**
	 * Update the appointment resource in storage.
	 *
	 * @param  UpdateAppointment  $request
	 * @param  Appointment  $appointment
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function update(UpdateAppointment $request, Appointment $appointment)
	{
		$appointment->update($request->validated());
	
		return response()->json([
			'message' => __('appointments.updated')
		], 200);
	}
	
	/**
	 * Remove the appointment from storage.
	 *
	 * @param DeleteAppointment $request
	 * @param Appointment $appointment
	 *
	 * @return \Illuminate\Http\JsonResponse
	 *
	 * @throws \Exception
	 */
	public function destroy(DeleteAppointment $request, Appointment $appointment)
	{
		$appointment->delete();
		
		return response()->json([
			'message' => __('appointments.deleted')
		], 200);
	}
	
	/**
	 * Export appointments in excel format
	 *
	 * @return AppointmentExport
	 */
	public function export()
	{
		return new AppointmentExport();
	}
}
