<?php
/**
 * @var \App\Order $order
 * @var \App\Product $product
 */
?>
@extends ('layout')

@section ('content')
    <h3>{{ __('Order') }}</h3>

    <table>
        <tr>
            <td>{{ __('Id Order') }}</td>
            <td>{{ __('Customer Name') }}</td>
            <td>{{ __('Customer Email') }}</td>
            <td>{{ __('Comment') }}</td>
            <td>{{ __('Total Order') }}</td>
        </tr>

        <tr>
            <td>
                <a href="{{ route('order', $order->getKey()) }}">{{ $order->id }}</a>
            </td>
            <td>
                {{ $order->name }}<br>
            </td>
            <td>
                {{ $order->email }}<br>
            </td>
            <td>
                {{ $order->comment }}<br>
            </td>
            <td>
                {{ $order->total }}<br>
            </td>
        </tr>
    </table>

    <br>

    <?php if ($order->products->isNotEmpty()) : ?>
    <table>

        <?php foreach ($order->products as $product) : ?>
        <tr>
            <td>
                <img src="{{ asset('images/' . $product->image) }}" width="100" height="100" alt="{{ __('Image product') }}">
            </td>
            <td>
                {{ $product->title }}<br/>
                {{ $product->description }}<br/>
                {{ $product->price }}<br/>
            </td>
        </tr>
        <?php endforeach ?>

    </table>
    <?php else : ?>
    {{ __('No data') }}
    <?php endif ?>
@endsection
