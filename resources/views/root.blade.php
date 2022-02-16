<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="description" content="A reservation booking system for equestrian centers.">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        @stack('meta')
        <script src="{{ url('libs/jquery/jquery-3.6.0.min.js') }}"></script>
        <script src="{{ url('libs/bootstrap/bootstrap.bundle.min.js') }}"></script>
        <script src="{{ url(mix('js/app.js')) }}"></script>
        @stack('scripts')
        <link rel="stylesheet" href="{{ url('libs/bootstrap/bootstrap.min.css') }}">
        <link rel="stylesheet" href="{{ url('libs/bootstrap-icons/bootstrap-icons.css') }}">
        @stack('css')
        <title>{{ config('app.name') }}</title>
    </head>
    <body>
        @yield('layout')
    </body>
</html>