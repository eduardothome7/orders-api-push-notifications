<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = ['room_id', 'pos', 'day', 'year', 'total', 'start_at', 'end_at'];

    public static function createWithRoom(array $data)
    {
        $room = Room::findOrFail($data['room_id']);

        $events = [];
        $grids = $room->grids;

        foreach ($data['pos'] as $i) {
            $exists = self::where('room_id', $data['room_id'])
                ->where('day', $data['day'])
                ->where('pos', $i)
                ->where('year', $data['year'])
                ->exists();
        
            if ($exists) {
               continue;
            }

            foreach ($grids as $j => $grid) {
                if ($i == $j) {
                    $data['pos'] = $i;
                    $data['start_at'] = $grid['stars'];
                    $data['end_at'] = $grid['ends'];
                    $data['total'] = $room['price'] * $room['interval'];

                    $events[] = self::create($data);
                }
            }
        }

        return $events;
    }
}