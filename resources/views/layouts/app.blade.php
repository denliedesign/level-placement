<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>MDU Level Placement</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
        @include('partials.brand-styles')
        <!-- Scripts -->
    </head>
    <body>
        <nav class="text-center">
            <!-- For logged-in users -->
            @auth
                <div class="my-3 d-flex justify-content-center align-items-center gap-3 flex-wrap">
                    <a class="brand-link" href="/">Home</a>
                    <a class="brand-link" href="{{ route('dashboard') }}">Dashboard</a>
                    <a class="brand-link" href="{{ route('dance-classes.scheduler') }}">Scheduler</a>
                    <form method="POST" action="{{ route('logout') }}" class="m-0">
                        @csrf
                        <button type="submit" class="btn-brand-danger py-2 px-3">
                            Logout
                        </button>
                    </form>
                </div>
            @endauth

        <!-- For guests (not logged in) -->
            @guest
                <div class="my-3 d-flex justify-content-center align-items-center gap-3 flex-wrap">
                    <a class="brand-link" href="/">Home</a>
                    <a class="brand-link" href="{{ route('dance-classes.scheduler') }}">Scheduler</a>
                    <a class="btn-brand-primary" href="{{ route('register') }}">Register</a>
                    <a class="btn-brand-secondary" href="{{ route('login') }}">Login</a>
                </div>
            @endguest
            <div class="d-flex justify-content-center mb-4">
                <img src="/images/logo-mdu.png" style="height: 100px" width="auto" alt="">
            </div>
        </nav>
       @yield('content')

       <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>
       @yield('scripts')
    </body>
</html>
