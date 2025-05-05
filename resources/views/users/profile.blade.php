@extends('layouts.base')

@section('title', 'আমার প্রোফাইল')

@section('breadcrumb')
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
                <h1 class="flex-sm-fill h3 my-2">
                    আমার প্রোফাইল
                </h1>
            </div>
        </div>
    </div>
@endsection


@section('content')

    <div class="row">
        <div class="col-md-4">
            <!-- About -->
            <div class="block block-rounded block-link-shadow">
                <div class="block-header ">
                    <h3 class="block-title">About</h3>
                </div>
                <div class="block-content block-content-full text-center">
                    <div class="mb-3">
                        <img class="img-avatar img-avatar96" src="{{ $user->photo_path }}" alt="">
                    </div>
                    <div class="font-size-h5 mb-1">{{ $user->name }}</div>
                    <div class="font-size-sm text-muted">#{{ $user->userid }}</div>
                </div>
                <div class="block-content border-top">
                    <p class="mb-2">{{ $user->designation_id ? $user->designation->name : ''}}</p>
                    <p class="mb-2">Member Roles: {{ $user->roles->implode('title', ', ') }}</p>
                    <p class="mb-2">Joined On: {{ $user->joining_date ? $user->joining_date->format('d M Y') : $user->created_at->format('d M Y') }}</p>
                    <p class="mb-2">Supervisor: {{ $user->supervisor_id ? $user->supervisor_of_user->name : '' }}</p>
                </div>
                <div class="block-content border-top">
                    <p class="mb-2">Phone: {{ $user->phone  }}</p>
                    <p class="mb-2">Email: {{ $user->email }}</p>
                </div>
                <br>
            </div>
            <!-- END About -->
        </div>

        <div class="col-md-8">
            <div class="block block-rounded">
                <ul class="nav nav-tabs nav-tabs-alt align-items-center" data-toggle="tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" href="#ecom-product-info">Basic Info</a>
                    </li>
                </ul>
                <div class="block-content tab-content">
                    <!-- Info -->
                    <div class="tab-pane active" id="ecom-product-info" role="tabpanel">
                        <div class="row">
                            <div class="col-3"><strong>Education</strong></div>
                            <div class="col-6">{{$user->last_educational_qual ?: 'N/A' }}</div>
                        </div>
                        <div class="row">
                            <div class="col-3"><strong>Date of Birth</strong></div>
                            <div class="col-6">{{$user->dob ? $user->dob->format('d M Y') : 'N/A' }} ({{ $user->age }} years)</div>
                        </div>
                        <div class="row">
                            <div class="col-3"><strong>Gender</strong></div>
                            <div class="col-6">{{ $user->gender_text }}</div>
                        </div>
                        <div class="row">
                            <div class="col-3"><strong>Blood Group</strong></div>
                            <div class="col-6">{{ $user->blood_group_text }}</div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-3"><strong>Address</strong></div>
                            <div class="col-6">{{ $user->address ?: 'N/A' }}</div>
                        </div>
                        <div class="row">
                            <div class="col-3"><strong>District</strong></div>
                            <div class="col-6">{{ $user->district ? $user->district->name : 'N/A' }}</div>
                        </div>
                        <div class="row">
                            <div class="col-3"><strong>Division</strong></div>
                            <div class="col-6">{{ $user->division ? $user->division->name : 'N/A' }}</div>
                        </div>
                        <div class="row">
                            <div class="col-3"><strong>Post Code</strong></div>
                            <div class="col-6">{{ $user->post_code ?: 'N/A' }}</div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-3"><strong>Emergency</strong></div>
                            <div class="col-6">{{ $user->em_contact_name ?: 'N/A'}}</div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-3"><strong></strong></div>
                            <div class="col-6">{{ $user->em_contact_phone ?: 'N/A' }}</div>
                        </div>
                    </div>
                    <!-- END Info -->
                </div>
            </div>
            <!-- END Extra Info Tabs -->
        </div>
    </div>

@endsection


@section('styles')

@endsection

@section('scripts')

@endsection
