<?php

namespace App\Model;

use App\Models\Tatoo;
use App\User;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = ['user_id', 'tatoo_id', 'status', 'note_date'];

    public function customer()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function tatoo()
    {
        return $this->belongsTo(Tatoo::class, 'tatoo_id', 'id');
    }
}
