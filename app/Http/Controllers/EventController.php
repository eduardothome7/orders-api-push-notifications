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

        try {
            $event = Event::createWithRoom($data);
            
            return response()->json($event);

        } catch (\Exception $e) {

            return response()->json([
                'error' => 'Erro ao criar evento',
                'message' => $e->getMessage(),
            ], 409);
        }
    }

    public function delete($id)
    {
        $event = Event::find($id);

        if (!$event) {
            return response()->json(['error' => 'Evento não encontrado'], 404);
        }

        $event->delete();

        return response()->json(['message' => 'Evento deletado com sucesso'], 200);
    }
}