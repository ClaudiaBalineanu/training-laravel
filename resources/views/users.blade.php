<?php /** @var \Illuminate\Database\Eloquent\Collection $users */ ?>
@extends ('layout')

@section ('content')
    <h3>{{ __('Users') }}</h3>

    <?php if ($users->isNotEmpty()) : ?>
        <table>
            <tr>
                <td>{{ __('Name') }}</td>
                <td>{{ __('Email') }}</td>
                <td>{{ __('Email verified at') }}</td>
                <td>{{ __('Status') }}</td>
                <td>{{ __('Created at') }}</td>
                <td>{{ __('Updated at') }}</td>
                <td></td>
                <td></td>
            </tr>

            <?php foreach ($users as $user) : ?>
                <?php /** @var \App\User $user */ ?>
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->email_verified_at }}</td>
                        <td>{{ $user->status }}</td>
                        <td>{{ $user->created_at }}</td>
                        <td>{{ $user->updated_at }}</td>
                        <td><a href="{{ route('reset', $user) }}">{{ __('Reset password') }}</a></td>
                        <td>
                            <?php if ($user->status == true) : ?>
                                <a href="{{ route('disable', $user) }}">{{ __('Disable') }}</a>
                            <?php else : ?>
                                <a href="{{ route('enable', $user) }}">{{ __('Enable') }}</a>
                            <?php endif ?>
                        </td>
                    </tr>
            <?php endforeach ?>

        </table>
    <?php else : ?>
        {{ __('No data') }}
    <?php endif ?>

    @error('email')
    <span class="invalid-feedback" role="alert">
        <strong>{{ $message }}</strong>
    </span>
    @enderror

    @if (session('status'))
        <div class="alert alert-success" role="alert">
            {{ session('status') }}
        </div>
    @endif


@endsection
