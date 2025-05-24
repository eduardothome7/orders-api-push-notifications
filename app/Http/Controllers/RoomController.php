<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Pusher\Pusher;
use App\Models\Room;
use App\Models\Event;

class RoomController extends Controller
{
    public function index(Request $request)
    {
        $items = Room::all();
        return response()->json(['items' => $items, 'total' => count($items), 'page' => 1]);
    }

    public function retrieve($id)
    {
        $room = Room::find($id);

        if (! $room) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Room not found'
            ], 404);
        }

        return response()->json($room);
    }
  
    public function create(Request $request)
    {
        $data = $request->only(['title', 'price', 'interval', 'starts_at', 'ends_at']);

        $item = Room::create($data);

        return response()->json($item);
    }
}