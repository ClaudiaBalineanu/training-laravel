 <form method="POST" class="form" action="{{ route('cart') }}">
     @csrf

     <?php if (session('message')) : ?>
         <div>{{ session('message') }}</div>
     <?php endif ?>

     <input id="name" type="text" name="name" placeholder="{{ __('Name') }}" value="{{ old('name') }}">

     <input id="email" type="email" name="email" placeholder="{{ __('Email') }}" value="{{ old('email') }}">

     <textarea id="comment" name="comment" cols="20" rows="7" placeholder="{{ __('Comment') }}">{{ old('comment') }}</textarea>

     <input type="submit" name="submit" value="{{ __('Checkout') }}">

     @component('layouts.errors') @endcomponent
 </form>

