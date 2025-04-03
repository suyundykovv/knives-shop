<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Illuminate\Routing\Controller as BaseController;

class PaymentController extends BaseController
{
    public function checkout(Request $request)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));

        $items = $request->items; // Массив товаров из запроса

        if (!$items || !is_array($items)) {
            return response()->json(['error' => 'Invalid cart data'], 400);
        }

        $lineItems = array_map(function ($item) {
            return [
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => $item['name'],
                    ],
                    'unit_amount' => $item['price'] * 100, // Конвертация в центы
                ],
                'quantity' => 1,
            ];
        }, $items);

        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => route('payment.success'),
            'cancel_url' => route('payment.cancel'),
        ]);

        return response()->json(['url' => $session->url]);
    }

    public function success()
    {
        return inertia('SuccessPage');
    }

    public function cancel()
    {
        return inertia('CancelPage');
    }
}

