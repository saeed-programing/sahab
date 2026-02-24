@extends('layouts.master')

@section('title', 'Add Violation')

@push('links')
    <link rel="stylesheet" href="https://unpkg.com/@majidh1/jalalidatepicker/dist/jalalidatepicker.min.css">
@endpush

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h4 class="fw-bold">ثبت تخلف انضباطی</h4>
    </div>

    <div class="container-fluid p-0">
        <form action="{{ route('student-violations.store') }}" data-confirm="create"
            data-confirm-item="تخلف انضباطی برای این دانش آموز" method="post" autocomplete="off">
            @csrf
            <div class="row g-3">
                <div class="col-6 col-md-4">
                    <label class="form-label">دانش آموز: </label>
                    <select name="student_id" class="selectpicker form-control" data-live-search="true">
                        <option value="">لطفا انتخاب کنید</option>
                        @foreach ($students as $student)
                            <option value="{{ $student->id }}" {{ old('student_id') == $student->id ? 'selected' : '' }}>
                                {{ $student->family . ' - ' . $student->name }}</option>
                        @endforeach
                    </select>
                    <div class="text text-danger">
                        @error('student_id')
                            {{ $message }}
                        @enderror
                    </div>
                </div>
                <div class="col-6 col-md-4">
                    <label class="form-label">تاریخ:</label>
                    <input data-jdp name="date" type="text" value="{{ old('date') }}" class="form-control" />
                    <div class="text text-danger">
                        @error('date')
                            {{ $message }}
                        @enderror
                    </div>
                </div>

                <div class="col-6 col-md-4">
                    <label class="form-label">مورد انضباطی:</label>
                    <select name="case_id" class="selectpicker form-control" data-live-search="true">
                        <option value="">لطفا انتخاب کنید</option>
                        @foreach ($cases as $case)
                            <option value="{{ $case->id }}" {{ old('case_id') == $case->id ? 'selected' : '' }}>
                                {{ $case->title }}</option>
                        @endforeach
                    </select>
                    <div class="text text-danger">
                        @error('case_id')
                            {{ $message }}
                        @enderror
                    </div>
                </div>

                <div class="col-6 d-md-none">
                    <label class="form-label">ثبت کننده:</label>
                    <input disabled type="text" value="{{ Auth::user()->name }}" class="form-control" />
                </div>

                <div class="col-12 col-md-6">
                    <label for="description" class="form-label">توضیحات:</label>
                    <textarea id="description" name="description" class="form-control"></textarea>
                    <div class="text text-danger">
                        @error('description')
                            {{ $message }}
                        @enderror
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-start mt-3 gap-2">
                <button type="submit" class="btn btn-outline-dark mt-3 mb-5">
                    ثبت تغییرات
                </button>
                <a data-confirm="operation" data-confirm-item="لغو عملیات" href="{{ route('student-violations.index') }}"
                    class="btn btn-outline-danger mt-3 mb-5">
                    لغو
                </a>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script type="text/javascript" src="https://unpkg.com/@majidh1/jalalidatepicker/dist/jalalidatepicker.min.js"></script>

    <script>
        jalaliDatepicker.startWatch();
    </script>
@endpush
