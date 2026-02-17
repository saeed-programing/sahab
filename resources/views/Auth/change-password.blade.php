@extends('layout.master')

@section('title', 'Password Change')

@section('body')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h4 class="fw-bold">تغییر رمز عبور</h4>
    </div>
    <form action="{{ route('changePasswordPost') }}" data-confirm="edit" data-confirm-item="رمز عبور خود" method="post"
        autocomplete="off">
        @csrf
        <div class="row">
            <div class="col-3 mb-2">
                <label for="previous_password" class="form-label">رمز عبور قبلی:</label>
                <input id="previous_password" name="previous_password" type="password" class="form-control" />
                <div class="text text-danger">
                    @error('previous_password')
                        {{ $message }}
                    @enderror
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-3 mb-2">
                <label for="new_password" class="form-label">کلمه عبور جدید:</label>
                <input id="new_password" name="new_password" type="password" class="form-control" />
                <div class="text text-danger">
                    @error('new_password')
                        {{ $message }}
                    @enderror
                </div>
            </div>
            <div class="col-3 mb-2">
                <label for="new_password_confirmation" class="form-label">تکرار کلمه عبور :</label>
                <input id="new_password_confirmation" name="new_password_confirmation" type="password"
                    class="form-control" />
                <div class="text text-danger">
                    @error('new_password_confirmation')
                        {{ $message }}
                    @enderror
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-outline-dark mt-3 mb-5">
            ویرایش رمز عبور
        </button>
    </form>
@endsection
