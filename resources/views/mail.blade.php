<?php
/**
 * @var \Illuminate\Database\Eloquent\Collection $products
 * @var \App\Product $product
 */
?>
@extends ('layout')

@section ('content')
    <h3>{{ __('Products in mail') }}</h3>

        <?php if ($products->isNotEmpty()) : ?>
            <table>

                    <?php foreach ($products as $product) : ?>
                        <tr>
                            <td>
                                <img src="{{ url('/') }}/images/{{ $product->image }}" width="100" height="100" alt="{{ __('Image product') }}">
                            </td>
                            <td>
                                {{ $product->title }}<br/>
                                {{ $product->description }}<br/>
                                {{ $product->price }}<br/>
                            </td>
                        </tr>
                    <?php endforeach; ?>

            </table>
        <?php else : ?>
            {{ __('No data') }}
        <?php endif ?>

    <a href="http://training-laravel.local.ro/index">training-laravel.local.ro/index</a>
@endsection
