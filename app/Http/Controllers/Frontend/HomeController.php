<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\NoticeBoard;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display the home page with featured campaigns and notices
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = auth()->user();
        if(!$user){
           return redirect()->route('login');

        }
        $primaryRole = optional($user->roles()->wherePivot('is_primary', 1)->first())->slug;
        $isSuperuser = $user->is_superuser;

        $campaignQuery = Campaign::where('status', 'publish')
            ->where(function ($query) use ($user, $primaryRole, $isSuperuser) {
                // 🔓 If user is high-tier, show all published campaigns
                if ($isSuperuser || in_array($primaryRole, ['admin', 'central'])) {
                    $query->whereRaw('1=1'); // no location filtering
                    return;
                }

                // 👇 Apply location-based filtering
                $query->where('is_nationwide', true);

                if ($user) {
                    $query->orWhere(function ($q) use ($user) {
                        $q->whereHas('unions', fn($uq) => $uq->where('unions.id', $user->union_id));
                    });

                    $query->orWhere(function ($q) use ($user) {
                        $q->whereHas('upazilas', fn($uq) => $uq->where('upazilas.id', $user->upazila_id))
                        ->whereDoesntHave('unions');
                    });

                    $query->orWhere(function ($q) use ($user) {
                        $q->whereHas('districts', fn($dq) => $dq->where('districts.id', $user->district_id))
                        ->whereDoesntHave('upazilas')->whereDoesntHave('unions');
                    });

                    $query->orWhere(function ($q) use ($user) {
                        $q->whereHas('divisions', fn($dq) => $dq->where('divisions.id', $user->division_id))
                        ->whereDoesntHave('districts')->whereDoesntHave('upazilas')->whereDoesntHave('unions');
                    });
                }
            });

        // 🔥 Featured campaigns
        $featuredCampaigns = (clone $campaignQuery)
            ->where('featured', true)
            ->with(['images', 'analytics'])
            ->latest()
            ->take(5)
            ->get();

        // 🆕 Latest campaigns
        $latestCampaigns = (clone $campaignQuery)
            ->with(['images', 'analytics'])
            ->latest()
            ->take(6)
            ->get();

        // 🚀 Top campaigns by engagement metrics
        $topCampaigns = (clone $campaignQuery)
            ->with(['images', 'analytics'])
            ->whereHas('analytics')
            ->get()
            ->sortByDesc(fn($campaign) =>
                $campaign->analytics->views +
                $campaign->analytics->engagements +
                $campaign->analytics->supporters_count
            )
            ->take(3);

        return view('frontend.home', compact('featuredCampaigns', 'latestCampaigns', 'topCampaigns'));
    }


    /**
     * Display the about page
     *
     * @return \Illuminate\View\View
     */
    public function about()
    {
        return view('frontend.about');
    }

    /**
     * Display the volunteer opportunities page
     *
     * @return \Illuminate\View\View
     */
    public function volunteer()
    {
        return view('frontend.volunteer');
    }

    /**
     * Register as a volunteer
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function volunteerRegister(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'skills' => 'required|string',
            'availability' => 'required|string',
            'message' => 'nullable|string'
        ]);

        // Process volunteer registration logic here
        // Store in volunteers table or create a notification for admins

        return redirect()->route('volunteer.index')
            ->with('success', 'আপনার স্বেচ্ছাসেবী আবেদন সফলভাবে জমা হয়েছে। আমরা শীঘ্রই আপনার সাথে যোগাযোগ করব।');
    }
}