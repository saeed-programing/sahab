@extends('layouts.master')

@section('title', 'Import Students')

@section('content')
    <div class="container-fluid px-2 px-md-4">

        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h4 class="fw-bold">ایجاد گروهی دانش آموزان</h4>
        </div>
        <div class="col-12 col-md-9 col-lg-7 p-0">
            <form action="{{ route('students.importStudents.store') }}" method="post" data-confirm="create"
                data-confirm-item="دانش آموزان به صورت گروهی" enctype="multipart/form-data">
                @csrf
                <div class="alert alert-info col-12" role="alert">
                    لطفا فایل نمونه را <a href="{{ route('students.downloadImportTemplate') }}">دانلود</a> کرده و آن را
                    تکمیل
                    کنید.
                    سپس آن را با فرمت CSV بارگزاری کنید</div>

                <input class="form-control" type="file" id="import" name="import" accept=".csv">
                <div class="text text-danger mt-1">
                    @error('import')
                        {{ $message }}
                    @enderror
                </div>

                <div class="mt-3">
                    <button type="submit" class="btn btn-outline-dark mb-3">
                        بررسی فایل
                    </button>
                </div>
            </form>

            @if ($errors->import->any())
                <div class="alert alert-danger col-12">خطا های زیر را برطرف کرده و مجددا بارگزاری کنید
                    <ul class="mt-2 mb-0">
                        @foreach ($errors->import->getMessages() as $row => $messages)
                            @foreach ($messages as $msg)
                                <li>{{ $row }} : {{ $msg }}</li>
                            @endforeach
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </div>
@endsection
