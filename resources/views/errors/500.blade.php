<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <script src="{{ url('libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
        <link rel="stylesheet" href="{{ url('libs/bootstrap/css/bootstrap.min.css') }}">
        <title>Error 500</title>
    </head>
    <body>
        <div class="container mt-5 pt-5">
            <div class="alert alert-danger text-center">
                <h2 class="display-3">500</h2>
                <p class="display-5">System Error!</p>
            </div>
        </div>
    </body>
</html>