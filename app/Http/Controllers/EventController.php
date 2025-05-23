<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Pusher\Pusher;
use App\Models\Event;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $items = Event::all();
        return response()->json(['items' => $items, 'total' => count($items), 'page' => 1]);
    }

    public function create(Request $request)
    {
        $data = $request->only(['room_id', 'pos', 'day', 'year']);

        $items = Event::createWithRoom($data);

        if (count($items) <> count($data['pos'])) {
            return response()->json([
                'status'  => 'error',
                'items' => $items,
                'message' => "Um dos agendamentos não pode ser realizado. Revise e envie novamente"
            ], 422);
        }

        return response()->json($items);
    }
}