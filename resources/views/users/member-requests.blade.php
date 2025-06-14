@extends('layouts.base')

@section('title', 'সদস্য আবেদন পরিচালনা')

@section('breadcrumb')
    <div style="background-color: #f8f9fa; padding: 1rem 0; margin-bottom: 2rem; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
        <div style="max-width: 1200px; margin: 0 auto; padding: 0 1rem;">
            <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                <div style="display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center;">
                    <h1 style="font-size: 1.5rem; font-weight: 600; margin: 0; color: #2c3e50;">
                        সদস্য আবেদন পরিচালনা
                    </h1>
                    <nav aria-label="breadcrumb">
                        <ol style="display: flex; list-style: none; margin: 0; padding: 0;">
                            <li style="margin-right: 0.5rem;">সদস্য</li>
                            <li>
                                <span style="margin: 0 0.5rem;">/</span>
                                <a href="#" style="color: #3498db; text-decoration: none;">সদস্য আবেদন</a>
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <x-alert />

    <div
        style="background-color: #fff; border-radius: 0.5rem; overflow: hidden; box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.075); margin-bottom: 2rem;">
        <!-- Card Header with Actions -->
        <div
            style="display: flex; justify-content: space-between; align-items: center; padding: 1.25rem 1.5rem; border-bottom: 1px solid #edf2f7; flex-wrap: wrap; gap: 1rem;">
            <h3 style="font-size: 1.25rem; font-weight: 600; margin: 0; color: #2c3e50;">
                সদস্য আবেদন তালিকা
            </h3>

            <div style="display: flex; flex-wrap: wrap; gap: 0.5rem;">
                <a href="{{route('users.member_requests_excel_download')}}" style="text-decoration: none;">
                    <button type="button"
                        style="display: inline-flex; align-items: center; background-color: #e4f2fa; color: #3498db; border: none; padding: 0.5rem 0.75rem; border-radius: 0.375rem; font-size: 0.875rem; cursor: pointer; transition: all 0.2s; font-weight: 500;">
                        <i class="fa fa-file-excel" style="margin-right: 0.5rem;"></i> ডাউনলোড আবেদন তথ্য
                    </button>
                </a>
                <button type="button"
                    style="display: inline-flex; align-items: center; background-color: #e4f2fa; color: #3498db; border: none; padding: 0.5rem 0.75rem; border-radius: 0.375rem; font-size: 0.875rem; cursor: pointer; transition: all 0.2s; font-weight: 500;"
                    data-toggle="modal" data-target="#modal-filter" data-class="d-none">
                    <i class="fa fa-flask" style="margin-right: 0.5rem;"></i> ফিল্টার আবেদন
                </button>
            </div>
        </div>

        <!-- Table Container with Horizontal Scroll for Small Screens -->
        <div style="padding: 1.5rem; overflow-x: auto;">
            @php
                $columns = [
                    ['data' => 'id', 'name' => 'id', 'visible' => false, 'searchable' => false],
                    ['data' => 'userid', 'name' => 'users.userid', 'visible' => false, 'sortable' => false],
                    ['data' => 'photo', 'name' => 'photo', 'searchable' => false, 'sortable' => false, 'className' => 'text-center'],
                    ['data' => 'name', 'name' => 'users.name'],
                    ['data' => 'phone', 'name' => 'users.phone'],
                    ['data' => 'location', 'name' => 'location', 'sortable' => false],
                    ['data' => 'status', 'name' => 'users.status'],
                    ['data' => 'created_at', 'name' => 'users.created_at', 'th' => 'Application Date', 'searchable' => false],
                    ['data' => 'action', 'name' => 'action', 'searchable' => false, 'sortable' => false]
                ];
                $url = route('users.getMemberRequests');
            @endphp

            <x-datatable :columns="$columns" :url="$url" id="member-requests-table">
                <thead>
                    <tr
                        style="background-color: #2c3e50; color: #ffffff; text-transform: uppercase; font-weight: 600; white-space: nowrap;">
                        <th style="padding: 0.75rem 1rem;">id</th>
                        <th style="padding: 0.75rem 1rem;">Member ID</th>
                        <th style="width: 80px; padding: 0.75rem 1rem;">ছবি</th>
                        <th style="padding: 0.75rem 1rem;">নাম</th>
                        <th style="padding: 0.75rem 1rem;">মোবাইল</th>
                        <th style="padding: 0.75rem 1rem;">ঠিকানা</th>
                        <th style="padding: 0.75rem 1rem;">অবস্থা</th>
                        <th style="padding: 0.75rem 1rem;">আবেদনের তারিখ</th>
                        <th style="padding: 0.75rem 1rem; text-align: right;">পদক্ষেপ</th>
                    </tr>
                </thead>
            </x-datatable>
        </div>
    </div>

    <!-- Rejection Modal -->
    <x-modal id="modal-reject" title="আবেদন প্রত্যাখ্যান কারণ">
        <form action="" id="rejectionForm" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" name="user_id" id="rejection_user_id">
            <div style="padding: 1.5rem; font-size: 0.875rem;">
                <div style="margin-bottom: 1rem;">
                    <label for="rejection_reason"
                        style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: #2c3e50;">প্রত্যাখ্যানের
                        কারণ</label>
                    <textarea name="rejection_reason" id="rejection_reason" rows="4"
                        style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #e2e8f0; border-radius: 0.375rem;"
                        required></textarea>
                </div>
            </div>
            <div style="display: flex; justify-content: flex-end; padding: 1rem 1.5rem; border-top: 1px solid #e2e8f0;">
                <button type="button"
                    style="background-color: #e2e8f0; color: #4a5568; border: none; padding: 0.5rem 1rem; border-radius: 0.375rem; margin-right: 0.5rem; cursor: pointer;"
                    data-dismiss="modal">বাতিল</button>
                <button type="submit"
                    style="background-color: #e74c3c; color: white; border: none; padding: 0.5rem 1rem; border-radius: 0.375rem; cursor: pointer;">প্রত্যাখ্যান
                    করুন</button>
            </div>
        </form>
    </x-modal>

    <!-- Filter Modal -->
    <x-modal id="modal-filter" title="ফিল্টার আবেদন">
        <form action="" id="requestFilterForm">
            @csrf
            <div style="padding: 1.5rem; font-size: 0.875rem;">
                <div style="margin-bottom: 1rem;">
                    <label for="status"
                        style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: #2c3e50;">অবস্থা</label>
                    <select name="status" id="status"
                        style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #e2e8f0; border-radius: 0.375rem; appearance: none; background-image: url('data:image/svg+xml;charset=US-ASCII,<svg xmlns="
                        http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
                        <path fill="none" d="M0 0h24v24H0z" />
                        <path d="M12 13.172l4.95-4.95 1.414 1.414L12 16 5.636 9.636 7.05 8.222z" /></svg>');
                        background-repeat: no-repeat; background-position: right 0.75rem center; background-size: 1rem;">
                        <option value="all">সব</option>
                        <option value="pending">বিচারাধীন</option>
                        <option value="approved">অনুমোদিত</option>
                        <option value="rejected">প্রত্যাখ্যাত</option>
                    </select>
                </div>
            </div>
            <div style="display: flex; justify-content: flex-end; padding: 1rem 1.5rem; border-top: 1px solid #e2e8f0;">
                <button type="button"
                    style="background-color: #e2e8f0; color: #4a5568; border: none; padding: 0.5rem 1rem; border-radius: 0.375rem; margin-right: 0.5rem; cursor: pointer;"
                    data-dismiss="modal">বাতিল</button>
                <button type="submit"
                    style="background-color: #3498db; color: white; border: none; padding: 0.5rem 1rem; border-radius: 0.375rem; cursor: pointer;">ফিল্টার
                    করুন</button>
            </div>
        </form>
    </x-modal>
@endsection

@section('scripts_after')
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function () {
            // Filter form submission
            $('#requestFilterForm').submit(function (e) {
                e.preventDefault();

                var status = $('#status').val();
                var actionUrl = '{{ $url }}' + `?status=${status}`;

                var dt = $('#member-requests-table').DataTable();
                dt.ajax.url(actionUrl);
                dt.draw();

                $('#modal-filter').modal('hide');
            });

            // Set up rejection form
            $('body').on('click', '.reject-btn', function () {
                var userId = $(this).data('id');
                $('#rejection_user_id').val(userId);
                // Fixed route generation with proper ID parameter
                var rejectUrl = "{{ route('users.rejectMemberRequest', ['id' => ':id']) }}";
                rejectUrl = rejectUrl.replace(':id', userId);
                $('#rejectionForm').attr('action', rejectUrl);
                $('#modal-reject').modal('show');
            });
        });

        function confirmApprove() {
            return confirm('আপনি কি সত্যিই এই আবেদন অনুমোদন করতে চান?');
        }
    </script>
@endsection