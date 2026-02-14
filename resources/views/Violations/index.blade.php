@extends('layout.master')

@section('title', 'Violations')

@section('body')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h4 class="fw-bold">تخلفات انضباطی</h4>
        <div>
            <a href="{{ route('student-violations.create') }}" type="button" class="btn btn-sm btn-outline-danger">ثبت تخلف
                جدید</a>
            <a href="#" type="button" class="btn btn-sm btn-outline-secondary">
                ارسال گزارش موارد انضباطی</a>
            <a href="{{ route('student-violations.report') }}" type="button" class="btn btn-sm btn-outline-danger">
                مشاهده گزارش تمام تخلفات</a>
        </div>
    </div>

    <h5 class="fw-bold">آمار کلی</h5>

    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-4">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered text-center align-middle">
                        <thead class="table-secondary">
                            <tr>
                                <th>عنوان تخلف</th>
                                <th>تعداد</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($violations_count as $v)
                                <tr>
                                    <td>{{ $v->case->title }}</td>
                                    <td>{{ $v->total }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


    <h5 class="fw-bold mt-4">ده مورد اخیر</h5>

    <div class="table-responsive">
        <table class="table text-center align-middle">
            <thead>
                <tr>
                    <th>ردیف</th>
                    <th>نام خانوادگی</th>
                    <th>نام</th>
                    <th>کلاس</th>
                    <th>تاریخ</th>
                    <th>مورد انضباطی</th>
                    <th>توضیحات</th>
                    <th>ثبت کننده</th>
                    <th>عملیات</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($violations as $violation)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <th>{{ $violation->student->family }}</th>
                        <th>{{ $violation->student->name }}</th>
                        <th>{{ $violation->student->schoolClass->name ?? 'در انتظار کلاس بندی' }}</th>
                        <th>{{ toJalali($violation->date) }}</th>
                        <th>{{ $violation->case->title }}</th>
                        <th>{{ $violation->description }}</th>
                        <th>{{ $violation->register->name }}</th>
                        <td>
                            <div class="d-flex justify-content-center">
                                <a href="{{ route('student-violations.edit', $violation->id) }}"
                                    class="btn btn-sm btn-outline-info me-2">
                                    ویرایش
                                </a>
                                <form action="{{ route('student-violations.destroy', $violation->id) }}"
                                    data-confirm="delete" data-confirm-item="تخلف انضباطی برای این دانش آموز"
                                    method="post">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">حذف</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>


@endsection
