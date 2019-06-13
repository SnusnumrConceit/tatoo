<?php
/**
 * Created by PhpStorm.
 * User: snusnumr
 * Date: 09.04.19
 * Time: 23:02
 */

namespace App\Services;

use App\Events\WriteAudit;
use App\Exports\TatooExport;
use App\Http\Resources\Admin\Tatoo\TatooCollection;
use App\Http\Resources\Admin\Tatoo\TatooInfo;
use App\Model\Order;
use App\Models\Tatoo;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class TatooService
{
    public $image;

    public function __construct(ImageService $image)
    {
        $this->image = $image;
    }

    /**
     * Добавление татуировки
     *
     * @return \Illuminate\Http\Response
     */
    public function create($request)
    {
        try {
            /** проверка параметров */
            $request->validated();

            /** поиск и проверка на наличие дубля */
            $tatoo = Tatoo::where([
                'name'        => $request->name,
                'description' => $request->description,
            ])->first();
            if ($tatoo) {
                throw new \Exception('Такая татуировка есть в системе');
            }

            /** перемещение загруженного эскиза */
            $this->image->move($request->url, $request->destination);
            /** инициализация возможности "общения" с таблицей Tatoos через модель */
            $tatoo = new Tatoo();
            /** заполнение промежуточными данными */
            $tatoo->fill([
                'name'        => $request->name,
                'description' => $request->description,
                'price'       => $request->price,
                'url'         => $request->destination
            ]);
            /** сохранение */
            $tatoo->save();
            /** возврат успешного статуса и сообщения */
            $this->makeLog($tatoo, 4, 1);
            return response()->json([
                'status' => 'success',
                'msg' => 'Тату успешно добавлена в систему!'
            ], 200);
        } catch (\Exception $error) {
            return response()->json([
                'status' => 'error',
                'msg' => $error->getMessage()
            ]);
        }
    }

    /**
     * Хранилище татуировок
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($request)
    {
        try {
            /** получение по 15 татуировок на странице */
            $tatoos = Tatoo::paginate(15);
            return response()->json([
                'tatoos' => new TatooCollection($tatoos)
            ], 200);
        } catch (\Exception $error) {
            return response()->json([
                'status' => 'error',
                'msg'    => $error->getMessage()
            ]);
        }
    }

    /***
     * Поиск и сортировка по татуировкам
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search($request)
    {
        try {
            /** инициализация возможности "общения" с таблицей Tatoos через модель */
            $tatoos = new Tatoo();

            /** при наличии параметра keyword */
            if (isset($request->keyword)) {
                /** выполняется поиск по названию */
                $tatoos = $tatoos->where('name', 'LIKE', $request->keyword.'%');
            }

            /** при наличии параметра filter */
            if (isset($request->filter)) {
                /** конвертация из JSON */
                $filter = json_decode($request->filter);

                /** выполняется сортировка по полю и типу */
                if (!empty($filter->name) && !empty($filter->type)) {
                    $tatoos = $tatoos->orderBy($filter->name, $filter->type);
                }
            }
            /** получение по 10 татуировок на страницу */
            $tatoos = $tatoos->paginate(10);
            return response()->json([
                'tatoos' => new TatooCollection($tatoos)
            ], 200);
        } catch (\Exception $error) {
            return response()->json([
                'status' => 'error',
                'msg' => $error->getMessage()
            ]);
        }
    }

    /**
     * Получение информации о татуировке
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function info($id)
    {
        try {
            /** поиск татуировки с мастерами по идентификатору */
            $tatoo = Tatoo::with('masters')->findOrFail($id);
            return response()->json([
                'tatoo' => new TatooInfo($tatoo)
            ], 200);
        } catch (\Exception $error) {
            return response()->json([
                'status' => 'error',
                'msg' => $error->getMessage()
            ]);
        }
    }

    /**
     * Получение татуировки для редактирования
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            /** поиск татуировки по идентификатору */
            $tatoo = Tatoo::findOrFail($id);
            return response()->json([
                'tatoo' => $tatoo
            ], 200);
        } catch (\Exception $error) {
            return response()->json([
                'status' => 'error',
                'msg' => $error->getMessage()
            ]);
        }
    }

    /**
     * Обновление информации о татуировке
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($request, $id)
    {
        try {
            /** проверка входных параметров */
            $request->validated();

            /** если указан параметр destination */
            if (! empty($request->destination)) {
                /** переместить картинку по этому пути */
                $this->image->move($request->url, $request->destination);
                $url = $request->destination;
            } else {
                /** переместить по url */
                $url = $request->url;
            }

            /** поиск татуировки по идентификатору */
            $tatoo = Tatoo::findOrFail($id);
            /** заполнение промежуточными данными */
            $tatoo->fill([
                'name'        => $request->name,
                'description' => $request->description,
                'price'       => $request->price,
                'url'         => $url
            ]);
            /** сохранение */
            $tatoo->save();
            $this->makeLog($tatoo, 5, 1);
            return response()->json([
                'status' => 'success',
                'msg' => 'Тату успешно обновлена!'
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $tatoo = Tatoo::findOrFail($id);
            $this->makeLog($tatoo, 6, 1);
            $tatoo->delete();
            return response()->json([
                'status' => 'success',
                'msg' => 'Тату успешно удалён!'
            ], 200);
        } catch (\Exception $error) {
            return response()->json([
                'status' => 'error',
                'msg' => $error->getMessage()
            ]);
        }
    }

    /***
     * Экспорт татуировок
     *
     * @return TatooExport
     */
    public function export()
    {
        return new TatooExport();
    }

    /***
     * Конвертация даты в формат Y-m-d
     *
     * @param $date
     * @return string
     */
    public function convertDate($date)
    {
        return Carbon::parse($date)->format('Y-m-d');
    }

    /***
     * Получение списка мастеров для татуировок
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTatooMasters($id)
    {
        try {
            $tatoo = Tatoo::with('masters')->findOrFail($id);
            return response()->json([
                'masters' => $tatoo->masters
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
            'id' => ($type !== 3) ? $subject->id : null,
            'type' => 'tatoo',
            'name' => $subject->name]);
        event(new WriteAudit($subject, $type, $status));
    }
}
