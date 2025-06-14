@extends('layouts.base')

@section('title', 'Employee Department')

@section('breadcrumb')
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
                <h1 class="flex-sm-fill h3 my-2">
                    Manage Employee Department
                </h1>
                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item">Settings</li>
                        <li class="breadcrumb-item" aria-current="page">
                            <a class="link-fx" href="">Employee Department</a>
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
                    <h3 class="block-title">Employee Department</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option mr-2" data-toggle="modal" data-target="#modal-new">
                            <i class="fa fa-plus mr-1"></i> New
                        </button>
                    </div>
                </div>

                <div class="block-content block-content-full">
                    <table class="table table-bordered table-striped table-vcenter " id="leave-table">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Action</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach($employee_departments as $employee_department)
                            <tr>
                                <td>{{ $employee_department->name }}</td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-alt-info js-tooltip-enabled" data-toggle="modal"
                                            data-original-title="Edit" data-target="#modal-update-{{ $employee_department->id }}">
                                        <i class="fa fa-fw fa-pencil-alt"></i>
                                    </button>
                                </td>
                            </tr>

                            <x-modal id="modal-update-{{ $employee_department->id }}" title="Update Employee Department">
                                <form action="{{ route('employee_department.update', ['employee_department' => $employee_department->id]) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="block-content font-size-sm">
                                        <div class="form-group">
                                            <label for="name">Name *</label>
                                            <input type="text" name="name" id="name{{ $employee_department->id }}"
                                                   value="{{ $employee_department->name }}"
                                                   class="form-control" placeholder="Employee Department" required />
                                        </div>


                                    </div>
                                    <div class="block-content block-content-full text-right border-top">
                                        <button type="button" class="btn btn-alt-info mr-1" data-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-info" >Submit</button>
                                    </div>
                                </form>
                            </x-modal>

                        @endforeach
                        </tbody>

                    </table>
                </div>

                <x-modal id="modal-new" title="New Employee Department">
                    <form action="{{ route('employee_department.store') }}" method="POST">
                        @csrf
                        <div class="block-content font-size-sm">
                            <div class="form-group">
                                <label for="name">Name *</label>
                                <input type="text" name="name" id="name" value="{{ old('name') }}" class="form-control" placeholder="Employee Department" required />
                            </div>
                          
                        </div>
                        <div class="block-content block-content-full text-right border-top">
                            <button type="button" class="btn btn-alt-info mr-1" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-info" >Submit</button>
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
            $('#leave-table').DataTable();
        })
    </script>

@endsection
