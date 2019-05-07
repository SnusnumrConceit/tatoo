<?php
/**
 * Created by PhpStorm.
 * User: snusnumr
 * Date: 09.04.19
 * Time: 23:24
 */

namespace App\Services;


use App\Http\Resources\Admin\Employee\EmployeeCollection;
use App\Http\Resources\Admin\Employee\EmployeeInfo;
use App\Models\Appointment;
use App\Models\Employee;
use Carbon\Carbon;

class EmployeeService
{
    public $image;

    public function __construct(ImageService $image)
    {
        $this->image = $image;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($request)
    {
        try {
            if  (empty($request->destination)) {
                throw new \Exception('Не удалось передать конечную директорию');
            }
            $this->image->move($request->url, $request->destination);
            $employee = new Employee();
            $employee->fill([
                'name'           => $request->name,
                'description'    => $request->description,
                'appointment_id' => $request->appointment_id,
                'birthday'       => $this->convertDate($request->birthday),
                'url'            => $request->destination
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
    public function store($request)
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
    public function search($request)
    {
        try {
            $employees = new Employee();
            if (isset($request->keyword)) {
                $employees = $employees->where('name', 'LIKE', $request->keyword.'%');
            }
            if (isset($request->filter)) {
                $filter = json_decode($request->filter);

                if (!empty($filter->name) && !empty($filter->type)) {
                    if ($filter->name === 'appointment') {
                        $employees = $employees->sortByAppointment($filter->type);
                    } else {
                        $employees = $employees->orderBy($filter->name, $filter->type);
                    }
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
            $employee = Employee::with(['appointment', 'tatoos'])->findOrFail($id);
            return response()->json([
                'employee' => new EmployeeInfo($employee)
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
            $employee = Employee::with('appointment')->findOrFail($id);
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
    public function update($request, $id)
    {
        try {
            if (! empty($request->destination)) {
                $this->image->move($request->url, $request->destination);
                $url = $request->destination;
            } else {
                $url = $request->url;
            }
            $this->image->move($request->url, $request->destination);
            $employee = new Employee();
            $employee->fill([
                'name' => $request->name,
                'description' => $request->description,
                'appointment_id' => $request->appointment_id,
                'birthday' => $this->convertDate($request->birthday),
                'url' => $url
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