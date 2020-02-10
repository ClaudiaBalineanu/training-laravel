<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ __('Login') }}</title>
    </head>
    <body>

        <h3>{{ __('Login') }}</h3>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="post" action="/login">
            @csrf
            <input type="text" name="username" placeholder="{{ __('Username') }}"
                   value="{{ old('username') }}"/><br/><br/>
            <input type="password" name="password" placeholder="{{ __('Password') }}"/><br/><br/>
            <input type="submit" name="submit" value="{{ __('Login') }}"/>
        </form>
    </body>
</html>
