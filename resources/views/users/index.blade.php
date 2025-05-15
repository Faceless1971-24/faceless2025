@extends('layouts.base')

@section('title', 'ব্যবহারকারী পরিচালনা')


@section('breadcrumb')<div style="background-color: #f8f9fa; padding: 1rem 0; margin-bottom: 2rem; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
        <div style="max-width: 1200px; margin: 0 auto; padding: 0 1rem;">
            <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                <div style="display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center;">
                    <h1 style="font-size: 1.5rem; font-weight: 600; margin: 0; color: #2c3e50;">
                        সদস্য পরিচালনা
                    </h1>
                    <nav aria-label="breadcrumb">
                        <ol style="display: flex; list-style: none; margin: 0; padding: 0;">
                            <li style="margin-right: 0.5rem;">সদস্য</li>
                            <li>
                                <span style="margin: 0 0.5rem;">/</span>
                                <a href="#" style="color: #3498db; text-decoration: none;">সদস্য পরিচালনা</a>
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

    <div style="background-color: #fff; border-radius: 0.5rem; overflow: hidden; box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.075); margin-bottom: 2rem;">
        <!-- Card Header with Actions -->
        <div style="display: flex; justify-content: space-between; align-items: center; padding: 1.25rem 1.5rem; border-bottom: 1px solid #edf2f7; flex-wrap: wrap; gap: 1rem;">
            <h3 style="font-size: 1.25rem; font-weight: 600; margin: 0; color: #2c3e50;">
                সদস্য তালিকা
            </h3>

            <div style="display: flex; flex-wrap: wrap; gap: 0.5rem;">
                <a href="{{route('user_excel_download')}}" style="text-decoration: none;">
                    <button type="button" style="display: inline-flex; align-items: center; background-color: #e4f2fa; color: #3498db; border: none; padding: 0.5rem 0.75rem; border-radius: 0.375rem; font-size: 0.875rem; cursor: pointer; transition: all 0.2s; font-weight: 500;">
                        <i class="fa fa-file-excel" style="margin-right: 0.5rem;"></i> ডাউনলোড সদস্য তথ্য
                    </button>
                </a>
                <button type="button" style="display: inline-flex; align-items: center; background-color: #e4f2fa; color: #3498db; border: none; padding: 0.5rem 0.75rem; border-radius: 0.375rem; font-size: 0.875rem; cursor: pointer; transition: all 0.2s; font-weight: 500;" data-toggle="modal" data-target="#modal-filter" data-class="d-none">
                    <i class="fa fa-flask" style="margin-right: 0.5rem;"></i> ফিল্টার সদস্য
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
                    ['data' => 'user_type', 'name' => 'user_type', 'th' => 'Member Type', 'sortable' => true],
                    ['data' => 'status', 'name' => 'users.is_active'],
                    ['data' => 'last_login', 'name' => 'users.last_login', 'th' => 'Last Login', 'searchable' => false],
                    ['data' => 'action', 'name' => 'action', 'searchable' => false, 'sortable' => false]
                ];
                $url = route('users.get-users');
            @endphp

            <x-datatable :columns="$columns" :url="$url" id="users-table">
                <thead>
                    <tr style="background-color: #2c3e50; color: #ffffff; text-transform: uppercase; font-weight: 600; white-space: nowrap;">
                        <th style="padding: 0.75rem 1rem;">id</th>
                        <th style="padding: 0.75rem 1rem;">Member ID</th>
                        <th style="width: 80px; padding: 0.75rem 1rem;">Photo</th>
                        <th style="padding: 0.75rem 1rem;">Name</th>
                        <th style="padding: 0.75rem 1rem;">Member Type</th>
                        <th style="padding: 0.75rem 1rem;">Status</th>
                        <th style="padding: 0.75rem 1rem;">Last Login</th>
                        <th style="padding: 0.75rem 1rem; text-align: right;">Action</th>
                    </tr>
                </thead>
            </x-datatable>
        </div>
    </div>

    <x-modal id="modal-filter" title="Filter Member">
        <form action="" id="userFilterForm">
            @csrf
            @method('PUT')
            <div style="padding: 1.5rem; font-size: 0.875rem;">
                <div style="margin-bottom: 1rem;">
                    <label for="is_active" style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: #2c3e50;">Status</label>
                    <select name="is_active" id="is_active" style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #e2e8f0; border-radius: 0.375rem; appearance: none; background-image: url('data:image/svg+xml;charset=US-ASCII,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M12 13.172l4.95-4.95 1.414 1.414L12 16 5.636 9.636 7.05 8.222z"/></svg>'); background-repeat: no-repeat; background-position: right 0.75rem center; background-size: 1rem;">
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div>
            </div>
            <div style="display: flex; justify-content: flex-end; padding: 1rem 1.5rem; border-top: 1px solid #e2e8f0;">
                <button type="button" style="background-color: #e2e8f0; color: #4a5568; border: none; padding: 0.5rem 1rem; border-radius: 0.375rem; margin-right: 0.5rem; cursor: pointer;" data-dismiss="modal">Close</button>
                <button type="submit" style="background-color: #3498db; color: white; border: none; padding: 0.5rem 1rem; border-radius: 0.375rem; cursor: pointer;">Submit</button>
            </div>
        </form>
    </x-modal>
@endsection

@section('scripts_after')
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {


 

            // Filter form submission
            $('#userFilterForm').submit(function(e) {
                e.preventDefault();

                var is_active = $('#is_active').val();
                var actionUrl = '{{ $url }}' + `?is_active=${is_active}`;

                var dt = $('#users-table').DataTable();
                dt.ajax.url(actionUrl);
                dt.draw();
            });
        });

        function confirmPasswordReset() {
            return confirm('Are you sure want to reset password?');
        }

        function confirmActivate() {
            return confirm('Are you sure want to activate user?');
        }

        function confirmDeactivate() {
            return confirm('Are you sure want to deactivate user?');
        }
    </script>
@endsection