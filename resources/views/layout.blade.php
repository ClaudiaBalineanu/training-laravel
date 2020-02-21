<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ __('Training Laravel') }}</title>

        <style>
            .form input, .form textarea, .form img, .form a {
                display: block;
                margin-top: 10px;
            }
            table, th, td {
                border: 1px solid black;
            }
        </style>
    </head>
    <body>

        @yield('content')

    </body>
</html>
