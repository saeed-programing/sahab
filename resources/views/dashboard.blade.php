@extends('layouts.master')

@section('title', 'DashBoard')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h4 class="fw-bold">پنل ادمین - دبیرستان سحاب رحمت</h4>
    </div>

    @if (Auth::user()->hasRole('AttendanceOfficer'))
        <div class="row">
            <div class="col-12 text-center">
                <a href="{{ route('attendances.index') }}" class="alert alert-primary d-inline-block">
                    شروع فرایند حضور و غیاب
                </a>
            </div>
        </div>
    @else
        <div class="row g-3">
            <div class="col-md-12 col-lg-6">
                <div class="row g-3">
                    <div class="col-12 col-md-6">
                        <div class="list-group">
                            <div class="list-group-item fw-bold list-group-item-secondary">
                                آموزش
                            </div>
                            <a href="{{ route('attendances.index') }}" class="list-group-item list-group-item-action">مدیریت
                                حضورغیاب</a>
                            <a href="{{ route('attendance.report.index') }}"
                                class="list-group-item list-group-item-action">مشاهده
                                گزارش حضورغیاب</a>
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <div class="list-group">
                            <div class="list-group-item list-group-item-secondary fw-bold">
                                تخلفات انضباطی
                            </div>
                            <a href="{{ route('student-violations.index') }}"
                                class="list-group-item list-group-item-action">آمار
                                کلی
                                تخلفات</a>
                            <a href="{{ route('student-violations.create') }}"
                                class="list-group-item list-group-item-action">ثبت
                                تخلف
                                انضباطی</a>
                            <a href="{{ route('student-violations.report') }}"
                                class="list-group-item list-group-item-action">مشاهده
                                گزارش موارد انضباطی</a>
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <div class="list-group">
                            <div class="list-group-item list-group-item-secondary fw-bold">
                                مدیریت
                            </div>
                            <a href="{{ route('employees.index') }}" class="list-group-item list-group-item-action">مدیریت
                                کارکنان</a>
                            <a href="{{ route('classes.index') }}" class="list-group-item list-group-item-action">مدیریت
                                کلاس
                                ها</a>
                            <a href="{{ route('students.index') }}" class="list-group-item list-group-item-action">مدیریت
                                دانش
                                آموزان</a>
                            <a href="{{ route('violation-titles.index') }}"
                                class="list-group-item list-group-item-action">مدیریت
                                عناوین انضباطی</a>
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <div class="list-group">
                            <div class="list-group-item list-group-item-secondary fw-bold">
                                متفرقه
                            </div>
                            <a href="#" class="list-group-item list-group-item-action">ارسال پیامک به
                                خانواده
                                ها</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-6">
                @if ($unknownAttendance->isNotEmpty())
                    <div class="alert alert-warning mb-3">
                        <h6 class="fw-bold">در انتظار تعیین وضعیت حضور (کلاس شما)</h6>
                        <ul class="mb-0">
                            @foreach ($unknownAttendance as $attendance)
                                <li><a
                                        href="{{ route('editAttendance', ['date' => $attendance->date, 'student_id' => $attendance->student->id]) }}?return_url={{ url()->current() }}">
                                        {{ $attendance->student->family . ' - ' . $attendance->student->name . ' || ' . toJalali($attendance->date) }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @can('showUnknownClassAlert', Auth::user())
                    @if ($unknownClass->isNotEmpty())
                        <div class="alert alert-warning mb-3">
                            <h6 class="fw-bold">در انتظار کلاس بندی</h6>
                            <ul class="mb-0">
                                @foreach ($unknownClass as $student)
                                    <li><a
                                            href="{{ route('students.edit', $student->id) }}">{{ $student->name . ' ' . $student->family }}</a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                @endcan

                @if ($studentsThisMonth->isNotEmpty())
                    <div class="alert alert-primary">
                        <h6 class="fw-bold">متولدین این ماه</h6>
                        <ul class="mb-0">
                            @foreach ($studentsThisMonth as $studentsProfile)
                                <li>{{ $studentsProfile->student->name . ' ' . $studentsProfile->student->family . ' (' . ($studentsProfile->student->schoolClass->name ?? 'در انتظار کلاس بندی') . ') : ' . toJalali($studentsProfile->date_of_birth) }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @else
                    <div class="alert alert-danger">
                        <h6 class="fw-bold mb-0">برای این ماه، تولدی یافت نشد</h6>
                    </div>
                @endif
            </div>
        </div>
    @endif

    <!-- Toast برای نمایش پیام‌های خطا و موفقیت -->
    {{-- <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1100">
        <div id="toastMsg" class="toast align-items-center text-bg-primary border-0">
            <div class="d-flex">
                <div class="toast-body" id="toastBody">پیغام نمونه</div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    </div> --}}
@endsection
