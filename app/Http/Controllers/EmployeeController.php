<?php

namespace App\Http\Controllers;

use App\Http\Resources\Admin\Employee\EmployeeCollection;
use App\Models\Appointment;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        try {
            $employee = Employee::where([
                'name' => $request->name,
                'description' => $request->description,
                'appointment_id' => $request->appointment,
            ])->first();
            if ($employee) {
                throw new \Exception('Такой работник есть в системе');
            }
            $employee = new Employee();
            $employee->fill([
                'name' => $request->name,
                'description' => $request->description,
                'appointment_id' => $request->appointment,
                'birthday' => $this->convertDate($request->birthday),
                'url' => $request->url
            ]);
            $employee->save();
            return response()->json([
                'status' => 'success',
                'msg' => 'Работник успешно добавлен в систему!'
            ], 200);
        } catch (\Exception $error) {
            return response()->json([
                'status' => 'error',
                'msg' => $error->getMessage()
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $employees = (isset($request->page))
                ? Employee::with('appointment')->paginate(15)
                : Employee::with('appointment')->all();
            return response()->json([
                'employees' => new EmployeeCollection($employees)
            ], 200);
        } catch (\Exception $error) {
            return response()->json([
                'status' => 'error',
                'msg' => $error->getMessage()
            ]);
        }
    }

    public function extends()
    {
        try {
            $appointments = Appointment::all();
            return response()->json([
                'appointments' => $appointments
            ], 200);
        } catch (\Exception $error) {
            return response()->json([
                'status' => 'error',
                'msg' => $error->getMessage()
            ]);
        }
    }

    /***
     * Search by keyword and filter data in storage
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request)
    {
        try {
            $employees = new Employee();
            if (isset($request->keyword)) {
                $employees = $employees->where('name', 'LIKE', $request->keyword.'%');
            }
            if (isset($request->filter)) {
                $filter = json_decode($request->filter);

                if (!empty($filter->name) && !empty($filter->type)) {
                    $employees = $employees->orderBy($filter->name, $filter->type);
                }
            }
            $employees = $employees->with('appointment')->paginate(10);
            return response()->json([
                'employees' => new EmployeeCollection($employees)
            ], 200);
        } catch (\Exception $error) {
            return response()->json([
                'status' => 'error',
                'msg' => $error->getMessage()
            ]);
        }
    }

    /**
     * Display the info with smth relations.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function info($id)
    {
        try {
            $employee = Employee::findOrFail($id);
            return response()->json([
                'employee' => $employee
            ], 200);
        } catch (\Exception $error) {
            return response()->json([
                'status' => 'error',
                'msg' => $error->getMessage()
            ]);
        }
    }

    /**
     * Show the form info for editing.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            $employee = Employee::findOrFail($id);
            return response()->json([
                'employee' => $employee
            ], 200);
        } catch (\Exception $error) {
            return response()->json([
                'status' => 'error',
                'msg' => $error->getMessage()
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $employee = Employee::where([
                'name' => $request->name,
                'description' => $request->description,
                'appointment_id' => $request->appointment,
            ])->first();
            if ($employee) {
                throw new \Exception('Такой работник есть в системе');
            }
            $employee = new Employee();
            $employee->fill([
                'name' => $request->name,
                'description' => $request->description,
                'appointment_id' => $request->appointment,
                'birthday' => $this->convertDate($request->birthday),
                'url' => $request->url
            ]);
            $employee->save();
            return response()->json([
                'status' => 'success',
                'msg' => 'Работник успешно обновлён!'
            ], 200);
        } catch (\Exception $error) {
            return response()->json([
                'status' => 'error',
                'msg' => $error->getMessage()
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $employee = Employee::findOrFail($id);
            $employee->delete();
            return response()->json([
                'status' => 'success',
                'msg' => 'Работник успешно удалён!'
            ], 200);
        } catch (\Exception $error) {
            return response()->json([
                'status' => 'error',
                'msg' => $error->getMessage()
            ]);
        }
    }

    public function convertDate($date)
    {
        return Carbon::parse($date)->format('Y-m-d');
    }
}
