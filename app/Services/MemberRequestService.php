<?php
namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class MemberRequestService
{
    /**
     * Get member requests filtered by role and status
     *
     * @param string|null $status
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function getMemberRequestsByRole(?string $status = 'pending')
    {
        $authUser = Auth::user();
        $userRole = optional($authUser->roles()->wherePivot('is_primary', 1)->first())->slug;

        $query = User::query()
            ->select(['id', 'name', 'userid', 'phone', 'photo', 'created_at', 'status', 'division_id', 'district_id', 'upazila_id', 'union_id'])
            ->with([
                'division:id,bn_name',
                'district:id,bn_name',
                'upazila:id,bn_name',
                'union:id,bn_name',
            ]);

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        // No location filtering for global admins
        if (in_array($userRole, ['admin', 'central-admin'])) {
            return $query;
        }

        // Filter by role and location
        switch ($userRole) {
            case 'division-admin':
                $query->where('division_id', $authUser->division_id);
                break;
            case 'district-admin':
                $query->where('district_id', $authUser->district_id);
                break;
            case 'upazila-admin':
                $query->where('upazila_id', $authUser->upazila_id);
                break;
            case 'union-admin':
                $query->where('union_id', $authUser->union_id);
                break;
            // optionally add default for other roles or no filtering
        }

        return $query;
    }

}
