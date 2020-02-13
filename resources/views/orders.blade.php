<?php
/**
 * @var \Illuminate\Database\Eloquent\Collection $orders
 * @var \App\Order $order
 */
?>
@extends ('layout')

@section ('content')
    <h3>{{ __('Orders') }}</h3>

    <?php if ($orders->isNotEmpty()) : ?>
    <table>
        <tr>
            <td>{{ __('Id Order') }}</td>
            <td>{{ __('Customer Name') }}</td>
            <td>{{ __('Customer Email') }}</td>
            <td>{{ __('Comment') }}</td>
            <td>{{ __('Total Order') }}</td>
        </tr>

        <?php foreach ($orders as $order) : ?>
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
        <?php endforeach ?>

    </table>
    <?php else : ?>
        {{ __('No data') }}
    <?php endif ?>
@endsection
