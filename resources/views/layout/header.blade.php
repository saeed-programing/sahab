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
    {{-- <header class="navbar text-center navbar-dark sticky-top bg-dark flex-md-nowrap p-1 shadow">
        <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-bs-toggle="collapse"
            data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false"
            aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="w-100"></div>
        <div class="navbar-nav">
            <div class="nav-item text-nowrap d-flex align-items-center">

                <div class="dropdown text-end"> <a href="#"
                        class="d-block link-body-emphasis text-decoration-none dropdown-toggle"
                        data-bs-toggle="dropdown" aria-expanded="false"> <img src="https://github.com/mdo.png"
                            alt="mdo" width="32" height="32" class="rounded-circle"> </a>
                    <ul class="dropdown-menu text-small" style="">
                        <li><a class="dropdown-item" href="#">New project...</a></li>
                        <li><a class="dropdown-item" href="#">Settings</a></li>
                        <li><a class="dropdown-item" href="#">Profile</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" href="#">Sign out</a></li>
                    </ul>
                </div>

                <span class="nav-link text-white">{{ auth()->user()->name }}</span>
                <form action="{{ route('logout') }}" method="post">
                    @csrf
                    <button type="submit" class="nav-link text-white px-3">خروج</button>
                </form>
            </div>
        </div>
    </header> --}}

    {{-- <header class="navbar navbar-dark border-bottom sticky-top bg-dark p-1 shadow">
        <div class="container">
            <!-- wrapper اصلی: دو ستون بصورت space-between -->
            <div class="d-flex w-100 align-items-center justify-content-between">

                <!-- بخش راست (در RTL این بخش سمت راست صفحه قرار می‌گیرد) -->
                <div class="d-flex align-items-center">

                </div>

                <!-- بخش چپ (در RTL این بخش سمت چپ صفحه قرار می‌گیرد): آواتار و dropdown -->
                <div class="d-flex align-items-center">

                    <i class="bi bi-person-circle"></i>
                    <i class="bi bi-person-circle"></i>
                    <i class="bi bi-person-circle"></i>
                    <i class="bi bi-person-circle"></i>


                    <span class="nav-link text-white">{{ auth()->user()->name }}</span>


                    <div class="dropdown">
                        <a href="#" class="d-block link-body-emphasis text-decoration-none dropdown-toggle"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="" alt="mdo" width="32" height="32" class="rounded-circle">
                        </a>

                        <!-- توجه: برای RTL اگر می‌خواهی منو به سمت چپ تراز شود از dropdown-menu-end استفاده کن -->
                        <ul class="dropdown-menu dropdown-menu-end text-small">
                            <li><a class="dropdown-item" href="#">New project...</a></li>
                            <li><a class="dropdown-item" href="#">Settings</a></li>
                            <li><a class="dropdown-item" href="#">Profile</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <a class="dropdown-item" href="#">Sign out</a>
                                <form action="{{ route('logout') }}" method="post">
                                    @csrf
                                    <button type="submit" class="nav-link ropdown-item text-white px-3">خروج</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </header> --}}

    {{-- <header class="navbar text-center navbar-dark sticky-top bg-dark flex-md-nowrap p-2 shadow">
        <div class="container-fluid d-flex justify-content-between align-items-center">

            <!-- دکمه منو موبایل -->
            <button class="navbar-toggler d-md-none" type="button" data-bs-toggle="collapse"
                data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- آیکون‌های سمت چپ -->
            <div class="d-flex gap-4 align-items-center">

                <!-- آیکون ۱ -->
                <a href="#" class="icon-wrapper text-white text-decoration-none">
                    <i class="bi bi-speedometer2 fs-4"></i>
                    <span class="icon-hint">داشبورد</span>
                </a>

                <!-- آیکون ۲ -->
                <a href="#" class="icon-wrapper text-white text-decoration-none">
                    <i class="bi bi-envelope fs-4"></i>
                    <span class="icon-hint">پیام‌ها</span>
                </a>

                <!-- آیکون ۳ -->
                <a href="#" class="icon-wrapper text-white text-decoration-none">
                    <i class="bi bi-gear fs-4"></i>
                    <span class="icon-hint">تنظیمات</span>
                </a>

            </div>

            <!-- بخش پروفایل -->
            <div class="navbar-nav">
                <div class="nav-item text-nowrap d-flex align-items-center">

                    <div class="dropdown text-end">
                        <a href="#" class="d-block text-decoration-none dropdown-toggle"
                            data-bs-toggle="dropdown">
                            <img src="https://github.com/mdo.png" alt="profile" width="32" height="32"
                                class="rounded-circle">
                        </a>

                        <ul class="dropdown-menu text-small">
                            <li><a class="dropdown-item" href="#">New project...</a></li>
                            <li><a class="dropdown-item" href="#">Settings</a></li>
                            <li><a class="dropdown-item" href="#">Profile</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="{{ route('logout') }}">Sign out</a></li>
                        </ul>
                    </div>

                    <span class="nav-link text-white me-2">{{ auth()->user()->name }}</span>
                </div>
            </div>
        </div>
    </header> --}}

    {{-- <header class="navbar navbar-dark bg-dark sticky-top p-2 shadow">
        <div class="container-fluid d-flex justify-content-between align-items-center">

            <!-- سمت راست خالی -->
            <div></div>

            <!-- سمت چپ: عکس + نام + دکمه‌ها -->
            <div class="d-flex align-items-center gap-3">

                <!-- عکس پروفایل -->
                <img src="https://cdn-icons-png.flaticon.com/512/149/149071.png" alt="profile" width="36"
                    height="36" class="rounded-circle border">

                <!-- نام کاربر -->
                <span class="text-white fw-bold">
                    {{ auth()->user()->name }}
                </span>

                <!-- تغییر رمز -->
                <a href="#" class="text-white fs-5" data-bs-toggle="tooltip" data-bs-title="تغییر کلمه عبور">
                    <i class="bi bi-key-fill"></i>
                </a>

                <!-- ویرایش پروفایل -->
                <a href="#" class="text-white fs-5" data-bs-toggle="tooltip" data-bs-title="ویرایش پروفایل">
                    <i class="bi bi-pencil-square"></i>
                </a>

                <!-- خروج -->
                <form action="{{ route('logout') }}" method="post" class="m-0 p-0">
                    @csrf
                    <button type="submit" class="btn btn-link text-white fs-5 p-0" data-bs-toggle="tooltip"
                        data-bs-title="خروج">
                        <i class="bi bi-box-arrow-right"></i>
                    </button>
                </form>

            </div>

        </div>
    </header> --}}

    <header class="navbar navbar-dark bg-dark sticky-top p-2 shadow">
        <div class="container-fluid d-flex justify-content-between align-items-center">

            <!-- سمت راست خالی -->
            <div></div>

            <!-- سمت چپ -->
            <div class="d-flex align-items-center gap-3">

                <!-- عکس پروفایل -->
                <img src="https://cdn-icons-png.flaticon.com/512/149/149071.png" alt="profile" width="36"
                    height="36" class="rounded-circle border profile-img">

                <!-- نام کاربر -->
                <span class="text-white fw-bold">
                    {{ auth()->user()->name }}
                </span>

                <!-- تغییر رمز -->
                <a href="{{ route('changePassword') }}" class="text-white fs-5 custom-tooltip"
                    data-tooltip="تغییر کلمه عبور">
                    <i class="bi bi-key-fill"></i>
                </a>

                {{-- <!-- ویرایش پروفایل -->
                <a href="#" class="text-white fs-5 custom-tooltip" data-tooltip="ویرایش پروفایل">
                    <i class="bi bi-pencil-square"></i>
                </a> --}}

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
