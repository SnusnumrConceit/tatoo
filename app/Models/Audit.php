<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Audit extends Model
{
    protected $table = 'audits';
    protected $fillable = ['subject', 'status', 'type', 'user_id'];

    public function event()
    {
        return $this->belongsTo(AuditEvent::class, 'type', 'id');
    }
}
