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
use Maatwebsite\Excel\Facades\Excel;

class UserService
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($request)
    {
        try {
            $request->validated();
            $user = User::where('email', $request->email)->first();
            if ($user) {
                throw new \Exception('Такой пользователь есть в системе');
            }
            $user = new User();
            $user->fill([
                'last_name'  => $request->last_name,
                'first_name' => $request->first_name,
                'password'   => bcrypt($request->password),
                'email'      => $request->email,
                'birthday'   => $this->convertDate($request->birthday)
            ]);
            $user->save();
            $this->makeLog($user, 1, 1);
            return response()->json([
                'status' => 'success',
                'msg'    => 'Пользователь успешно добавлен в систему!'
            ], 200);
        } catch (\Exception $error) {
            return response()->json([
                'status' => 'error',
                'msg'    => $error->getMessage()
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
            $users = ($request->page) ? User::paginate(15) : User::all();
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

    /***
     * Search by keyword and filter data in storage
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search($request)
    {
        try {
            $users = new User();
            if (isset($request->keyword)) {
                $users = $users->where('name', 'LIKE', $request->keyword.'%');
            }
            if (isset($request->filter)) {
                $filter = json_decode($request->filter);

                if (!empty($filter->name) && !empty($filter->type)) {
                    $users = $users->orderBy($filter->name, $filter->type);
                }
            }
            $users = $users->paginate(10);
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
     * Display the info with smth relations.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function info($id)
    {
        try {
            $id = (! empty($id)) ? $id : auth()->id();
            $user = User::findOrFail($id);
            $user->orders = Order::with('tatoo')->where('user_id', $id)->paginate(15);
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
     * Show the form info for editing.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            $user = User::findOrFail($id);
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($request, $id)
    {
        try {
            $request->validated();
            $user = User::findOrFail($id);
            $user->fill([
                'last_name'  => $request->last_name,
                'first_name' => $request->first_name,
                'password'   => bcrypt($request->password),
                'email'      => $request->email,
                'birthday'   => $this->convertDate($request->birthday)
            ]);
            $user->save();
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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
            $this->makeLog($user, 3, 1);
            $user->delete();
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

    public function export()
    {
        return new UserExport();
    }

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
}