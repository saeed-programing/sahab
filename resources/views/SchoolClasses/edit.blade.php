@extends('layout.master')

@section('title', 'Edit class')

@section('link')
    <link href="https://cdn.jsdelivr.net/npm/cropperjs@1.5.13/dist/cropper.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/cropperjs@1.5.13/dist/cropper.min.js"></script>
@endsection


@section('body')

    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h4 class="fw-bold">ویرایش کلاس {{ $class->name }}</h4>
    </div>
    <div>
        <form action="{{ route('classes.update', $class->id) }}" data-confirm="edit" data-confirm-item="کلاس" method="post"
            autocomplete="off">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-3 mb-2">
                    <label class="form-label">نام کلاس:</label>
                    <input placeholder="مثال: هفتم شهید فخری زاده" name="name" type="text" class="form-control"
                        value="{{ old('name', $class->name) }}" />
                    <div class="text text-danger">
                        @error('name')
                            {{ $message }}
                        @enderror
                    </div>
                </div>
                <div class="col-3 mb-2">
                    <label class="form-label">مقطع:</label>
                    <select class="form-select" name="level">
                        <option selected>لطفا انتخاب کنید</option>
                        <option {{ $class->level == 'seven' ? 'selected' : '' }} value="seven">هفتم</option>
                        <option {{ $class->level == 'eight' ? 'selected' : '' }} value="eight">هشتم</option>
                        <option {{ $class->level == 'nine' ? 'selected' : '' }} value="nine">نهم</option>
                    </select>
                    <div class="text text-danger">
                        @error('level')
                            {{ $message }}
                        @enderror
                    </div>
                </div>
                <div class="col-2 mb-2">
                    <label class="form-label">معلم راهنما:</label>
                    <select class="form-select" name="teacher_id">
                        <option selected>لطفا انتخاب کنید</option>
                        @foreach ($teachers as $teacher)
                            <option {{ $teacher->id == $class->teacher_id ? 'selected' : '' }} value="{{ $teacher->id }}">
                                {{ $teacher->name }}</option>
                        @endforeach
                    </select>
                    <div class="text text-danger">
                        @error('teacher_id')
                            {{ $message }}
                        @enderror
                    </div>
                </div>
            </div>


            <div>
                <button type="submit" class="btn btn-outline-dark mt-3 mb-5">
                    ویرایش
                </button>
                <a href="{{ route('classes.index') }}" data-confirm="operation" data-confirm-item="لغو عملیات"
                    class="btn btn-outline-danger mt-3 mb-5">
                    لغو
                </a>
            </div>
        </form>
    </div>
@endsection
