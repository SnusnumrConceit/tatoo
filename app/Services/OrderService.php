<?php
/**
 * Created by PhpStorm.
 * User: snusnumr
 * Date: 09.04.19
 * Time: 23:55
 */

namespace App\Services;


use App\Http\Resources\Admin\Order\OrderCollection;
use App\Http\Resources\Admin\Order\OrderCustomerExtendsCollection;
use App\Http\Resources\Admin\Order\OrderInfo;
use App\Http\Resources\Admin\Order\OrderTatooExtendsCollection;
use App\Model\Order;
use App\Models\Tatoo;
use App\User;
use Carbon\Carbon;

class OrderService
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
            $order = new Order();
            $order->fill([
                'tatoo_id'  =>  $request->tatoo_id,
                'user_id'   =>  $request->user_id,
                'status'    =>  $request->status,
                'note_date' =>  $this->convertDate($request->note_date)
            ]);
            $order->save();
            return response()->json([
                'status'  =>  'success',
                'msg'     =>  'Заказ успешно обновлён'
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
            $orders = Order::with(['customer', 'tatoo'])->paginate(25);
            return response()->json([
                'orders' => new OrderCollection($orders)
            ], 200);
        } catch (\Exception $error) {
            return response()->json([
                'status' => 'error',
                'msg'    => $error->getMessage()
            ]);
        }
    }

    public function search($request)
    {
        try {
            $query = new Order();
            if (isset($request->keyword)) {
                $query = $query->where('name', 'LIKE', $request->keyword.'%');
            }
            if (isset($request->filter)) {
                $filter = json_decode($request->filter);

                if (!empty($filter->name) && !empty($filter->type)) {
                    $query = $query->orderBy($filter->name, $filter->type);
                }
            }
            $orders = $query->with(['customer', 'tatoo'])->paginate(25);
            return response()->json([
                'orders' => new OrderCollection($orders)
            ], 200);
        } catch (\Exception $error) {
            return response()->json([
                'status' => 'error',
                'msg' => $error->getMessage()
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Model\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function extends()
    {
        try {
            $users = User::all();
            $tatoos = Tatoo::all();
            return response()->json([
                'clients'  => new OrderCustomerExtendsCollection($users),
                'tatoos' => new OrderTatooExtendsCollection($tatoos)
            ]);
        } catch (\Exception $error) {
            return response()->json([
                'status' => 'error',
                'msg'    => $error->getMessage()
            ]);
        }
    }

    public function info($id)
    {
        try {
            $order = Order::with(['customer', 'tatoo'])->findOrFail($id);
            return response()->json([
                'order' => new OrderInfo($order)
            ], 200);
        } catch (\Exception $error) {
            return response()->json([
                'status' => 'error',
                'msg'    => $error->getMessage()
            ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Model\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            $order = Order::with(['tatoo', 'customer'])->findOrFail($id);
            return response()->json([
                'order' => $order
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
     * @param  \App\Model\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update($request, $id)
    {
        try {
            $request->validated();
            $order = Order::findOrFail($id);
            $order->fill([
                'tatoo_id'  =>  $request->tatoo_id,
                'user_id'   =>  $request->user_id,
                'status'    =>  $request->status,
                'note_date' =>  $this->convertDate($request->note_date)
            ]);
            $order->save();
            return response()->json([
                'status'  =>  'success',
                'msg'     =>  'Заказ успешно обновлён'
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
     * @param  \App\Model\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            Order::findOrFail($id)->delete();
            return response()->json([
                'status' => 'success'
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
        return Carbon::parse($date)->format('Y-m-d H:i:s');
    }
}