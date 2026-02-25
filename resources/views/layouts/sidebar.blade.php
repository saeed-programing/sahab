@php
    $menu = config('menu.sidebar');
@endphp

<div class="list-group list-group-flush rounded-0">
    @foreach ($menu as $item)
        @if (empty($item['can']) || auth()->user()?->can($item['can'][0], $item['can'][1]))
            <a href="{{ route($item['url']) }}"
                class="list-group-item list-group-item-action
               {{ request()->routeIs($item['route']) ? 'bg-secondary-subtle text-black fw-bold' : '' }}">
                <i class="bi {{ $item['icon'] }} me-2"></i>
                {{ $item['title'] }}
            </a>
        @endif
    @endforeach
</div>

{{-- <div class="list-group list-group-flush rounded-0">
    @foreach ($menu as $item)
        @can($item['can'][0] ?? null, $item['can'][1] ?? null)
            <a href="{{ route($item['url']) }}"
                class="list-group-item list-group-item-action
           {{ request()->routeIs($item['route']) ? 'bg-secondary-subtle text-black fw-bold' : '' }}">
                <i class="bi {{ $item['icon'] }} me-2"></i> {{ $item['title'] }}
            </a>
        @endcan
    @endforeach
</div> --}}

{{-- <div class="list-group list-group-flush rounded-0">
        <a href="{{ route('dashboard') }}"
            class="list-group-item list-group-item-action {{ request()->routeIs('dashboard') ? 'bg-secondary-subtle text-black fw-bold' : '' }}">
            <i class="bi bi-speedometer2 me-2"></i> داشبورد
        </a>

        <a href="{{ route('students.index') }}"
            class="list-group-item list-group-item-action {{ request()->routeIs('students.*') ? 'bg-secondary-subtle text-black fw-bold' : '' }}">
            <i class="bi bi-person-vcard me-2"></i> دانش آموزان
        </a>

        <a href="{{ route('attendances.index') }}"
            class="list-group-item list-group-item-action {{ request()->routeIs('attendances.*') ? 'bg-secondary-subtle text-black fw-bold' : '' }}">
            <i class="bi bi-calendar-check me-2"></i>حضور و غیاب</a>

        <a href="{{ route('student-violations.index') }}"
            class="list-group-item list-group-item-action {{ request()->routeIs('student-violations.*') ? 'bg-secondary-subtle text-black fw-bold' : '' }}">
            <i class="bi bi-exclamation-circle me-2"></i>تخلفات</a>

        <a href="{{ route('classes.index') }}"
            class="list-group-item list-group-item-action {{ request()->routeIs('classes.*') ? 'bg-secondary-subtle text-black fw-bold' : '' }}">
            <i class="bi bi-building me-2"></i>کلاس ها
        </a>

        <a href="{{ route('violation-titles.index') }}"
            class="list-group-item list-group-item-action {{ request()->routeIs('violation-titles.*') ? 'bg-secondary-subtle text-black fw-bold' : '' }}">
            <i class="bi bi-sign-stop me-2"></i>عناوین انضباطی
        </a>

        <a href="{{ route('employees.index') }}"
            class="list-group-item list-group-item-action {{ request()->routeIs('employees.*') ? 'bg-secondary-subtle text-black fw-bold' : '' }}">
            <i class="bi bi-people me-2"></i> مدیریت کارکنان
        </a>

        {{-- <a class="list-group-item list-group-item-action" data-bs-toggle="collapse" href="#violation">
            <i class="bi bi-exclamation-circle me-2"></i>تخلفات انضباطی</a>
        <div class="collapse ps-4" id="violation">
            <a href="{{ route('student-violations.index') }}"
                class="list-group-item list-group-item-action border-0">مدیریت
                تخلفات</a>
            <a href="{{ route('student-violations.create') }}"
                class="list-group-item list-group-item-action border-0">ایجاد
                تخلف
                جدید</a>
            <a href="{{ route('student-violations.report') }}"
                class="list-group-item list-group-item-action border-0">گزارش تخلفات</a>
        </div>
    </div> --}}
