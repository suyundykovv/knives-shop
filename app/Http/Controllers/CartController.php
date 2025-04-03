<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CartItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controller as BaseController;

class CartController extends BaseController
{
    public function getCart() {
        return response()->json(Auth::user()->cartItems()->with('knife')->get());
    }

    public function addToCart(Request $request) {
        $cartItem = CartItem::firstOrCreate([
            'user_id' => Auth::id(),
            'knife_id' => $request->knife_id
        ]);
        $cartItem->increment('quantity');
        return response()->json(['message' => 'Knife added to cart']);
    }

    public function removeFromCart(Request $request) {
        $cartItem = CartItem::where('user_id', Auth::id())->where('knife_id', $request->knife_id)->first();
        if ($cartItem) {
            if ($cartItem->quantity > 1) {
                $cartItem->decrement('quantity');
            } else {
                $cartItem->delete();
            }
        }
        return response()->json(['message' => 'Knife removed from cart']);
    }

    public function clearCart() {
        Auth::user()->cartItems()->delete();
        return response()->json(['message' => 'Cart cleared']);
    }
}
