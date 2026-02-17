<!DOCTYPE html>
<html lang="fa" dir="rtl">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />


    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.rtl.min.css"
        integrity="sha384-Xbg45MqvDIk1e563NLpGEulpX6AvL404DP+/iCgW9eFa2BqztiwTexswJo2jLMue" crossorigin="anonymous">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

    <link rel="stylesheet" href="{{ asset('css/main.css') }}" />

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    @yield('link')

    <title>Sahab || @yield('title')</title>
</head>


<body>
    <header class="navbar navbar-dark bg-dark sticky-top p-2 shadow">
        <div class="container-fluid d-flex justify-content-between align-items-center">

            <!-- سمت راست خالی -->
            <div></div>

            <!-- سمت چپ -->
            <div class="d-flex align-items-center gap-3">

                <!-- عکس پروفایل -->
                <img src="{{ asset('images/default.png') }}" alt="profile" width="36" height="36"
                    class="rounded-circle border profile-img">

                <!-- نام کاربر -->
                <span class="text-white fw-bold">
                    {{ Auth::user()->name }}
                </span>
                @can('changePassword', Auth::user())
                    <!-- تغییر رمز -->
                    <a href="{{ route('changePassword') }}" class="text-white fs-5 custom-tooltip"
                        data-tooltip="تغییر کلمه عبور">
                        <i class="bi bi-key-fill"></i>
                    </a>

                    <!-- ویرایش پروفایل -->
                    <a href="#" class="text-white fs-5 custom-tooltip" data-tooltip="ویرایش پروفایل">
                        <i class="bi bi-pencil-square"></i>
                    </a>
                @endcan

                <!-- خروج -->
                <form action="{{ route('logout') }}" method="post" data-confirm="operation"
                    data-confirm-item="خروج از حساب کاربری" class="m-0 p-0 custom-tooltip" data-tooltip="خروج">
                    @csrf
                    <button type="submit" class="btn btn-link text-white fs-5 p-0">
                        <i class="bi bi-box-arrow-right"></i>
                    </button>
                </form>

            </div>

        </div>
    </header>



    <div class="container-fluid">
        <div class="row">
