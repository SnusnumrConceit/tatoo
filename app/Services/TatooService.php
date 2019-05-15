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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($request)
    {
        try {
            $request->validated();
            $tatoo = Tatoo::where([
                'name'        => $request->name,
                'description' => $request->description,
            ])->first();
            if ($tatoo) {
                throw new \Exception('Такая татуировка есть в системе');
            }
            $this->image->move($request->url, $request->destination);
            $tatoo = new Tatoo();
            $tatoo->fill([
                'name'        => $request->name,
                'description' => $request->description,
                'price'       => $request->price,
                'url'         => $request->destination
            ]);
            $tatoo->save();
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($request)
    {
        try {
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
     * Search by keyword and filter data in storage
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search($request)
    {
        try {
            $tatoos = new Tatoo();
            if (isset($request->keyword)) {
                $tatoos = $tatoos->where('name', 'LIKE', $request->keyword.'%');
            }
            if (isset($request->filter)) {
                $filter = json_decode($request->filter);

                if (!empty($filter->name) && !empty($filter->type)) {
                    $tatoos = $tatoos->orderBy($filter->name, $filter->type);
                }
            }
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
     * Display the info with smth relations.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function info($id)
    {
        try {
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
     * Show the form info for editing.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
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
            if (! empty($request->destination)) {
                $this->image->move($request->url, $request->destination);
                $url = $request->destination;
            } else {
                $url = $request->url;
            }
            $tatoo = Tatoo::findOrFail($id);
            $tatoo->fill([
                'name'        => $request->name,
                'description' => $request->description,
                'price'       => $request->price,
                'url'         => $url
            ]);
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

    public function export()
    {
        return new TatooExport();
    }

    public function convertDate($date)
    {
        return Carbon::parse($date)->format('Y-m-d');
    }

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