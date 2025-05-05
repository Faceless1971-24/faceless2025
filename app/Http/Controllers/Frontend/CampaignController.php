<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\CampaignComment;
use App\Models\CampaignSupporter;
use App\Models\Division;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CampaignController extends Controller
{
    /**
     * Display a listing of public campaigns with filters
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = Campaign::query()->where('status', 'publish');

        // Check for higher-level roles like admin or superadmin
        $isAdmin = $user->is_superuser || $user->hasRole('admin') || $user->hasRole('central');
        
        // Location-based visibility logic (for lower-level users)
        if (!$isAdmin) {
            $query->where(function ($q) use ($user) {
                $q->where('is_nationwide', true)
                    ->orWhere(function ($q) use ($user) {
                        $q->whereHas('unions', function ($q) use ($user) {
                            $q->where('unions.id', $user->union_id);
                        });
                    })
                    ->orWhere(function ($q) use ($user) {
                        $q->whereDoesntHave('unions')
                            ->whereHas('upazilas', function ($q) use ($user) {
                                $q->where('upazilas.id', $user->upazila_id);
                            });
                    })
                    ->orWhere(function ($q) use ($user) {
                        $q->whereDoesntHave('unions')
                            ->whereDoesntHave('upazilas')
                            ->whereHas('districts', function ($q) use ($user) {
                                $q->where('districts.id', $user->district_id);
                            });
                    })
                    ->orWhere(function ($q) use ($user) {
                        $q->whereDoesntHave('unions')
                            ->whereDoesntHave('upazilas')
                            ->whereDoesntHave('districts')
                            ->whereHas('divisions', function ($q) use ($user) {
                                $q->where('divisions.id', $user->division_id);
                            });
                    });
            });
        }

        // Apply campaign type filter
        if ($request->filled('campaign_type')) {
            if ($request->campaign_type === 'nationwide') {
                $query->where('is_nationwide', true);
            } else {
                $query->where('campaign_type', $request->campaign_type);
            }
        }

        // Apply search filter
        if ($request->filled('search')) {
            $search = '%' . $request->search . '%';
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', $search)
                    ->orWhere('description', 'like', $search);
            });
        }

        // Get campaigns with the count of supporters and comments, along with other data
        $campaigns = $query->withCount(['supporters', 'comments'])
            ->with(['images', 'analytics'])
            ->latest()
            ->paginate(8);

        // Fetch divisions for filters in the view
        $divisions = Division::all();

        // Get stats for total and new supporters this month
        $totalSupporters = CampaignSupporter::count();
        $newSupportersThisMonth = CampaignSupporter::whereMonth('created_at', now()->month)->count();

        return view('frontend.campaigns.index', [
            'campaigns' => $campaigns,
            'divisions' => $divisions,
            'totalSupporters' => $totalSupporters,
            'newSupportersThisMonth' => $newSupportersThisMonth,
        ]);
    }



    /**
     * Display a specific campaign
     *
     * @param Campaign $campaign
     * @return \Illuminate\View\View
     */
    public function show(Campaign $campaign)
    {
        // Ensure the campaign is published
        if ($campaign->status !== 'publish') {
            abort(404);
        }

        if (!$campaign->analytics) {
            $campaign->analytics()->create(); // creates with default values (0s)
            $campaign->refresh(); // reload relationships
        }
        $campaign->analytics->incrementViews();


        // Load relationships
        $campaign->load([
            'images',
            'analytics',
            'audio',
            'video',
            'divisions',   // 🛠️ Added
            'districts',   // 🛠️ Added
            'upazilas',    // 🛠️ Added
            'unions',      // 🛠️ Added
            'comments' => function ($query) {
                $query->where('approved', true)->latest();
            },
            'comments.user',
            'supporters' => function ($query) {
                $query->latest()->take(10);
            }
        ]);


        // Check if current user has supported this campaign
        $hasSupported = false;
        if (Auth::check()) {
            $hasSupported = CampaignSupporter::where('campaign_id', $campaign->id)
                ->where('user_id', Auth::id())
                ->exists();
        }

        // Get related campaigns
        $relatedCampaigns = Campaign::where('status', 'publish')
            ->where('id', '!=', $campaign->id)
            ->when($campaign->campaign_type, function ($query) use ($campaign) {
                return $query->where('campaign_type', $campaign->campaign_type);
            })
            ->with('images')
            ->latest()
            ->take(3)
            ->get();

        return view('frontend.campaigns.show', compact(
            'campaign',
            'hasSupported',
            'relatedCampaigns'
        ));
    }

    /**
     * Support a campaign
     *
     * @param Request $request
     * @param Campaign $campaign
     * @return \Illuminate\Http\RedirectResponse
     */
    public function support(Request $request, Campaign $campaign)
    {
        // Check if campaign is published
        if ($campaign->status !== 'publish') {
            return redirect()->back()->with('error', 'ক্যাম্পেইনটি বর্তমানে সক্রিয় নয়!');
        }

        // Check if user has already supported
        $exists = CampaignSupporter::where('campaign_id', $campaign->id)
            ->where('user_id', Auth::id())
            ->exists();

        if ($exists) {
            return redirect()->back()->with('info', 'আপনি ইতিমধ্যে এই ক্যাম্পেইন সমর্থন করেছেন!');
        }

        // Create new supporter record
        CampaignSupporter::create([
            'campaign_id' => $campaign->id,
            'user_id' => Auth::id(),
            'message' => $request->input('message')
        ]);

        // Update the supporters count
        if ($campaign->analytics) {
            $campaign->analytics->increment('supporters_count');
        }

        return redirect()->back()->with('success', 'আপনি সফলভাবে এই ক্যাম্পেইন সমর্থন করেছেন!');
    }

    /**
     * Share a campaign
     *
     * @param Campaign $campaign
     * @return \Illuminate\Http\RedirectResponse
     */
    public function share(Campaign $campaign)
    {
        // Record share analytics
        if ($campaign->analytics) {
            $campaign->analytics->increment('shares_count');
        }

        return response()->json(['success' => true]);
    }

    /**
     * Add a comment to a campaign
     *
     * @param Request $request
     * @param Campaign $campaign
     * @return \Illuminate\Http\RedirectResponse
     */
    public function comment(Request $request, Campaign $campaign)
    {
       $validate = $request->validate([
            'comment' => 'required|string|max:500'
        ]);

        // Determine if comments need approval (you can set this in campaign settings)
        $needsApproval = true; // Set to your system's logic

        $comment_data = new CampaignComment();

        $comment_data->campaign_id = $campaign->id;
        $comment_data->user_id = auth()->user()->id;
        $comment_data->comment = $validate['comment'];
        $comment_data->approved = $needsApproval;
        $comment_data->save();

        $message = $needsApproval
            ? 'আপনার মন্তব্য জমা হয়েছে এবং অনুমোদনের অপেক্ষায় আছে।'
            : 'আপনার মন্তব্য সফলভাবে যোগ করা হয়েছে!';

        return redirect()->back()->with('success', $message);
    }
}