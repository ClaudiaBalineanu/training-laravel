<?php /** @var \Illuminate\Database\Eloquent\Collection $products */ ?>
@extends ('layout')

@section ('content')

    <h3>{{ __('Products') }}</h3>

    <?php if ($products->isNotEmpty()) : ?>
        <table>

            <?php foreach ($products as $product) : ?>
                <?php /** @var \App\Product $product */ ?>
                    <tr>
                        <td>
                            <img src="{{ $product->getImageUrl() }}" width="100" height="100" alt="{{ __('Image product') }}">
                        </td>
                        <td>
                            {{ $product->title }} <br />
                            {!! nl2br($product->description) !!} <br />
                            {{ $product->price }} <br />
                        </td>
                        <td>
                            <a href="{{ route('edit', $product) }}">{{ __('Edit') }}</a>
                        </td>
                        <td>
                            <a href="{{ route('delete', $product) }}">{{ __('Delete') }}</a>
                        </td>
                    </tr>
            <?php endforeach ?>

        </table>
    <?php else : ?>
        {{ __('No data') }}
    <?php endif ?>

    <a href="{{ route('create') }}">{{ __('Add') }}</a>

    <a href="{{ route('orders') }}">{{ __('Orders') }}</a>

    @component('layouts.login') @endcomponent
@endsection
