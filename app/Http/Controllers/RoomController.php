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

    // busca uma sala pelo ID
    public function retrieve($id)
    {
        $room = Room::find($id);

        if (! $room) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Room not found'
            ], 404);
        }

        $now = new \DateTime();
        $year = (int) $now->format('Y');
        $dayOfWeek = (int) $now->format('w');

        $weekdayNames = [
            0 => 'Domingo',
            1 => 'Segunda-feira',
            2 => 'Terça-feira',
            3 => 'Quarta-feira',
            4 => 'Quinta-feira',
            5 => 'Sexta-feira',
            6 => 'Sábado',
        ];

        $begginingWeek = (clone $now)->modify("-{$dayOfWeek} days");
        $lastOfWeek = (clone $now)->modify('saturday this week');

        $events = Event::where('room_id', $room['id'])
            ->where('year', $year)
            ->where('day', '>=', (int) $begginingWeek->format('z') + 1)
            ->where('day', '<=', (int) $lastOfWeek->format('z') + 1)
            ->get();

        $eventGrid = [];
        foreach ($events as $event) {
            $eventGrid[$event['day']][$event['pos']] = $event; 
        }

        $week = [];
        for ($i = 0; $i < 7; $i++) {
            $day = (int) $begginingWeek->format('z') + 1;
            
            $gridsWithEvents = [];
            foreach ($room['grids'] as $j => $grid) {
                if (! empty($eventGrid[$day][$j])) {
                    $grid['event'] = $eventGrid[$day][$j];
                }
                $gridsWithEvents[] = $grid;
            }

            $week[] = [
                'date' => $begginingWeek->format('Y-m-d'),
                'year' => $year,
                'dayYear' => $day,
                'title' => $weekdayNames[$begginingWeek->format('w')],
                'grids' => $gridsWithEvents
            ];

            $begginingWeek->modify('+1 day');
        }

        return response()->json([
            'status' => 'success',
            'room'   => $room,
            'calendar' => $week,
            'page' => 0
        ]);
    }
  
    public function create(Request $request)
    {
        $data = $request->only(['title', 'price', 'interval', 'starts_at', 'ends_at']);

        $item = Room::create($data);

        return response()->json($item);
    }
}