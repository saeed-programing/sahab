@extends('layout.master')

@section('title', 'Add Violation')

@section('link')
    <link rel="stylesheet" href="https://unpkg.com/@majidh1/jalalidatepicker/dist/jalalidatepicker.min.css">
    <script type="text/javascript" src="https://unpkg.com/@majidh1/jalalidatepicker/dist/jalalidatepicker.min.js"></script>
@endsection

@section('body')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h4 class="fw-bold">ثبت تخلف انضباطی</h4>
    </div>
    <div>
        <form action="{{ route('student-violations.store') }}" data-confirm="create"
            data-confirm-item="تخلف انضباطی برای این دانش آموز" method="post" autocomplete="off">
            @csrf
            <div class="row">
                <div class="col-md-3 mb-2">
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

                <div class="col-md-2 mb-2">
                    <label class="form-label">تاریخ:</label>
                    <input data-jdp name="date" type="text" value="{{ old('date') }}" class="form-control" />
                    <div class="text text-danger">
                        @error('date')
                            {{ $message }}
                        @enderror
                    </div>
                </div>

                <div class="col-md-2 mb-2">
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

                <div class="col-md-5 mb-2">
                    <label for="description" class="form-label">توضیحات:</label>
                    <textarea id="description" name="description" class="form-control"></textarea>
                    <div class="text text-danger">
                        @error('description')
                            {{ $message }}
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row mt-3">
                <div>
                    <button type="submit" class="btn btn-outline-dark mt-3 mb-5">
                        ثبت تغییرات
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection


@section('script')
    <script>
        jalaliDatepicker.startWatch();
    </script>
@endsection
