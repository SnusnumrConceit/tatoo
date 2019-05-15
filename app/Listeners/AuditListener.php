<?php

namespace App\Listeners;

use App\Events\WriteAudit;
use App\Models\Audit;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class AuditListener
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
    public function handle(WriteAudit $event)
    {
        try {
            $user_id = (empty($event->user_id)) ? auth()->id() : $event->user_id;
            $audit = (new Audit())->fill([
                'subject'   => $event->subject,
                'status'    => $event->status,
                'type'      => $event->type,
                'user_id'   => $user_id
            ]);
            $audit->save();
        } catch (\Exception $error) {
            Log::error('Something wrong in Audit module. The message is: '. $error->getMessage());
        }
    }
}
