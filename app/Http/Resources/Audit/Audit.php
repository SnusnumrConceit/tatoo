<?php

namespace App\Http\Resources\Audit;

use App\User;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class Audit extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'src'        => $this->getURL($this->subject),
            'subject'    => $this->subject,
            'created_at' => Carbon::parse($this->created_at)->format('d.m.Y H:i:s'),
            'status'     => $this->getStatus($this->status),
            'user'       => $this->getUser($this->user_id),
            'event'      => $this->getType($this->event->event)
        ];
    }

    public function getURL($subject)
    {
        $subject = json_decode($subject);
        switch ($subject->type) {
            case 'user':        return (! empty($subject->id)) ? '/admin/users/' . $subject->id : null;
            case 'appointment': return (! empty($subject->id)) ? '/admin/appointments/' . $subject->id : null;
            case 'employee':    return (! empty($subject->id)) ? '/admin/employees/' . $subject->id : null;
            case 'tatoo':        return (! empty($subject->id)) ? '/admin/tatoos/' . $subject->id : null;
        }
    }

    public function getStatus($status)
    {
        $status = json_decode($status);
        return $status->status;
    }

    public function getUser($id)
    {
        return (! empty($id)) ? User::findOrFail($id) : null;
    }

    public function getType($type) {
        return $type;
    }
}
