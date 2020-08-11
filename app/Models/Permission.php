<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $primaryKey = 'name';
    
    public $incrementing = false;
    protected $guarded = [];
    
    public $timestamps = false;
    
    protected $casts = [
        'is_group' => 'boolean'
    ];
    
    /**
     * Roles M-2-M relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(
            Role::class, 'roles_permissions',
            'permission_name', 'role_name',
            'name', 'name'
        );
    }
    
    /**
     * Users M-2-M relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(
            User::class, 'users_permissions',
            'permission_name', 'user_id',
            'user_id', 'name');
    }
}
