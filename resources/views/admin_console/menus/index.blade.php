@extends('layouts.base')

@section('title', 'মেনু কনফিগ')

@section('breadcrumb')
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
                <h1 class="flex-sm-fill h3 my-2">
                    অ্যাপ্লিকেশন মেনু পরিচালনা
                </h1>
                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item">অ্যাডমিন কনসোল</li>
                        <li class="breadcrumb-item" aria-current="page">
                            <a class="link-fx" href="">মেনু</a>
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
                <h3 class="block-title">Menus</h3>
                <div class="block-options">
                    <button type="button" class="btn-block-option mr-2" data-toggle="modal" data-target="#modal-new-menu">
                        <i class="fa fa-plus mr-1"></i> New Menu
                    </button>
                    {{-- <button type="button" class="btn-block-option" data-toggle="block-option" data-action="fullscreen_toggle"></button>
                    <button type="button" class="btn-block-option" data-toggle="block-option" data-action="state_toggle" data-action-mode="demo">
                        <i class="si si-refresh"></i>
                    </button> --}}
                </div>
            </div>

            <div class="block-content block-content-full">
                <table class="table table-bordered table-striped table-vcenter " id="menu-table">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Parent Menu</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($menus as $menu)
                        <tr>
                            <td style="border-left: {{ $menu->sub_menus_count ? '4px solid #5cace5' : '' }};">{{ $menu->title }}</td>
                            <td>{{ $menu->parent_menu->title ?? '-' }}</td>
                            <td>{!! $menu->status !!}</td>
                            <td>
                                <button type="button" class="btn btn-sm btn-alt-primary js-tooltip-enabled" data-toggle="modal"
                                    data-original-title="Edit" data-target="#modal-update-menu{{ $menu->id }}">
                                    <i class="fa fa-fw fa-pencil-alt"></i>
                                </button>

                                <button type="button" class="btn btn-sm btn-alt-danger js-tooltip-enabled" onclick="confirmDelete('menuDelete{{ $menu->id }}')" >
                                    <i class="fa fa-fw fa-times"></i>
                                </button>
                                <form method="POST" action="{{ route('menus.destroy', ['menu' => $menu->id]) }}" id="menuDelete{{ $menu->id }}">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>

                        <x-modal id="modal-update-menu{{ $menu->id }}" title="Update Menu">
                            <form action="{{ route('menus.update', ['menu' => $menu->id]) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="block-content font-size-sm">
                                    <div class="form-group">
                                        <label for="title">Title *</label>
                                        <input type="text" name="title" id="title{{ $menu->id }}"
                                            value="{{ $menu->title }}"
                                            class="form-control" placeholder="Menu title" required />
                                    </div>

                                    <div class="form-group">
                                        <label for="route_name">Route Name </label>
                                        <input type="text" name="route_name" id="route_name{{ $menu->id }}"
                                            value="{{ $menu->route_name }}"
                                            class="form-control" placeholder="Route name" />
                                    </div>

                                    <div class="form-group">
                                        <label for="menu_icon">Menu Icon</label>
                                        <input type="text" name="menu_icon" id="menu_icon{{ $menu->id }}"
                                            value="{{ $menu->menu_icon }}"
                                            class="form-control" placeholder="Menu Icon" />
                                    </div>

                                    <div class="form-group">
                                        <label for="menu_order">Menu Order</label>
                                        <input type="number" min="1" name="menu_order" id="menu_order{{ $menu->id }}"
                                            value="{{ $menu->menu_order }}"
                                            class="form-control" placeholder="Menu Order" />
                                    </div>

                                    <div class="form-group">
                                        <label for="parent_menu_id">Parent Menu</label>
                                        <select name="parent_menu_id" id="parent_menu_id{{ $menu->id }}" class="form-control">
                                            <option value="">-Select Parent Menu-</option>
                                            @foreach ($menus as $par_menu)
                                            <option value="{{ $par_menu->id }}" {{ $menu->parent_menu_id == $par_menu->id ? 'selected' : '' }}>
                                                {{ $par_menu->title }} {{ $par_menu->sub_menus_count ? '*' : '' }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="is_active">Status</label>
                                        <select name="is_active" id="is_active{{ $menu->id }}" class="form-control">
                                            <option value="1" {{ $menu->is_active == 1 ? 'selected' : '' }}>Active</option>
                                            <option value="0" {{ $menu->is_active == 0 ? 'selected' : '' }}>Inactive</option>
                                        </select>
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

            <x-modal id="modal-new-menu" title="New Menu">
                <form action="{{ route('menus.store') }}" method="POST">
                    @csrf
                    <div class="block-content font-size-sm">
                        <div class="form-group">
                            <label for="title">Title *</label>
                            <input type="text" name="title" id="title" class="form-control" placeholder="Menu title" required />
                        </div>

                        <div class="form-group">
                            <label for="route_name">Route Name </label>
                            <input type="text" name="route_name" id="route_name" class="form-control" placeholder="Route name" />
                        </div>

                        <div class="form-group">
                            <label for="menu_icon">Menu Icon</label>
                            <input type="text" name="menu_icon" id="menu_icon" class="form-control" placeholder="Menu Icon" />
                        </div>

                        <div class="form-group">
                            <label for="menu_order">Menu Order</label>
                            <input type="number" min="1" value="1" name="menu_order" id="menu_order" class="form-control" placeholder="Menu Order" />
                        </div>

                        <div class="form-group">
                            <label for="parent_menu_id">Parent Menu</label>
                            <select name="parent_menu_id" id="parent_menu_id" class="form-control">
                                <option value="">-Select Parent Menu-</option>
                                @foreach ($menus as $par_menu)
                                <option value="{{ $par_menu->id }}">{{ $par_menu->title }} {{ $par_menu->sub_menus_count ? '*' : '' }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="is_active">Status</label>
                            <select name="is_active" id="is_active" class="form-control">
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
        $('#menu-table').DataTable();
    })
</script>

@endsection
