@extends('frontend.layouts.master')

@section('title', 'প্রোফাইল সম্পাদনা')

@section('content')
    <div class="container py-5">
        <div class="row">
            <div class="col-md-8 mx-auto">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h3 class="mb-0">
                            <i class="fa-solid fa-user-edit me-2"></i> প্রোফাইল সম্পাদনা
                        </h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <!-- General Information Section -->
                            <div class="row">
                                <div class="col-md-4 text-center mb-4">
                                    <div class="profile-picture-container">
                                        @if(auth()->user()->photo)
                                            <img id="profile-preview"
                                                src="{{ asset('storage/' . auth()->user()->photo) }}"
                                                class="img-fluid rounded-circle mb-3"
                                                alt="{{ auth()->user()->name }} প্রোফাইল ছবি"
                                                style="max-width: 200px; height: 200px; object-fit: cover;">
                                        @else
                                            <img id="profile-preview" src="{{ asset('images/default-profile.png') }}"
                                                class="img-fluid rounded-circle mb-3" alt="ডিফল্ট প্রোফাইল ছবি"
                                                style="max-width: 200px; height: 200px; object-fit: cover;">
                                        @endif

                                        <div class="mt-2">
                                            <label for="profile_picture" class="btn btn-sm btn-outline-primary">
                                                <i class="fa-solid fa-camera me-2"></i> ছবি পরিবর্তন
                                                <input type="file" id="profile_picture" name="profile_picture"
                                                    class="d-none" accept="image/*" onchange="previewProfilePicture(this)">
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-8">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">নাম</label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                            id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="email" class="form-label">ইমেইল</label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                                            id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="phone" class="form-label">মোবাইল নম্বর</label>
                                        <input type="tel" class="form-control @error('phone') is-invalid @enderror"
                                            id="phone" name="phone" value="{{ old('phone', $user->phone) }}">
                                        @error('phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Password Update Section -->
                           <!-- Add a checkbox to show password fields -->
<div class="form-check mb-3">
    <input type="checkbox" class="form-check-input" id="change-password-checkbox">
    <label class="form-check-label" for="change-password-checkbox">পাসওয়ার্ড পরিবর্তন করতে চান?</label>
</div>

<!-- Password fields (initially hidden) -->
<div id="password-fields" style="display: none;">
    <div class="mb-3">
        <label for="password" class="form-label">নতুন পাসওয়ার্ড</label>
        <input type="password" class="form-control @error('password') is-invalid @enderror"
               id="password" name="password" placeholder="শুধুমাত্র পাসওয়ার্ড পরিবর্তন করতে চাইলে পূরণ করুন" 
               autocomplete="new-password">
        @error('password')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="password_confirmation" class="form-label">পাসওয়ার্ড নিশ্চিত করুন</label>
        <input type="password" class="form-control" id="password_confirmation"
               name="password_confirmation" placeholder="নতুন পাসওয়ার্ড আবার লিখুন" 
               autocomplete="new-password">
    </div>
</div>




                            <div class="d-flex justify-content-between mt-4">
                                <a href="{{ route('profile.show') }}" class="btn btn-outline-secondary">
                                    <i class="fa-solid fa-arrow-left me-2"></i> ফিরে যান
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa-solid fa-save me-2"></i> আপডেট করুন
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // Preview uploaded profile picture
        function previewProfilePicture(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    document.getElementById('profile-preview').src = e.target.result;
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        // Show/Hide password fields based on checkbox
        document.getElementById('change-password-checkbox').addEventListener('change', function () {
            var passwordFields = document.getElementById('password-fields');
            if (this.checked) {
                passwordFields.style.display = 'block';
            } else {
                passwordFields.style.display = 'none';
            }
        });
    </script>
    <!-- JavaScript to show/hide the password fields -->
    <script>
        document.getElementById('change-password-checkbox').addEventListener('change', function () {
            var passwordFields = document.getElementById('password-fields');
            if (this.checked) {
                passwordFields.style.display = 'block';
            } else {
                passwordFields.style.display = 'none';
            }
        });
    </script>
@endsection