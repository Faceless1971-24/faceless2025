<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\Division;
use App\Models\District;
use App\Models\Upazila;
use App\Models\Union;
use App\Models\CampaignImage;
use App\Models\CampaignAudio;
use App\Models\CampaignVideo;
use App\Models\CampaignFile;
use App\Models\CampaignAnalytics;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
    use Carbon\Carbon;
    
    use Illuminate\Support\Facades\Log;


class CampaignController extends Controller
{
    /**
     * Display a listing of the campaigns.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $primaryRole = optional($user->roles()->wherePivot('is_primary', 1)->first())->slug;
        $isSuperuser = $user->is_superuser;

        $query = Campaign::with(['images', 'analytics', 'creator']);

        // 🔓 If superuser or admin/central, skip location filters
        if (!$isSuperuser && !in_array($primaryRole, ['admin', 'central'])) {
            $query->where(function ($q) use ($user) {
                $q->where('is_nationwide', true)
                    ->orWhere(function ($q2) use ($user) {
                        $q2->whereHas('unions', function ($uq) use ($user) {
                            $uq->where('unions.id', $user->union_id);
                        });
                    })
                    ->orWhere(function ($q2) use ($user) {
                        $q2->whereHas('upazilas', function ($uq) use ($user) {
                            $uq->where('upazilas.id', $user->upazila_id);
                        })->whereDoesntHave('unions');
                    })
                    ->orWhere(function ($q2) use ($user) {
                        $q2->whereHas('districts', function ($dq) use ($user) {
                            $dq->where('districts.id', $user->district_id);
                        })->whereDoesntHave('upazilas')->whereDoesntHave('unions');
                    })
                    ->orWhere(function ($q2) use ($user) {
                        $q2->whereHas('divisions', function ($dq) use ($user) {
                            $dq->where('divisions.id', $user->division_id);
                        })->whereDoesntHave('districts')->whereDoesntHave('upazilas')->whereDoesntHave('unions');
                    });
            });
        }

        // 🧃 Optional filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('campaign_type')) {
            if ($request->campaign_type === 'nationwide') {
                $query->where('is_nationwide', true);
            } else {
                $query->where('campaign_type', $request->campaign_type);
            }
        }

        if ($request->filled('featured')) {
            $query->where('featured', $request->featured == '1');
        }

        if ($request->filled('search')) {
            $search = '%' . $request->search . '%';
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', $search)
                    ->orWhere('description', 'like', $search);
            });
        }

        $campaigns = $query->latest()->paginate(9);

        return view('campaigns.index', compact('campaigns'));
    }

    /**
     * Show the form for creating a new campaign.
     */
    public function create()
    {
        $divisions = Division::orderBy('bn_name')->get();

        return view('campaigns.create', compact('divisions'));
    }

    /**
     * Store a newly created campaign in storage.
     */

