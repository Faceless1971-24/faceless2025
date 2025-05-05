@extends('layouts.base')

@section('title', ' প্রোফাইল')

@section('breadcrumb')
    <div class="bg-body-light">
        <div class="container py-2">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
                <h1 class="h3 my-2">
                     প্রোফাইল
                </h1>
            </div>
        </div>
    </div>
@endsection

@section('content')
        <div class="container">
            <div class="row g-4">
                <!-- Profile Card -->
                <div class="col-12 col-lg-4 mb-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-header bg-primary text-white">
                            <h3 class="card-title fs-5 mb-0">About</h3>
                        </div>
                        <div class="card-body text-center">
                            <div class="mb-3">
                                <img class="rounded-circle img-thumbnail"
                                    style="width: 120px; height: 120px; object-fit: cover;" src="{{ $user->photo_path }}"
                                    alt="{{ $user->name }}">
                            </div>
                            <h4 class="mb-1">{{ $user->name }}</h4>
                            <div class="text-muted small">#{{ $user->userid }}</div>
                        </div>
                        <ul class="list-group list-group-flush">
                            @if($user->designation_id)
                                <li class="list-group-item d-flex justify-content-between">
                                    <span class="text-muted">Designation:</span>
                                    <span>{{ $user->designation->name }}</span>
                                </li>
                            @endif
                            <li class="list-group-item d-flex justify-content-between">
                                <span class="text-muted">Member Roles:</span>
                                <span>{{ $user->roles->implode('title', ', ') }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <span class="text-muted">Joined On:</span>
    <span>
        {{ $user->joining_date ? $user->joining_date->format('d M Y') : ($user->created_at ? $user->created_at->format('d M Y') : 'N/A') }}
    </span>
                            </li>
                            @if($user->supervisor_id)
                                <li class="list-group-item d-flex justify-content-between">
                                    <span class="text-muted">Supervisor:</span>
                                    <span>{{ $user->supervisor_of_user->name }}</span>
                                </li>
                            @endif
                            <li class="list-group-item d-flex justify-content-between">
                                <span class="text-muted">Phone:</span>
                                <span>{{ $user->phone }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <span class="text-muted">Email:</span>
                                <span>{{ $user->email }}</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Information Tabs -->
                <div class="col-12 col-lg-8">
                    <div class="card shadow-sm">
                        <div class="card-header bg-white">
                            <ul class="nav nav-tabs card-header-tabs" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="basic-info-tab" data-bs-toggle="tab" href="#basic-info"
                                        role="tab">Basic Info</a>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body">
                            <div class="tab-content">
                                <div class="tab-pane fade show active" id="basic-info" role="tabpanel">
                                    <div class="row mb-4">
                                        <div class="col-12">
                                            <h5 class="border-bottom pb-2 mb-3">Personal Information</h5>
                                        </div>
                                        <div class="col-sm-6 mb-3">
                                            <div class="d-flex flex-column">
                                                <span class="text-muted small">Education</span>
                                                <span>{{$user->last_educational_qual ?: 'N/A' }}</span>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 mb-3">
                                            <div class="d-flex flex-column">
                                                <span class="text-muted small">Date of Birth</span>
                                                <span>{{$user->dob ? $user->dob->format('d M Y') : 'N/A' }} ({{ $user->age }}
                                                    years)</span>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 mb-3">
                                            <div class="d-flex flex-column">
                                                <span class="text-muted small">Gender</span>
                                                <span>{{ $user->gender_text }}</span>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 mb-3">
                                            <div class="d-flex flex-column">
                                                <span class="text-muted small">Blood Group</span>
                                                <span>{{ $user->blood_group_text }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mb-4">
                                        <div class="col-12">
                                            <h5 class="border-bottom pb-2 mb-3">Address Information</h5>
                                        </div>
                                        <div class="col-sm-6 mb-3">
                                            <div class="d-flex flex-column">
                                                <span class="text-muted small">Address</span>
                                                <span>{{ $user->address ?: 'N/A' }}</span>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 mb-3">
                                            <div class="d-flex flex-column">
                                                <span class="text-muted small">District</span>
                                                <span>{{ $user->district ? $user->district->name : 'N/A' }}</span>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 mb-3">
                                            <div class="d-flex flex-column">
                                                <span class="text-muted small">Division</span>
                                                <span>{{ $user->division ? $user->division->name : 'N/A' }}</span>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 mb-3">
                                            <div class="d-flex flex-column">
                                                <span class="text-muted small">Post Code</span>
                                                <span>{{ $user->post_code ?: 'N/A' }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-12">
                                            <h5 class="border-bottom pb-2 mb-3">Emergency Contact</h5>
                                        </div>
                                        <div class="col-sm-6 mb-3">
                                            <div class="d-flex flex-column">
                                                <span class="text-muted small">Contact Name</span>
                                                <span>{{ $user->em_contact_name ?: 'N/A'}}</span>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 mb-3">
                                            <div class="d-flex flex-column">
                                                <span class="text-muted small">Contact Phone</span>
                                                <span>{{ $user->em_contact_phone ?: 'N/A' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
@endsection

@section('styles')
    <style>
        /* Custom styles for profile page */
        .card {
            border-radius: 0.75rem;
            border: none;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .15) !important;
        }

        .card-header {
            border-top-left-radius: 0.75rem !important;
            border-top-right-radius: 0.75rem !important;
        }

        /* Make sure text doesn't overflow on small screens */
        .list-group-item {
            overflow-wrap: break-word;
            word-wrap: break-word;
        }

        /* Responsive font sizes */
        @media (max-width: 576px) {
            h1 {
                font-size: 1.5rem;
            }

            h4 {
                font-size: 1.1rem;
            }
        }
    </style>
@endsection

@section('scripts')
    <script>
        // Add any custom JavaScript here
        document.addEventListener('DOMContentLoaded', function () {
            // Initialize Bootstrap components if needed
            const tooltips = document.querySelectorAll('[data-bs-toggle="tooltip"]');
            tooltips.forEach(tooltip => {
                new bootstrap.Tooltip(tooltip);
            });
        });
    </script>
@endsection