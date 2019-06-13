<?php
/**
 * Created by PhpStorm.
 * User: snusnumr
 * Date: 09.04.19
 * Time: 23:38
 */

namespace App\Services;


use App\Events\WriteAudit;
use App\Exports\UserExport;
use App\Http\Resources\Admin\User\UserCollection;
use App\Http\Resources\Admin\User\UserInfo;
use App\Http\Resources\UserCartInfo;
use App\Model\Order;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Kodeine\Acl\Models\Eloquent\Role;
use Maatwebsite\Excel\Facades\Excel;

class UserService
{
    /**
     * Добавление пользователя
     *
     * @return \Illuminate\Http\Response
     */
    public function create($request)
    {
        try {
            /** проверка входных параметров */
            $request->validated();
            /**  поиск пользователя по email */
            $user = User::where('email', $request->email)->first();

            /** если пользователь с таким email существует */
            if ($user) {
                /** генерируется исключение о дубликате */
                throw new \Exception('Такой пользователь есть в системе');
            }

            /** инициализация возможности "общения" с таблицей Users через модель */
            $user = new User();
            /** заполнение предварительными данными */
            $user->fill([
                'last_name'  => $request->last_name,
                'first_name' => $request->first_name,
                'password'   => bcrypt($request->password),
                'email'      => $request->email,
                'birthday'   => $this->convertDate($request->birthday)
            ]);
            /** сохранение */
            $user->save();
            $this->removeUserRoles($user->id);
            $this->addRole($user->id, $request->role['id']);
            $this->makeLog($user, 1, 1);
            /** возврат успешного статуса и сообщения */
            return response()->json([
                'status' => 'success',
                'msg'    => 'Пользователь успешно добавлен в систему!'
            ], 200);
        } catch (\Exception $error) {
            /** возврат ошибки и сообщения */
            return response()->json([
                'status' => 'error',
                'msg'    => $error->getMessage()
            ]);
        }
    }

    /**
     * Получение списка пользователей
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($request)
    {
        try {
            /** при наличии параметра page возвращать по 15 штук постранично, иначе - всех */
            $users = ($request->page) ? User::paginate(15) : User::all();
            /** возвращение пользователей */
            return response()->json([
                'users' => new UserCollection($users)
            ], 200);
        } catch (\Exception $error) {
            /** возврат ошибки и сообщения */
            return response()->json([
                'status' => 'error',
                'msg'    => $error->getMessage()
            ]);
        }
    }

    /***
     * Поиск пользователей
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search($request)
    {
        try {
            /** инициализация возможности "общения" с таблицей Users через модель */
            $users = new User();

            /** если параметр keyword не пустой */
            if (isset($request->keyword)) {
                /** выполняется поиск по имени */
                $users = $users->where('name', 'LIKE', $request->keyword.'%');
            }

            /** если параметр filter не пустой */
            if (isset($request->filter)) {
                /** конвертация из JSON */
                $filter = json_decode($request->filter);

                /** если параметры name и type заполнены */
                if (!empty($filter->name) && !empty($filter->type)) {
                    /** выполняется сортировка по названию поля и типу сортировки */
                    $users = $users->orderBy($filter->name, $filter->type);
                }
            }

            /** получение по 10 пользователей на страницу */
            $users = $users->paginate(10);
            /** возвращение пользователей */
            return response()->json([
                'users' => new UserCollection($users)
            ], 200);
        } catch (\Exception $error) {
            return response()->json([
                'status' => 'error',
                'msg'    => $error->getMessage()
            ]);
        }
    }

    /**
     * Получение информации о пользователе в модальном окне в админ-панели и в кабинете
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function info($id)
    {
        try {
            /** если параметр id пустой - берётся id авторизованного пользователя */
            $id = (! empty($id)) ? $id : auth()->id();
            /** поиск пользователя по идентификатору */
            $user = User::findOrFail($id);
            /** получение заказов пользователя */
            $user->orders = Order::with('tatoo')->where('user_id', $id)->paginate(15);
            /** возврат информации */
            return response()->json([
                'user_info' => new UserInfo($user)
            ], 200);
        } catch (\Exception $error) {
            return response()->json([
                'status' => 'error',
                'msg'    => $error->getMessage()
            ]);
        }
    }

    /**
     * Получение информации о пользователе для редактирования
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            $user = User::with('role')->findOrFail($id);
            return response()->json([
                'user' => $user
            ], 200);
        } catch (\Exception $error) {
            return response()->json([
                'status' => 'error',
                'msg'    => $error->getMessage()
            ]);
        }
    }

    /**
     * Обновление пользователя
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($request, $id)
    {
        try {
            /** проверка параметров */
            $request->validated();
            /** поиск пользователя по идентификатору */
            $user = User::findOrFail($id);
            /** заполнение предварительными данными */
            $user->fill([
                'last_name'  => $request->last_name,
                'first_name' => $request->first_name,
                'password'   => bcrypt($request->password),
                'email'      => $request->email,
                'birthday'   => $this->convertDate($request->birthday)
            ]);
            /** сохранение */
            $user->save();
            $this->removeUserRoles($user->id);
            $this->addRole($user->id, $request->role[0]['id']);
            $this->makeLog($user, 2, 1);
            return response()->json([
                'status' => 'success',
                'msg'    => 'Пользователь успешно обновлён!'
            ], 200);
        } catch (\Exception $error) {
            return response()->json([
                'status' => 'error',
                'msg'    => $error->getMessage()
            ]);
        }
    }

    /**
     * Удаление пользователя
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            /** поиск пользователя по идентификатору */
            $user = User::findOrFail($id);
            $this->makeLog($user, 3, 1);
            /** удаление */
            $user->delete();
            $this->removeUserRoles($id);
            return response()->json([
                'status' => 'success',
                'msg'    => 'Пользователь успешно удалён!'
            ], 200);
        } catch (\Exception $error) {
            return response()->json([
                'status' => 'error',
                'msg'    => $error->getMessage()
            ]);
        }
    }

    /***
     * Экспорт таблицы в Excel
     *
     * @return UserExport
     */
    public function export()
    {
        return new UserExport();
    }

    /***
     * Конвертация даты в формат "Дата и время"
     *
     * @param $date
     * @return string
     */
    public function convertDate($date)
    {
        return Carbon::parse($date)->format('Y-m-d');
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
            'type' => 'user',
            'name' => $subject->last_name.' '. $subject->first_name]);
        event(new WriteAudit($subject, $type, $status));
    }

    public function removeUserRoles($id) {
        DB::table('role_user')
            ->where('user_id', $id)
            ->delete();
    }

    public function addRole($user_id, $role_id)
    {
        $user_role = ['user_id' => $user_id, 'role_id' => $role_id];
        DB::table('role_user')->insert($user_role);
    }

    public function getRoles()
    {
        try {
            $roles = Role::all();
            return response()->json([
                'roles' => $roles
            ], 200);
        } catch (\Exception $error) {
            return response()->json([
                'status' => 'error',
                'msg' => $error->getMessage()
            ]);
        }
    }
}
