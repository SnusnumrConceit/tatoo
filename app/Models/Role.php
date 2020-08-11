<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $primaryKey = 'name';
    
    public $incrementing = false;
    
    protected $guarded = ['is_protected'];
    
    public $timestamps = false;
    
    protected $casts = [
        'is_protected' => 'boolean'
    ];
    
    /**
     * Role permissions
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function permissions()
    {
        return $this->belongsToMany(
            Permission::class, 'roles_permissions',
            'role_name', 'permission_name',
            'name', 'name'
        );
    }
    
    /**
     * Check role has permission
     *
     * @param string $permission
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function hasPermission(string $permission)
    {
        return $this->permissions->contains('name', $permission);
    }
}
