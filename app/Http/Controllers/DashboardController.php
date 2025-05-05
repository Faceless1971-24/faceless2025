<?php
namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;

class DashboardController extends Controller
{
    public function index()
    {
        $id = auth()->user()->id;
        $user_info = auth()->user();
        $role = Session::get('role');

        $total_employee = User::where('is_superuser', 0)
            ->where('is_active', 1)->count();

        $is_my_birth_day = 0;
        if (date('m-d', strtotime($user_info->dob)) == date('m-d')) {
            $is_my_birth_day = 1;
        }

        // Get user's location details
        $user_division = auth()->user()->division_id;
        $user_district = auth()->user()->district_id;
        $user_upazila = auth()->user()->upazila_id;
        $user_union = auth()->user()->union_id;

        // Query for campaigns based on user's location
        $campaignQuery = Campaign::where('status', 'publish')
            ->where(function ($query) use ($user_division, $user_district, $user_upazila, $user_union) {
                // Check for campaigns that match any of the user's locations
                $query->whereHas('divisions', function ($q) use ($user_division) {
                    if ($user_division) {
                        $q->where('division_id', $user_division);
                    }
                })
                    ->orWhereHas('districts', function ($q) use ($user_district) {
                    if ($user_district) {
                        $q->where('district_id', $user_district);
                    }
                })
                    ->orWhereHas('upazilas', function ($q) use ($user_upazila) {
                    if ($user_upazila) {
                        $q->where('upazila_id', $user_upazila);
                    }
                })
                    ->orWhereHas('unions', function ($q) use ($user_union) {
                    if ($user_union) {
                        $q->where('union_id', $user_union);
                    }
                });
            });

        // Get newest campaigns (last 2 days)
        $newestCampaigns = (clone $campaignQuery)
            ->where('created_at', '>=', Carbon::now()->subDays(2))
            ->orderBy('created_at', 'desc')
            ->get();

        // Count for display
        $countCampaigns = $newestCampaigns->count();

        return view('dashboard.index', [
            'user' => User::with('roles', 'supervisor_of_user', 'designation')
                ->where('id', $id)
                ->active()
                ->first(),
            'total_employee' => $total_employee,
            'user_info' => $user_info,
            'is_my_birth_day' => $is_my_birth_day,
            'role' => $role,
            'newestCampaigns' => $newestCampaigns,
            'countCampaigns' => $countCampaigns,
        ]);
    }
}