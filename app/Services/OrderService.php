<?php
/**
 * Created by PhpStorm.
 * User: snusnumr
 * Date: 09.04.19
 * Time: 23:55
 */

namespace App\Services;


use App\Events\OrderCompleted;
use App\Events\WriteAudit;
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

/** функциональность заказов **/
class OrderService
{
    /**
     * добавление товара
     *
     * @return \Illuminate\Http\Response
     */
    public function create($request)
    {
        try {
            /** валидация входных параметров */
            $request->validated();
            /** инициализация возможности "общения" с таблицей Orders через модель */
            $order = new Order();
            /** конвертация времени записи в формат Дата и время (datetime) */
            $order->note_date = $this->convertDate($request->note_date, $request->note_time);

            /** проверка на соответствие даты необходимому шаблону: d.m.Y HH:mm:ss**/
            if (! $this->validateNoteDate($order->note_date)) {
                throw new \Exception('Проверьте правильность указанной даты');
            }

            /** заполнение модели предварительными данными */
            $order->fill([
                'tatoo_id'  =>  $request->tatoo_id,
                'user_id'   =>  $request->user_id,
                'status'    =>  $request->status,
                'master_id' =>  $request->master,
                'note_end'  =>  Carbon::parse($order->note_date)->addHours(4)
            ]);
            /** сохранение заказа **/
            $order->save();
            /** формирование информации по заказу для письма */
            $mail_order = Order::with(['customer', 'tatoo'])->findOrFail($order->id);
            $this->makeLog($order, 13 ,1);
            /** генерация события для отправки письма */
            event(new OrderCompleted($mail_order));
            /** возвращение успешного статуса и сообщения */
            return response()->json([
                'status'  =>  'success',
                'msg'     =>  'Заказ успешно добавлен'
            ], 200);
        } catch (\Exception $error) {
            /** в случае исключения возвращается ошибка с сообщением */
            return response()->json([
                'status' => 'error',
                'msg'    => $error->getMessage()
            ]);
        }
    }

