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
     * Экспорт работников
     *
     * @return MasterExport
     */
    public function export()
    {
        return new MasterExport();
    }

    /**
     * Добавление работника
     *
     * @return \Illuminate\Http\Response
     */
    public function create($request)
    {
        try {
            /** проверка на наличие конечного пути перемещения фотографии */
            if  (empty($request->destination)) {
                throw new \Exception('Не удалось передать конечную директорию');
            }

            /** перемещение фотографии */
            $this->image->move($request->url, $request->destination);
            /** инициализация возможности "общения" с таблицей Employees через модель */
            $employee = new Employee();
            /** заполнение промежуточными данными */
            $employee->fill([
                'name'           => $request->name,
                'description'    => $request->description,
                'appointment_id' => $request->appointment_id,
                'birthday'       => $this->convertDate($request->birthday),
                'url'            => $request->destination
            ]);
            /** сохранение */
            $employee->save();
            $this->makeLog($employee, 7, 1);
            /** если параметр tatoos не пустой */
            if (! empty($request->tatoos)) {
                /** обновление татуировок */
                $this->addTatoos($employee->id, $request->tatoos);
            }
            /** возвращение успешного сообщения и статуса */
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
     * Хранилище должностей
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($request)
    {
        try {
            /** получение работников по 15 на странице */
            $employees = Employee::with('appointment')->paginate(15);
            /** возврат полученных работников */
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

    /***
     * Дополнительные данные для формы
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function extends()
    {
        try {
            /** получение списка должностей */
            $appointments = Appointment::all();
            /** получение списка татуировок */
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
     * Поиск и сортировка работников
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search($request)
    {
        try {
            /** инициализация возможности "общения" с таблицей Employees через модель */
            $employees = new Employee();

            /** если параметр keyword задан */
            if (isset($request->keyword)) {
                /** поиск по имени */
                $employees = $employees->where('name', 'LIKE', $request->keyword.'%');
            }

            /** если параметр filter задан */
            if (isset($request->filter)) {
                /** конвертация из JSON */
                $filter = json_decode($request->filter);

                /** проверка на наличие параметров name и type */
                if (!empty($filter->name) && !empty($filter->type)) {
                    /** если сортировка по должности */
                    if ($filter->name === 'appointment') {
                        /** происходит кастомная сортировка */
                        $employees = $employees->sortByAppointment($filter->type);
                    } else {
                        /** в противном случае по стандарту */
                        $employees = $employees->orderBy($filter->name, $filter->type);
                    }
                }
            }
            /** вывод по 10 должностей на страницу */
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
     * Получение информации о работнике
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function info($id)
    {
        try {
            /** поиск работника с должностью и татуировками по идентификатору */
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
     * Получение работника для редактирования
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            /** поиск работника с должностью и татуировками по идентификатору */
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
     * Обновление информации о работнике
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($request, $id)
    {
        try {
            /** проверка на наличие конечного пути перемещения фотографии */
            if (! empty($request->destination)) {
                $this->image->move($request->url, $request->destination);
                $url = $request->destination;
            } else {
                $url = $request->url;
            }

            /** перемещение фотографии */
            $this->image->move($request->url, $request->destination);
            /** поиск работника по идентификатору */
            $employee = Employee::findOrFail($id);
            /** заполнение промежуточными данными */
            $employee->fill([
                'name' => $request->name,
                'description' => $request->description,
                'appointment_id' => $request->appointment_id,
                'birthday' => $this->convertDate($request->birthday),
                'url' => $url
            ]);
            /** сохранение */
            $employee->save();
            $this->makeLog($employee, 8, 1);
            /** если параметр tatoos задан */
            if (! empty($request->tatoos)) {
                /** удаление текущего списка татуировок у работника*/
                $this->removeTatoos($employee->id);
                /** добавление нового списка татуировок у работника*/
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
     * Удаление работника
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            /** поиск работника по идентификатору */
            $employee = Employee::findOrFail($id);
            $this->makeLog($employee, 9, 1);
            /** удаление */
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

    /***
     * Добавление татуировок работника
     *
     * @param $id
     * @param $tatoos
     */
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

    /***
     * Удаление татуировок работника
     *
     * @param $id
     */
    public function removeTatoos($id)
    {
        MasterTatoo::where('employee_id', $id)->delete();
    }

    /** конвертация даты в формат Y-m-d */
    public function convertDate($date)
    {
        return Carbon::parse($date)->format('Y-m-d');
    }

    /** получение списка татуировок пользователя */
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
