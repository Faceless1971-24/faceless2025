@extends('layouts.base')

@section('title', 'অনুমতি কনফিগ')

@section('breadcrumb')
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
                <h1 class="flex-sm-fill h3 my-2">
                    অ্যাপ্লিকেশন অনুমতি পরিচালনা
                </h1>
                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item">অ্যাডমিন কনসোল</li>
                        <li class="breadcrumb-item" aria-current="page">
                            <a class="link-fx" href="">অনুমতি</a>
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
                <h3 class="block-title">Permissions</h3>
                <div class="block-options">
                    <button type="button" class="btn-block-option mr-2" data-toggle="modal" data-target="#modal-new">
                        <i class="fa fa-plus mr-1"></i> New
                    </button>
                    {{-- <button type="button" class="btn-block-option" data-toggle="block-option" data-action="fullscreen_toggle"></button>
                    <button type="button" class="btn-block-option" data-toggle="block-option" data-action="state_toggle" data-action-mode="demo">
                        <i class="si si-refresh"></i>
                    </button> --}}
                </div>
            </div>

            <div class="block-content block-content-full">
                <table class="table table-bordered table-striped table-vcenter " id="perms-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($permissions as $perm)
                        <tr>
                            <td>{{ $perm->name }}</td>
                            <td>
                                <button type="button" class="btn btn-sm btn-alt-primary js-tooltip-enabled" data-toggle="modal"
                                    data-original-title="Edit" data-target="#modal-update-{{ $perm->id }}">
                                    <i class="fa fa-fw fa-pencil-alt"></i>
                                </button>

                                <button type="button" class="btn btn-sm btn-alt-danger js-tooltip-enabled" onclick="confirmDelete('permDelete{{ $perm->id }}')" >
                                    <i class="fa fa-fw fa-times"></i>
                                </button>
                                <form method="POST" action="{{ route('permissions.destroy', ['permission' => $perm->id]) }}" id="permDelete{{ $perm->id }}">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>

                        <x-modal id="modal-update-{{ $perm->id }}" title="Update Permission">
                            <form action="{{ route('permissions.update', ['permission' => $perm->id]) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="block-content font-size-sm">
                                    <div class="form-group">
                                        <label for="name">Name *</label>
                                        <input type="text" name="name" id="name{{ $perm->id }}"
                                            value="{{ $perm->name }}"
                                            class="form-control" placeholder="Permission Name" required />
                                    </div>

                                </div>
                                <div class="block-content block-content-full text-right border-top">
                                    <button type="button" class="btn btn-alt-primary mr-1" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary" >Submit</button>
                                </div>
                            </form>
                        </x-modal>

                        @endforeach
                    </tbody>

                </table>
            </div>

            <x-modal id="modal-new" title="New Permission">
                <form action="{{ route('permissions.store') }}" method="POST">
                    @csrf
                    <div class="block-content font-size-sm">
                        <div class="form-group">
                            <label for="name">Name *</label>
                            <input type="text" name="name" id="name" class="form-control" placeholder="Permission Name" required />
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
        $('#perms-table').DataTable();
    })
</script>

@endsection
