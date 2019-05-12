<?php

namespace App\Http\Controllers;

use App\Http\Requests\Admin\Employee\EmployeeFormRequest;
use App\Http\Resources\Admin\Employee\EmployeeCollection;
use App\Models\Appointment;
use App\Models\Employee;
use App\Services\EmployeeService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public $employee;

    public function __construct(EmployeeService $employee)
    {
        $this->employee = $employee;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(EmployeeFormRequest $request)
    {
        return $this->employee->create($request);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return $this->employee->store($request);
    }

    public function extends()
    {
        return $this->employee->extends();
    }

    /***
     * Search by keyword and filter data in storage
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request)
    {
        return $this->employee->search($request);
    }

    /**
     * Display the info with smth relations.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function info($id)
    {
        return $this->employee->info($id);
    }

    /**
     * Show the form info for editing.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(int $id)
    {
        return $this->employee->edit($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(EmployeeFormRequest $request, int $id)
    {
        return $this->employee->update($request, $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        return $this->employee->destroy($id);
    }

    public function export()
    {
        return $this->employee->export();
    }

    public function getTatoos(int $id)
    {
        return $this->employee->getTatoos($id);
    }
}
