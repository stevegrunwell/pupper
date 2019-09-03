<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ __('Pupper: The World\'s Favorite Social Network') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body class="container-fluid homepage">
    <main class="row overflow-hidden" style="height: 100vh;">
        <section class="row align-items-center col-md bg-light pt-3 px-3 pt-md-5 px-md-5 text-center order-md-2">
            <div class="col text-center px-md-5 mx-auto">
                <img src="{{ asset('images/pupper-icon.svg') }}" alt="{{ __('Pupper') }}" class="mb-4" style="width: 100px;" />
                <p class="h2 mb-3">{{ __('See what\'s happening right now.') }}</p>
                <p class="lead mb-5">{{ __('Join Pupper today.') }}</p>
                <p><a href="{{ route('register') }}" class="btn btn-primary btn-block rounded-pill">{{ __('Sign up') }}</a></p>
                <p><a href="{{ route('login') }}" class="btn btn-outline-primary btn-block rounded-pill">{{ __('Log in') }}</a></p>
            </div>
        </section>

        <section class="row align-items-center col-md bg-dark py-3 px-3 py-md-5 px-md-5 text-white">
            <div class="col text-center">
                <h2 class="display-5">{{ __('Welcome to Pupper!') }}</h2>
                <p class="lead">{{ __('The world\'s favorite social network, featuring:') }}</p>
                <ul class="list-unstyled text-left mx-auto" style="max-width: 300px; font-size: 2em;">
                    <li><span class="mr-2">🐶</span> {{ __('Dog puns') }}</li>
                    <li><span class="mr-2">📝</span> {{ __('An edit button') }}</li>
                    <li><span class="mr-2">🚦</span> {{ __('Test coverage') }}</li>
                </ul>
            </div>
        </section>
    </main>
</body>
</html>
