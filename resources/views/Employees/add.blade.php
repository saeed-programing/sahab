@extends('layouts.master')

@section('title', 'Add New Employee')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h4 class="fw-bold">کاربر جدید</h4>
    </div>
    <div class="container-fluid p-0">
        <form action="{{ route('employees.store') }}" data-confirm="create" data-confirm-item="کاربر" method="post"
            autocomplete="off">
            @csrf
            <div class="row gy-3 mb-3">
                <div class="col-12 col-md-4">
                    <label class="form-label">نام و نام خانوادگی:</label>
                    <input name="name" type="text" class="form-control" value="{{ old('name') }}" />
                    <div class="text text-danger">
                        @error('name')
                            {{ $message }}
                        @enderror
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <label class="form-label">پست الکترونیکی (Email) :</label>
                    <input name="email" type="email" class="form-control" value="{{ old('email') }}" />
                    <div class="text text-danger">
                        @error('email')
                            {{ $message }}
                        @enderror
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <label class="form-label">موبایل:</label>
                    <input name="mobile" type="text" class="form-control" value="{{ old('mobile') }}" />
                    <div class="text text-danger">
                        @error('mobile')
                            {{ $message }}
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row gy-3 mb-3">
                <div class="col-12 col-md-4">
                    <label class="form-label">نام کاربری (username) :</label>
                    <input name="username" type="text" class="form-control" value="{{ old('username') }}" />
                    <div class="text text-danger">
                        @error('username')
                            {{ $message }}
                        @enderror
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <label class="form-label">کلمه عبور :</label>
                    <input name="password" type="password" class="form-control" />
                    <div class="text text-danger">
                        @error('password')
                            {{ $message }}
                        @enderror
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <label class="form-label">تکرار کلمه عبور :</label>
                    <input name="password_confirmation" type="password" class="form-control" />
                    <div class="text text-danger">
                        @error('password_confirmation')
                            {{ $message }}
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row gy-3">
                <div class="col-12">
                    <label class="form-label">سمت ها:</label>
                    <div class="text text-danger mb-2">
                        @error('roles')
                            {{ $message }}
                        @enderror
                    </div>
                    <div class="row gy-2">
                        @foreach ($roles as $role)
                            @if ($role->title === 'super_admin')
                                @continue
                            @endif
                            <div class="col-12 col-sm-6 col-lg-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="roles[]"
                                        value="{{ $role->id }}" @checked(in_array($role->id, old('roles', [])))>

                                    <label class="form-check-label">
                                        {{ $role->role_label }}
                                    </label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-outline-dark mt-3 mb-5">
                افزودن
            </button>
            <a data-confirm="operation" data-confirm-item="لغو عملیات" href="{{ route('employees.index') }}"
                class="btn btn-outline-danger mt-3 mb-5">
                لغو
            </a>
        </form>
    </div>
@endsection
