<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ env('APP_NAME') }} | @yield('title', 'Home')</title>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Icons -->
    <link rel="shortcut icon" href="{{ asset('theme/media/favicons/favicon.png') }}">

    <!-- Fonts and Styles -->
    @yield('styles_before')
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap">
    <link rel="stylesheet" href="{{ asset('theme/js/plugins/select2/css/select2.min.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-bs4.min.css" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('theme/js/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') }}">
    <link rel="stylesheet" href="{{ asset('theme/css/oneui.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    @yield('styles')

    <script src="{{ asset('js/alpine-2.8.1.min.js') }}" defer></script>

    <!-- Scripts -->
    <script>window.Laravel = {!! json_encode(['csrfToken' => csrf_token()]) !!};</script>
    @livewireStyles
</head>

<body>
    <div id="page-container" class="sidebar-o sidebar-dark enable-page-overlay side-scroll page-header-fixed">

        <!-- Sidebar -->
        <x-left-nav />
        <!-- END Sidebar -->

        <!-- Header -->
        <x-header />
        <!-- END Header -->

        <!-- Main Container -->
        <main id="main-container">
            <!-- Hero -->
            @yield('breadcrumb')
            <!-- END Hero -->

            <!-- Page Content -->
            <div class="content">
                @yield('content')
            </div>
            <!-- END Page Content -->
        </main>
        <!-- END Main Container -->

        <!-- Footer -->
        <footer id="page-footer" class="bg-body-light">
            <div class="content py-3">
                <div class="row font-size-sm">
                    <div class="col-sm-6 order-sm-1 py-1 text-center text-sm-left">
                        <a class="font-w600" href="#" target="_blank">{{ env('APP_NAME') }}</a> &copy; <span
                            data-toggle="year-copy"></span>
                    </div>
                </div>
            </div>
        </footer>
        <!-- END Footer -->

    </div>

    <!-- Add this line before oneui.app.min.js -->
    <!-- OneUI Core JS -->
    <script src="{{ asset('theme/js/oneui.core.min.js') }}"></script>
    <script src="{{ asset('js/jquery.scrolllock.js') }}"></script>
    <script src="{{ asset('theme/js/oneui.app.min.js') }}"></script>
    <script src="{{ asset('js/app.js') }}"></script>
<!-- Summernote JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-bs4.min.js"></script>
<script src="{{ asset('theme/js/plugins/select2/js/select2.full.min.js') }}"></script>
<script src="{{ asset('theme/js/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ asset('theme/js/plugins/moment/moment.js') }}"></script>

    <script type="text/javascript">
        function confirmDelete(formId = 'deleteForm') {
            if (confirm('Are you sure want to delete?')) {
                $('#' + formId).submit();
            } else {
                return;
            }
        }

        // Other JavaScript functionality can go here
    </script>

    @yield('scripts')
    @stack('scripts')
    @yield('scripts_after')
    @livewireScripts
</body>

</html>