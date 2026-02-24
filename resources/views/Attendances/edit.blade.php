@extends('layouts.master')

@section('title', 'Edit Attendance')

@section('content')
    <div class="d-flex justify-content-between flex-wrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h4 class="fw-bold">ویرایش وضعیت حضور</h4>
    </div>
    <div class="container-fluid p-0">
        <form action="{{ route('editAttendance.post') }}" method="post" data-confirm="edit">
            @csrf
            <input type="hidden" name="attendance_id" value="{{ $attendance->id }}">
            <input type="hidden" name="return_url" value="{{ request('return_url') }}">
            <div class="row gy-3">
                <div class="col-12 col-sm-6 col-md-3">
                    <label class="form-label">تاریخ</label>
                    <input class="form-control" value="{{ tojalali($attendance->date) }}" disabled />
                </div>
                <div class="col-12 col-sm-6 col-md-3">
                    <label class="form-label">نام خانوادگی - نام</label>
                    <input class="form-control"
                        value="{{ $attendance->student->family . ' - ' . $attendance->student->name }}" disabled />
                </div>
                <div class="col-12 col-sm-6 col-md-3">
                    <label class="form-label">کلاس</label>
                    <input class="form-control" value="{{ $attendance->student->schoolClass->name ?? '-' }}" disabled />
                </div>
                <div class="col-12 col-sm-6 col-md-3">
                    <label class="form-label">ثبت کننده</label>
                    <input class="form-control" value="{{ $attendance->register->name }}" disabled />
                </div>
            </div>

            <div class="row gy-3 mt-3">
                <div class="col-12 col-sm-6 col-md-3">
                    <label for="status" class="form-label">وضعیت حضور</label>
                    <select id="status" name="status" class="form-select">
                        <option {{ old('status', $attendance->status) == 'unknown' ? 'selected' : '' }} value="unknown">در
                            انتظار بررسی
                        </option>
                        <option {{ old('status', $attendance->status) == 'present' ? 'selected' : '' }} value="present">
                            حضور</option>
                        <option {{ old('status', $attendance->status) == 'late' ? 'selected' : '' }} value="late">تاخیر
                        </option>
                        <option {{ old('status', $attendance->status) == 'absent' ? 'selected' : '' }} value="absent">غیبت
                        </option>
                    </select>
                    <div class="text text-danger">
                        @error('status')
                            {{ $message }}
                        @enderror
                    </div>
                </div>
                <div class="col-12 col-sm-3 col-md-3 fade-field" id="excused-wrapper">
                    <label for="is_excused" class="form-label">موجه بودن</label>
                    <select id="is_excused" name="is_excused" class="form-select">
                        <option>لطفا انتخاب کنید</option>
                        <option {{ $attendance->is_excused == 1 ? 'selected' : '' }} value="1">موجه</option>
                        <option {{ $attendance->is_excused == 0 ? 'selected' : '' }} value="0">غیرموجه</option>
                    </select>
                    <div class="text text-danger">
                        @error('is_excused')
                            {{ $message }}
                        @enderror
                    </div>
                </div>
                <div class="col-12 col-sm-3 col-md-3 fade-field" id="delay-wrapper">
                    <label for="delay" class="form-label">میزان تاخیر (دقیقه)</label>
                    <input name="delay" id="delay" type="number" class="form-control"
                        value="{{ $attendance->delay }}" />
                    <div class="text text-danger">
                        @error('delay')
                            {{ $message }}
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row gy-3 mt-2">
                <div class="col-12 col-sm-6 fade-field" id="description">
                    <label for="description" class="form-label">توضیحات</label>
                    <textarea placeholder="درصورت تاخیر یا غیبت، نوشتن علت الزامی است" id="description" name="description"
                        class="form-control">{{ $attendance->description }}</textarea>
                    <div class="text text-danger">
                        @error('description')
                            {{ $message }}
                        @enderror
                    </div>
                </div>
            </div>

            <div class="d-flex flex-row gap-2 mt-2">
                <button type="submit" class="btn btn-outline-dark mt-3 mb-5">
                    ذخیره تغییرات
                </button>

                @php
                    $returnUrl = request('return_url');
                    if (!$returnUrl || !str_starts_with($returnUrl, url('/'))) {
                        $returnUrl = route('attendances.index');
                    }
                @endphp
                <a data-confirm="operation" data-confirm-item="لغو عملیات" href="{{ $returnUrl }}"
                    class="btn btn-outline-danger mt-3 mb-5">
                    لغو
                </a>
            </div>
        </form>
    </div>
@endsection


@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const status = document.getElementById('status');
            const delayWrapper = document.getElementById('delay-wrapper');
            const excusedWrapper = document.getElementById('excused-wrapper');
            const description = document.getElementById('description');

            function updateVisibility() {
                const value = status.value;

                if (value === 'late' || value === 'absent') {
                    excusedWrapper.classList.add('show');
                    description.classList.add('show');
                } else {
                    excusedWrapper.classList.remove('show');
                    description.classList.remove('show');
                }


                if (value === 'late') {
                    delayWrapper.classList.add('show');
                } else {
                    delayWrapper.classList.remove('show');
                }
            }

            // هر بار که وضعیت تغییر کرد:
            status.addEventListener('change', updateVisibility);

            // هنگام بارگذاری صفحه، وضعیت اولیه را تنظیم کن:
            updateVisibility();
        });
    </script>
@endpush
