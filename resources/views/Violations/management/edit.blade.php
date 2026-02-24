@extends('layouts.master')

@section('title', 'Edit Violation Title')

@section('content')

    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h4 class="fw-bold">ویرایش عنوان انضباطی</h4>
    </div>
    <div>
        <form action="{{ route('violation-titles.update', $violationTitle->id) }}"data-confirm="edit"
            data-confirm-item="عنوان تخلف انضباطی" method="post">
            @csrf
            @method('PUT')
            <div class="row g-3">
                <div class="col-12 col-md-6">
                    <label class="form-label">عنوان</label>
                    <input name="title" class="form-control" value="{{ old('title', $violationTitle->title) }}" />
                    <div class="text text-danger">
                        @error('title')
                            {{ $message }}
                        @enderror
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-outline-dark mt-3 mb-5">
                        ایجاد
                    </button>
                    <a data-confirm="operation" data-confirm-item="لغو عملیات" href="{{ route('violation-titles.index') }}"
                        class="btn btn-outline-danger mt-3 mb-5">
                        لغو
                    </a>
                </div>
            </div>
        </form>
    </div>
@endsection
