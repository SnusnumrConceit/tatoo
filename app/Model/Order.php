<?php

namespace App\Model;

use App\Models\Employee;
use App\Models\Tatoo;
use App\User;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = ['user_id', 'tatoo_id', 'status', 'note_date', 'master_id', 'note_end'];

    public function customer()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function tatoo()
    {
        return $this->belongsTo(Tatoo::class, 'tatoo_id', 'id');
    }

    public function master()
    {
        return $this->belongsTo(Employee::class, 'master_id', 'id');
    }

    public function sortByTatoo($type)
    {
        return $this->join('tatoos', 'tatoos.id', '=', 'orders.tatoo_id')
            ->orderBy('name', $type)
            ->select('orders.*');
    }

    public function sortByCustomer($type)
    {
        return $this->join('users', 'users.id', '=', 'orders.user_id')
            ->orderBy('last_name', $type)
            ->select('orders.*');
    }

    public function searchByTatoo($keyword)
    {
        return $this->join('tatoos', 'tatoos.id', '=', 'orders.tatoo_id')
            ->where('name', 'LIKE', $keyword.'%')
            ->select('orders.*');
    }
}
