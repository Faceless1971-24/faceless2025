@extends('frontend.layouts.master')

@section('title', 'ব্যবহারকারী প্রোফাইল')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card shadow border-0 rounded-4 overflow-hidden">
                    <div class="row g-0">
                        <!-- Left Panel: Profile Summary -->
                        <div class="col-md-4 bg-dark text-white text-center p-5 d-flex flex-column justify-content-center"
                            style="background: linear-gradient(135deg, #0d6efd, #6610f2);">
                            @if(auth()->user()->photo)
                                <img src="{{ asset('storage/' . auth()->user()->photo) }}"
                                    class="img-fluid rounded-circle border border-light mb-4 shadow"
                                    alt="{{ auth()->user()->name }} প্রোফাইল ছবি"
                                    style="width: 160px; height: 160px; object-fit: cover;">
                            @else
                                <img src="{{ asset('images/default-profile.png') }}"
                                    class="img-fluid rounded-circle border border-light mb-4 shadow" alt="ডিফল্ট প্রোফাইল ছবি"
                                    style="width: 160px; height: 160px; object-fit: cover;">
                            @endif
                            <h3 class="fw-bold mb-1">{{ $user->name }}</h3>
                            <p class="text-light-50 mb-0">{{ $user->email }}</p>
                        </div>

                        <!-- Right Panel: Details -->
                        <div class="col-md-8 p-5 bg-light">
                            <div class="mb-4 border-bottom pb-3 d-flex align-items-center justify-content-between">
                                <h4 class="text-primary m-0">
                                    <i class="fa-solid fa-user me-2"></i> প্রোফাইল বিবরণ
                                </h4>
                                <a href="{{ route('profile.edit') }}"
                                    class="btn btn-outline-primary rounded-pill px-4 shadow-sm">
                                    <i class="fa-solid fa-edit me-2"></i> সম্পাদনা করুন
                                </a>
                            </div>

                            <div class="row mb-4">
                                <div class="col-sm-6">
                                    <p class="mb-1 text-muted">📧 ইমেইল</p>
                                    <h6 class="fw-semibold">{{ $user->email }}</h6>
                                </div>
                                <div class="col-sm-6">
                                    <p class="mb-1 text-muted">📞 মোবাইল</p>
                                    <h6 class="fw-semibold">{{ $user->phone ?? 'যোগ করা হয়নি' }}</h6>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-6">
                                    <p class="mb-1 text-muted">📅 যোগদানের তারিখ</p>
                                    <h6 class="fw-semibold">
                                        {{ $user->created_at ? $user->created_at->format('d/m/Y') : 'N/A' }}</h6>
                                </div>

                            </div>
                        </div>
                    </div> <!-- row -->
                </div> <!-- card -->
            </div> <!-- col -->
        </div> <!-- row -->
    </div>
@endsection