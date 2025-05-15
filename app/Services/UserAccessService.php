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

        $isSuperuser = $authUser->is_superuser;

        // Define hierarchy using slugs
        $hierarchy = ['union-admin', 'upazila-admin', 'district-admin', 'division-admin'];

        // Superuser & Central access bypass hierarchy restrictions
        if ($isSuperuser || in_array($authPrimaryRole, ['admin', 'central-admin'])) {
            return User::query()
                ->select(['id', 'name', 'userid', 'photo', 'last_login', 'is_active'])
                ->when($isActive !== null, fn($q) => $q->where('is_active', $isActive))
                ->with(['roles' => fn($q) => $q->wherePivot('is_primary', 1)])
                ->get();
        }

        // Find current role level in hierarchy
        $currentLevelIndex = array_search($authPrimaryRole, $hierarchy);

        if ($currentLevelIndex === false) {
            // Role not in hierarchy, return empty collection
            return collect();
        }

        // Get allowed roles up to current level
        $allowedRoles = array_slice($hierarchy, 0, $currentLevelIndex + 1);

        // Extract prefix for filtering by location (e.g., 'union' from 'union-admin')
        $authRolePrefix = explode('-', $authPrimaryRole)[0];

        // Build query for accessible users
        return User::query()
            ->select(['id', 'name', 'userid', 'photo', 'last_login', 'is_active'])
            ->when($isActive !== null, fn($q) => $q->where('is_active', $isActive))
            ->where($authRolePrefix . '_id', $authUser->{$authRolePrefix . '_id'})
            ->whereHas('roles', fn($roleQuery) =>
                $roleQuery->whereIn('roles.slug', $allowedRoles)
                          ->where('role_user.is_primary', 1)
            )
            ->with(['roles' => fn($q) => $q->wherePivot('is_primary', 1)])
            ->get();
    }
}
