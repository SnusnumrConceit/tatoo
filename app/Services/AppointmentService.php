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
            /** инициализация возможности "общения" с таблицей Appointments через модель */
            $appointment = new Appointment();

            /** проверка по имени на наличие дублирования */
            if (! empty($request->appointment)) {
                $dublicate = Appointment::where('name', $request->appointment)->count();
                if ($dublicate) {
                    throw new \Exception('Данная должность внесена в систему');
                }
            } else {
                throw new \Exception('Вы не указали наименование должности');
            }

            /** заполнение промежуточными данными */
            $appointment->fill([
                'name' => $request->appointment
            ]);
            /** сохранение */
            $appointment->save();
            $this->makeLog($appointment, 10, 1);
            /** возвращение успешного статуса и сообщения */
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
     * Хранилище должностей
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

    /***
     * Поиск и сортировка должностей
     *
     * @param $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search($request)
    {
        try {
            /** инициализация возможности "общения" с таблицей Appointments через модель */
            $query = new Appointment();

            /** если параметр keyword заполненный, то происходит поиск по имени */
            if (! empty($request->keyword)) {
                $query = $query->where('name', 'LIKE', '%'.$request->keyword.'%');
            }

            /** если параметр filter заполнен */
            if (! empty($request->filter)) {
                /** конвертация из JSON */
                $filter = json_decode($request->filter);
                /** сортировка по названию и типу */
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
     * Получение должности на редактирование
     *
     * @param  \App\Models\Appointment  $appointment
     * @return \Illuminate\Http\Response
     */
    public function edit(int $id)
    {
        try {
            /** поиск должности по идентификатору */
            $appointment = Appointment::findOrFail($id);
            /** возврат найденной должности */
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
            /** проверка на наличие параметра */
            if (! empty($request->appointment)) {
                /** поиск на наличие дубликата */
                $dublicate = Appointment::where('name', $request->appointment)->count();
                if ($dublicate == 1) {
                    throw new \Exception('Данная должность внесена в систему');
                }
            } else {
                throw new \Exception('Вы не указали наименование должности');
            }

            /** поиск должности по идентификатору */
            $appointment = Appointment::findOrFail($id);
            /** заполнение промежуточными данными */
            $appointment->fill([
                'name' => $request->appointment
            ]);
            /** сохранение */
            $appointment->save();
            $this->makeLog($appointment, 11, 1);
            /** возвращение успешного статуса и сообщения */
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

    /**
     * Экспорт должностей в Excel
     *
     * @return AppointmentExport
     */
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
