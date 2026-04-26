<!DOCTYPE html>
<html lang="en">
<head>
    @include('partials.head')
</head>
<body>
    @include('partials.navbar')

    @yield('content')

    @include('partials.footer')

    <script src="{{ asset('script.js') }}" defer></script>
    @stack('scripts')
</body>
</html>
