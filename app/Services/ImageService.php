<?php
/**
 * Created by PhpStorm.
 * User: snusnumr
 * Date: 09.04.19
 * Time: 22:12
 */

namespace App\Services;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImageService
{
    /***
     * Загрузка картинки
     *
     * @param $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function upload($request)
    {
        try {
            /** если файл img отсутсвует */
            if (empty($request->file('img'))) {
                /** генерируется ошибка */
                throw new \Exception('Изображение не было загружено');
            }

            /** если отсутствует параметр channel */
            if (empty($request->channel)) {
                /** генерируется ошибка */
                throw new \Exception('Изображение не было загружено');
            }

            $channel = $request->channel;
            /** относительно канала получаем путь до временной папки */
            switch ($channel) {
                case 'tatoo': $tmp = '/public/pictures/tatoos/tmp'; break;
                case 'master': $tmp = '/public/pictures/masters/tmp'; break;
                default: $tmp = null; break;
            }

            /** если был передан неверный канал */
            if (empty($tmp)) {
                /** генерируется ошибка */
                throw new \Exception('Изображение не было загружено');
            }

            $picture = $request->file('img');
            /** загрузка картинки по определённому выше пути */
            $destination = Storage::disk('local')->put($tmp, $picture);

            return response()->json([
                'status' => 'success',
                'tmp'    => $destination
            ], 200);
        } catch (\Exception $error) {
            return response()->json([
                'status' => 'error',
                'msg'    => $error->getMessage()
            ]);
        }
    }

    /***
     * Перемещение картинки
     *
     * @param $from
     * @param $to
     * @return \Illuminate\Http\JsonResponse
     */
    public function move($from, $to)
    {
        try {
            /** если не удалось переместить картинку */
            if (! Storage::disk('local')->move($from, $to)) {
                /** генерируется ошибка */
                throw new \Exception('Не удалось переместить файл');
            }
            return response()->json([
                'status' => 'success'
            ]);
        } catch (\Exception $error) {
            return response()->json([
                'status' => 'error',
                'msg'    => $error->getMessage()
            ]);
        }
    }

    /***
     * Удаление картинки
     *
     * @param $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function remove($request)
    {
        try {
            /** если не передан параметр destination */
            if (empty($request->destination)) {
                /** генерируется ошибка */
                throw new \Exception('Не удалось удалить фотографию');
            }

            $destination = $request->destination;
            /** если картинка не найдена */
            if (! Storage::disk('local')->exists($destination)) {
                /** генерируется ошибка */
                throw new \Exception('Не удалось удалить фотографию');
            }
            /** удаление картинки */
            Storage::disk('local')->delete($destination);
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
}
