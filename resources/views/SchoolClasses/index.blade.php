@extends('layout.master')

@section('title', 'Classes Management')

@section('body')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h4 class="fw-bold">مدیریت کلاس ها</h4>
        <div>
            <a href="{{ route('classes.create') }}" type="button" class="btn btn-sm btn-outline-primary">
                ایجاد کلاس جدید</a>
        </div>
    </div>


    <div class="table-responsive">
        <table class="table text-center align-middle">
            <thead>
                <tr>
                    <th>ردیف</th>
                    <th>نام کلاس</th>
                    <th>مقطع</th>
                    <th>تعداد دانش آموزان</th>
                    <th>استاد راهنما</th>
                    <th>عملیات</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <th>کلاس بندی نشده</th>
                    <th>-</th>
                    <th>{{ $unknownStudents->count() }}</th>
                    <th>-</th>
                    <td>
                        <div class="d-flex justify-content-center">
                            @if ($unknownStudents->count() === 0)
                                <button disabled class="btn btn-sm btn-outline-success me-2">
                                    مشاهده افراد در انتظار کلاس بندی</button>
                            @else
                                <button type="button" data-bs-toggle="modal" data-bs-target="#showStudents"
                                    class="btn btn-sm btn-outline-success me-2 btn-show-students"
                                    data-url="{{ route('students.pendingAssignment') }}">
                                    مشاهده افراد در انتظار کلاس بندی</button>
                            @endif
                        </div>
                    </td>
                </tr>
                @if ($schoolClasses->isEmpty())
                    <div class="alert alert-danger">کلاسی وجود ندارد. لطفا از طریق بخش «مدیریت کلاس ها» کلاسی را اضافه کنید
                    </div>
                @else
                    @foreach ($schoolClasses as $schoolClass)
                        <tr>
                            <td>{{ $loop->iteration + 1 }}</td>
                            <th>{{ $schoolClass->name }}</th>
                            <th>{{ $schoolClass->level_label }}</th>
                            <th>{{ $schoolClass->students->count() }}</th>
                            <th>{{ $schoolClass->teacher->name ?? '-' }}</th>
                            <td>
                                <div class="d-flex justify-content-center">
                                    @if ($schoolClass->students->count() === 0)
                                        <button disabled class="btn btn-sm btn-outline-success me-2 btn-show-students">
                                            مشاهده افراد کلاس </button>
                                    @else
                                        <button type="button" data-bs-toggle="modal" data-bs-target="#showStudents"
                                            class="btn btn-sm btn-outline-success me-2 btn-show-students"
                                            data-class_id="{{ $schoolClass->id }}"
                                            data-url="{{ route('students.showStudentByClass', $schoolClass->id) }}">
                                            مشاهده افراد کلاس </button>
                                    @endif

                                    <a href="{{ route('classes.edit', $schoolClass->id) }}"
                                        class="btn btn-sm btn-outline-primary me-2">
                                        ویرایش اطلاعات کلاس
                                    </a>
                                    @if ($schoolClass->students->count() == 0)
                                        <form action="{{ route('classes.destroy', $schoolClass->id) }}"
                                            data-confirm="delete" data-confirm-item="کلاس" method="post">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">حذف کلاس</button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="showStudents" tabindex="-1" aria-labelledby="showStudentsLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="showStudentsLabel"></h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="showStudentsBody">
                    {{-- joining modal content by java script --}}
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">بستن</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        const studentShowRoute = "{{ route('students.show', ':id') }}";

        document.addEventListener('DOMContentLoaded', function() {
            const modalBody = document.getElementById('showStudentsBody');
            const modalTitle = document.getElementById('showStudentsLabel');

            document.querySelectorAll('.btn-show-students').forEach(button => {
                button.addEventListener('click', function() {
                    const class_id = this.getAttribute('data-class_id');
                    const url = this.getAttribute('data-url');

                    modalBody.innerHTML = `
    <div class="text-center py-3 text-muted">
        در حال بارگذاری اطلاعات...
    </div>
`;

                    fetch(url)
                        .then(res => {
                            if (!res.ok) throw new Error('Network error');
                            return res.json();
                        })
                        .then(data => {
                            let html = '';

                            if (!data || data.length === 0) {
                                html = `
                <div class="text-center text-muted py-3">
                    هیچ دانش‌آموزی یافت نشد.
                </div>
            `;
                            } else {
                                html = `
                <table class="table text-center align-middle">
                    <thead>
                        <tr>
                            <th>نام خانوادگی</th>
                            <th>نام</th>
                            <th>کد ملی</th>
                            <th>عملیات</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${data.map(s => `
                                                                                                                                                                                                                                                                                                                                    <tr>
                                                                                                                                                                                                                                                                                                                                    <td>${s.family}</td>
                                                                                                                                                                                                                                                                                                                                    <td>${s.name}</td>
                                                                                                                                                                                                                                                                                                                                    <td>${s.national_code}</td>
                                                                                                                                                                                                                                                                                                                                    <td>
                                                                                                                                                                                                                                                                                                                                    <a href="${studentShowRoute.replace(':id' , s.id)}" class="btn btn-sm btn-outline-info">
                                                                                                                                                                                                                                                                                                                                    مشاهده
                                                                                                                                                                                                                                                                                                                                    </a>
                                                                                                                                                                                                                                                                                                                                    </td>
                                                                                                                                                                                                                                                                                                                                    </tr>
                                                                                                                                                                                                                                                                                                                                    `).join('')}
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
    </script>
@endsection
