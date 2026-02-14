@extends('layout.master')

@section('title', 'Employees Management')

@section('body')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h4 class="fw-bold">مدیریت کارمندان</h4>
        <a href="{{ route('employees.create') }}" type="button" class="btn btn-sm btn-outline-primary">
            ایجاد کاربر جدید</a>
    </div>


    <div class="table-responsive">
        <table class="table text-center align-middle">
            <thead>
                <tr>
                    <th>ردیف</th>
                    <th>نام و نام خانوادگی</th>
                    <th>نقش ها</th>
                    <th>شماره موبایل</th>
                    <th>آدرس ایمیل</th>
                    <th>عملیات</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <th>{{ $user->name }}</th>

                        <th>{{ $user->roles->pluck('role_label')->implode(' | ') }}</th>
                        <th>{{ $user->mobile }}</th>
                        <th>{{ $user->email }}</th>
                        <td>
                            <div class="d-flex">
                                <a href="{{ route('employees.edit', $user->id) }}" class="btn btn-sm btn-outline-info me-2">
                                    ویرایش
                                </a>
                                @if ($user->id == auth()->user()->id)
                                    <button class="btn btn-sm btn-danger" disabled>حذف</button>
                                @else
                                    <form action="{{ route('employees.destroy', $user->id) }}" data-confirm="delete"
                                        data-confirm-item="کاربر" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">حذف</button>
                                    </form>
                                @endif

                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>
@endsection
