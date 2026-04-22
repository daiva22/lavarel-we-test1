@extends('layouts.app')

@section('title', 'Cart')

@section('content')

<div class="cart-page">
    <div class="cart-container">
        <h1 class="cart-title">Your Cart</h1>

        @if(session('success'))
            <div class="flash-message">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="cart-error-message">
                {{ session('error') }}
            </div>
        @endif

        @php
            $cart = session('cart', []);
            $total = 0;
        @endphp

        @if(count($cart) > 0)
            <div class="cart-table-wrap">
                <table class="cart-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Subtotal</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($cart as $key => $item)
                            @php
                                $subtotal = $item['price'] * $item['quantity'];
                                $total += $subtotal;
                            @endphp

                            <tr>
                                <td class="cart-product-name">
                                    {{ $item['name'] }}
                                </td>

                                <td class="cart-product-type">
                                    {{ ucfirst($item['type']) }}
                                </td>

                                <td class="cart-price">
                                    Rs {{ number_format($item['price'], 2) }}
                                </td>

                                <td>
                                    <form action="{{ route('cart.update') }}" method="POST" class="cart-qty-form">
                                        @csrf
                                        <input type="hidden" name="key" value="{{ $key }}">

                                        <div class="cart-qty-wrap">
                                            <input type="number"
                                                   name="quantity"
                                                   value="{{ $item['quantity'] }}"
                                                   min="1"
                                                   class="qty-input">

                                            <button type="submit" class="cart-btn">
                                                Update
                                            </button>
                                        </div>
                                    </form>
                                </td>

                                <td class="cart-subtotal">
                                    Rs {{ number_format($subtotal, 2) }}
                                </td>

                                <td>
                                    <form action="{{ route('cart.remove') }}" method="POST" class="cart-remove-form">
                                        @csrf
                                        <input type="hidden" name="key" value="{{ $key }}">

                                        <button type="submit" class="cart-btn cart-btn-danger">
                                            Remove
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- ONLY TOTAL -->
            <div class="cart-summary">
                <div class="cart-total">
                    Total: <span>Rs {{ number_format($total, 2) }}</span>
                </div>

                <div class="cart-actions">
                    <a href="{{ url('/#shop') }}" class="cart-link-btn">
                        Continue Shopping
                    </a>
                </div>
            </div>

        @else
            <div class="cart-empty">
                <h2>Your cart is empty</h2>
                <p>Add products or services before continuing.</p>

                <a href="{{ url('/#shop') }}" class="cart-link-btn">
                    Go to Shop
                </a>
            </div>
        @endif
    </div>
</div>

@endsection