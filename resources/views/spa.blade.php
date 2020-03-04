<html>
<head>
    <!-- Load the jQuery JS library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/js-cookie@2/src/js.cookie.min.js"></script>

    <style>
        form input, form textarea, form img, form a {
            display: block;
            margin-top: 10px;
        }
        .error {
            color: #FF0000;
        }
    </style>

    <!-- Custom JS script -->
    <script type="text/javascript">
        $(document).ready(function () {

            // if the user is logged in or not
            var logged = false;

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });

            function renderList(products, hash) {
                html = '';

                $.each(products, function (key, product) {
                    var action;
                    if (hash === "#cart") {
                        action = '<td><a href="#cart-remove/' + product.id + '" class="action">{{ __('Remove') }}</a></td>';
                    } else if (hash === "#products") {
                        action = '<td><a href="#products-edit/' + product.id + '" class="action">{{ __('Edit') }}</a></td><td><a href="#products-delete/' + product.id + '" class="action">{{ __('Delete') }}</a></td>';
                    } else if (hash === '#order') {
                        action = '';
                    } else {
                        action = '<td><a href="#cart-add/' + product.id + '" class="action">{{ __('Add') }}</a></td>';
                    }

                    html += [
                        '<tr>',
                            '<td><img src="images/' + product.image + '" width="100" height="100" alt="{{ __("Image product") }}"></td>',
                            '<td>' +
                                product.title + '<br>' +
                                product.description + '<br>' +
                                product.price +
                            '</td>',
                             action,
                        '</tr>'
                    ].join('');
                });

                return html;
            }

            function getSaveForm(product = '', parts = '') {

                var url = window.location.origin + window.location.pathname + '#products';
                var method, image = '';

                if (product) {
                    method = '@method('PUT')';
                    url += '/' + parts;
                    image = '<img src="images/' + product.image + '" id="img" width="100" height="100" alt="{{ __('Image product') }}">' +
                            '<label for="img">' + product.image + '</label>';
                }

                return [
                    '<form method="POST" class="save_form" action="' + url + '" enctype="multipart/form-data">',
                        '@csrf',
                        method,
                        '<input id="title" type="text" name="title" placeholder="{{ __('Title') }}"  value="' + (product ? product.title : '') + '">',
                        '<span class="title error"></span>',
                        '<textarea id="description" name="description" cols="20" rows="7" placeholder="{{ __('Description') }}">' + (product ? product.description : "") + '</textarea>',
                        '<span class="description error"></span>',
                        '<input id="price" type="text" name="price" placeholder="{{ __('Price') }}" value="' + (product ? product.price : "") + '">',
                        '<span class="price error"></span>',
                        image,
                        '<input id="image" type="file" name="image" class="image" value="{{ __('Browse') }}">',
                        '<span class="image error"></span>',
                        '<input type="submit" class="submit" name="submit" value="{{ __('Save') }}">',
                    '</form>'
                ].join('');
            }

            function renderOrders(orders) {
                html = [
                    '<tr>',
                        '<th>{{ __('Id') }}</th>',
                        '<th>{{ __('Name') }}</th>',
                        '<th>{{ __('Email') }}</th>',
                        '<th>{{ __('Comment') }}</th>',
                        '<td>{{ __('Total Order') }}</td>',
                    '</tr>'
                ].join('');

                $.each(orders, function (key, order) {
                    html += [
                        '<tr>',
                            '<td><a href="#order/' + order.id + '" class="order_id">' + order.id + '</a></td>',
                            '<td>' + order.name + '</td>',
                            '<td>' + order.email + '</td>',
                            '<td>' + order.comment + '</td>',
                            '<td>' + (order.value[0] ? order.value[0].total : 0) + '</td>',
                        '</tr>'
                    ].join('');
                });

                return html;
            }

            function getOrder(order) {
                return [
                    '<tr>',
                        '<th>{{ __('Id') }}</th>',
                        '<th>{{ __('Name') }}</th>',
                        '<th>{{ __('Email') }}</th>',
                        '<th>{{ __('Comment') }}</th>',
                        '<th>{{ __('Total Order') }}</th>',
                    '</tr>',
                    '<tr>',
                        '<td>' + order.id + '</td>',
                        '<td>' + order.name + '</td>',
                        '<td>' + order.email + '</td>',
                        '<td>' + order.comment + '</td>',
                        '<td>' + order.value.total + '</td>',
                    '</tr>'
                ].join('');
            }

            $('.cart form.checkout').on('submit', function(e) {
                e.preventDefault();

                $('.checkout .error').empty();
                $('.checkout .message').empty();

                tokenRequest(function() {
                    $.ajax('/cart', {
                        method: 'POST',
                        data: $('.cart .checkout').serialize(),
                        success: function (response) {
                            $('.cart .list').empty();
                            $('.checkout .message').append(response.message);
                        },
                        error: function (response) {
                            if (response.status === 422) {
                                var errors = response.responseJSON.errors;
                                $.each(errors, function (key, value) {
                                    $('.checkout .' + key + '.error').append(value);
                                });
                            } // end if
                        } // end error
                    }); // end ajax - post
                });
            }); // end onclick

            $('.login form.form_login').on('submit', function(e) {
                e.preventDefault();

                $('.form_login .error').empty();

                tokenRequest(function() {
                    var email = $('form.form_login .email').val();
                    var password = $('form.form_login .password').val();
                    var token = $('meta[name="csrf-token"]').attr('content');

                    $.ajax('/login', {
                        dataType: 'json',
                        method: 'POST',
                        data: {'email': email, 'password': password, '_token': token},
                        success: function (response) {
                            if (response.success) {
                                $('.form_login .email').val('');
                                $('.form_login .password').val('');

                                logged = true;
                                window.location.hash = '#products';
                            } else if (response.errors) {
                                $.each(response.errors, function (key, value) {
                                    $('.form_login .' + key + '.error').append(value);
                                });
                            }
                        },
                        error: function (response) {
                            if (response.status === 422) {
                                var errors = response.responseJSON.errors;
                                $.each(errors, function (key, value) {
                                    $('.form_login .' + key + '.error').append(value);
                                });
                            }
                        }
                    });
                }); // end tokenRequest
            });

            function tokenRequest(callback) {
                $.ajax('/token', {
                    success: function (response) {
                        if (response.token) {
                            $.ajaxSetup({
                                headers: {
                                    'X-CSRF-TOKEN': response.token
                                }
                            });
                            if (callback) {
                                callback();
                            }
                        } else if (response.errors) {
                            alert('Error');
                        }
                    },
                    error: function (response) {
                        alert('Error');
                    }
                });
            }

            /**
             * URL hash change handler
             */
            window.onhashchange = function () {
                // First hide all the pages
                $('.page').hide();

                // split link in #cart or #index and string after /, usually an id
                var parts = window.location.hash.split('/');

                switch(parts[0]) {
                    case '#cart':
                        // Show the cart page
                        $('.cart').show();

                        // Load the cart products from the server
                        $.ajax('/cart', {
                            dataType: 'json',
                            success: function (response) {
                                // Render the products in the cart list
                                $('.cart .list').html(renderList(response, parts[0]));

                                $('form.checkout .name').val('');
                                $('form.checkout .email').val('');
                                $('form.checkout .comment').val('');
                                $('.checkout .error').empty();
                                $('.checkout .message').empty();
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
                    case '#cart-remove':
                        $.ajax('/cart/remove/' + parts[1], {
                            dataType: 'json',
                            success: function (response) {
                                window.location.hash = '#cart';
                            }
                        });
                        break;

                    case '#products':
                        $('.products').show();

                        $.ajax('/products', {
                            dataType: 'json',
                            success: function (response) {
                                $('.products .list').html(renderList(response, parts[0]));
                            },
                            error: function (response) {
                                if (response.status === 401) {
                                    window.location.hash = '#login';
                                }
                            },
                        });
                        break;
                    case '#products-delete':
                        $.ajax('/products/delete/' + parts[1], {
                            dataType: 'json',
                            success: function (response) {
                                window.location.hash = '#products';
                            }
                        });
                        break;
                    case '#products-edit':
                        $('.product').show();

                        $.ajax('/products/edit/' + parts[1], {
                            dataType: 'json',
                            success: function (response) {
                                $('.product .save_div').html(getSaveForm(response, parts[1]));

                                $('.product form.save_form').on('submit', function(e) {
                                    e.preventDefault();

                                    $('.save_form .error').empty();
                                    var formData = $('.save_form').get(0);

                                    tokenRequest(function() {
                                        $.ajax('/products/' + parts[1], {
                                            method: 'POST',
                                            data: new FormData(formData),
                                            cache: false,
                                            contentType: false,
                                            processData: false,
                                            success: function (response) {
                                                location.reload();
                                            },
                                            error: function (response) {
                                                if (response.status === 422) {
                                                    var errors = response.responseJSON.errors;
                                                    $.each(errors, function (key, value) {
                                                        $('.save_form .' + key + '.error').append(value);
                                                    });
                                                } // end if
                                            }, // end error
                                        }); // end ajax - post
                                    }); // end tokenRequest
                                }); // onclick submit save_form
                            }
                        });
                        break;
                    case '#products-create':
                        $('.product').show();

                        $('.product .save_div').html(getSaveForm());

                        $('.product form.save_form').on('submit', function(e) {
                            e.preventDefault();

                            $('.save_form .error').empty();
                            var formData = $('.save_form').get(0);

                            tokenRequest(function() {
                                $.ajax('/products', {
                                    method: 'POST',
                                    data: new FormData(formData),
                                    cache: false,
                                    contentType: false,
                                    processData: false,
                                    success: function (response) {
                                        location.reload();
                                    },
                                    error: function (response) {
                                        if (response.status === 422) {
                                            var errors = response.responseJSON.errors;
                                            $.each(errors, function (key, value) {
                                                $('.save_form .' + key + '.error').append(value);
                                            });
                                        } // end if
                                    }, // end error
                                }); // end ajax - post
                            }); // end tokenRequest
                        }); // onclick submit save_form
                        break;

                    case '#orders':
                        $('.orders').show();

                        $.ajax('/orders', {
                            dataType: 'json',
                            success: function (response) {
                                $('.orders .list').html(renderOrders(response));
                            }
                        });
                        break;
                    case '#order':
                        $('.order').show();

                        $.ajax('/order/' + parts[1], {
                            dataType: 'json',
                            success: function (response) {
                                $('.order .order_list').html(getOrder(response.order));
                                $('.order .products_list').html(renderList(response.products, parts[0]))
                            }
                        });
                        break;

                    case '#login':
                        $('.login').show();

                        break;
                    case '#logout':
                        tokenRequest(function() {
                            $.ajax('/logout', {
                                method: 'POST',
                                success: function (response) {
                                    logged = false;
                                    window.location.href = '#login';
                                },
                                error: function (response) {
                                    if (response.status === 422) {
                                        alert('Error');
                                    }
                                    window.location.href = '#login';
                                }
                            });
                        });
                        break;

                    default:
                        // If all else fails, always default to index
                        // Show the index page
                        $('.index').show();

                        // if logged in show link to logout else show link to login
                        logged === true ? $('.index a.login').attr('href', '#logout').text('{{ __('Logout') }}') : $('.index a.login').attr('href', '#login').text('{{ __('Login') }}');

                        // Load the index products from the server
                        $.ajax('/', {
                            dataType: 'json',
                            success: function (response) {
                                // Render the products in the index list
                                $('.index .list').html(renderList(response, parts[0]));
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

    <h3>{{ __('Products') }}</h3>
    <!-- The index element where the products list is rendered -->
    <table class="list"></table>

    <!-- A link to go to the cart by changing the hash -->
    <a href="#cart" class="button">{{ __('Go to cart') }}</a><br>
    <a href="" class="button login"></a><br>
    <a href="#products" class="button">{{ __('Products') }}</a>
</div>

<!-- The cart page -->
<div class="page cart">

    <h3>{{ __('Cart') }}</h3>
    <!-- The cart element where the products list is rendered -->
    <table class="list"></table>

    <div class="checkout">
        <form method="POST" class="checkout" action="https://training-laravel.local.ro/spa#cart">

            <span class="message"></span>

            <input type="text" name="name" class="name" placeholder="{{ __('Name') }}">
            <span class="name error"></span>

            <input type="email" name="email" class="email" placeholder="{{ __('Email') }}">
            <span class="email error"></span>

            <textarea name="comment" cols="20" rows="7" class="comment" placeholder="{{ __('Comment') }}"></textarea>
            <span class="comment error"></span>

            <input type="submit" class="submit" name="submit" value="{{ __('Checkout') }}">
        </form>
    </div>

    <!-- A link to go to the index by changing the hash -->
    <a href="#" class="button">{{ __('Go to index') }}</a>
</div>

<!-- The login page -->
<div class="page login">

    <h3>{{ __('Login') }}</h3>

    <div class="login_div">
        <form method="POST" class="form_login" action="https://training-laravel.local.ro/login">

            <!-- <input type="hidden" name="_token"> -->

            <input class="email" type="email" name="email" required="required" placeholder="{{ __('Email') }}">
            <span class="email error"></span>

            <input class="password" type="password" name="password" required="required" placeholder="{{ __('Password') }}">
            <span class="password error"></span>

            <input type="submit" class="submit" name="submit" value="{{ __('Login') }}">
        </form>
    </div>

    <a href="#" class="button">{{ __('Go to index') }}</a>
</div>

<!-- The products page -->
<div class="page products">

    <h3>{{ __('Products') }}</h3>
    <!-- The products element where the products list is rendered -->
    <table class="list"></table>

    <!-- A link to go to the form to add a new product -->
    <a href="#products-create" class="button">{{ __('Add') }}</a><br>
    <a href="#" class="button">{{ __('Go to index') }}</a>
    <a href="#orders" class="button">{{ __('Orders') }}</a>

    <!--  snippet code taken from app.blade.php for logout, doesn't work without form and onclick  -->
    <a href="#logout">
        {{ __('Logout') }}
    </a>

</div>

<!-- The page for edit or add new product -->
<div class="page product">

    <div class="save_div"></div>

    <a href="#products" class="button">{{ __('Products') }}</a>
</div>

<!-- The orders page -->
<div class="page orders">

    <h3>{{ __('Orders') }}</h3>
    <!-- The products element where the order list is rendered -->
    <table class="list"></table>

    <a href="#products" class="button">{{ __('Products') }}</a>
</div>

<!-- The order page -->
<div class="page order">

    <h3>{{ __('Order') }}</h3>

    <table class="order_list"></table>

    <h3>{{ __('Products') }}</h3>

    <table class="products_list"></table>

    <a href="#orders" class="button">{{ __('Orders') }}</a>
</div>

</body>
</html>
