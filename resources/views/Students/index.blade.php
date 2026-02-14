@extends('layout.master')

@section('title', 'Students')

@section('body')
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
            <table class="table text-center align-middle">
                <thead>
                    <tr>
                        <th>ردیف</th>
                        <th>تصویر</th>
                        <th>نام خانوادگی</th>
                        <th>نام</th>
                        <th>مقطع</th>
                        <th>کلاس</th>
                        <th>کدملی</th>
                        <th>عملیات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($students as $student)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <th>
                                <img width="50px" height="50px"
                                    src="{{ $student->image === 'default.png' ? asset('images/default.png') : asset("images/students/$student->image") }}"
                                    alt="student image">
                            </th>
                            <th>{{ $student->family }}</th>
                            <th>{{ $student->name }}</th>
                            <th>{{ $student->schoolClass->level_label ?? '' }}</th>
                            <th>{{ $student->schoolClass->name ?? 'درانتظار کلاس بندی' }}</th>
                            <th>{{ $student->national_code }}</th>
                            <td>
                                <div class="d-flex">
                                    <a href="{{ route('students.show', $student->id) }}"
                                        class="btn btn-sm btn-outline-info me-2">
                                        مشاهده
                                    </a>
                                    <a href="{{ route('students.edit', $student->id) }}"
                                        class="btn btn-sm btn-outline-info me-2">
                                        ویرایش
                                    </a>
                                    <form data-confirm="delete" data-confirm-item="دانش آموز"
                                        action="{{ route('students.destroy', $student->id) }}" method="POST">
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
        @endif

    </div>
@endsection
