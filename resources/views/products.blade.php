@extends ('layout')

@section ('content')
    <h3>Products</h3>

    <?php /** @var \Illuminate\Database\Eloquent\Collection $products */ ?>

    <?php if ($products->isNotEmpty()) : ?>
        <table>
            <?php /** @var \App\Product $product */ ?>

            <?php foreach ($products as $product) : ?>
                <tr>
                    <td>
                        <img src="images/{{ $product->image }}" width="100" height="100" alt="{{ __('Image product') }}">
                    </td>
                    <td>
                        {{ $product->title }}<br/>
                        {{ $product->description }}<br/>
                        {{ $product->price }}<br/>
                    </td>
                    <td>
                        <a href="{{ route('edit', $product->getKey()) }}">{{ __('Edit') }}</a>
                    </td>
                    <td>
                        <a href="{{ route('delete', $product->getKey()) }}">{{ __('Delete') }}</a>
                    </td>
                </tr>
            <?php endforeach ?>

        </table>
    <?php else : ?>
        {{ __('No data') }}
    <?php endif ?>
    <a href="/products/create">{{ __('Add') }}</a>



    <!--

    <p>{/{ url('/') }}</p>
    <p></p>

    request()->root() sau url
    nope -- url()->previous()
    <p>{/{ $name }}</p>
    -->
@endsection
