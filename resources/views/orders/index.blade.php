<?php /** @var \Illuminate\Database\Eloquent\Collection $orders */ ?>
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
                <?php /** @var \App\Order $order  */ ?>
                    <tr>
                        <td><a href="{{ route('order', $order) }}">{{ $order->id }}</a></td>
                        <td>{{ $order->name }}</td>
                        <td>{{ $order->email }}</td>
                        <td>{!! nl2br($order->comment) !!}</td>
                        <td>{{ data_get($order, 'value.0.total', 0) }}</td>
                    </tr>
            <?php endforeach ?>

        </table>
    <?php else : ?>
        {{ __('No data') }}
    <?php endif ?>
@endsection
