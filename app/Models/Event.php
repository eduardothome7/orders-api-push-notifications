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

        $exists = self::where('room_id', $data['room_id'])
            ->where('day', $data['day'])
            ->where('pos', $data['pos'])
            ->where('year', $data['year'])
            ->exists();

        if ($exists) {
            throw new \Exception('Já existe um agendamento no horário selecionado.');
        }

        foreach ($grids as $j => $grid) {
            if ($data['pos'] == $j) {
                
                if (self::preventPastEvent([
                    'day' => $data['day'],
                    'year' => $data['year'],
                    'start_at' => $grid['start_at']
                ])) {
                    throw new \Exception('Data/Hora de agendado inválida.');
                }
                $data['start_at'] = $grid['start_at'];
                $data['end_at'] = $grid['end_at'];
                $data['total'] = $room['price'] * $room['interval'];
            }
        }

        return self::create($data);
    }

    protected static function preventPastEvent($dataEvent)
    {
        $now = new \DateTime();
        
        $dayOfYear = $dataEvent['day'];
        $year = $dataEvent['year'];
        $startAt = $dataEvent['start_at'];

        $date = new \DateTime("$year-01-01");
        $date->modify("+". ($dayOfYear - 1) ." days");
        $date->setTime(substr($startAt, 0, 2), substr($startAt, 3, 2));

        return $date < $now;
    }
}