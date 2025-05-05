@extends('layouts.base')

@section('title', 'ইউজার কনফিগ')

@section('breadcrumb')
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
                <h1 class="flex-sm-fill h3 my-2">
                    ইউজার টাইপ কনফিগ
                </h1>
                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item">অ্যাডমিন কনসোল</li>
                        <li class="breadcrumb-item" aria-current="page">
                            <a class="link-fx" href="{{ route('user-types.index') }}">ইউজার টাইপ</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">ইউজার টাইপ কনফিগ</li>
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
                <h3 class="block-title">User Type</h3>
                <div class="block-options">
                    {{-- <button type="button" class="btn-block-option mr-2" data-toggle="modal" data-target="#modal-new-menu">
                        <i class="fa fa-plus mr-1"></i> New Menu
                    </button> --}}
                    {{-- <button type="button" class="btn-block-option" data-toggle="block-option" data-action="fullscreen_toggle"></button>
                    <button type="button" class="btn-block-option" data-toggle="block-option" data-action="state_toggle" data-action-mode="demo">
                        <i class="si si-refresh"></i>
                    </button> --}}
                </div>
            </div>

            <div class="block-content block-content-full">

                <form action="{{ route('user-types.update', ['user_type' => $userType->id]) }}" method="POST">
                    @csrf
                    @method('put')
                    <div class="form-group">
                        <label for="title">Title *</label>
                        <input type="text" name="title" id="title"
                            value="{{ $userType->title }}"
                            class="form-control" placeholder="User type name" required />
                    </div>

                    <div class="form-group">
                        <label for="is_active">Status *</label>
                        <select name="is_active" id="is_active" class="form-control" required>
                            <option value="1" {{ $userType->is_active == 1 ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ $userType->is_active == 0 ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary" >Update</button>
                </form>
            </div>
        </div>

        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <h3 class="block-title">List of menu for user type</h3>
            </div>

            <form action="{{ route('user-types.update-menus', ['user_type' => $userType->id]) }}" method="POST">
                @csrf
                @method('put')
                <div class="block-content block-content-full">
                    @foreach ($menus as $menu)
                        @if(!$menu->parent_menu_id)
                        <div class="">
                            <label for="role_menu{{ $menu->id }}">
                                <input type="checkbox" name="role_menus[]"
                                    value="{{ $menu->id }}"
                                    id="role_menu{{ $menu->id }}"
                                    {{ $user_type_menus->contains($menu->id) ? 'checked' : '' }}> {{ $menu->title }}
                            </label>
                            @foreach ($menus as $ch_menu)
                                @if($ch_menu->parent_menu_id == $menu->id)
                                <div class="ml-4">
                                    <label for="role_menu{{ $ch_menu->id }}">
                                        <input type="checkbox" name="role_menus[]"
                                            value="{{ $ch_menu->id }}"
                                            id="role_menu{{ $ch_menu->id }}"
                                            {{ $user_type_menus->contains($ch_menu->id) ? 'checked' : '' }}> {{ $ch_menu->title }}
                                    </label>
                                </div>
                                @endif
                            @endforeach
                        </div>
                        @endif
                    @endforeach
                </div>
                <div class="block-content block-content-sm block-content-full bg-body-light">
                    <button type="submit" class="btn btn-primary" >Save</button>
                </div>
            </form>
        </div>


        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <h3 class="block-title">List of permissions for user type</h3>
            </div>

            <form action="{{ route('user-types.update-permissions', ['user_type' => $userType->id]) }}" method="POST">
                @csrf
                @method('put')
                <div class="block-content block-content-full">

                    <div class="row">
                        @foreach ($permissions as $perm)
                            <div class="col-md-4 mb-3">
                                <label for="role_permission{{ $perm->id }}">
                                    <input type="checkbox" name="role_permissions[]"
                                        value="{{ $perm->id }}"
                                        id="role_permission{{ $perm->id }}"
                                        {{ $user_type_permissions->contains($perm->id) ? 'checked' : '' }}> {{ $perm->name }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="block-content block-content-sm block-content-full bg-body-light">
                    <button type="submit" class="btn btn-primary" >Save</button>
                </div>
            </form>
        </div>

    </div>
</div>

@endsection


@section('styles')

@endsection

@section('scripts')

<script>
    $(document).ready(function(){
        // console.log('Page loaded')
    })
</script>

@endsection
