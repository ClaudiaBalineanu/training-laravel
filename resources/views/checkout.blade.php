@extends ('cart')

@section('checkout')
    <form method="post" action="{{ route('cart') }}">
        @csrf
        <?php if (session('message')) : ?>
        <div>
            {{ session('message') }}
        </div>
        <?php endif ?>
        <br>
        <input id="name" type="text" name="name" class="@error('name') is-invalid @enderror" placeholder="{{ __('Name') }}"
               value="{{ old('name') }}"/>
        @error('name')
        <span class="alert alert-danger">{{ $message }}</span>
        @enderror
        <br/><br/>
        <input id="email" type="email" name="email" class="@error('email') is-invalid @enderror" placeholder="{{ __('Email') }}"
               value="{{ old('email') }}"/>
        @error('email')
        <span class="alert alert-danger">{{ $message }}</span>
        @enderror
        <br/><br/>
        <textarea id="comment" name="comment" cols="20" rows="7"
                  placeholder="{{ __('Comment') }}">{{ old('comment') }}</textarea>
        <br/><br/>
        <input type="submit" name="submit" value="{{ __('Checkout') }}">
    </form>
@endsection
