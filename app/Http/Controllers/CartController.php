<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartProductMap;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function getItems(Request $request)
    {

    }

    public function addItems(Request $request)
    {
        $user = Auth::user();

        $cart = Cart::where('user_id', $user->getAuthIdentifier())->first();

        if ($cart) {
            foreach ($request->cart as $item) {
                $cartItem = CartProductMap::where('cart_id', $cart->id)->where('product_id', $item['id'])->first();
                if ($cartItem) {
                    $cartItem->quantity += $item['count'];
                    $cartItem->save();
                } else {
                    $newItem = new CartProductMap;
                    $newItem->product_id = $item['id'];
                    $newItem->cart_id = $cart->id;
                    $newItem->quantity = $item['count'];
                    $newItem->save();
                }
            }
        } else {
            $cart = new Cart;
            $cart->user_id = $user->id;
            $cart->save();

            foreach ($request->cart as $item) {
                $newItem = new CartProductMap;
                $newItem->product_id = $item['id'];
                $newItem->cart_id = $cart->id;
                $newItem->quantity = $item['count'];
                $newItem->save();
            }
        }
    }

    public function deleteItems(Request $request)
    {

    }
}
