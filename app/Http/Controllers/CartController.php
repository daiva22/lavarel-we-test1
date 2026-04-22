<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Service;

class CartController extends Controller
{
    public function add(Request $request)
    {
        $cart = session()->get('cart', []);

        if ($request->type === 'product') {

            $product = Product::findOrFail($request->id);

            $key = 'product_' . $product->id;

            if (isset($cart[$key])) {
                $cart[$key]['quantity']++;
            } else {
                $cart[$key] = [
                    'type' => 'product',
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => (float) $product->price,
                    'quantity' => 1,
                ];
            }
        }

        if ($request->type === 'service') {

            $service = Service::findOrFail($request->id);

            $key = 'service_' . $service->id;

            $cart[$key] = [
                'type' => 'service',
                'id' => $service->id,
                'name' => $service->name,
                'price' => (float) $service->price,
                'quantity' => 1,
                'date' => $request->date ?? null,
                'time' => $request->time ?? null,
            ];
        }

        session()->put('cart', $cart);

        return redirect()->route('cart')->with('success', 'Added to cart successfully');
    }

    public function update(Request $request)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$request->key])) {
            $cart[$request->key]['quantity'] = max(1, (int)$request->quantity);
        }

        session()->put('cart', $cart);

        return redirect()->route('cart');
    }

    public function remove(Request $request)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$request->key])) {
            unset($cart[$request->key]);
        }

        session()->put('cart', $cart);

        return redirect()->route('cart');
    }

    public function addAjax(Request $request)
{
    $cart = session()->get('cart', []);

    if ($request->type === 'product') {
        $product = Product::findOrFail($request->id);

        $key = 'product_' . $product->id;

        if (isset($cart[$key])) {
            $cart[$key]['quantity']++;
        } else {
            $cart[$key] = [
                'type' => 'product',
                'id' => $product->id,
                'name' => $product->name,
                'price' => (float) $product->price,
                'quantity' => 1,
            ];
        }
    }

    if ($request->type === 'service') {
        $service = Service::findOrFail($request->id);

        $key = 'service_' . $service->id;

        $cart[$key] = [
            'type' => 'service',
            'id' => $service->id,
            'name' => $service->name,
            'price' => (float) $service->price,
            'quantity' => 1,
            'date' => $request->date ?? null,
            'time' => $request->time ?? null,
        ];
    }

    session()->put('cart', $cart);

    $cartCount = collect($cart)->sum('quantity');

    return response()->json([
        'success' => true,
        'message' => 'Added to cart successfully',
        'cart_count' => $cartCount,
    ]);
}
}