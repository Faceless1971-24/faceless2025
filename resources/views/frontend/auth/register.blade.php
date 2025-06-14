@extends('frontend.layouts.master')

@section('title', 'রেজিস্টার')

@section('content')
        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white py-3 border-0">
                            <h4 class="text-center text-primary">নতুন অ্যাকাউন্ট তৈরি করুন</h4>
                        </div>
                        <div class="card-body p-4">
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                        <form method="POST" action="{{ route('register') }}" novalidate>
                            @csrf

                            <div class="mb-3">
                                <label for="name" class="form-label">পূর্ণ নাম</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name"
                                   placeholder="Mr. Full Name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                                @error('name')
                                    <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="phone" class="form-label">ফোন নম্বর</label>
                                <input type="tel" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone"
                                   placeholder="01xxxxxxxxx" value="{{ old('phone') }}" required autocomplete="tel">
                                @error('phone')
                                    <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="password" class="form-label">পাসওয়ার্ড</label>
                                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password"
                                         placeholder="Minimum length 8"   name="password" required autocomplete="new-password">
                                        @error('password')
                                            <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="password_confirmation" class="form-label">পাসওয়ার্ড নিশ্চিত করুন</label>
                                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation"
                                           placeholder="Confirm Password" required autocomplete="new-password">
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input @error('terms') is-invalid @enderror" id="terms" name="terms"
                                    required>
                                <label class="form-check-label" for="terms">
                                    আমি <a href="#" class="text-decoration-none">শর্তাবলী</a> এবং
                                    <a href="#" class="text-decoration-none">গোপনীয়তা নীতি</a> মেনে নিচ্ছি
                                </label>
                                @error('terms')
                                    <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary py-2">রেজিস্টার করুন</button>
                            </div>
                        </form>

                        </div>
                        <div class="card-footer bg-white text-center py-3 border-0">
                            <p class="mb-0">ইতিমধ্যে অ্যাকাউন্ট আছে? <a href="{{ route('login') }}"
                                    class="text-decoration-none">লগইন করুন</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
@endsection