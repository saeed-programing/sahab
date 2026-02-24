@extends('layouts.master')

@section('title', 'Students')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h4 class="fw-bold">دانش آموزان</h4>
        <div>
            <a href="{{ route('students.create') }}" type="button" class="btn btn-sm btn-outline-primary">
                ایجاد دانش آموز جدید</a>
            <a href="{{ route('students.createBatch') }}" type="button" class="btn btn-sm btn-outline-primary">
                ایجاد گروهی دانش آموزان</a>
        </div>
    </div>


    <div class="table-responsive">
        @if ($students->isEmpty())
            <div class="alert alert-danger">
                هیچ دانش آموزی ثبت نشده است. <a href="{{ route('students.create') }}">ثبت دانش آموز جدید</a>
            </div>
        @else
            <table class="table table-striped align-middle text-center">
                <thead>
                    <tr>
                        <th>ردیف</th>
                        <th>تصویر</th>
                        <th>نام خانوادگی</th>
                        <th>نام</th>
                        <th>مقطع</th>
                        <th>کلاس</th>
                        <th>کد دانش‌آموزی</th>
                        <th>کدملی</th>
                        <th>عملیات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($students as $student)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <img width="50px" height="50px"
                                    src="{{ $student->image === 'default.png' ? asset('images/default.png') : asset("images/students/$student->image") }}"
                                    alt="student image">
                            </td>
                            <td>{{ $student->family }}</td>
                            <td>{{ $student->name }}</td>
                            <td>{{ $student->schoolClass->level_label ?? '' }}</td>
                            <td>{{ $student->schoolClass->name ?? 'درانتظار کلاس بندی' }}</td>
                            <td>{{ $student->student_code }}</td>
                            <td>{{ $student->national_code }}</td>
                            <td>
                                <div class="d-flex flex-column flex-md-row gap-1 justify-content-center">
                                    <a href="{{ route('students.show', $student->id) }}"
                                        class="btn btn-sm btn-outline-info me-2 w-100 w-md-auto">
                                        مشاهده
                                    </a>
                                    <a href="{{ route('students.edit', $student->id) }}"
                                        class="btn btn-sm btn-outline-info me-2 w-100 w-md-auto">
                                        ویرایش
                                    </a>
                                    <form data-confirm="delete" data-confirm-item="دانش آموز"
                                        action="{{ route('students.destroy', $student->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger w-100 w-md-auto">حذف</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach

                </tbody>
            </table>
        @endif

    </div>
@endsection
