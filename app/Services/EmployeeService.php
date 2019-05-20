<?php
/**
 * Created by PhpStorm.
 * User: snusnumr
 * Date: 09.04.19
 * Time: 23:24
 */

namespace App\Services;


use App\Events\WriteAudit;
use App\Exports\MasterExport;
use App\Http\Resources\Admin\Employee\EmployeeCollection;
use App\Http\Resources\Admin\Employee\EmployeeInfo;
use App\Model\MasterTatoo;
use App\Models\Appointment;
use App\Models\Employee;
use App\Models\Tatoo;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;

class EmployeeService
{
    public $image;

    public function __construct(ImageService $image)
    {
        $this->image = $image;
    }

    /***
     * Export masters
     *
     * @return MasterExport
     */
    public function export()
    {
        return new MasterExport();
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
            $this->makeLog($employee, 7, 1);
            if (! empty($request->tatoos)) {
                $this->addTatoos($employee->id, $request->tatoos);
            }
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
//            $employees = (isset($request->page))
//                ? Employee::with('appointment')->paginate(15)
//                : Employee::with('appointment')->all();

            $employees = Employee::with('appointment')->paginate(15);
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
            $tatoos = Tatoo::all();
            return response()->json([
                'appointments' => $appointments,
                'tatoos'       => $tatoos
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
            $employee = Employee::with(['appointment', 'tatoos'])->findOrFail($id);
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
            $employee = Employee::findOrFail($id);
            $employee->fill([
                'name' => $request->name,
                'description' => $request->description,
                'appointment_id' => $request->appointment_id,
                'birthday' => $this->convertDate($request->birthday),
                'url' => $url
            ]);
            $employee->save();
            $this->makeLog($employee, 8, 1);
            if (! empty($request->tatoos)) {
                $this->removeTatoos($employee->id);
                $this->addTatoos($employee->id, $request->tatoos);
            }
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
            $this->makeLog($employee, 9, 1);
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

    public function addTatoos($id, $tatoos)
    {
        $ids = [];
        foreach ($tatoos as $tatoo) {
            if (!empty($tatoo['id']) && ! in_array($tatoo['id'], $ids)) {
                array_push($ids, $tatoo['id']);
                MasterTatoo::create([
                    'tatoo_id' => $tatoo['id'],
                    'employee_id' => $id
                ]);
            } else {
                continue;
            }
        }
    }

    public function removeTatoos($id)
    {
        MasterTatoo::where('employee_id', $id)->delete();
    }

    public function convertDate($date)
    {
        return Carbon::parse($date)->format('Y-m-d');
    }

    public function getTatoos($id)
    {
        try {
            $master = Employee::findOrFail($id);
            foreach ($master->tatoos as $tatoo) {
                $tatoo->url = str_replace('public', 'storage', $tatoo->url);
            }
            return response()->json([
                'tatoos' => $master->tatoos
            ], 200);
        } catch (\Exception $error) {
            return response()->json([
                'status' => 'error',
                'msg' => $error->getMessage()
            ]);
        }
    }

    public function publish($request)
    {
        try {
            $appointment = Appointment::where('name', 'Мастер')->first();
            $employees = Employee::with(['appointment'])
                ->where('appointment_id', $appointment->id)
                ->paginate(15);
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

    public function makeLog($subject, $type, $status)
    {
        switch ($status) {
            case 1: $status = json_encode((object)['status' => 'success']); break;
            case 2: $status = json_encode((object)['status' => 'error']); break;
            default: break;
        }
        $subject = json_encode((object)[
            'id' => $subject->id,
            'type' => 'employee',
            'name' => $subject->name]);
        event(new WriteAudit($subject, $type, $status));
    }
}