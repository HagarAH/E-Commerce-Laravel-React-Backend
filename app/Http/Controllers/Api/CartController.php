<?php

namespace App\Http\Controllers\Api;

use App\Models\Cart;
use App\Models\CartProductMap;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{

    public function getItems(Request $request)
    {
        $items = [];
        $user = Auth::user();
        $cart = Cart::where('user_id', $user->getAuthIdentifier())->first();
        if ($cart) {
            $products = CartProductMap::where('cart_id', $cart->id)->get();
            foreach ($products as $item) {
                $amount = $item->quantity;
                $cartItem = Product::where('id', $item->product_id)->first();
                if ($cartItem) {
                    $newItem = array_merge($cartItem->toArray(), ['amount' => $amount]);
                    array_push($items, $newItem);
                }
            }
            return response(['products' => $items]);
        }
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

    public function deleteItem(Request $request)
    {
        $user = Auth::user();
        $cart = Cart::where('user_id', $user->getAuthIdentifier())->first();
        if ($cart) {
            $products = CartProductMap::where('cart_id', $cart->id)->get();
            foreach ($products as $item) {
                if ($item->product_id == $request->id) {
                    $item->delete();
                    return response()->json(['message' => 'Item deleted successfully']);
                }
            }
        }
        return response()->json(['message' => 'Item not found']);
    }

}
