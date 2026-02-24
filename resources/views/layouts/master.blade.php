<!doctype html>
<html lang="fa" dir="rtl">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Sahab || @yield('title', 'پنل مدیریت')</title>

    {{-- Bootstrap RTL --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.rtl.min.css" rel="stylesheet">

    {{-- Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

    {{-- Costum Styles --}}
    <link rel="stylesheet" href="{{ asset('css/main.css') }}" />

    @stack('links')
    @stack('styles')
</head>

{{-- <body class="bg-light"> --}}

<body>

    @include('layouts.header')

    <div class="container-fluid">
        <div class="row">

            {{-- Sidebar desktop --}}
            <aside class="col-lg-2 d-none d-lg-block p-0 bg-white border-start">
                @include('layouts.sidebar')
            </aside>

            {{-- Main content --}}
            <main class="col-12 col-lg-10 p-3">
                @yield('content')
            </main>

        </div>
    </div>

    @include('layouts.footer')

    {{-- Mobile Sidebar (Offcanvas) --}}
    <div class="offcanvas offcanvas-start" style="width: 75vw;
    max-width: 320px;" tabindex="-1" id="mobileSidebar">
        <div class="offcanvas-header border-bottom">
            <h5 class="offcanvas-title">منو</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body p-0">
            @include('layouts.sidebar')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script src="{{ asset('js/confirm.js') }}"></script>

    {{-- show sweet alert --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-right',
            iconColor: 'white',
            customClass: {
                popup: 'colored-toast',
            },
            showConfirmButton: false,
            timer: 5000,
            timerProgressBar: true,
        })

        @if (session('success'))
            Toast.fire({
                icon: 'success',
                title: '{!! session('success') !!}',
            })
        @elseif (session('error'))
            Toast.fire({
                icon: 'error',
                title: '{!! session('error') !!}',
            })
        @elseif (session('warning'))
            Toast.fire({
                icon: 'warning',
                title: '{!! session('warning') !!}',
            })
        @endif
    </script>

    @stack('scripts')
</body>

</html>
