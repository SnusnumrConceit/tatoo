<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        try {
            $appointment = new Appointment();
            if (! empty($request->name)) {
                $dublicate = Appointment::where('name', $request->name)->count();
                if ($dublicate) {
                    throw new \Exception('Данная должность внесена в систему');
                }
            } else {
                throw new \Exception('Вы не указали наименование должности');
            }
            $appointment->fill([
                'name' => $request->name
            ]);
            $appointment->save();
            return response()->json([
                'status' => 'success'
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
            $appointments = Appointment::paginate(25);
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

    public function search(Request $request)
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
            $appointments = $query->paginate(25);
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
    public function edit(Appointment $appointment, int $id)
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
    public function update(Request $request, int $id)
    {
        try {
            if (! empty($request->name)) {
                $dublicate = Appointment::where('name', $request->name)->count();
                if ($dublicate == 1) {
                    throw new \Exception('Данная должность внесена в систему');
                }
            } else {
                throw new \Exception('Вы не указали наименование должности');
            }
            $appointment = Appointment::findOrFail($id);
            $appointment->fill([
                'name' => $request->name
            ]);
            $appointment->save();
            return response()->json([
                'status' => 'success'
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
            $appointment = Appointment::findOrFail($id)->delete();
            return response()->json([
                'status' => 'success',
            ], 200);
        } catch (\Exception $error) {
            return response()->json([
                'status' => 'error',
                'msg' => $error->getMessage()
            ]);
        }
    }
}
