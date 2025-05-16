@extends('frontend.layouts.master')

@section('title', 'সদস্যর আবেদন')

@section('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <style>
        .form-section {
            background-color: white;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            margin-bottom: 30px;
        }

        .form-section-title {
            padding-bottom: 15px;
            margin-bottom: 20px;
            border-bottom: 1px solid #eee;
            color: #0D6A37;
        }

        .required-field::after {
            content: "*";
            color: red;
            margin-left: 3px;
        }

        .preview-image {
            max-width: 200px;
            max-height: 200px;
            margin-top: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .membership-sidebar {
            background: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            position: sticky;
            top: 20px;
        }

        .sidebar-step {
            padding: 15px;
            margin-bottom: 10px;
            border-radius: 4px;
            background-color: #f8f9fa;
            position: relative;
        }

        .sidebar-step.active {
            background-color: #e8f5e9;
            border-left: 4px solid #0D6A37;
        }

        .step-number {
            display: inline-flex;
            justify-content: center;
            align-items: center;
            width: 24px;
            height: 24px;
            background-color: #0D6A37;
            color: white;
            border-radius: 50%;
            margin-right: 8px;
        }

        .step-check {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #0D6A37;
        }
    </style>
@endsection

@section('content')
    <div class="container py-5">
        <div class="row mb-4">
            <div class="col-12">
                <h1 class="mb-2">সদস্যর আবেদন</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">হোম</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('membership.index') }}">সদস্য</a></li>
                        <li class="breadcrumb-item active" aria-current="page">আবেদন</li>
                    </ol>
                </nav>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
            </div>
        @endif

        <div class="row">
            <div class="col-lg-8">
                <form action="{{ route('membership.store') }}" method="POST" enctype="multipart/form-data"
                    id="membershipForm">
                    @csrf

                    <!-- Personal Information Section -->
                    <div class="form-section" id="personal-info">
                        <h3 class="form-section-title">
                            <i class="fas fa-user me-2"></i> ব্যক্তিগত তথ্য
                        </h3>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label required-field">পূর্ণ নাম</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    id="name" name="name" value="{{ old('name', $user->name ?? '') }}"
                                    required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="father_name" class="form-label required-field">পিতার নাম</label>
                                <input type="text" class="form-control @error('father_name') is-invalid @enderror"
                                    id="father_name" name="father_name" value="{{ old('father_name') }}" required>
                                @error('father_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="mother_name" class="form-label required-field">মাতার নাম</label>
                                <input type="text" class="form-control @error('mother_name') is-invalid @enderror"
                                    id="mother_name" name="mother_name" value="{{ old('mother_name') }}" required>
                                @error('mother_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="dob" class="form-label required-field">জন্ম তারিখ</label>
                                <input type="text"
                                    class="form-control flatpickr-date @error('dob') is-invalid @enderror"
                                    id="dob" name="dob" value="{{ old('dob') }}">
                                @error('dob')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="gender" class="form-label required-field">লিঙ্গ</label>
                                <select class="form-select @error('gender') is-invalid @enderror" id="gender" 
                                    name="gender" required>
                                    <option value="">নির্বাচন করুন</option>
                                    <option value="1" {{ old('gender') == '1' ? 'selected' : '' }}>পুরুষ</option>
                                    <option value="2" {{ old('gender') == '2' ? 'selected' : '' }}>মহিলা</option>
                                    <option value="3" {{ old('gender') == '3' ? 'selected' : '' }}>অন্যান্য</option>
                                </select>
                                @error('gender')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="nid" class="form-label required-field">জাতীয় পরিচয়পত্র নম্বর</label>
                                <input type="text" class="form-control @error('nid') is-invalid @enderror" id="nid"
                                    name="nid" value="{{ old('nid') }}" required>
                                @error('nid')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="blood_group" class="form-label">রক্তের গ্রুপ</label>
                                <select class="form-select @error('blood_group') is-invalid @enderror" id="blood_group"
                                    name="blood_group">
                                    <option value="">নির্বাচন করুন</option>
                                    <option value="1" {{ old('blood_group') == '1' ? 'selected' : '' }}>A+</option>
                                    <option value="2" {{ old('blood_group') == '2' ? 'selected' : '' }}>A-</option>
                                    <option value="3" {{ old('blood_group') == '3' ? 'selected' : '' }}>B+</option>
                                    <option value="4" {{ old('blood_group') == '4' ? 'selected' : '' }}>B-</option>
                                    <option value="5" {{ old('blood_group') == '5' ? 'selected' : '' }}>AB+</option>
                                    <option value="6" {{ old('blood_group') == '6' ? 'selected' : '' }}>AB-</option>
                                    <option value="7" {{ old('blood_group') == '7' ? 'selected' : '' }}>O+</option>
                                    <option value="8" {{ old('blood_group') == '8' ? 'selected' : '' }}>O-</option>
                                </select>
                                @error('blood_group')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Contact Information Section -->
                    <div class="form-section" id="contact-info">
                        <h3 class="form-section-title">
                            <i class="fas fa-phone-alt me-2"></i> যোগাযোগের তথ্য
                        </h3>

                        <div class="row">


                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label required-field">ইমেইল</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                                    name="email" value="{{ old('email', $user->email ?? '') }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-12 mb-3">
                                <label for="address" class="form-label">বর্তমান ঠিকানা</label>
                                <textarea class="form-control @error('address') is-invalid @enderror"
                                    id="address" name="address" rows="3">{{ old('address') }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-12 mb-3">
                                <label for="permanent_address" class="form-label">স্থায়ী ঠিকানা</label>
                                <textarea class="form-control @error('permanent_address') is-invalid @enderror"
                                    id="permanent_address" name="permanent_address" rows="3">{{ old('permanent_address') }}</textarea>
                                @error('permanent_address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="post_code" class="form-label">পোস্ট কোড</label>
                                <input type="text" class="form-control @error('post_code') is-invalid @enderror" 
                                    id="post_code" name="post_code" value="{{ old('post_code') }}">
                                @error('post_code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Emergency Contact Section -->
                    <div class="form-section" id="emergency-contact">
                        <h3 class="form-section-title">
                            <i class="fas fa-first-aid me-2"></i> জরুরী যোগাযোগ
                        </h3>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="em_contact_name" class="form-label">নাম</label>
                                <input type="text" class="form-control @error('em_contact_name') is-invalid @enderror" 
                                    id="em_contact_name" name="em_contact_name" value="{{ old('em_contact_name') }}">
                                @error('em_contact_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="em_contact_relation" class="form-label">সম্পর্ক</label>
                                <input type="text" class="form-control @error('em_contact_relation') is-invalid @enderror" 
                                    id="em_contact_relation" name="em_contact_relation" value="{{ old('em_contact_relation') }}">
                                @error('em_contact_relation')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="em_contact_phone" class="form-label">ফোন নম্বর</label>
                                <input type="tel" class="form-control @error('em_contact_phone') is-invalid @enderror" 
                                    id="em_contact_phone" name="em_contact_phone" value="{{ old('em_contact_phone') }}">
                                @error('em_contact_phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="em_contact_email" class="form-label">ইমেইল</label>
                                <input type="email" class="form-control @error('em_contact_email') is-invalid @enderror" 
                                    id="em_contact_email" name="em_contact_email" value="{{ old('em_contact_email') }}">
                                @error('em_contact_email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Location Information Section -->
                    <div class="form-section" id="location-info">
                        <h3 class="form-section-title">
                            <i class="fas fa-map-marker-alt me-2"></i> আপনার অবস্থান তথ্য
                        </h3>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="division_id" class="form-label required-field">বিভাগ</label>
                                <select class="form-select @error('division_id') is-invalid @enderror" id="division_id"
                                    name="division_id" required>
                                    <option value="">নির্বাচন করুন</option>
                                    @foreach($divisions as $division)
                                        <option value="{{ $division->id }}" {{ old('division_id') == $division->id ? 'selected' : '' }}>
                                            {{ $division->bn_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('division_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="district_id" class="form-label required-field">জেলা</label>
                                <select class="form-select @error('district_id') is-invalid @enderror" id="district_id"
                                    name="district_id" required>
                                    <option value="">প্রথমে বিভাগ নির্বাচন করুন</option>
                                </select>
                                @error('district_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="upazila_id" class="form-label required-field">উপজেলা</label>
                                <select class="form-select @error('upazila_id') is-invalid @enderror" id="upazila_id"
                                    name="upazila_id" required>
                                    <option value="">প্রথমে জেলা নির্বাচন করুন</option>
                                </select>
                                @error('upazila_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="union_id" class="form-label required-field">ইউনিয়ন</label>
                                <select class="form-select @error('union_id') is-invalid @enderror" id="union_id"
                                    name="union_id" >
                                    <option value="">প্রথমে উপজেলা নির্বাচন করুন</option>
                                </select>
                                @error('union_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Educational & Professional Information -->
                    <div class="form-section" id="education-profession">
                        <h3 class="form-section-title">
                            <i class="fas fa-user-graduate me-2"></i> শিক্ষাগত ও পেশাগত তথ্য
                        </h3>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="educational_qualification" class="form-label required-field">শিক্ষাগত
                                    যোগ্যতা</label>
                                <input type="text"
                                    class="form-control @error('educational_qualification') is-invalid @enderror"
                                    id="educational_qualification" name="educational_qualification"
                                    value="{{ old('educational_qualification') }}" required>
                                @error('educational_qualification')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="last_educational_qual" class="form-label">সর্বশেষ শিক্ষাগত যোগ্যতা</label>
                                <input type="text" class="form-control @error('last_educational_qual') is-invalid @enderror" 
                                    id="last_educational_qual" name="last_educational_qual" value="{{ old('last_educational_qual') }}">
                                @error('last_educational_qual')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="profession" class="form-label required-field">পেশা</label>
                                <input type="text" class="form-control @error('profession') is-invalid @enderror"
                                    id="profession" name="profession" value="{{ old('profession') }}" required>
                                @error('profession')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Membership Information -->
                    <div class="form-section" id="membership-info">
                        <h3 class="form-section-title">
                            <i class="fas fa-id-card me-2"></i> সদস্য তথ্য
                        </h3>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="joining_date" class="form-label">দলে যোগদানের তারিখ</label>
                                <input type="text" class="form-control flatpickr-date @error('joining_date') is-invalid @enderror" 
                                    id="joining_date" name="joining_date" value="{{ old('joining_date') }}">
                                @error('joining_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>


                        </div>
                    </div>

                    <!-- Document Upload Section -->
                    <div class="form-section" id="document-upload">
                        <h3 class="form-section-title">
                            <i class="fas fa-file-upload me-2"></i> নথি আপলোড
                        </h3>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="photo" class="form-label">ফটো আপলোড</label>
                                <input type="file" class="form-control @error('photo') is-invalid @enderror" id="photo" name="photo">
                                @error('photo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>


                            <div class="col-md-6 mb-3">
                                <label for="nid_scan" class="form-label required-field">NID স্ক্যান</label>
                                <input type="file" class="form-control @error('nid_scan') is-invalid @enderror" id="nid_scan" name="nid_scan"
                                    required>
                                @error('nid_scan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>
                    </div>

                    <!-- Terms and Submission -->
                    <div class="form-section" id="terms-submission">
                    <div class="form-check mb-3">
                        <input class="form-check-input @error('agreed_to_terms') is-invalid @enderror" type="checkbox" id="agreed_to_terms"
                            name="agreed_to_terms" {{ old('agreed_to_terms') ? 'checked' : '' }} required>
                        <label class="form-check-label" for="agreed_to_terms">
                            আমি সকল শর্তাবলী মেনে নিচ্ছি
                        </label>
                        @error('agreed_to_terms')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>


                        <button type="submit" class="btn btn-primary btn-lg px-5">
                            <i class="fas fa-paper-plane me-2"></i> আবেদন জমা দিন
                        </button>
                    </div>
                </form>
            </div>

            <div class="col-lg-4">
                <div class="membership-sidebar">
                    <h4 class="mb-3">আবেদন গাইড</h4>

                    <div class="sidebar-step active" data-section="personal-info">
                        <span class="step-number">1</span> ব্যক্তিগত তথ্য
                    <i class="fas fa-check step-check"></i>
                    </div>

                    <div class="sidebar-step" data-section="contact-info">
                        <span class="step-number">2</span> যোগাযোগের তথ্য
                    </div>

                    <div class="sidebar-step" data-section="emergency-contact">
                        <span class="step-number">3</span> জরুরী যোগাযোগ
                    </div>

                    <div class="sidebar-step" data-section="location-info">
                        <span class="step-number">4</span> অবস্থান তথ্য
                    </div>

                    <div class="sidebar-step" data-section="education-profession">
                        <span class="step-number">5</span> শিক্ষাগত ও পেশাগত তথ্য
                    </div>

                    <div class="sidebar-step" data-section="membership-info">
                        <span class="step-number">6</span> সদস্য তথ্য
                    </div>

                    <div class="sidebar-step" data-section="document-upload">
                        <span class="step-number">7</span> নথি আপলোড
                    </div>

                    <div class="sidebar-step" data-section="terms-submission">
                        <span class="step-number">8</span> জমা দেওয়া
                    </div>

                    <div class="alert alert-info mt-4">
                        <h6><i class="fas fa-info-circle me-2"></i> দ্রষ্টব্য:</h6>
                        <ul class="mb-0 ps-3">
                            <li>সমস্ত তথ্য যাচাই করা হবে।</li>
                            <li>মিথ্যা তথ্য প্রদানের ফলে আবেদন বাতিল করা হবে।</li>
                            <li>আবেদন জমা দেওয়ার পর তা যাচাই-বাছাই করে জানানো হবে।</li>
                            <li>সকল (*) চিহ্নিত ক্ষেত্র পূরণ করা বাধ্যতামূলক।</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Terms and Conditions Modal -->
    <div class="modal fade" id="termsModal" tabindex="-1" aria-labelledby="termsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="termsModalLabel">নিয়ম ও শর্তাবলী</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h5>সদস্যর শর্তাবলী</h5>
                    <ol>
                        <li>আবেদনকারীকে বাংলাদেশী নাগরিক এবং ১৮ বছর বা তার বেশি বয়সী হতে হবে।</li>
                        <li>প্রদত্ত সকল তথ্য সঠিক ও সত্য হতে হবে। মিথ্যা তথ্য প্রদান করলে সদস্যপদ বাতিল করা হবে।</li>
                        <li>জাতীয় পরিচয়পত্র ছাড়া আবেদন গ্রহণযোগ্য নয়।</li>
                        <li>দলের সকল নীতি ও আদর্শের প্রতি অঙ্গীকারবদ্ধ থাকতে হবে।</li>
                        <li>দলের সংবিধান ও নিয়মাবলী মেনে চলতে সম্মত থাকতে হবে।</li>
                        <li>আপনি সম্মত হচ্ছেন যে, আপনার প্রদত্ত তথ্য দলের কার্যক্রমের জন্য সংরক্ষণ ও ব্যবহার করা হবে।</li>
                        <li>সদস্যপদ প্রদানের বিষয়ে দলের সিদ্ধান্তই চূড়ান্ত বলে গণ্য হবে।</li>
                        <li>সদস্য বাতিলের অধিকার দল সংরক্ষণ করে।</li>
                    </ol>

                    <h5 class="mt-4">ব্যক্তিগত তথ্য নীতিমালা</h5>
                    <p>আপনার প্রদত্ত তথ্য নিম্নলিখিত উদ্দেশ্যে ব্যবহার করা হবে:</p>
                    <ul>
                        <li>সদস্য যাচাইকরণ ও অনুমোদন</li>
                        <li>দলীয় কার্যক্রম সম্পর্কে অবহিতকরণ</li>
                        <li>সদস্য ব্যবস্থাপনা</li>
                        <li>অভ্যন্তরীণ বিশ্লেষণ ও পরিসংখ্যান</li>
                    </ul>

                    <p>আমরা আপনার তথ্য তৃতীয় পক্ষের সাথে শেয়ার করি না, আইনগত বাধ্যবাধকতা ছাড়া।</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">বুঝেছি</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        $(document).ready(function () {
            // Initialize date picker
            $(".flatpickr-date").flatpickr({
                dateFormat: "Y-m-d",
                maxDate: "today",
                allowInput: true,
                yearRange: "1940:{{ date('Y') }}",
            });

            // Handle image previews
            $("#photo").change(function () {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        $("#photo-preview").attr("src", e.target.result);
                        $("#photo-preview-container").show();
                    };
                    reader.readAsDataURL(file);
                }
            });

            $("#nid_scan").change(function () {
                const file = this.files[0];
                if (file) {
                    if (file.type === "application/pdf") {
                        $("#nid-preview").hide();
                        $("#pdf-filename").text(file.name).show();
                        $("#nid-preview-container").show();
                    } else {
                        const reader = new FileReader();
                        reader.onload = function (e) {
                            $("#pdf-filename").hide();
                            $("#nid-preview").attr("src", e.target.result).show();
                            $("#nid-preview-container").show();
                        };
                        reader.readAsDataURL(file);
                    }
                }
            });

            // Scroll to section on sidebar click
            $(".sidebar-step").click(function () {
                const sectionId = $(this).data("section");
                $([document.documentElement, document.body]).animate({
                    scrollTop: $("#" + sectionId).offset().top - 100
                }, 500);

                $(".sidebar-step").removeClass("active");
                $(this).addClass("active");
            });

            // Update active section on scroll
            $(window).scroll(function () {
                const scrollPosition = $(window).scrollTop();

                $(".form-section").each(function () {
                    const sectionTop = $(this).offset().top - 200;
                    const sectionId = $(this).attr("id");

                    if (scrollPosition >= sectionTop) {
                        $(".sidebar-step").removeClass("active");
                        $(".sidebar-step[data-section='" + sectionId + "']").addClass("active");
                    }
                });
            });

            // Handle division, district, upazila cascade
            $("#division_id").change(function () {
                const divisionId = $(this).val();
                if (divisionId) {
                    // Reset and disable lower selects
                    $("#district_id").html('<option value="">প্রথমে বিভাগ নির্বাচন করুন</option>').prop("disabled", true);
                    $("#upazila_id").html('<option value="">প্রথমে জেলা নির্বাচন করুন</option>').prop("disabled", true);
                    $("#union_id").html('<option value="">প্রথমে উপজেলা নির্বাচন করুন</option>').prop("disabled", true);

                    // Get districts
                    $.ajax({
                        url: '/get-districts/' + divisionId,
                        type: 'GET',
                        success: function (data) {
                            let districtOptions = '<option value="">জেলা নির্বাচন করুন</option>';
                            $.each(data, function (id, bn_name) {
                                districtOptions += `<option value="${id}">${bn_name}</option>`;
                            });
                            $("#district_id").html(districtOptions).prop("disabled", false);
                        }
                    });
                } else {
                    $("#district_id").html('<option value="">প্রথমে বিভাগ নির্বাচন করুন</option>').prop("disabled", true);
                    $("#upazila_id").html('<option value="">প্রথমে জেলা নির্বাচন করুন</option>').prop("disabled", true);
                    $("#union_id").html('<option value="">প্রথমে উপজেলা নির্বাচন করুন</option>').prop("disabled", true);
                }
            });

            $("#district_id").change(function () {
                const districtId = $(this).val();
                if (districtId) {
                    // Reset and disable lower selects
                    $("#upazila_id").html('<option value="">প্রথমে জেলা নির্বাচন করুন</option>').prop("disabled", true);
                    $("#union_id").html('<option value="">প্রথমে উপজেলা নির্বাচন করুন</option>').prop("disabled", true);

                    // Get upazilas
                    $.ajax({
                        url: '/get-upazilas/' + districtId,
                        type: 'GET',
                        success: function (data) {
                            let upazilaOptions = '<option value="">উপজেলা নির্বাচন করুন</option>';
                            $.each(data, function (id, bn_name) {
                                upazilaOptions += `<option value="${id}">${bn_name}</option>`;
                            });
                            $("#upazila_id").html(upazilaOptions).prop("disabled", false);
                        }
                    });
                } else {
                    $("#upazila_id").html('<option value="">প্রথমে জেলা নির্বাচন করুন</option>').prop("disabled", true);
                    $("#union_id").html('<option value="">প্রথমে উপজেলা নির্বাচন করুন</option>').prop("disabled", true);
                }
            });

            $("#upazila_id").change(function () {
                const upazilaId = $(this).val();
                if (upazilaId) {
                    // Reset union select
                    $("#union_id").html('<option value="">প্রথমে উপজেলা নির্বাচন করুন</option>').prop("disabled", true);

                    // Get unions
                    $.ajax({
                        url: '/get-unions/' + upazilaId,
                        type: 'GET',
                        success: function (data) {
                            let unionOptions = '<option value="">ইউনিয়ন নির্বাচন করুন</option>';
                            $.each(data, function (id, bn_name) {
                                unionOptions += `<option value="${id}">${bn_name}</option>`;
                            });
                            $("#union_id").html(unionOptions).prop("disabled", false);
                        }
                    });
                } else {
                    $("#union_id").html('<option value="">প্রথমে উপজেলা নির্বাচন করুন</option>').prop("disabled", true);
                }
            });
        });
    </script>
@endsection