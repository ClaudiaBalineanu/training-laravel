<?php /** @var \Illuminate\Database\Eloquent\Model $order */ ?>
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
            <td>{{ $order->id }}</td>
            <td>{{ $order->name }}</td>
            <td>{{ $order->email }}</td>
            <td>{{ $order->comment }}</td>
            <td>{{ data_get($order, 'value.0.total', 0) }}</td>
        </tr>
    </table>

    <br />
    <?php /** \Illuminate\Database\Eloquent\Collection $order->products */ ?>
        <?php if ($order->products->isNotEmpty()) : ?>
            <table>

                <?php foreach ($order->products as $product) : ?>
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
                        </tr>
                    <?php endforeach ?>

            </table>
        <?php else : ?>
            {{ __('No data') }}
        <?php endif ?>

    <a href="{{ url('/') }}">{{ url('/') }}</a>
@endsection
