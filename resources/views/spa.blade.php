<html>
<head>

    <!-- Load the jQuery JS library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

    <!-- Custom JS script -->
    <script type="text/javascript">
        $(document).ready(function () {

            /**
             * A function that takes a products array and renders it's html
             *
             * The products array must be in the form of
             * [{
             *     "title": "Product 1 title",
             *     "description": "Product 1 desc",
             *     "price": 1
             * },{
             *     "title": "Product 2 title",
             *     "description": "Product 2 desc",
             *     "price": 2
             * }]
             */

            //this js
            //add here a variable for change links (remove/add/delete/edit)
            function renderList(products) {
                html = [
                    '<tr>',
                    '<th>{{ __('Image') }}</th>',
                    '<th>{{ __('Details') }}</th>',
                    '<th>{{ __('Action') }}</th>',
                    '<th></th>',
                    '</tr>'
                ].join('');

                $.each(products, function (key, product) {
                    html += [
                        '<tr>',
                        '<td><img src="images/' + product.image + '" width="100" height="100" alt="{{ __("Image product") }}"></td>',
                        '<td>' + product.title + '<br>' +
                            product.description + '<br>' +
                            product.price + '</td>',
                        '<td><a href="#cart-add/' + product.id + '">{{ __('Add') }}</a></td>',
                        '</tr>'
                    ].join('');
                });

                return html;
            }

            /**
             * URL hash change handler
             */
            window.onhashchange = function () {
                // First hide all the pages
                $('.page').hide();

                var parts = window.location.hash.split('/');

                console.log(window.location.href);

                switch(parts[0]) {
                    case '#cart':
                        // Show the cart page
                        $('.cart').show();
                        // Load the cart products from the server
                        $.ajax('/cart', {
                            dataType: 'json',
                            success: function (response) {
                                // Render the products in the cart list
                                $('.cart .list').html(renderList(response));
                            }
                        });
                        break;
                    case '#cart-add':
                        $.ajax('/cart/add/' + parts[1], {
                            dataType: 'json',
                            success: function (response) {
                                window.location.hash = '#';
                            }
                        });
                        break;
                    default:
                        // If all else fails, always default to index
                        // Show the index page
                        $('.index').show();
                        // Load the index products from the server
                        $.ajax('/', {
                            dataType: 'json',
                            success: function (response) {
                                // Render the products in the index list
                                $('.index .list').html(renderList(response));
                            }
                        });
                        break;
                }
            };

            // one forced call of fct / to load something, the index page
            window.onhashchange();
        });
    </script>
</head>
<body>
<!-- The index page -->
<div class="page index">
    <!-- The index element where the products list is rendered -->
    <table class="list"></table>

    <!-- A link to go to the cart by changing the hash -->
    <a href="#cart" class="button">Go to cart</a>
</div>

<!-- The cart page -->
<div class="page cart">
    <!-- The cart element where the products list is rendered -->
    <table class="list"></table>

    <!-- A link to go to the index by changing the hash -->
    <a href="#" class="button">Go to index</a>
</div>
</body>
</html>
