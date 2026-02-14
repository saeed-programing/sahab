<!DOCTYPE html>
<html lang="fa" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.rtl.min.css"
        integrity="sha384-+qdLaIRZfNu4cVPK/PxJJEy0B0f3Ugv8i482AKY7gwXwhaCroABd086ybrVKTa0q" crossorigin="anonymous">

    <link rel="stylesheet" href="{{ asset('css/main.css') }}">

    <title>Sahab || Login</title>
</head>

<body>
    <div class="row mt-5 justify-content-center align-items-center">
        <div class="col-9 col-md-6 col-lg-3">
            <div class="card">
                <div class="card-body py-5">
                    <h4 class="mb-5 text-center">ورود به بخش ادمین</h4>
                    @session('success')
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endsession
                    @session('error')
                        <div class="alert alert-danger" role="alert">
                            {{ session('error') }}
                        </div>
                    @endsession
                    @error('username')
                        <div class="alert alert-danger" role="alert">
                            {{ $message }}
                        </div>
                    @enderror
                    @error('password')
                        <div class="alert alert-danger" role="alert">
                            {{ $message }}
                        </div>
                    @enderror
                    <form action="{{ route('login.post') }}" method="post">
                        @csrf
                        <div class="mb-3 mt-5">
                            <label for="username" class="form-label">نام کاربری</label>
                            <input value="{{ old('username') }}" name="username" type="text" class="form-control"
                                id="username" />
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">رمز عبور</label>
                            <input name="password" type="password" class="form-control" id="password" />
                        </div>

                        <div class="d-flex">
                            <button type="submit" class="btn btn-dark">ورود</button>
                            <a href="{{ route('forgetPassword') }}"
                                class="fs-6 ms-auto align-items-center justify-content-center">فراموشی
                                کلمه
                                عبور</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.getElementById('username').focus();
        })
    </script>

</body>

</html>
