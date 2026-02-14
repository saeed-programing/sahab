@include('layout.header')
<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 mt-4">
    @include('layout.sidebar')
    @yield('body')
</main>
@include('layout.footer')
