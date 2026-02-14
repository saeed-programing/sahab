<!DOCTYPE html>
<html lang="fa" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.rtl.min.css"
        integrity="sha384-+qdLaIRZfNu4cVPK/PxJJEy0B0f3Ugv8i482AKY7gwXwhaCroABd086ybrVKTa0q" crossorigin="anonymous">

    <link rel="stylesheet" href="{{ asset('css/main.css') }}">

    <title>Sahab || ResetPassword</title>
</head>

<body>
    <div class="row mt-5 justify-content-center align-items-center">
        <div class="col-12 col-md-6">
            <div class="card">
                <h5 class="card-header">تغییر رمز عبور</h5>
                <div class="card-body">
                    @if (session()->has('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif
                    @if (session()->has('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    <form action="{{ route('resetPasswordPost') }}" method="POST">
                        @csrf
                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="mb-3">
                            <label class="form-label">کلمه عبور جدید:</label>
                            <input autocomplete="off" type="password" name="password" class="form-control">
                            <div class="form-text text-danger">
                                @error('password')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">تکرار کلمه عبور:</label>
                            <input autocomplete="off" type="password" name="password_confirmation" class="form-control">
                            <div class="form-text text-danger">
                                @error('password_confirmation')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>
                        <button type="submit" class="btn btn-secondary">تغییر کلمه عبور</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</body>

</html>
