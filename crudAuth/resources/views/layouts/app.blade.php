<html lang="{{ app()->getLocale() }}">
@include('layouts.head')
<body>
    <div id="app">
        @include('layouts.nav')
        <main class="py-4">
            <div class="container">
            @yield('content')
            </div>
        </main>
    </div>
    @stack('scripts')
</body>
</html>