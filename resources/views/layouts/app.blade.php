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
        <!-- Scripts -->
    </head>
    <body>
        <nav class="text-center">
            <!-- For logged-in users -->
            @auth
                <div class="my-3">
                    <a class="mx-3" href="{{ route('dashboard') }}">Dashboard</a>
                    <form class="mx-3" method="POST" action="{{ route('logout') }}" style="display: inline;">
                        @csrf
                        <button type="submit">Logout</button>
                    </form>
                </div>
            @endauth

        <!-- For guests (not logged in) -->
            @guest
                <div class="my-3">
                    <a class="mx-3" href="{{ route('register') }}"><div class="btn btn-lg btn-danger">Register</div></a>
                    <a class="mx-3 text-muted" href="{{ route('login') }}"><small>Login</small></a>
                </div>
            @endguest
            <div class="d-flex justify-content-center">
                <img src="/images/logo-mdu.png" style="height: 100px" width="auto" alt="">
            </div>
        </nav>
       @yield('content')

       <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>
    </body>
</html>
