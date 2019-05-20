<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class OrderMail extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($order)
    {
        $this->order = $order;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $url = (auth()->user()->hasRole('superadmin|admin')) ? 'http://tatoo.ru/#/admin/orders' : 'http://tatoo.ru/?#/tatoos';
        return $this->from('tatoo.ru@world.info')->markdown('emails.orders.shipping')
            ->with([
                'tatoo_name'        => $this->order->tatoo->name,
                'price'             => $this->order->tatoo->price,
                'order_note_date'   => $this->order->note_date,
                'customer_name'     => $this->order->customer->last_name.' '.$this->order->customer->first_name,
                'url'               => $url
            ])->subject('Заказ на '. config('app.name'));
    }
}
