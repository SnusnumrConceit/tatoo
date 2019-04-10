<?php

namespace App\Http\Controllers;

use App\Http\Requests\Admin\Appointment\AppointmentFormRequest;
use App\Models\Appointment;
use App\Services\AppointmentService;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public $appointment;

    public function __construct(AppointmentService $appointment)
    {
        $this->appointment = $appointment;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(AppointmentFormRequest $request)
    {
        return $this->appointment->create($request);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return $this->appointment->store($request);
    }

    public function search(Request $request)
    {
        return $this->appointment->search($request);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Appointment  $appointment
     * @return \Illuminate\Http\Response
     */
    public function edit(int $id)
    {
        return $this->appointment->destroy($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Appointment  $appointment
     * @return \Illuminate\Http\Response
     */
    public function update(AppointmentFormRequest $request, int $id)
    {
        return $this->appointment->update($request, $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Appointment  $appointment
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        return $this->appointment->destroy($id);
    }
}
