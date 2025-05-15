<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Division;
use App\Models\User;
use App\Models\UserType;
use Illuminate\Validation\ValidationException;

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

try {
    $validated = $request->validate([
        'name' => 'required|string',
        'father_name' => 'required|string|max:255',
        'mother_name' => 'required|string|max:255',
        'dob' => 'date|nullable',
        'gender' => 'integer',
        'nid' => 'required|string|max:20|unique:users,nid',
        'email' => 'string|required|email',
        'address' => 'nullable|string',
        'permanent_address' => 'required|string',
        'post_code' => 'nullable|string',
        'division_id' => 'required|integer',
        'district_id' => 'required|integer',
        'upazila_id' => 'required|integer',
        'union_id' => 'nullable|integer',
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
} catch (ValidationException $e) {
    session()->flash('custom_error', 'আপনার দেওয়া তথ্য সঠিক নয়, আবার চেষ্টা করুন।');
    throw $e; // Re-throw so Laravel still does its thing
}


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

        $phone = Auth::user()->phone;
        $validated['phone'] = $phone;

        $validated['membership_id'] = 'TMP-' . time() . '-' . Auth::id();
        $validated['company_id'] = 1; // Set default company ID
        $validated['is_active'] = 0;
        $validated['is_superuser'] = 0;
        $validated['is_admin'] = 0;

        // Set a temporary password (will be reset by admin after approval)
        $validated['userid'] = Str::random(5);


        // Remove the agreed_to_terms field as it's not in the database
        unset($validated['agreed_to_terms']);

        // dd($validated);

        $user = Auth::user();
        $user->update($validated);

        return redirect()->route('membership.status')
            ->with('success', 'আপনার সদস্যতা আবেদন সফলভাবে জমা হয়েছে। আমরা যাচাই করার পর আপনাকে জানাব।');
    }

    public function status()
    {

        $phone = Auth::user()->phone;

        // Remove leading zeros for search or add wildcards
        $application = User::where('phone', Auth::user()->phone)
            ->whereIn('status', ['pending', 'approved', 'rejected'])
            ->with(['division', 'district', 'upazila', 'union'])
            ->first();



        return view('frontend.membership.status', compact('application'));
    }



}