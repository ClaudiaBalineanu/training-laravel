<?php /** @var \App\Product $product */?>
@extends ('layout')

@section('content')
    <form method="POST" class="form" action="{{ isset($product) ? route('update', $product) : route('store') }}" enctype="multipart/form-data">
        @csrf

        <?php if (isset($product)) : ?>
            @method('PUT')
        <?php endif ?>

        <input id="title" type="text" name="title" placeholder="{{ __('Title') }}" value="{{ isset($product) ? $product->title : old('title') }}">

        <textarea id="description" name="description" cols="20" rows="7" placeholder="{{ __('Description') }}">{{ isset($product) ? $product->description : old('description') }}</textarea>

        <input id="price" type="text" name="price" placeholder="{{ __('Price') }}" value="{{ isset($product) ? $product->price : old('price') }}">

        <?php if (isset($product)) : ?>
            <img src="{{ $product->getImageUrl() }}" id="img" width="100" height="100" alt="{{ __('Image product') }}">
            <label for="img">{{ basename($product->getImageUrl()) }}</label>
        <?php endif ?>

        <input id="image" type="file" name="image" value="{{ __('Browse') }}">

        <input type="submit" name="submit" value="{{ __('Save') }}">

        <a href="{{ route('products') }}">{{ __('Products') }}</a>
    </form>

    @component('layouts.errors') @endcomponent

@endsection
