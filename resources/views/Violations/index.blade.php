@extends('layouts.master')

@section('title', 'Violations')

@section('content')
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

    <div class="row mt-3">
        <div class="col-12 col-md-6 col-lg-4">
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


    <h5 class="fw-bold mt-4">تخلفات ثبت شده</h5>

    {{-- Desktop table --}}
    <div class="table-responsive d-none d-md-block">
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
                        <td>{{ $total_count-- }}</td>
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

    {{-- Mobile cards --}}
    <div class="d-block d-md-none">
        @foreach ($violations as $violation)
            <div class="card mb-3 shadow-sm">
                <div class="card-body">
                    <h6 class="fw-bold mb-2">
                        {{ $violation->student->family }} - {{ $violation->student->name }}
                    </h6>

                    <p class="mb-1"><strong>کلاس:</strong>
                        {{ $violation->student->schoolClass->name ?? 'در انتظار کلاس بندی' }}
                    </p>

                    <p class="mb-1"><strong>تاریخ:</strong> {{ toJalali($violation->date) }}</p>
                    <p class="mb-1"><strong>تخلف:</strong> {{ $violation->case->title }}</p>
                    <p class="mb-1"><strong>توضیحات:</strong> {{ $violation->description }}</p>
                    <p class="mb-3"><strong>ثبت کننده:</strong> {{ $violation->register->name }}</p>

                    <div class="d-flex gap-2">
                        <a href="{{ route('student-violations.edit', $violation->id) }}"
                            class="btn btn-sm btn-outline-info">
                            ویرایش
                        </a>

                        <form action="{{ route('student-violations.destroy', $violation->id) }}" data-confirm="delete"
                            data-confirm-item="تخلف انضباطی برای این دانش آموز" method="post">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger w-100">
                                حذف
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
