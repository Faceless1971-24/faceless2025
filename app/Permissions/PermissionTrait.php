<?php


namespace App\Permissions;


use App\Models\Role;
use Illuminate\Support\Facades\Cache;

trait PermissionTrait
{
    public function hasPermission($permission): bool
    {
        $user = $this;
        if($user->is_superuser) {
            return TRUE;
        }

        $permissions = Cache::remember('user_permissions_'.$user->id, 60, function() use ($user) {
            $role = session('role');
            return $role->permissions;
        });

        return $permissions->pluck('name')->contains($permission);
    }

    public function permissions()
    {
        $role = session('role');
        return $role->permissions();
    }

    /* public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function is_admin()
    {
        return $this->role->slug === 'admin';
    } */

}
