<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserAccessService
{
    public function getAccessibleUsers($isActive = 1)
    {
        $authUser = Auth::user();

        $authPrimaryRole = optional(
            $authUser->roles()->wherePivot('is_primary', 1)->first()
        )->slug;

        if ($authUser->is_superuser || in_array($authPrimaryRole, ['admin', 'central-admin'])) {
            // Superuser or central-admin -> see all users
            return User::query()
                ->select(['id', 'name', 'userid','phone', 'photo', 'last_login', 'is_active'])
                ->when($isActive !== null, fn($q) => $q->where('is_active', $isActive))
                ->with(['roles' => fn($q) => $q->wherePivot('is_primary', 1)])
                ->get();
        }

        if ($authPrimaryRole === 'division-admin') {
            return User::query()
                ->select(['id', 'name', 'userid', 'photo', 'last_login', 'is_active'])
                ->when($isActive !== null, fn($q) => $q->where('is_active', $isActive))
                ->where('division_id', $authUser->division_id)
                ->where('is_admin', 0)
                ->with(['roles' => fn($q) => $q->wherePivot('is_primary', 1)])
                ->get();
        }

        if ($authPrimaryRole === 'district-admin') {
            return User::query()
                ->select(['id', 'name', 'userid', 'photo', 'last_login', 'is_active'])
                ->when($isActive !== null, fn($q) => $q->where('is_active', $isActive))
                ->where('district_id', $authUser->district_id)
                ->where('is_admin', 0)
                ->with(['roles' => fn($q) => $q->wherePivot('is_primary', 1)])
                ->get();
        }

        if ($authPrimaryRole === 'upazila-admin') {
            return User::query()
                ->select(['id', 'name', 'userid', 'photo', 'last_login', 'is_active'])
                ->when($isActive !== null, fn($q) => $q->where('is_active', $isActive))
                ->where('upazila_id', $authUser->upazila_id)
                ->where('is_admin', 0)
                ->with(['roles' => fn($q) => $q->wherePivot('is_primary', 1)])
                ->get();
        }

        if ($authPrimaryRole === 'union-admin') {
            return User::query()
                ->select(['id', 'name', 'userid', 'photo', 'last_login', 'is_active'])
                ->when($isActive !== null, fn($q) => $q->where('is_active', $isActive))
                ->where('union_id', $authUser->union_id)
                ->where('is_admin', 0)
                ->with(['roles' => fn($q) => $q->wherePivot('is_primary', 1)])
                ->get();
        }

        // No matching role? No access.
        return collect();
    }
}
