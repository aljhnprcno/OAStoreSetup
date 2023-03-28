<?php $session = \Illuminate\Support\Facades\Session::class; ?>
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Scripts -->
        <script src="{{ asset('public/js/app.js') }}" defer></script>
        <script>
            var auth = {
                branch_code: {!! json_encode($session::get('branch_code')) !!},
                user_info: {!! json_encode($session::get('user_info')) !!},
                user_id: {!! json_encode($session::get('user_id')) !!},
                permissions: {!! json_encode($session::get('permissions')) !!}
            }
        </script>
        {{--
        @if(App::environment('production'))
        <!-- Global site tag (gtag.js) - Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=UA-125028676-3"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.0/css/all.min.css" integrity="sha512-10/jx2EXwxxWqCLX/hHth/vu2KY3jCF70dCQB8TSgNjbCVAC/8vai53GfMDrO2Emgwccf2pJqxct9ehpzG+MTw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());

            gtag('config', 'UA-125028676-3');
        </script>
        @endif
        --}}

        <!-- Styles -->
        @yield('css')
        <link href="{{ asset('public/css/app.css') }}" rel="stylesheet">
    </head>
    <body>
        <div id="app">
            <div class="main-content">
                @yield('content')
            </div>
        </div>
        @yield('footer')
    </body>
</html>
