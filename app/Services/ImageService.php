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
    public function upload($request)
    {
        try {
            if (empty($request->file('img'))) {
                throw new \Exception('Изображение не было загружено');
            }
            if (empty($request->channel)) {
                throw new \Exception('Изображение не было загружено');
            }
            $channel = $request->channel;
            switch ($channel) {
                case 'tatoo': $tmp = '/public/pictures/tatoos/tmp'; break;
                case 'master': $tmp = '/public/pictures/masters/tmp'; break;
                default: $tmp = null; break;
            }
            if (empty($tmp)) {
                throw new \Exception('Изображение не было загружено');
            }
            $picture = $request->file('img');

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

    public function move($from, $to)
    {
        try {
            if (! Storage::disk('local')->move($from, $to)) {
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

    public function remove($request)
    {
        try {
            if (empty($request->destination)) {
                throw new \Exception('Не удалось удалить фотографию');
            }
            $destination = $request->destination;
            if (! Storage::disk('local')->exists($destination)) {
                throw new \Exception('Не удалось удалить фотографию');
            }
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