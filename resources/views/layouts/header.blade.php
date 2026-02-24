<header class="navbar navbar-expand-lg navbar-dark bg-dark border-bottom sticky-top p-2 shadow">
    {{-- <div class="container-fluid"> --}}
    <div class="container-fluid d-flex justify-content-between align-items-center">

        <!-- سمت راست -->
        <div>
            {{-- Mobile sidebar toggle --}}
            <button class="btn btn-outline-secondary d-lg-none me-2" data-bs-toggle="offcanvas"
                data-bs-target="#mobileSidebar">
                <i class="bi bi-list"></i>
            </button>
            <span class="text-white fw-bold d-none d-sm-inline bg-dark " href="#">
                دبیرستان سحاب رحمت </span>
        </div>

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

    {{-- </div> --}}
</header>
