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

        $roleHierarchy = [
            'super-admin'    => 0,
            'admin'          => 1,
            'central-admin'  => 2,
            'division-admin' => 3,
            'district-admin' => 4,
            'upazila-admin'  => 5,
            'union-admin'    => 6,
        ];

        $authLevel = $roleHierarchy[$authPrimaryRole] ?? 999;

        // Top dogs get full access, no filters needed
        if ($authUser->is_superuser || in_array($authPrimaryRole, ['admin', 'central-admin'])) {
            return User::query()
                ->select(['id', 'name', 'userid', 'phone', 'photo', 'last_login', 'is_active'])
                ->when($isActive !== null, fn($q) => $q->where('is_active', $isActive))
                ->with(['roles' => fn($q) => $q->wherePivot('is_primary', 1)])
                ->get();
        }

        // Regular users: scope by division/district/etc and exclude users with same or higher role
        $query = User::query()
            ->select(['id', 'name', 'userid', 'phone', 'photo', 'last_login', 'is_active'])
            ->when($isActive !== null, fn($q) => $q->where('is_active', $isActive))
            ->with(['roles' => fn($q) => $q->wherePivot('is_primary', 1)])
            ->where(function ($q) use ($authPrimaryRole, $authUser) {
                match ($authPrimaryRole) {
                    'division-admin' => $q->where('division_id', $authUser->division_id),
                    'district-admin' => $q->where('district_id', $authUser->district_id),
                    'upazila-admin'  => $q->where('upazila_id', $authUser->upazila_id),
                    'union-admin'    => $q->where('union_id', $authUser->union_id),
                    default          => $q->whereNull('id'), // No access at all
                };
            });

        // Exclude users with same or higher role level
        $query->where(function ($q) use ($authLevel, $roleHierarchy) {
            $q->where('is_admin', 0)
              ->orWhereHas('roles', function ($roleQuery) use ($authLevel, $roleHierarchy) {
                  // Fix: Use 'role_user.is_primary' not wherePivot here
                  $roleQuery->where('role_user.is_primary', 1)
                      ->where(function ($subQuery) use ($authLevel, $roleHierarchy) {
                          foreach ($roleHierarchy as $role => $level) {
                              if ($level > $authLevel) {
                                  $subQuery->orWhere('slug', $role);
                              }
                          }
                      });
              });
        });

        return $query->get();
    }
}
    