@extends('layout.master')

@section('title', 'Attendance Days')

@section('body')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h4 class="fw-bold">روز های درسی</h4>
        <div>
            <a data-confirm="create" data-confirm-item="تاریخ {{ toJalali($today) }}"
                href="{{ route('attendances.addNewDay', $today) }}" type="button" class="btn btn-sm btn-outline-secondary">
                ایجاد روز درسی جدید (تاریخ امروز)</a>
            <a data-confirm="operation" data-confirm-item="ارسال غیبت های {{ toJalali($today) }}"
                href="{{ route('sendAbsenceReport', $today) }}" type="button" class="btn btn-sm btn-outline-secondary">
                ارسال گزارش غیبت های امروز (ایتا)</a>
            <a href="{{ route('attendance.report.index') }}" type="button" class="btn btn-sm btn-outline-secondary">
                مشاهده گزارش حضور غیاب</a>
        </div>
    </div>


    <div class="table-responsive">
        <table class="table text-center align-middle">
            <thead>
                <tr>
                    <th>ردیف</th>
                    <th>تاریخ</th>
                    <th>در انتظار بررسی</th>
                    <th>کل حاضرین</th>
                    <th>حاضرین با تاخیر</th>
                    <th>حاضرین بدون تاخیر</th>
                    <th>تعداد غائبین</th>
                    <th>عملیات</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($report as $r)
                    <tr>
                        <td>{{ $loop->count - $loop->index }}</td>
                        <th>{{ toJalali($r->date) }}</th>
                        <th {{ $r->unknown == 0 ? '' : 'class=bg-danger' }}>{{ $r->unknown }}</th>
                        <th>{{ $r->present + $r->late }}</th>
                        <th>{{ $r->late }}</th>
                        <th>{{ $r->present }}</th>
                        <th>{{ $r->absent }}</th>
                        <td>
                            <div class="d-flex justify-content-center">
                                @if ($r->unknown != 0)
                                    <div class="btn-group position-static">
                                        <button type="button" class="btn btn-sm btn-outline-info me-2 dropdown-toggle"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                            انجام حضورغیاب
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item"
                                                    href="{{ route('attendances.registration.ByNationalCode', $r->date) }}">با
                                                    کد ملی</a>
                                            </li>
                                            <li><a class="dropdown-item"
                                                    href="{{ route('attendances.registration.ByStudentCode', $r->date) }}">با
                                                    کد دانش آموزی</a></li>
                                        </ul>
                                    </div>
                                @endif

                                @if ($unknownStudents->contains('date', $r->date))
                                    <button type="button" data-bs-toggle="modal" data-bs-target="#unknownStudents"
                                        class="btn btn-sm btn-outline-success me-2 btn-show-unknown"
                                        data-url="{{ route('unknownStudents', $r->date) }}"
                                        data-date="{{ $r->date }}" data-dateJalali="{{ toJalali($r->date) }}">
                                        مشاهده افراد در انتظار بررسی
                                    </button>
                                @endif

                                <form action="{{ route('attendances.deleteDay', $r->date) }}" method="post"
                                    data-confirm="delete" data-confirm-item="تاریخ {{ toJalali($r->date) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">حذف روز درسی</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="unknownStudents" tabindex="-1" aria-labelledby="unknownStudentsLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" id="unknownStudentsContent">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="unknownStudentsLabel"></h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="unknownStudentsBody">
                    {{-- joining modal content by java script --}}
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">بستن</button>
                    <a href="#" id="btnMarkAllAbsent" class="btn btn-danger">ثبت غیبت غیرموجه برای همه</a>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const modalBody = document.getElementById('unknownStudentsBody');
            const modalTitle = document.getElementById('unknownStudentsLabel');
            const editAttendanceBaseUrl =
                "{{ route('editAttendance', ['date' => ':date', 'student_id' => ':id']) }}";


            document.querySelectorAll('.btn-show-unknown').forEach(button => {
                button.addEventListener('click', function() {
                    // ۱. گرفتن تاریخ از data-date
                    const date = this.getAttribute('data-date');
                    const dateJalali = this.getAttribute('data-dateJalali');
                    const url = this.getAttribute('data-url');

                    // ۲. نمایش تاریخ در عنوان مودال
                    modalTitle.textContent = `دانش‌آموزان در انتظار بررسی : ${dateJalali}`;

                    // ۳. نمایش حالت بارگذاری
                    modalBody.innerHTML = `
                <div class="text-center py-3 text-muted">
                    در حال بارگذاری اطلاعات...
                </div>
            `;
                    document.getElementById('btnMarkAllAbsent').setAttribute('data-date', date);

                    // ۴.ارسال درخواست به کنترلر برای دریافت لیست دانش‌آموزان
                    fetch(url)
                        .then(res => res.json())
                        .then(data => {
                            let html = '';
                            if (data.length === 0) {
                                html =
                                    `<div class="text-center text-muted py-3">هیچ دانش‌آموزی یافت نشد.</div>`;
                            } else {
                                html = `

              <table class="table text-center align-middle">
                        <thead>
                            <tr>
                                <th>نام خانوادگی</th>
                                <th>نام</th>
                                <th>کلاس</th>
                                <th>کدملی</th>
                                <th>عملیات</th>
                            </tr>
                        </thead>
                  <tbody>
        ${data.map((s) => {

            let url = editAttendanceBaseUrl
                        .replace(':date', s.date)
                        .replace(':id', s.student.id);

            return `
                                                                                                                                                                                    <tr>
                                                                                                                                                                                        <td>${s.student.family}</td>
                                                                                                                                                                                        <td>${s.student.name}</td>
                                                                                                                                                                                        <td>${s.student.school_class.name}</td>
                                                                                                                                                                                        <td>${s.student.national_code}</td>
                                                                                                                                                                                        <td>
                                                                                                                                                                                            <div class="d-flex justify-content-center">
                                                                                                                                                                                                <a href="${url}" class="btn btn-sm btn-outline-info me-2">
                                                                                                                                                                                                    ثبت وضعیت
                                                                                                                                                                                                </a>
                                                                                                                                                                                            </div>
                                                                                                                                                                                        </td>
                                                                                                                                                                                    </tr>`;
        }).join('')}
    </tbody>
              </table>
              `;
                            }
                            modalBody.innerHTML = html;
                        })
                        .catch(() => {
                            modalBody.innerHTML = `
                    <div class="text-center text-danger py-3">
                        خطا در دریافت اطلاعات.
                    </div>
                `;
                        });
                });
            });
        })


        document.getElementById('btnMarkAllAbsent').addEventListener('click', function() {
            const date = this.getAttribute('data-date');
            if (!date) return;

            if (confirm('آیا از ثبت غیبت برای همه دانش‌آموزان این روز مطمئن هستید؟')) {
                window.location.href = `/Attendances/absence-registration/${date}`;
            }
        });
    </script>
@endsection
