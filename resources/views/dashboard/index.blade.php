@extends('layouts.base')

@section('title', 'Dashboard')

@section('breadcrumb')

@endsection

@section('content')

    <x-alert />

    <div class="row">
        @if($is_my_birth_day == 1)
            <div class="col-sm-12 col-xxl-3 mb-4">
                <div class="card">
                    <div class="card-header" style="background-color: rgba(106, 115, 5, 0.3)">
                        <b>Happy Birth Day !!!</b>
                    </div>
                    <div class="card-body">
                        <blockquote class="blockquote mb-0">
                            <p>Dear {{$user_info->name}},</p>
                            <p>"Happy Birthday! 🎉🎂 Wishing you a fantastic day filled with joy, laughter, and unforgettable
                                moments. Your hard work, dedication, and positive energy contribute so much to our team, and
                                we're grateful to have you as part of our family. </p>
                            <footer class="blockquote-footer">Appinion Bd Limited</footer>
                        </blockquote>
                    </div>
                </div>
            </div>
        @endif

        <div class="col-sm-3 col-xxl-3">
            <div class="block block-rounded d-flex flex-column">
                <div class="block-content block-content-full flex-grow-1 d-flex justify-content-between align-items-center">
                    <dl class="mb-0">
                        <dt class="fs-3 fw-bold">{{$countCampaigns}}</dt>
                            <dd class="fs-sm fw-medium fs-sm fw-medium text-muted mb-0">New Campaigns</dd>
                        </dl>
                        <button type="button" class="btn btn-sm btn-dual" data-toggle="layout" data-action="side_overlay_toggle"
                            style="padding: 0.75rem 1rem; font-size: 1.25rem;">
                            <i class="fas fa-bullhorn text-primary"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <aside id="side-overlay">
            <div class="content-header border-bottom">
                <div class="ml-2">
                    <a class="text-dark font-w600 font-size-sm" href="javascript:void(0)">Campaign Notifications</a>
                </div>
                <a class="ml-auto btn btn-sm btn-alt-danger" href="javascript:void(0)" data-toggle="layout"
                    data-action="side_overlay_close">
                    <i class="fa fa-fw fa-times"></i>
                </a>
            </div>
            <div class="content-side">
                <div class="block block-transparent pull-x pull-t">
                    <div class="block-content tab-content overflow-hidden">
                        <div class="block">
                            <div class="block-header block-header-default">
                                <h3 class="block-title">Latest Campaigns</h3>
                                <div class="col-6 text-right">
                                    <small>
                                        <span class="ext-muted">Past 2 Days</span>
                                    </small>
                                </div>
                            </div>

                            <div class="block-content">
                                <ul class="nav-items mb-0">
                                    @forelse ($newestCampaigns as $key => $campaign)
                                        <li>
                                            <a class="text-dark media py-2" href="{{ route('campaigns.show', $campaign->id) }}" title="View Campaign">
                                                <div class="mr-3 ml-2">
                                                    <i class="si si-pin text-success"></i>
                                                </div>
                                                <div class="media-body">
                                                    <div class="font-w600">{{ $campaign->title }}</div>
                                                    <small
                                                        class="text-muted">{{ \Carbon\Carbon::parse($campaign->created_at)->format('d M Y, h:i A') }}</small>
                                                </div>
                                            </a>
                                        </li>
                                    @empty
                                        <div class="text-center text-dark py-3">
                                            <p class="mb-0">No New Campaigns</p>
                                        </div>
                                    @endforelse
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </aside>

@endsection

@section('styles')

@endsection

@section('scripts')
@endsection