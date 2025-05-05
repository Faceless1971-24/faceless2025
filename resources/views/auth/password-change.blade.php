@extends('layouts.base')

@section('title', 'পাসওয়ার্ড পরিবর্তন')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                    <div class="card-header bg-gradient-primary text-white p-4 border-0">
                        <div class="d-flex align-items-center">
                            
                            <h3 class="m-0 fw-bold text-white">পাসওয়ার্ড পরিবর্তন করুন</h3>
                        </div>
                    </div>

                    <div class="card-body p-4 p-lg-5">
                        <x-alert />

                        <p class="text-muted mb-4">আপনার অ্যাকাউন্ট সুরক্ষার জন্য একটি শক্তিশালী পাসওয়ার্ড নির্বাচন করুন।
                        </p>

                        <form action="{{ route('password-change.update') }}" method="POST">
                            @csrf
                            @method('PUT')

                            <!-- Current Password -->
                            <div class="mb-4">
                                <label for="current_password" class="form-label fw-medium">বর্তমান পাসওয়ার্ড <span
                                        class="text-danger">*</span></label>
                                <div class="input-group input-group-lg border rounded-3 overflow-hidden">
                                    <span class="input-group-text bg-light border-0">
                                        <i class="bi bi-lock-fill text-primary"></i>
                                    </span>
                                    <input type="password" class="form-control border-0 shadow-none py-3"
                                        id="current_password" name="current_password"
                                        placeholder="আপনার বর্তমান পাসওয়ার্ড লিখুন" required />
                                    <button class="btn btn-light border-0 password-toggle" type="button"
                                        data-target="current_password">
                                        <i class="bi bi-eye-slash"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- New Password -->
                            <div class="mb-4">
                                <label for="password" class="form-label fw-medium">নতুন পাসওয়ার্ড <span
                                        class="text-danger">*</span></label>
                                <div class="input-group input-group-lg border rounded-3 overflow-hidden">
                                    <span class="input-group-text bg-light border-0">
                                        <i class="bi bi-key-fill text-primary"></i>
                                    </span>
                                    <input type="password" class="form-control border-0 shadow-none py-3" id="password"
                                        name="password" placeholder="আপনার নতুন পাসওয়ার্ড লিখুন" required />
                                    <button class="btn btn-light border-0 password-toggle" type="button"
                                        data-target="password">
                                        <i class="bi bi-eye-slash"></i>
                                    </button>
                                </div>
                                <div class="password-strength mt-2 d-none">
                                    <div class="progress" style="height: 6px;">
                                        <div class="progress-bar bg-danger" role="progressbar" style="width: 0%"></div>
                                    </div>
                                    <small class="text-muted mt-1 d-block">পাসওয়ার্ডের শক্তি: <span
                                            class="strength-text">দুর্বল</span></small>
                                </div>
                            </div>

                            <!-- Confirm New Password -->
                            <div class="mb-4">
                                <label for="password_confirmation" class="form-label fw-medium">নতুন পাসওয়ার্ড নিশ্চিত করুন
                                    <span class="text-danger">*</span></label>
                                <div class="input-group input-group-lg border rounded-3 overflow-hidden">
                                    <span class="input-group-text bg-light border-0">
                                        <i class="bi bi-check-circle-fill text-primary"></i>
                                    </span>
                                    <input type="password" class="form-control border-0 shadow-none py-3"
                                        id="password_confirmation" name="password_confirmation"
                                        placeholder="আপনার নতুন পাসওয়ার্ড নিশ্চিত করুন" required />
                                    <button class="btn btn-light border-0 password-toggle" type="button"
                                        data-target="password_confirmation">
                                        <i class="bi bi-eye-slash"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Warning -->
                            <div class="alert alert-warning d-flex align-items-center rounded-3 my-4" role="alert">
                                <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
                                <div>পাসওয়ার্ড পরিবর্তনের পর, আপনি সিস্টেম থেকে লগআউট হয়ে যাবেন।</div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="d-grid gap-3 d-sm-flex justify-content-sm-between mt-4">
                                <button type="reset" class="btn btn-outline-secondary btn-lg px-4">
                                    <i class="bi bi-arrow-counterclockwise me-2"></i>রিসেট
                                </button>
                                <button type="submit" class="btn btn-primary btn-lg px-5">
                                    <i class="bi bi-check-lg me-2"></i>পাসওয়ার্ড পরিবর্তন করুন
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.min.css">
    <style>
        .bg-gradient-primary {
            background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
        }

        .icon-box {
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .rounded-4 {
            border-radius: 1rem !important;
        }

        .input-group {
            transition: all 0.3s;
        }

        .input-group:focus-within {
            box-shadow: 0 0 0 0.25rem rgba(78, 115, 223, 0.25);
        }

        .password-toggle {
            cursor: pointer;
        }

        .password-strength {
            transition: all 0.3s;
        }

        /* Custom progress bar styling */
        .progress-bar {
            height: 6px;
            transition: width 0.3s ease-in-out;
        }

        .password-strength small {
            font-size: 0.875rem;
        }
    </style>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Password visibility toggle
            document.querySelectorAll('.password-toggle').forEach(button => {
                button.addEventListener('click', function () {
                    const targetId = this.getAttribute('data-target');
                    const passwordInput = document.getElementById(targetId);
                    const icon = this.querySelector('i');

                    if (passwordInput.type === 'password') {
                        passwordInput.type = 'text';
                        icon.classList.replace('bi-eye-slash', 'bi-eye');
                    } else {
                        passwordInput.type = 'password';
                        icon.classList.replace('bi-eye', 'bi-eye-slash');
                    }
                });
            });

            // Password strength meter
            const passwordInput = document.getElementById('password');
            const strengthBar = document.querySelector('.progress-bar');
            const strengthText = document.querySelector('.strength-text');
            const strengthContainer = document.querySelector('.password-strength');

            passwordInput.addEventListener('input', function () {
                strengthContainer.classList.remove('d-none');
                const password = this.value;
                let strength = 0;

                if (password.length >= 8) strength += 25;
                if (password.match(/[A-Z]/)) strength += 25;
                if (password.match(/[0-9]/)) strength += 25;
                if (password.match(/[^A-Za-z0-9]/)) strength += 25;

                strengthBar.style.width = strength + '%';

                if (strength <= 25) {
                    strengthBar.className = 'progress-bar bg-danger';
                    strengthText.textContent = 'দুর্বল';
                } else if (strength <= 50) {
                    strengthBar.className = 'progress-bar bg-warning';
                    strengthText.textContent = 'মাঝারি';
                } else if (strength <= 75) {
                    strengthBar.className = 'progress-bar bg-info';
                    strengthText.textContent = 'ভালো';
                } else {
                    strengthBar.className = 'progress-bar bg-success';
                    strengthText.textContent = 'শক্তিশালী';
                }
            });
        });
    </script>
@endsection