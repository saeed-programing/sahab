<!DOCTYPE html>
<html lang="fa" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.rtl.min.css"
        integrity="sha384-+qdLaIRZfNu4cVPK/PxJJEy0B0f3Ugv8i482AKY7gwXwhaCroABd086ybrVKTa0q" crossorigin="anonymous">

    <link rel="stylesheet" href="{{ asset('css/main.css') }}">

    <title>Sahab || forgetPassword</title>
</head>

<body>
    <div class="row mt-5 justify-content-center align-items-center">
        <div class="col-4">
            <div class="card">
                <h5 class="card-header">فراموشی رمز عبور</h5>
                <div class="card-body">
                    @if (session()->has('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif
                    @if (session()->has('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    <form action="{{ route('forgetPassword.post') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">ایمیل:</label>
                            <input autocomplete="off" name="email" value="{{ old('email') }}" class="form-control">
                            <div class="form-text text-danger">
                                @error('email')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>
                        <button type="submit" class="btn btn-secondary">ارسال لینک به ایمیل</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</body>

</html>
