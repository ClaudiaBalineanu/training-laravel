<?php
/**
 * @var \Illuminate\Database\Eloquent\Collection $products
 * @var \App\Product $product
 */
?>
@extends ('layout')

@section ('content')
    <h3>{{ __('Cart') }}</h3>

    <?php if ($products->isNotEmpty()) : ?>
        <table>

                <?php foreach ($products as $product) : ?>
                    <tr>
                        <td>
                            <img src="{{ asset('images/' . $product->image) }}" width="100" height="100" alt="{{ __('Image product') }}">
                        </td>
                        <td>
                            {{ $product->title }}<br/>
                            {{ $product->description }}<br/>
                            {{ $product->price }}<br/>
                        </td>
                        <td>
                            <a href="{{ route('removeFromCart.product', $product->getKey()) }}">{{ __('Remove') }}</a>
                        </td>
                    </tr>
                <?php endforeach ?>

        </table>
    <?php else : ?>
        {{ __('No data') }}
    <?php endif ?>

    <br/>

         @yield('checkout')

    <br/>
    <a href="{{ route('index') }}">{{ __('Go To Products') }}</a>
@endsection