    public function store(Request $request)
    {
        // Debugging to ensure data is coming in correctly

        // Validate the request
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'campaign_type' => 'required|in:nationwide,division,district,upazila,union',
            'is_nationwide' => 'sometimes|boolean',
            'start_date' => 'required|string|date_format:d/m/Y',
            'end_date' => 'required|string|date_format:d/m/Y',
            'status' => 'required|in:draft,publish,scheduled',
            'notification_send' => 'required|in:yes,no',
            'featured' => 'sometimes|boolean',
            'division_ids' => 'sometimes|array',
            'district_ids' => 'sometimes|array',
            'upazila_ids' => 'sometimes|array',
            'union_ids' => 'sometimes|array',
            'campaign_images.*' => 'sometimes|file|image|max:5120', // 5MB max
            'campaign_audio' => 'sometimes|file|mimes:mp3,wav,ogg|max:10240', // 10MB max
            'campaign_video' => 'sometimes|file|mimes:mp4,webm,mov|max:51200', // 50MB max
            'file_paths.*' => 'sometimes|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx|max:10240', // 10MB max
        ]);

        // Safer date parsing and validation
        try {
            $startDate = Carbon::createFromFormat('d/m/Y', $request->start_date)->format('Y-m-d');
            $endDate = Carbon::createFromFormat('d/m/Y', $request->end_date)->format('Y-m-d');

            // Additional date validation
            if ($startDate > $endDate) {
                return back()
                    ->withInput()
                    ->with('error', 'Start date cannot be later than the end date.');
            }
        } catch (\Exception $e) {
            Log::error('Date parsing error: ' . $e->getMessage());
            return back()
                ->withInput()
                ->with('error', 'Invalid date format. Please use dd/mm/yyyy format.');
        }

        // Create the Campaign
        $campaign = Campaign::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'campaign_type' => $validated['campaign_type'],
            'is_nationwide' => $validated['is_nationwide'] ?? false,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'status' => $validated['status'],
            'notification_send' => $validated['notification_send'],
            'featured' => $validated['featured'] ?? false,
            'created_by' => auth()->id(),
        ]);

        // Attach campaign locations
        if (!$campaign->is_nationwide) {
            $this->attachCampaignLocations($campaign, $request);
        }

        // Handle file uploads for images, audio, video, and documents
        if ($request->hasFile('campaign_images')) {
            $this->uploadCampaignImages($campaign, $request);
        }

        if ($request->hasFile('campaign_audio')) {
            $this->uploadCampaignAudio($campaign, $request);
        }

        if ($request->hasFile('campaign_video')) {
            $this->uploadCampaignVideo($campaign, $request);
        }

        if ($request->hasFile('file_paths')) {
            $this->uploadCampaignFiles($campaign, $request);
        }

        // Attach campaign analytics
        $campaign->analytics()->create();

        return back()
            ->withInput()
            ->with('success', 'ক্যাম্পেইন তৈরি হয়েছে: ');        
    }

    private function attachCampaignLocations($campaign, $request)
    {
        // Attach divisions, districts, upazilas, and unions
        if ($request->has('division_ids') && !empty($request->division_ids)) {
            $campaign->divisions()->attach($request->division_ids);
        }

        if ($request->has('district_ids') && !empty($request->district_ids)) {
            $campaign->districts()->attach($request->district_ids);
        }

        if ($request->has('upazila_ids') && !empty($request->upazila_ids)) {
            $campaign->upazilas()->attach($request->upazila_ids); // Attach upazilas here
        }

        if ($request->has('union_ids') && !empty($request->union_ids)) {
            $campaign->unions()->attach($request->union_ids);
        }
    }

    private function uploadCampaignImages($campaign, $request)
    {
        foreach ($request->file('campaign_images') as $image) {
            $path = $image->store('campaign_images', 'public');
            $campaign->images()->create(['path' => $path]);
        }
    }

    private function uploadCampaignAudio($campaign, $request)
    {
        $audio = $request->file('campaign_audio');
        $path = $audio->store('campaign_audio', 'public');
        $campaign->audio()->create(['path' => $path]);
    }

    private function uploadCampaignVideo($campaign, $request)
    {
        $video = $request->file('campaign_video');
        $path = $video->store('campaign_video', 'public');
        $campaign->video()->create(['path' => $path]);
    }

    private function uploadCampaignFiles($campaign, $request)
    {
        foreach ($request->file('file_paths') as $file) {
            $path = $file->store('campaign_files', 'public');
            $campaign->files()->create(['path' => $path]);
        }
    }



    /**
     * Attach campaign locations based on campaign type
     */


    /**
     * Handle campaign media and file uploads
     */
    private function handleCampaignMedia(Campaign $campaign, Request $request)
    {
        // Handle campaign images
        if ($request->hasFile('campaign_images')) {
            foreach ($request->file('campaign_images') as $image) {
                $path = $image->store('campaigns/images', 'public');

                CampaignImage::create([
                    'campaign_id' => $campaign->id,
                    'file_path' => $path,
                    'file_name' => $image->getClientOriginalName(),
                    'file_type' => $image->getMimeType(),
                    'file_size' => $image->getSize(),
                ]);
            }
        }

        // Handle campaign audio
        if ($request->hasFile('campaign_audio')) {
            $audio = $request->file('campaign_audio');
            $path = $audio->store('campaigns/audio', 'public');

            CampaignAudio::create([
                'campaign_id' => $campaign->id,
                'file_path' => $path,
                'file_name' => $audio->getClientOriginalName(),
                'file_type' => $audio->getMimeType(),
                'file_size' => $audio->getSize(),
            ]);
        }

        // Handle campaign video
        if ($request->hasFile('campaign_video')) {
            $video = $request->file('campaign_video');
            $path = $video->store('campaigns/videos', 'public');

            CampaignVideo::create([
                'campaign_id' => $campaign->id,
                'file_path' => $path,
                'file_name' => $video->getClientOriginalName(),
                'file_type' => $video->getMimeType(),
                'file_size' => $video->getSize(),
            ]);
        }

        // Handle campaign files/documents
        if ($request->hasFile('file_paths')) {
            foreach ($request->file('file_paths') as $file) {
                $path = $file->store('campaigns/files', 'public');

                CampaignFile::create([
                    'campaign_id' => $campaign->id,
                    'file_path' => $path,
                    'file_name' => $file->getClientOriginalName(),
                    'file_type' => $file->getMimeType(),
                    'file_size' => $file->getSize(),
                ]);
            }
        }
    }
    /**
     * Display the specified campaign.
     */
    public function show(Campaign $campaign)
    {
        $campaign->load([
            'creator',
            'divisions',
            'districts',
            'upazilas',
            'unions',
            'images',
            'audio',
            'video',
            'files',
            'analytics'
        ]);

        if (!$campaign->analytics) {
            $campaign->analytics()->create(); // creates with default values (0s)
            $campaign->refresh(); // reload relationships
        }
        $campaign->analytics->incrementViews();


        return view('campaigns.show', compact('campaign'));
    }

    /**
     * Show the form for editing the specified campaign.
     */
    public function edit(Campaign $campaign)
    {
        $divisions = Division::orderBy('bn_name')->get();

        // Load campaign relationships
        $campaign->load([
            'divisions',
            'districts',
            'upazilas',
            'unions',
            'images',
            'audio',
            'video',
            'files'
        ]);

        // Get selected location IDs
        $selectedDivisions = $campaign->divisions->pluck('id')->toArray();
        $selectedDistricts = $campaign->districts->pluck('id')->toArray();
        $selectedUpazilas = $campaign->upazilas->pluck('id')->toArray();
        $selectedUnions = $campaign->unions->pluck('id')->toArray();

        // Load districts, upazilas, and unions based on selections
        $districts = [];
        $upazilas = [];
        $unions = [];

        if (!empty($selectedDivisions)) {
            $districts = District::whereIn('division_id', $selectedDivisions)
                ->orderBy('bn_name')
                ->get();
        }

        if (!empty($selectedDistricts)) {
            $upazilas = Upazila::whereIn('district_id', $selectedDistricts)
                ->orderBy('bn_name')
                ->get();
        }

        if (!empty($selectedUpazilas)) {
            $unions = Union::whereIn('upazila_id', $selectedUpazilas)
                ->orderBy('bn_name')
                ->get();
        }

        return view('campaigns.update', compact(
            'campaign',
            'divisions',
            'districts',
            'upazilas',
            'unions',
            'selectedDivisions',
            'selectedDistricts',
            'selectedUpazilas',
            'selectedUnions'
        ));
    }

    /**
     * Update the specified campaign in storage.
     */
    public function update(Request $request, Campaign $campaign)
    {
        // Validate the request (similar to store method)
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'campaign_type' => 'required|in:nationwide,division,district,upazila,union',
            'is_nationwide' => 'sometimes',
            'start_date' => 'required|string',
            'end_date' => 'required|string',
            'status' => 'required|in:draft,publish,scheduled',
            'notification_send' => 'required|in:yes,no',
            'featured' => 'sometimes|boolean',
            'division_ids' => 'sometimes|array',
            'district_ids' => 'sometimes|array',
            'upazila_ids' => 'sometimes|array',
            'union_ids' => 'sometimes|array',
            'campaign_images.*' => 'sometimes|file|image|max:5120',
            'campaign_audio' => 'sometimes|file|mimes:mp3,wav,ogg|max:10240',
            'campaign_video' => 'sometimes|file|mimes:mp4,webm,mov|max:51200',
            'file_paths.*' => 'sometimes|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx|max:10240',
        ]);

        // Parse dates
        $startDateParts = explode('/', $request->start_date);
        $endDateParts = explode('/', $request->end_date);

        $startDate = $startDateParts[2] . '-' . $startDateParts[1] . '-' . $startDateParts[0];
        $endDate = $endDateParts[2] . '-' . $endDateParts[1] . '-' . $endDateParts[0];

        // Begin database transaction
        DB::beginTransaction();

        try {
            // Update the campaign
            $campaign->update([
                'title' => $request->title,
                'description' => $request->description,
                'campaign_type' => $request->campaign_type,
                'is_nationwide' => $request->input('is_nationwide', 0) == 1,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'status' => $request->status,
                'notification_send' => $request->notification_send,
                'featured' => $request->input('featured', 0),
            ]);

            // Update locations
            // First, detach all existing locations
            $campaign->divisions()->detach();
            $campaign->districts()->detach();
            $campaign->upazilas()->detach();
            $campaign->unions()->detach();

            // Then attach new locations based on campaign type
            if (!$campaign->is_nationwide) {
                if ($request->campaign_type === 'division' && !empty($request->division_ids)) {
                    $campaign->divisions()->attach($request->division_ids);
                }

                if ($request->campaign_type === 'district' && !empty($request->district_ids)) {
                    $campaign->districts()->attach($request->district_ids);

                    // Also attach the parent divisions
                    $divisions = District::whereIn('id', $request->district_ids)
                        ->pluck('division_id')
                        ->unique()
                        ->toArray();

                    if (!empty($divisions)) {
                        $campaign->divisions()->attach($divisions);
                    }
                }

                if ($request->campaign_type === 'upazila' && !empty($request->upazila_ids)) {
                    $campaign->upazilas()->attach($request->upazila_ids);

                    // Get districts and divisions
                    $upazilas = Upazila::whereIn('id', $request->upazila_ids)->get();
                    $districtIds = $upazilas->pluck('district_id')->unique()->toArray();

                    $campaign->districts()->attach($districtIds);

                    $divisions = District::whereIn('id', $districtIds)
                        ->pluck('division_id')
                        ->unique()
                        ->toArray();

                    $campaign->divisions()->attach($divisions);
                }

                if ($request->campaign_type === 'union' && !empty($request->union_ids)) {
                    $campaign->unions()->attach($request->union_ids);

                    // Get upazilas, districts, and divisions
                    $unions = Union::whereIn('id', $request->union_ids)->get();
                    $upazilaIds = $unions->pluck('upazila_id')->unique()->toArray();

                    $campaign->upazilas()->attach($upazilaIds);

                    $upazilas = Upazila::whereIn('id', $upazilaIds)->get();
                    $districtIds = $upazilas->pluck('district_id')->unique()->toArray();

                    $campaign->districts()->attach($districtIds);

                    $divisions = District::whereIn('id', $districtIds)
                        ->pluck('division_id')
                        ->unique()
                        ->toArray();

                    $campaign->divisions()->attach($divisions);
                }
            }

            // Handle campaign images
            if ($request->hasFile('campaign_images')) {
                foreach ($request->file('campaign_images') as $image) {
                    $path = $image->store('campaigns/images', 'public');

                    CampaignImage::create([
                        'campaign_id' => $campaign->id,
                        'file_path' => $path,
                        'file_name' => $image->getClientOriginalName(),
                        'file_type' => $image->getMimeType(),
                        'file_size' => $image->getSize(),
                    ]);
                }
            }

            // Handle campaign audio
            if ($request->hasFile('campaign_audio')) {
                // Delete existing audio file if it exists
                if ($campaign->audio) {
                    Storage::disk('public')->delete($campaign->audio->file_path);
                    $campaign->audio->delete();
                }

                $audio = $request->file('campaign_audio');
                $path = $audio->store('campaigns/audio', 'public');

                CampaignAudio::create([
                    'campaign_id' => $campaign->id,
                    'file_path' => $path,
                    'file_name' => $audio->getClientOriginalName(),
                    'file_type' => $audio->getMimeType(),
                    'file_size' => $audio->getSize(),
                ]);
            }

            // Handle campaign video
            if ($request->hasFile('campaign_video')) {
                // Delete existing video file if it exists
                if ($campaign->video) {
                    Storage::disk('public')->delete($campaign->video->file_path);
                    $campaign->video->delete();
                }

                $video = $request->file('campaign_video');
                $path = $video->store('campaigns/videos', 'public');

                CampaignVideo::create([
                    'campaign_id' => $campaign->id,
                    'file_path' => $path,
                    'file_name' => $video->getClientOriginalName(),
                    'file_type' => $video->getMimeType(),
                    'file_size' => $video->getSize(),
                ]);
            }

            // Handle campaign files/documents
            if ($request->hasFile('file_paths')) {
                foreach ($request->file('file_paths') as $file) {
                    $path = $file->store('campaigns/files', 'public');

                    CampaignFile::create([
                        'campaign_id' => $campaign->id,
                        'file_path' => $path,
                        'file_name' => $file->getClientOriginalName(),
                        'file_type' => $file->getMimeType(),
                        'file_size' => $file->getSize(),
                    ]);
                }
            }

            // Commit the transaction
            DB::commit();

            return redirect()->route('campaigns.index')
                ->with('success', 'ক্যাম্পেইন সফলভাবে আপডেট করা হয়েছে!');

        } catch (\Exception $e) {
            // Roll back the transaction on error
            DB::rollBack();

            return back()
                ->withInput()
                ->with('error', 'ক্যাম্পেইন আপডেট করতে সমস্যা হয়েছে: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified campaign from storage.
     */
    public function destroy(Campaign $campaign)
    {
        try {
            // Delete campaign files from storage
            foreach ($campaign->images as $image) {
                Storage::disk('public')->delete($image->file_path);
            }

            if ($campaign->audio) {
                Storage::disk('public')->delete($campaign->audio->file_path);
            }

            if ($campaign->video) {
                Storage::disk('public')->delete($campaign->video->file_path);
            }

            foreach ($campaign->files as $file) {
                Storage::disk('public')->delete($file->file_path);
            }

            // Delete the campaign (related records will be deleted via cascade or manually)
            $campaign->delete();

            return redirect()->route('campaigns.index')
                ->with('success', 'ক্যাম্পেইন সফলভাবে মুছে ফেলা হয়েছে!');
        } catch (\Exception $e) {
            return back()->with('error', 'ক্যাম্পেইন মুছতে সমস্যা হয়েছে: ' . $e->getMessage());
        }
    }

    /**
     * Get districts for the selected divisions
     */
    public function getDistricts($divisionIds)
    {
        $divisionIds = explode(',', $divisionIds);
        $districts = District::whereIn('division_id', $divisionIds)
            ->orderBy('bn_name')
            ->get(['id', 'bn_name']);

        return response()->json($districts);
    }

    /**
     * Get upazilas for the selected districts
     */
    public function getUpazilas($districtIds)
    {
        $districtIds = explode(',', $districtIds);
        $upazilas = Upazila::whereIn('district_id', $districtIds)
            ->orderBy('bn_name')
            ->get(['id', 'bn_name']);

        return response()->json($upazilas);
    }

    /**
     * Get unions for the selected upazilas
     */
    public function getUnions($upazilaIds)
    {
        $upazilaIds = explode(',', $upazilaIds);
        $unions = Union::whereIn('upazila_id', $upazilaIds)
            ->orderBy('bn_name')
            ->get(['id', 'bn_name']);

        return response()->json($unions);
    }

    /**
     * Toggle the featured status of a campaign
     */
    public function toggleFeatured(Campaign $campaign)
    {
        $campaign->featured = !$campaign->featured;
        $campaign->save();

        return back()->with('success', 'ক্যাম্পেইন ফিচার স্ট্যাটাস পরিবর্তন করা হয়েছে!');
    }

    /**
     * Change the status of a campaign
     */
    public function changeStatus(Request $request, Campaign $campaign)
    {
        $request->validate([
            'status' => 'required|in:draft,publish,scheduled'
        ]);

        $campaign->status = $request->status;
        $campaign->save();

        return back()->with('success', 'ক্যাম্পেইন স্ট্যাটাস পরিবর্তন করা হয়েছে!');
    }

    /**
     * Delete a campaign media item
     */
    public function deleteMedia(Request $request)
    {
        $request->validate([
            'type' => 'required|in:image,audio,video,file',
            'id' => 'required|integer'
        ]);

        try {
            if ($request->type === 'image') {
                $media = CampaignImage::findOrFail($request->id);
            } elseif ($request->type === 'audio') {
                $media = CampaignAudio::findOrFail($request->id);
            } elseif ($request->type === 'video') {
                $media = CampaignVideo::findOrFail($request->id);
            } else {
                $media = CampaignFile::findOrFail($request->id);
            }

            // Delete the file from storage
            Storage::disk('public')->delete($media->file_path);

            // Delete the record
            $media->delete();

            return response()->json(['success' => true, 'message' => 'মিডিয়া সফলভাবে মুছে ফেলা হয়েছে!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'মিডিয়া মুছতে সমস্যা হয়েছে!'], 500);
        }
    }

    /**
     * Display campaigns by location
     */
    public function byLocation($type, $locationId)
    {
        $campaigns = Campaign::byLocation($type, $locationId)
            ->where('status', 'publish')
            ->latest()
            ->paginate(12);

        $location = null;

        if ($type === 'division') {
            $location = Division::find($locationId);
        } elseif ($type === 'district') {
            $location = District::find($locationId);
        } elseif ($type === 'upazila') {
            $location = Upazila::find($locationId);
        } elseif ($type === 'union') {
            $location = Union::find($locationId);
        }

        return view('campaigns.by_location', compact('campaigns', 'location', 'type'));
    }

    /**
     * Display featured campaigns
     */
    public function featured()
    {
        $campaigns = Campaign::featured()
            ->where('status', 'publish')
            ->latest()
            ->paginate(12);

        return view('campaigns.featured', compact('campaigns'));
    }

    /**
     * Record campaign engagement
     */
    public function recordEngagement(Request $request, Campaign $campaign)
    {
        if ($campaign->analytics) {
            $campaign->analytics->incrementEngagements();

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false], 404);
    }

    /**
     * Record campaign share
     */
    public function recordShare(Request $request, Campaign $campaign)
    {
        if ($campaign->analytics) {
            $campaign->analytics->incrementShares();

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false], 404);
    }

    /**
     * Support a campaign
     */
    public function support(Request $request, Campaign $campaign)
    {
        $request->validate([
            'support_type' => 'required|in:volunteer,donor,advocate,participant',
            'notes' => 'nullable|string'
        ]);

        $existingSupport = $campaign->supporters()
            ->where('user_id', Auth::id())
            ->first();

        if ($existingSupport) {
            $existingSupport->update([
                'support_type' => $request->support_type,
                'notes' => $request->notes
            ]);

            $message = 'আপনার সমর্থন আপডেট করা হয়েছে!';
        } else {
            $campaign->supporters()->create([
                'user_id' => Auth::id(),
                'support_type' => $request->support_type,
                'notes' => $request->notes
            ]);

            if ($campaign->analytics) {
                $campaign->analytics->updateSupportersCount();
            }

            $message = 'ক্যাম্পেইন সমর্থন করার জন্য ধন্যবাদ!';
        }

        return back()->with('success', $message);
    }
}