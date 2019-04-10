<?php

namespace App\Http\Controllers;

use App\Http\Requests\Admin\Tatoo\TatooFormRequest;
use App\Services\TatooService;
use Illuminate\Http\Request;

class TatooController extends Controller
{
    public $tatoo;

    public function __construct(TatooService $tatoo)
    {
        $this->tatoo = $tatoo;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(TatooFormRequest $request)
    {
        return $this->tatoo->create($request);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return $this->tatoo->store($request);
    }

    /***
     * Search by keyword and filter data in storage
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request)
    {
        return $this->tatoo->search($request);
    }

    /**
     * Display the info with smth relations.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function info(int $id)
    {
        return $this->tatoo->info($id);
    }

    /**
     * Show the form info for editing.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(int $id)
    {
        return $this->tatoo->edit($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(TatooFormRequest $request, int $id)
    {
        return $this->tatoo->update($request, $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return $this->tatoo->destroy($id);
    }
}
