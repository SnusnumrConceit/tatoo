<?php

namespace App\Http\Controllers;

use App\Http\Requests\Admin\Order\OrderFormRequest;
use App\Model\Order;
use App\Services\OrderService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public $order;

    public function __construct(OrderService $order)
    {
        $this->order = $order;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(OrderFormRequest $request)
    {
        return $this->order->create($request);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return $this->order->store($request);
    }

    public function search(Request $request)
    {
        return $this->order->search($request);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Model\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function edit(int $id)
    {
        return $this->order->edit($id);
    }

    public function info(int $id)
    {
        return $this->order->info($id);
    }

    public function extends()
    {
        return $this->order->extends();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(OrderFormRequest $request, int $id)
    {
        return $this->order->update($request, $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        return $this->order->destroy($id);
    }

    public function export()
    {
        return $this->order->export();
    }

    public function publish(Request $request)
    {
        return $this->order->publish($request);
    }
}
