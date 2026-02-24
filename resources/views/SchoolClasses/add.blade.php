@extends('layouts.master')

@section('title', 'Add New class')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h4 class="fw-bold">ایجاد کلاس جدید</h4>
    </div>
    <div class="container-fluid p-0">
        <form action="{{ route('classes.store') }}" data-confirm="create" data-confirm-item="کلاس" method="post"
            autocomplete="off">
            @csrf
            <div class="row gy-3">
                <div class="col-12 col-md-4">
                    <label class="form-label">نام کلاس:</label>
                    <input placeholder="مثال: هفتم شهید فخری زاده" name="name" type="text" class="form-control"
                        value="{{ old('name') }}" />
                    <div class="text text-danger">
                        @error('name')
                            {{ $message }}
                        @enderror
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <label class="form-label">مقطع:</label>
                    <select class="form-select" name="level">
                        <option selected>لطفا انتخاب کنید</option>
                        <option value="seven">هفتم</option>
                        <option value="eight">هشتم</option>
                        <option value="nine">نهم</option>
                    </select>
                    <div class="text text-danger">
                        @error('level')
                            {{ $message }}
                        @enderror
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <label class="form-label">معلم راهنما:</label>
                    <select class="form-select" name="teacher">
                        <option selected>لطفا انتخاب کنید</option>
                        @foreach ($teachers as $teacher)
                            <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                        @endforeach
                    </select>
                    <div class="text text-danger">
                        @error('teacher')
                            {{ $message }}
                        @enderror
                    </div>
                </div>
            </div>


            <div class="d-flex flex-column flex-md-row gap-2 mt-4">
                <button type="submit" class="btn btn-outline-dark mt-3 mb-5">
                    افزودن
                </button>
                <a href="{{ route('classes.index') }}" data-confirm="operation" data-confirm-item="لغو عملیات"
                    class="btn btn-outline-danger mt-3 mb-5">
                    لغو
                </a>
            </div>
        </form>
    </div>
@endsection
