<?php
/**
 * @var \App\Product $product
 */
?>
@extends ('layout')

@section('content')
    <form method="POST" action="{{ isset($product) ? route('update', $product->getKey()) : route('store') }}" enctype="multipart/form-data">
        @csrf

        <?php if (isset($product)) : ?>
            @method('PUT')
        <?php endif ?>

        <?php if (session('message')) : ?>
            <div>
                {{ session('message') }}
            </div>
        <?php endif; ?>

        <br>

        <input id="title" type="text" name="title" class="@error('title') is-invalid @enderror" placeholder="{{ __('Title') }}"
               value="{{ isset($product) ? $product->title : old('title') }}"/>
        @error('title')
        <span class="alert alert-danger">{{ $message }}</span>
        @enderror
        <br/><br/>
        <input id="description" type="text" name="description" class="@error('description') is-invalid @enderror" placeholder="{{ __('Description') }}"
               value="{{ isset($product) ? $product->description : old('description') }}"/>
        @error('description')
        <span class="alert alert-danger">{{ $message }}</span>
        @enderror
        <br/><br/>
        <input id="price" type="text" name="price" class="@error('price') is-invalid @enderror" placeholder="{{ __('Price') }}"
               value="{{ isset($product) ? $product->price : old('price') }}"/>
        @error('price')
        <span class="alert alert-danger">{{ $message }}</span>
        @enderror
        <br/><br/>
        <input id="image" type="file" name="image" class="@error('image') is-invalid @enderror" value="{{ __('Browse') }}"/>
        @error('image')
        <span class="alert alert-danger">{{ $message }}</span>
        @enderror
        <br/><br/>
        <a href="{{ route('products') }}">{{ __('Products') }}</a>
        <input type="submit" name="submit" value="{{ __('Save') }}">
    </form>
@endsection
