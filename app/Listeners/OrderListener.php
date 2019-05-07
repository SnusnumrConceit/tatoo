<?php

namespace App\Listeners;

use App\Events\OrderCompleted;
use App\Mail\OrderMail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class OrderListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(OrderCompleted$event)
    {
        Mail::to($event->order->customer)->send(new OrderMail(
            $event->order
        ));
    }
}
