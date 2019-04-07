<?php

namespace App\Http\Controllers;

use App\Http\Resources\Admin\Tatoo\TatooCollection;
use App\Models\Tatoo;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TatooController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        try {
            $tatoo = Tatoo::where([
                'name' => $request->name,
                'description' => $request->description,
            ])->first();
            if ($tatoo) {
                throw new \Exception('Такой работник есть в системе');
            }
            $tatoo = new Tatoo();
            $tatoo->fill([
                'name' => $request->name,
                'description' => $request->description,
                'price' => $request->price,
                'url' => $request->url
            ]);
            $tatoo->save();
            return response()->json([
                'status' => 'success',
                'msg' => 'Тату успешно добавлен в систему!'
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
    public function store(Request $request)
    {
        try {
            $tatoos = ($request->page)
                ? Tatoo::paginate(15)
                : Tatoo::all();
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

    /***
     * Search by keyword and filter data in storage
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request)
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
    public function update(Request $request, $id)
    {
        try {
            $tatoo = Tatoo::where([
                'name' => $request->name,
                'description' => $request->description,
            ])->first();
            if ($tatoo) {
                throw new \Exception('Такой работник есть в системе');
            }
            $tatoo = new Tatoo();
            $tatoo->fill([
                'name' => $request->name,
                'description' => $request->description,
                'price' => $request->price,
                'url' => $request->url
            ]);
            $tatoo->save();
            return response()->json([
                'status' => 'success',
                'msg' => 'Тату успешно обновлён!'
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

    public function convertDate($date)
    {
        return Carbon::parse($date)->format('Y-m-d');
    }
}
