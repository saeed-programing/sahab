@extends('layout.master')

@section('title', 'Management Violations')

@section('body')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h4 class="fw-bold">مدیریت عناوین انضباطی</h4>
        <div>
            <a href="{{ route('violation-titles.create') }}" type="button" class="btn btn-sm btn-outline-secondary">
                افزودن عنوان جدید</a>
        </div>
    </div>


    <div class="table-responsive">
        <table class="table text-center align-middle">
            <thead>
                <tr>
                    <th>ردیف</th>
                    <th>عنوان</th>
                    <th>تعداد ثبت شده</th>
                    <th>عملیات</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($cases as $case)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <th>{{ $case->title }}</th>
                        <th>{{ $case->student_violations_count }}</th>
                        <td>
                            <div class="d-flex justify-content-center">
                                <a href="{{ route('violation-titles.edit', $case->id) }}"
                                    class="btn btn-sm btn-outline-info me-2">
                                    ویرایش عنوان
                                </a>

                                <form action="{{ route('violation-titles.destroy', $case->id) }}" data-confirm="delete"
                                    data-confirm-item="عنوان تخلف انضباطی" method="post">
                                    @csrf
                                    @method('DELETE')
                                    <input type="hidden" name="count" value="{{ $case->disciplinaries_count }}">
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
