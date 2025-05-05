<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Division;
use App\Models\User;
use App\Models\UserType;
use App\Models\Designation;
use App\Services\FileUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class MembershipController extends Controller
{
    public $fileUploadService;

    public function __construct(FileUploadService $fileUploadService)
    {
        $this->fileUploadService = $fileUploadService;
    }
    /**
     * Display membership information page
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('frontend.membership.index');
    }

    /**
     * Show membership application form
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        // Check if user already has a pending or approved membership
        $existingApplication = User::where('phone', Auth::user()->phone)
            ->whereIn('status', ['pending', 'approved'])
            ->first();

        if ($existingApplication) {
            return redirect()->route('membership.status')
                ->with('info', 'আপনি ইতিমধ্যে একটি সদস্যতা আবেদন জমা দিয়েছেন। দয়া করে আপনার আবেদনের স্ট্যাটাস দেখুন।');
        }

        // Get all divisions for the form
        $divisions = Division::all();



        // Pre-fill form with user's existing data
        $user = Auth::user();

        return view('frontend.membership.apply', compact('divisions', 'user'));
    }

    /**
     * Process membership application
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validate form data
        $validated = $request->validate([
            'name' => 'required|string',
            'userid' => 'required|string|unique:users|max:30',
            'father_name' => 'required|string|max:255',
            'mother_name' => 'required|string|max:255',
            'dob' => 'date|nullable',
            'gender' => 'integer',
            'nid' => 'required|string|max:20|unique:users,nid',
            'phone' => 'required|string',
            'email' => 'string|required|email',
            'address' => 'nullable|string',
            'permanent_address' => 'required|string',
            'post_code' => 'nullable|string',
            'division_id' => 'required|integer',
            'district_id' => 'required|integer',
            'upazila_id' => 'required|integer',
            'union_id' => 'required|integer',
            'educational_qualification' => 'required|string|max:255',
            'last_educational_qual' => 'string|nullable',
            'profession' => 'required|string|max:255',
            'blood_group' => 'integer|nullable',
            'photo' => 'mimes:png,jpg,jpeg|max:2048|nullable',
            'nid_scan' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'em_contact_name' => 'nullable|string',
            'em_contact_relation' => 'nullable|string',
            'em_contact_phone' => 'nullable|string',
            'em_contact_email' => 'string|nullable|email',
            'joining_date' => 'date|nullable',
            'duration' => 'integer|nullable',
            'reference_name' => 'nullable|string|max:255',
            'reference_phone' => 'nullable|string|max:15',
            'reference_membership_id' => 'nullable|string|max:50',
            'agreed_to_terms' => 'required|accepted',
        ]);

        // Handle file uploads


        // Handle file uploads
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('membership/photos', 'public');
            $validated['photo'] = $photoPath;
        }


        if ($request->hasFile('nid_scan')) {
            $nidPath = $request->file('nid_scan')->store('membership/nid', 'public');
            $validated['nid_scan'] = $nidPath;
        }



        // Add status and default values
        $validated['status'] = 'pending';
        $validated['membership_id'] = 'TMP-' . time() . '-' . Auth::id();
        $validated['company_id'] = 1; // Set default company ID
        $validated['is_active'] = 1;
        $validated['is_superuser'] = 0;
        $validated['is_admin'] = 0;

        // Set a temporary password (will be reset by admin after approval)
        $validated['password'] = Hash::make(Str::random(10));

        // Remove the agreed_to_terms field as it's not in the database
        unset($validated['agreed_to_terms']);

        // Create user record as pending member
        $user = User::create($validated);

        return redirect()->route('membership.status')
            ->with('success', 'আপনার সদস্যতা আবেদন সফলভাবে জমা হয়েছে। আমরা যাচাই করার পর আপনাকে জানাব।');
    }

    /**
     * Show membership application status
     *
     * @return \Illuminate\View\View
     */
    public function status()
    {
        $application = User::where('phone', Auth::user()->phone)
            ->whereIn('status', ['pending', 'approved', 'rejected'])
            ->with(['division', 'district', 'upazila', 'union'])
            ->first();

        // if (!$application) {
        //     return redirect()->route('membership.apply')
        //         ->with('info', 'আপনার কোন সদস্যতা আবেদন পাওয়া যায়নি। নতুন আবেদন করতে নীচের ফর্ম পূরণ করুন।');
        // }

        return view('frontend.membership.status', compact('application'));
    }

    /**
     * Admin method to approve membership
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function approve($id)
    {
        // Check admin permissions
        if (!auth()->user()->can('user-approve')) {
            return redirect()->back()->with('error', 'আপনার এই অনুমতি নেই।');
        }

        $user = User::findOrFail($id);

        // Update status
        $user->status = 'approved';

        // Generate a proper membership ID
        $user->membership_id = 'MEM-' . date('Y') . '-' . str_pad($user->id, 5, '0', STR_PAD_LEFT);

        $user->save();

        // TODO: Send email to member with password reset link

        return redirect()->back()->with('success', 'সদস্যতা অনুমোদন করা হয়েছে।');
    }

    /**
     * Admin method to reject membership
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function reject($id)
    {
        // Check admin permissions
        if (!auth()->user()->can('user-reject')) {
            return redirect()->back()->with('error', 'আপনার এই অনুমতি নেই।');
        }

        $user = User::findOrFail($id);
        $user->status = 'rejected';
        $user->save();

        // TODO: Send email to notify about rejection

        return redirect()->back()->with('success', 'সদস্যতা প্রত্যাখ্যান করা হয়েছে।');
    }
}