@extends('layouts.base')

@section('title', 'ইউজার টাইপ')

@section('breadcrumb')
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
                <h1 class="flex-sm-fill h3 my-2">
                    অ্যাপ্লিকেশন ইউজার টাইপ ম্যানেজ করুন
                </h1>
                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item">অ্যাডমিন কনসোল</li>
                        <li class="breadcrumb-item" aria-current="page">
                            <a class="link-fx" href="">ইউজার টাইপ</a>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
@endsection



@section('content')
<div class="row">
    <div class="col-xl-8 offset-xl-2 col-sm-12">

        <x-alert />

        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <h3 class="block-title">User Types</h3>
                <div class="block-options">
                    <button type="button" class="btn-block-option mr-2" data-toggle="modal" data-target="#modal-new">
                        <i class="fa fa-plus mr-1"></i> New
                    </button>
                </div>
            </div>

            <div class="block-content block-content-full">
                <table class="table table-bordered table-striped table-vcenter " id="user-types-table">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($user_types as $userType)
                        <tr>
                            <td>{{ $userType->title }}</td>
                            <td>{!! $userType->status !!}</td>
                            <td>
                                <a href="{{ route('user-types.config', ['user_type' => $userType->id]) }}"
                                    class="btn btn-sm btn-alt-primary js-tooltip-enabled mr-1"
                                    data-original-title="Config">
                                    <i class="fa fa-fw fa-cogs"></i>
                                </a>

                                <button type="button" class="btn btn-sm btn-alt-danger js-tooltip-enabled" onclick="confirmDelete('roleDelete{{ $userType->id }}')" >
                                    <i class="fa fa-fw fa-times"></i>
                                </button>
                                <form method="POST" action="{{ route('user-types.destroy', ['user_type' => $userType->id]) }}" id="roleDelete{{ $userType->id }}">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>

            <x-modal id="modal-new" title="New User Type">
                <form action="{{ route('user-types.store') }}" method="POST">
                    @csrf
                    <div class="block-content font-size-sm">
                        <div class="form-group">
                            <label for="title">Title *</label>
                            <input type="text" name="title" id="title" class="form-control" placeholder="User type name" required />
                        </div>

                        <div class="form-group">
                            <label for="is_active">Status *</label>
                            <select name="is_active" id="is_active" class="form-control" required>
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                    </div>

                    <div class="block-content block-content-full text-right border-top">
                        <button type="button" class="btn btn-alt-primary mr-1" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" >Submit</button>
                    </div>
                </form>
            </x-modal>

        </div>
    </div>
</div>

@endsection


@section('styles')
<link rel="stylesheet" href="{{ asset('theme/js/plugins/datatables/dataTables.bootstrap4.css') }}">
<link rel="stylesheet" href="{{ asset('theme/js/plugins/datatables/buttons-bs4/buttons.bootstrap4.min.css') }}">
@endsection

@section('scripts')
<script src="{{ asset('theme/js/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('theme/js/plugins/datatables/dataTables.bootstrap4.min.js') }}"></script>

<script>
    $(document).ready(function(){
        $('#user-types-table').DataTable();
    })
</script>

@endsection
