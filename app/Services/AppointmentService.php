<?php
/**
 * Created by PhpStorm.
 * User: snusnumr
 * Date: 09.04.19
 * Time: 23:44
 */

namespace App\Services;


use App\Events\WriteAudit;
use App\Exports\AppointmentExport;
use App\Models\Appointment;
use Maatwebsite\Excel\Facades\Excel;

class AppointmentService
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($request)
    {
        try {
            $appointment = new Appointment();
            if (! empty($request->appointment)) {
                $dublicate = Appointment::where('name', $request->appointment)->count();
                if ($dublicate) {
                    throw new \Exception('Данная должность внесена в систему');
                }
            } else {
                throw new \Exception('Вы не указали наименование должности');
            }
            $appointment->fill([
                'name' => $request->appointment
            ]);
            $appointment->save();
            $this->makeLog($appointment, 10, 1);
            return response()->json([
                'status' => 'success',
                'msg'    => 'Должность успешно добавлена'
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
            $appointments = Appointment::with('employee')->paginate(25);
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

    public function search($request)
    {
        try {
            $query = new Appointment();
            if (! empty($request->keyword)) {
                $query = $query->where('name', 'LIKE', '%'.$request->keyword.'%');
            }
            if (! empty($request->filter)) {
                $filter = json_decode($request->filter);
                $query = $query->orderBy($filter->name, $filter->type);
            }
            $appointments = $query->with('employee')->paginate(25);
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


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Appointment  $appointment
     * @return \Illuminate\Http\Response
     */
    public function edit(int $id)
    {
        try {
            $appointment = Appointment::findOrFail($id);
            return response()->json([
                'appointment' => $appointment
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
     * @param  \App\Models\Appointment  $appointment
     * @return \Illuminate\Http\Response
     */
    public function update($request, int $id)
    {
        try {
            if (! empty($request->appointment)) {
                $dublicate = Appointment::where('name', $request->appointment)->count();
                if ($dublicate == 1) {
                    throw new \Exception('Данная должность внесена в систему');
                }
            } else {
                throw new \Exception('Вы не указали наименование должности');
            }
            $appointment = Appointment::findOrFail($id);
            $appointment->fill([
                'name' => $request->appointment
            ]);
            $appointment->save();
            $this->makeLog($appointment, 11, 1);
            return response()->json([
                'status' => 'success',
                'msg'    => 'Должность успешно обновлена'
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
     * @param  \App\Models\Appointment  $appointment
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        try {
            $appointment = Appointment::findOrFail($id);
            $this->makeLog($appointment, 12, 1);
            $appointment->delete();
            return response()->json([
                'status' => 'success',
                'msg'    => 'Должность успешно удалена'
            ], 200);
        } catch (\Exception $error) {
            return response()->json([
                'status' => 'error',
                'msg' => $error->getMessage()
            ]);
        }
    }

    public function export()
    {
        return new AppointmentExport();
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
            'type' => 'appointment',
            'name' => $subject->name]);
        event(new WriteAudit($subject, $type, $status));
    }
}