<?php
/**
 * Created by PhpStorm.
 * User: snusnumr
 * Date: 09.04.19
 * Time: 23:38
 */

namespace App\Services;


use App\Http\Resources\Admin\User\UserCollection;
use App\Http\Resources\UserCartInfo;
use App\User;
use Carbon\Carbon;

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
            $user = User::with('orders')->findOrFail($id);
            return response()->json([
                'user' => new UserCartInfo($user)
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

    public function convertDate($date)
    {
        return Carbon::parse($date)->format('Y-m-d');
    }
}