<?php
/**
 * Created by PhpStorm.
 * User: snusnumr
 * Date: 09.04.19
 * Time: 23:55
 */

namespace App\Services;


use App\Events\OrderCompleted;
use App\Exports\OrderExport;
use App\Http\Resources\Admin\Order\OrderCollection;
use App\Http\Resources\Admin\Order\OrderCustomerExtendsCollection;
use App\Http\Resources\Admin\Order\OrderInfo;
use App\Http\Resources\Admin\Order\OrderTatooExtendsCollection;
use App\Http\Resources\Order\OrderForm;
use App\Model\Order;
use App\Models\Tatoo;
use App\User;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;

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
            $order->note_date = $this->convertDate($request->note_date, $request->note_time);
            if (! $this->validateNoteDate($order->note_date)) {
                throw new \Exception('Проверьте правильность указанной даты');
            }
            $order->fill([
                'tatoo_id'  =>  $request->tatoo_id,
                'user_id'   =>  $request->user_id,
                'status'    =>  $request->status,
                'master_id' =>  $request->master
            ]);
            $order->save();
            $mail_order = Order::with(['customer', 'tatoo'])->findOrFail($order->id);
            event(new OrderCompleted($mail_order));
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
                $query = $query->searchByTatoo($request->keyword);
            }
            if (isset($request->filter)) {
                $filter = json_decode($request->filter);

                if (!empty($filter->name) && !empty($filter->type)) {
                    switch ($filter->name) {
                        case 'tatoo': $query = $query->sortByTatoo($filter->type); break;
                        case 'name': $query = $query->sortByCustomer($filter->type); break;
                        default: $query = $query->orderBy($filter->name, $filter->type); break;
                    }
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
            $order = Order::with(['tatoo', 'customer', 'master'])->findOrFail($id);
            return response()->json([
//                'order' => $order
                'order' => new OrderForm($order)
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
                'note_date' =>  $this->convertDate($request->note_date, $request->note_time),
                'master_id' =>  $request->master
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
                'status' => 'success',
                'msg' => 'Заказ успешно удалён'
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
        return new OrderExport();
    }

    public function convertDate($date, $time)
    {
        return Carbon::parse($date)->format('Y-m-d') . ' '. $time['HH'].':'.$time['mm'];
    }

    public function publish($request)
    {
        try {
//            Validator::make([]);
            $date = $this->convertDate($request->note_date, $request->note_time);
            if  (! $this->validateNoteDate($date)) {
                throw new \Exception('Проверьте корректность даты');
            }
            $order = (new Order)->fill([
                'tatoo_id'  => $request->tatoo,
                'user_id'   => auth()->id(),
                'master_id' => $request->master['id'],
                'status'    => 2,
                'note_date' => $date
            ]);
            $order->save();
            $mail_order = Order::with(['customer', 'tatoo'])->findOrFail($order->id);
            event(new OrderCompleted($mail_order));
            return response()->json([
                'status' => 'success',
                'msg' => 'Запись успешно совершена!'
            ], 200);
        } catch (\Exception $error) {
            return response()->json([
                'status' => 'error',
                'msg' => $error->getMessage()
            ]);
        }
    }

    public function validateNoteDate($date)
    {
        if ($date < Carbon::now()) {
            return false;
        }
        $only_date = Carbon::parse($date)->format('Y-m-d');
        if ($date < $only_date . ' 09:00' || $date > $only_date . ' 22:00') {
            return false;
        }
        return true;
    }
}
