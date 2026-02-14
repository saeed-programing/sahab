@php
    use App\Models\User;
    use App\Models\ViolationTitle;
    use App\Models\StudentViolation;
    use App\Models\Student;
    use App\Models\SchoolClass;
    use App\Models\Attendance;

    $menuGroups = [
        [
            'id' => 'Attendances',
            'title' => 'حضوروغیاب',
            'icon' => 'bi bi-people-fill me-1',
            'pattern' => 'Attendances*',
            'can' => 'viewAny',
            'model' => Attendance::class,
            'links' => [
                [
                    'route' => 'attendances.index',
                    'title' => 'مدیریت حضورغیاب',
                    'icon' => 'bi bi-gear me-2',
                    'pattern' => '*Attendances*',
                    'can' => 'viewAny',
                    'model' => Attendance::class,
                ],
                [
                    'route' => 'attendance.report.index',
                    'title' => 'گزارش حضورغیاب',
                    'icon' => 'bi bi-eye me-2',
                    'pattern' => '*Report*',
                    'can' => 'reportIndex',
                    'model' => Attendance::class,
                ],
            ],
        ],

        [
            'id' => 'student-violations',
            'title' => 'تخلفات انضباطی',
            'icon' => 'bi bi-exclamation-circle-fill me-2',
            'pattern' => 'student-violations*',
            'can' => 'viewAny',
            'model' => StudentViolation::class,
            'links' => [
                [
                    'route' => 'student-violations.index',
                    'title' => 'مدیریت تخلفات',
                    'icon' => 'bi bi-gear me-2',
                    'pattern' => 'student-violations',
                    'can' => 'viewAny',
                    'model' => StudentViolation::class,
                ],
                [
                    'route' => 'student-violations.create',
                    'title' => 'ثبت تخلف جدید',
                    'icon' => 'bi bi-plus-square me-2',
                    'pattern' => 'student-violations/create',
                    'can' => 'create',
                    'model' => StudentViolation::class,
                ],
                [
                    'route' => 'student-violations.report',
                    'title' => 'مشاهده گزارش تخلفات',
                    'icon' => 'bi bi-eye me-2',
                    'pattern' => 'student-violations/report*',
                    'can' => 'report',
                    'model' => StudentViolation::class,
                ],
            ],
        ],

        [
            'id' => 'management',
            'title' => 'مدیریت',
            'icon' => 'bi bi-gear me-2',
            'pattern' => 'management*',
            'can' => 'viewAny',
            'model' => Student::class,
            'links' => [
                [
                    'route' => 'employees.index',
                    'title' => 'مدیریت کارکنان',
                    'icon' => 'bi bi-person-fill-gear me-2',
                    'pattern' => 'management/employees*',
                    'can' => 'viewAny',
                    'model' => User::class,
                ],
                [
                    'route' => 'classes.index',
                    'title' => 'مدیریت کلاس ها',
                    'icon' => 'bi bi-building me-2',
                    'pattern' => 'management/classes*',
                    'can' => 'viewAny',
                    'model' => SchoolClass::class,
                ],
                [
                    'route' => 'students.index',
                    'title' => 'مدیریت دانش آموزان',
                    'icon' => 'bi bi-person-vcard me-2',
                    'pattern' => 'management/students*',
                    'can' => 'viewAny',
                    'model' => Student::class,
                ],
                [
                    'route' => 'violation-titles.index',
                    'title' => 'مدیریت عناوین انضباطی',
                    'icon' => 'bi bi-sign-stop me-2',
                    'pattern' => 'management/violation-titles*',
                    'can' => 'viewAny',
                    'model' => ViolationTitle::class,
                ],
            ],
        ],
    ];
@endphp

<nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
    <div class="accordion" id="accordionPanelsStayOpenExample">

        {{-- گزینه داشبورد (تکی) --}}
        <div class="accordion-item nav mt-2">
            <div class="nav-item accordion-body">
                <a href="{{ route('dashboard') }}" class="btn nav-link fw-bold">
                    <i class="bi bi-house"></i> داشبورد
                </a>
            </div>
        </div>
        @foreach ($menuGroups as $group)
            @if (isset($group['can']) && !auth()->user()->can($group['can'], $group['model']))
                @continue
            @endif

            <div class="accordion-item nav flex-column mt-1">

                <h2 class="accordion-header" id="heading-{{ $group['id'] }}">
                    <button class="accordion-button fw-bold {{ request()->is($group['pattern']) ? '' : 'collapsed' }}"
                        type="button" data-bs-toggle="collapse" data-bs-target="#collapse-{{ $group['id'] }}"
                        aria-expanded="{{ request()->is($group['pattern']) ? 'true' : 'false' }}"
                        aria-controls="collapse-{{ $group['id'] }}">
                        <i class="{{ $group['icon'] }}"></i>
                        {{ $group['title'] }}
                    </button>
                </h2>

                <div id="collapse-{{ $group['id'] }}"
                    class="accordion-collapse collapse {{ request()->is($group['pattern']) ? 'show' : '' }}"
                    aria-labelledby="heading-{{ $group['id'] }}">

                    <div class="accordion-body">
                        @foreach ($group['links'] as $item)
                            @if (isset($item['can']) && !auth()->user()->can($item['can'], $item['model']))
                                @continue
                            @endif

                            <li class="nav-item">
                                <a class="nav-link {{ request()->is($item['pattern']) ? 'fw-bold' : '' }}"
                                    href="{{ route($item['route']) }}">
                                    <i class="{{ $item['icon'] }}"></i>
                                    {{ $item['title'] }}
                                </a>
                            </li>
                            <hr style="margin: 0%" />
                        @endforeach
                    </div>

                </div>
            </div>
        @endforeach
        <hr style="margin: 0%" />
    </div>
</nav>
