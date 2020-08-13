<?php

namespace App;

use Carbon\Carbon;
use App\Model\Order;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'last_name', 'email', 'password', 'birthday', 'first_name'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get the full name includes last name and first name
     *
     * @return string
     */
    public function getFullNameAttribute()
    {
        return trim($this->last_name . ' ' . $this->first_name);
    }

    /**
     * Get the formatted birthday attribute
     *
     * @return string
     */
    public function getDisplayBirthdayAttribute()
    {
        if (now()->diffInDays($this->birthday) > 0) {
            return Carbon::parse($this->birthday)->format('d.m.Y');
        }

        return Carbon::parse($this->birthday)->diffForHumans();
    }

    /**
     * Get the formatted created_at attribute
     *
     * @return string
     */
    public function getDisplayCreatedAtAttribute()
    {
        if (now()->diffInDays($this->created_at) > 0) {
            return Carbon::parse($this->created_at)->format('d.m.Y');
        }

        return Carbon::parse($this->created_at)->diffForHumans();
    }

    /**
     * Role O-2-O relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function role()
    {
        return $this->hasOne(Role::class, 'name', 'role_name');
    }

    /**
     * Permissions M-2-M relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function permissions()
    {
        return $this->belongsToMany(
            Permission::class, 'users_permissions',
            'user_id', 'permission_name',
            'id', 'name'
        );
    }

    /**
     * Role permissions M-2-M through role relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function rolePermissions()
    {
        return $this->belongsToMany(
            Permission::class, 'roles_permissions',
            'role_name', 'permission_name',
            'role_name', 'name'
        );
    }

    /**
     * Order O-2-M relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Check user has permission for action
     *
     * @param string $permission
     *
     * @return bool
     */
    public function hasPermission(string $permission)
    {
        return $this->hasRolePermission($permission) || $this->hasExtendedPermission($permission);
    }

    /**
     * Check user has extended permission for action
     *
     * @param string $permission
     *
     * @return bool
     */
    public function hasExtendedPermission(string $permission) : bool
    {
        return $this->permissions->contains('name', $permission);
    }

    /**
     * Check user's role has permission for action
     *
     * @param string $permission
     *
     * @return bool
     */
    public function hasRolePermission(string  $permission) : bool
    {
        return $this->rolePermissions->contains('name', 'full') || $this->rolePermissions->contains('name', $permission);
    }
}
