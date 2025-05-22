<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Pusher\Pusher;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        return response()->json(['status' => 'success', 'items' => [], 'total' => 0, 'page' => 1]);
    }

    public function create(Request $request)
    {
        $data = $request->all();

        $order = [
            'id' => rand(1000, 9999),
            'total' => $data['total'] ?? 0,
            'customer' => $data['customer'] ?? '',
            'product' => $data['product'] ?? '',
            'address' => $data['address'] ?? '',
            'status' => 'pending'
        ];

        $pusher = new Pusher(
            env('PUSHER_APP_KEY'),
            env('PUSHER_APP_SECRET'),
            env('PUSHER_APP_ID'),
            [
                'cluster' => env('PUSHER_APP_CLUSTER'),
                'useTLS' => true
            ]
        );

        $pusher->trigger('orders-channel', 'new-order', $order);

        return response()->json(['status' => 'success', 'order' => $order]);
    }
}