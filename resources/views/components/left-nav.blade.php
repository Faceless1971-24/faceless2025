<div>

    <nav id="sidebar" aria-label="Main Navigation"
    style="width: 250px; background-color: #fff; border-right: 1px solid #ddd; min-height: 100vh; position: fixed; transition: all 0.3s;">
        <div class="content-header bg-white-5">
                <a class="font-w600 text-dual d-flex align-items-start" href="{{ route('dashboard.index') }}"
                    style="text-decoration: none;">
                    <!-- Collapsed Logo (mini sidebar mode) -->
                    <span class="smini-visible">
                        <img src="{{ asset('theme/media/facelessD.jpeg') }}" alt="Logo" width="24">
                    </span>
                
                    <!-- Full App Name (expanded sidebar mode) -->
                    <span class="smini-hide ml-3 tracking-wider"
                        style="font-family: 'Roboto', sans-serif; display: flex; flex-direction: column; line-height: 1.2; overflow: hidden; white-space: nowrap; text-overflow: ellipsis;">
                
                        <!-- Main App Name -->
                        <span style="font-size: 1.15rem; font-weight: 600; color: #111827;  margin-top: 2px;">
                            {{ env('APP_NAME') }}
                        </span>
                
                        <!-- Mid Name / Version / Subtitle -->
                        <span style="font-size: 1.15rem; font-weight: 400; color:rgb(0, 0, 0); margin-top: 2px;">
                            {{ env('APP_MID_NAME') }}
                        </span>
                    </span>
                </a>
            <div>
                 

                <a class="d-lg-none btn btn-sm btn-dual ml-1" data-toggle="layout" data-action="sidebar_close" href="javascript:void(0)">
                    <i class="fa fa-fw fa-times"></i>
                </a>
            </div>
        </div>

        <div class="js-sidebar-scroll">
            <div class="content-side">
                <ul class="nav-main">
                    @foreach ($menus as $menu)
                        @php
    $menu_url = '#';
    $menu_dropdown_class = '';
    if ($menu->route_name && Route::has($menu->route_name)) {
        $menu_url = route($menu->route_name);
    } else {
        $menu_url = $menu->menu_url ? url($menu->menu_url) : '#';
    }

    if ($menu->sub_menus->count() > 0) {
        $menu_url = '#';
        $menu_dropdown_class = 'nav-main-link-submenu';
    }
                        @endphp

                        <li style="margin-bottom: 5px;" class="nav-main-item {{ isset($main_menu) && strtolower($main_menu) == strtolower($menu->title) ? 'open' : '' }}">
                            <a 
                                class="nav-main-link {{ $menu_dropdown_class }} {{ ($menu->route_name && request()->routeIs($menu->route_name)) || isset($main_menu) && strtolower($main_menu) == strtolower($menu->title) ? 'active' : '' }}"
                                href="{{ $menu_url }}"
                                data-toggle="{{ $menu->sub_menus->count() > 0 ? 'submenu' : '' }}"
                                aria-haspopup="{{ $menu->sub_menus->count() > 0 ? 'true' : '' }}"
                                aria-expanded=""
                                style="background-color:rgb(255, 255, 255); color:rgb(0, 0, 0); padding: 10px 15px; border-radius: 6px; margin-bottom: 4px; display: flex; align-items: center; transition: background 0.2s ease;">
                                <i class="nav-main-link-icon si si-{{ $menu->menu_icon }}" style="margin-right: 10px; color: #9ca3af;"></i>
                                <span class="nav-main-link-name" style="font-weight: 500;">{{ $menu->title }}</span>
                            </a>


                            @if($menu->main_menu)
                              <ul style="list-style: none; padding-left: 15px; margin-top: 8px;" class="nav-main-submenu">
                                    @foreach($menu->sub_menus as $child_menu)
                                        <li class="nav-main-item" style="margin-bottom: 6px;">
                                            <a class="nav-main-link {{ ($child_menu->route_name && request()->routeIs($child_menu->route_name)) || isset($sub_menu) && strtolower($sub_menu) == strtolower($child_menu->title) ? 'active' : '' }}"
                                                href="{{ $child_menu->route_name && Route::has($child_menu->route_name) ? route($child_menu->route_name) : $child_menu->menu_url }}"
                                                style="display: block; margin-top: 5px; padding: 10px 15px; color:rgb(0, 0, 0); background-color:rgb(255, 255, 255); border-radius: 150px; transition: background-color 0.2s, color 0.2s;">
                                                <span class="nav-main-link-name" style="font-weight: 400;">{{ $child_menu->title }}</span>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </nav>
</div>