    /**
     * список всех заказов
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($request)
    {
        try {
            /** получение по 25 заказов постранично с пользователями и татуировками */
            $orders = Order::with(['customer', 'tatoo'])->paginate(25);
            /** возвращение найденных заказов */
            return response()->json([
                'orders' => new OrderCollection($orders)
            ], 200);
        } catch (\Exception $error) {
            /** в случае исключения возвращается ошибка с сообщением */
            return response()->json([
                'status' => 'error',
                'msg'    => $error->getMessage()
            ]);
        }
    }

    public function search($request)
    {
        try {
            /** инициализация возможности "общения" с таблицей Orders через модель */
            $query = new Order();

            /** если в запросе параметр keyword заполнен */
            if (isset($request->keyword)) {
                /** то производится поиск по названию татуировки */
                $query = $query->searchByTatoo($request->keyword);
            }

            /** если в запросе параметр filter заполнен */
            if (isset($request->filter)) {
                /** конвертация из JSON */
                $filter = json_decode($request->filter);

                /** если параметры name и type заполнены */
                if (!empty($filter->name) && !empty($filter->type)) {
                    /** формируется запрос сортировки относительно поля таблиц */
                    switch ($filter->name) {
                        case 'tatoo': $query = $query->sortByTatoo($filter->type); break;
                        case 'name': $query = $query->sortByCustomer($filter->type); break;
                        default: $query = $query->orderBy($filter->name, $filter->type); break;
                    }
                }
            }

            /** получение по 25 найденных заказов постранично с пользователями и татуировками */
            $orders = $query->with(['customer', 'tatoo'])->paginate(25);
            /** возвращение найденных заказов */
            return response()->json([
                'orders' => new OrderCollection($orders)
            ], 200);
        } catch (\Exception $error) {
            /** в случае исключения возвращается ошибка с сообщением */
            return response()->json([
                'status' => 'error',
                'msg' => $error->getMessage()
            ]);
        }
    }

    /**
     * Дополнительные данные для формы
     *
     * @param  \App\Model\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function extends()
    {
        try {
            /** получение всех пользователей */
            $users = User::all();
            /** получение всех татуировок */
            $tatoos = Tatoo::all();
            /** возвращение найденных данных */
            return response()->json([
                'clients'  => new OrderCustomerExtendsCollection($users),
                'tatoos' => new OrderTatooExtendsCollection($tatoos)
            ]);
        } catch (\Exception $error) {
            /** в случае исключения возвращается ошибка с сообщением */
            return response()->json([
                'status' => 'error',
                'msg'    => $error->getMessage()
            ]);
        }
    }

    /***
     * информация о заказе
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function info($id)
    {
        try {
            /** поиск заказа с покупателем и тату по идентификатору */
            $order = Order::with(['customer', 'tatoo'])->findOrFail($id);
            /** возвращение найденного заказа */
            return response()->json([
                'order' => new OrderInfo($order)
            ], 200);
        } catch (\Exception $error) {
            /** в случае исключения возвращается ошибка с сообщением */
            return response()->json([
                'status' => 'error',
                'msg'    => $error->getMessage()
            ]);
        }
    }

    /**
     * Получение заказа на редактирование
     *
     * @param  \App\Model\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            /** поиск заказа с покупателем, тату и мастером по идентификатору */
            $order = Order::with(['tatoo', 'customer', 'master'])->findOrFail($id);
            /** возвращение найденного заказа */
            return response()->json([
                'order' => new OrderForm($order)
            ], 200);
        } catch (\Exception $error) {
            /** в случае исключения возвращается ошибка с сообщением */
            return response()->json([
                'status' => 'error',
                'msg'    => $error->getMessage()
            ]);
        }
    }

    /**
     * Обновление заказа
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update($request, $id)
    {
        try {
            /** проверка входных параметров */
            $request->validated();
            /** поиск заказа по идентификатору */
            $order = Order::findOrFail($id);
            /** заполнение модели предварительными данными */
            $order->fill([
                'tatoo_id'  =>  $request->tatoo_id,
                'user_id'   =>  $request->user_id,
                'status'    =>  $request->status,
                'note_date' =>  $this->convertDate($request->note_date, $request->note_time),
                'master_id' =>  $request->master
            ]);
            /** сохранение в таблице */
            $order->save();
            /** возвращение статуса и сообщения об успешности */
            $this->makeLog($order, 14, 1);
            return response()->json([
                'status'  =>  'success',
                'msg'     =>  'Заказ успешно обновлён'
            ], 200);
        } catch (\Exception $error) {
            /** в случае исключения возвращается ошибка с сообщением */
            return response()->json([
                'status' => 'error',
                'msg'    => $error->getMessage()
            ]);
        }
    }

    /**
     * Удаление заказа
     *
     * @param  \App\Model\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $order = Order::findOrFail($id);
            $this->makeLog($order, 15, 1);
            $order->delete();
            return response()->json([
                'status' => 'success',
                'msg' => 'Заказ успешно удалён'
            ], 200);
        } catch (\Exception $error) {
            /** в случае исключения возвращается ошибка с сообщением */
            return response()->json([
                'status' => 'error',
                'msg'    => $error->getMessage()
            ]);
        }
    }

    /** экспорт в Excel */
    public function export()
    {
        return new OrderExport();
    }

    /** конвертация в нужный формат */
    public function convertDate($date, $time)
    {
        return Carbon::parse($date)->addDays(1)->format('Y-m-d') . ' '. $time['HH'].':'.$time['mm'];
    }

    /** добавление заказа из пользовательского интерфейса */
    public function publish($request)
    {
        try {
//            Validator::make([]);
            /** конвертация даты */
            $date = $this->convertDate($request->note_date, $request->note_time);

            /** проверка корреткности даты */
            if  (! $this->validateNoteDate($date)) {
                throw new \Exception('Проверьте корректность даты');
            }
            if (empty($request->master['id'])) {
                throw new \Exception('Вы не указали мастера');
            }

            /** заполнение модели предварительными данными */
            $order = (new Order)->fill([
                'tatoo_id'  => $request->tatoo,
                'user_id'   => auth()->id(),
                'master_id' => $request->master['id'],
                'status'    => 2,
                'note_date' => $date,
                'note_end'  =>  Carbon::parse($date)->addHours(4)
            ]);
            /** сохранение данных в таблице */
            $order->save();
            /** поиск заказа для формирования заказа для email */
            $mail_order = Order::with(['customer', 'tatoo'])->findOrFail($order->id);
            /** отправка письма */
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

    /** проверка корректности даты */
    public function validateNoteDate($date)
    {
        if ($date < Carbon::now()) {
            return false;
        }
        $only_date = Carbon::parse($date)->format('Y-m-d');
        if ($date < $only_date . ' 09:00' || $date > $only_date . ' 22:00') {
            return false;
        }
        $range = Order::whereBetween('note_date', [$only_date. ' 00:00', $only_date. ' 23:59'])->get();
        if (! count($range)) {
            return true;
        }
        foreach ($range as $note_record) {
            if ($date >= $note_record->note_date && $date <= $note_record->note_end) {
                return false;
            }
        }
        return true;
    }

    public function makeLog($subject, $type, $status)
    {
        switch ($status) {
            case 1: $status = json_encode((object)['status' => 'success']); break;
            case 2: $status = json_encode((object)['status' => 'error']); break;
            default: break;
        }
        $subject = json_encode((object)[
            'id' => ($type !== 15) ? $subject->id : null,
            'type' => 'order',
            'customer' => $subject->user_id,
            'tatoo' => $subject->tatoo_id
        ]);
        event(new WriteAudit($subject, $type, $status));
    }
}
