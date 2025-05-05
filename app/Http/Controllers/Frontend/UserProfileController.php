<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Services\FileUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserProfileController extends Controller
{
    public $fileUploadService;

    public function __construct(FileUploadService $fileUploadService)
    {
        $this->fileUploadService = $fileUploadService;
        view()->share('main_menu', 'employee');
    }
    /**
     * Show the user's profile
     */
    public function show()
    {
        // Get the authenticated user
        $user = Auth::user();

        // If no user is authenticated, redirect to login
        if (!$user) {
            return redirect()->route('login');
        }

        // Render the profile view and pass the user data
        return view('frontend.profile.show', compact('user'));
    }

    /**
     * Show the form for editing the user's profile
     */
    public function edit()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        return view('frontend.profile.edit', compact('user'));
    }

    /**
     * Update the user's profile
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        // Validate the incoming request
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:15', // Validate phone number
            'profile_picture' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048', // Profile picture validation
        ]);

        // Check if password is being updated
        if ($request->filled('password') || $request->filled('password_confirmation')) {
            // Validate password if provided
            $validatedData = $request->validate([
                'password' => 'required|confirmed|min:8', // Validate password change
            ]);
        }

        // Handle profile picture upload using the file upload service
        if ($request->hasFile('profile_picture')) {
            // Optionally delete the old image if exists
            if ($user->photo && file_exists(public_path('storage/' . $user->photo))) {
                unlink(public_path('storage/' . $user->photo)); // Delete old image
            }

            // Use the provided file upload service to store the new image
            $filePath = $this->fileUploadService->uploadImage('profile_picture', 'users');
            $validatedData['photo'] = $filePath; // Store the file path in 'photo' field
        }

        // Handle password update if provided
        if ($request->filled('password')) {
            $validatedData['password'] = bcrypt($request->password); // Encrypt the new password
        }

        // Update the user's profile with validated data
        $user->update($validatedData);

        // Return success message
        return redirect()->route('profile.show')
            ->with('success', 'প্রোফাইল সফলভাবে আপডেট করা হয়েছে');
    }

}