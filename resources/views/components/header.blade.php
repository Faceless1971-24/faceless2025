<header
    style="background:transparent; border-bottom:1px solid rgba(255,255,255,0.2); padding:10px 14px; border-radius:0 0 12px 12px; box-shadow:none;">
    <div style="display:flex; justify-content:space-between; align-items:center;">

        <div style="display:flex; align-items:center;">

            <button type="button"
                style="background:rgba(255,255,255,0.8); border:none; padding:6px; margin-right:8px; border-radius:50%; box-shadow:0 1px 3px rgba(0,0,0,0.05); display:inline-block;"
                data-toggle="layout" data-action="sidebar_toggle">
                <i class="fa fa-fw fa-bars" style="color:#3b82f6;"></i>
            </button>

            <!-- -->

            <div style="font-weight:600; color:rgba(0, 0, 0, 0.8);">
                দায়িত্ব:
                <span style="color:#60a5fa;">
                    {{ auth()->user()->is_superuser ? 'Super Admin' : optional(session('role'))->title }}
                </span>
            </div>
        </div>

        <!-- Right: Profile Dropdown -->
        <div style="position:relative;">
            <button id="dropdown-toggle" onclick="toggleDropdown()"
                style="display:flex; align-items:center; background:#3b82f6; color:white; border:none; padding:6px 10px; border-radius:9999px; box-shadow:0 2px 5px rgba(0,0,0,0.1); cursor:pointer;">

                <div
                    style="width:28px; height:28px; overflow:hidden; border-radius:50%; border:2px solid white; margin-right:8px;">
                    <img src="{{ asset('storage/' . auth()->user()->photo) }}" alt="{{ auth()->user()->name }}"
                        style="width:100%; height:100%; object-fit:cover;">

                </div>
                <span style="font-weight:500; display:none; d-sm:inline-block;">{{ auth()->user()->name }}</span>
                <i class="fa fa-chevron-down" style="margin-left:6px; color:white;"></i>
            </button>

            <!-- Dropdown Menu -->
            <div id="dropdown-menu"
                style="display:none; position:absolute; right:0; top:100%; background:#fff; margin-top:8px; width:240px; border-radius:0 0 10px 10px; box-shadow:0 4px 12px rgba(0,0,0,0.1); z-index:999;">

                <!-- Profile Header -->
                <div
                    style="background:#3b82f6; color:white; text-align:center; padding:16px; border-radius:10px 10px 0 0;">
                    <div
                        style="width:60px; height:60px; border-radius:50%; overflow:hidden; border:2px solid white; margin:0 auto 8px;">
                        <img src="{{ asset('storage/' . auth()->user()->photo) }}" alt="{{ auth()->user()->name }}"
                            style="width:100%; height:100%; object-fit:cover;">
                    </div>
                    <div style="font-weight:bold;">{{ auth()->user()->name }}</div>
                    <div style="font-size:0.85rem;">
                        {{ session('role')->title ?? (auth()->user()->is_superuser ? 'Super Admin' : '') }}
                    </div>
                    <small style="color:rgba(255,255,255,0.7);">({{ auth()->user()->userid }})</small>
                </div>

                <!-- Menu Items -->
                <div style="padding:10px; background:#f8f9fa;">
                    @if (!auth()->user()->is_superuser)
                        <a href="{{ route('users.show', auth()->user()->id) }}"
                            style="display:flex; align-items:center; color:#0d6efd; text-decoration:none; padding:6px 10px; border-radius:6px; margin-bottom:4px;">
                            <i class="fa fa-user-circle" style="margin-right:8px; color:#6c757d;"></i> আমার প্রোফাইল
                        </a>
                        <a href="{{ route('users.edit', auth()->user()->id) }}"
                            style="display:flex; align-items:center; color:#17a2b8; text-decoration:none; padding:6px 10px; border-radius:6px; margin-bottom:4px;">
                            <i class="fa fa-cog" style="margin-right:8px; color:#6c757d;"></i> প্রোফাইল আপডেট করুন
                        </a>
                    @endif

                    <a href="{{ route('password-change.index') }}"
                        style="display:flex; align-items:center; color:#ffc107; text-decoration:none; padding:6px 10px; border-radius:6px; margin-bottom:4px;">
                        <i class="fa fa-key" style="margin-right:8px; color:#6c757d;"></i> পাসওয়ার্ড পরিবর্তন
                    </a>

                    <hr style="margin:8px 0; border-color:#dee2e6;">

                    <a href="#"
                        onclick="event.preventDefault();  document.getElementById('logoutForm').submit();"
                        style="display:flex; align-items:center; color:#dc3545; text-decoration:none; padding:6px 10px; border-radius:6px;">
                        <i class="fa fa-sign-out-alt" style="margin-right:8px; color:#6c757d;"></i> লগ আউট
                    </a>

                    <form id="logoutForm" action="{{ route('logout') }}" method="POST" style="display:none;">
                        @csrf
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>

<script>
    function toggleDropdown() {
        const menu = document.getElementById('dropdown-menu');
        menu.style.display = menu.style.display === 'block' ? 'none' : 'block';
    }

    // Optional: Close dropdown when clicking outside
    document.addEventListener('click', function (event) {
        const toggle = document.getElementById('dropdown-toggle');
        const menu = document.getElementById('dropdown-menu');
        if (!toggle.contains(event.target) && !menu.contains(event.target)) {
            menu.style.display = 'none';
        }
    });
</script>