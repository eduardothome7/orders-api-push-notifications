<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $fillable = ['title', 'price', 'interval', 'starts_at', 'ends_at'];

    protected $appends = ['grids'];

    public function getGridsAttribute()
    {
        $grids = [];
        $start = strtotime($this->starts_at);
        $end = strtotime($this->ends_at);
        $interval = $this->interval * 3600;

        $today = new \DateTime();
        $dayOfWeek = (int) $today->format('w');

        $pos = 0;
        while ($start + $interval <= $end) {
            $startTime = date('H:i', $start);
            $endTime = date('H:i', $start + $interval);
            $grids[] = [
                'start_at' => $startTime, 
                'end_at' => $endTime,
                'days' => $this->getWeek($dayOfWeek, $pos),
                'dayNames' => $this->getDayNames()
            ];

            $pos++;
            $start += $interval;
        }

        return $grids;
    }

    protected function getWeek(int $dayOfWeek, int $pos)
    {
        $daysWeek = $this->getDayNames();
        $today = new \DateTime();
        $year = (int) $today->format('Y');
        $begginingWeek = (clone $today)->modify("-{$dayOfWeek} days");
        $lastOfWeek = (clone $today)->modify('saturday this week');

        $weekEvents = Event::where('room_id', $this->id)
            ->where('year', $year)
            ->where('day', '>=', (int) $begginingWeek->format('z') + 1)
            ->where('day', '<=', (int) $lastOfWeek->format('z') + 1)
            ->get();

        $eventGrid = [];
        foreach ($weekEvents as $event) {
            $eventGrid[$event['year']][$event['day']][$event['pos']] = $event; 
        }

        foreach ($daysWeek as $i => &$dayWeek) {
            $targetDate = clone $today;
            $currentDayOfWeek = (int) $today->format('w');

            $daysToAdd = ($i - $currentDayOfWeek);

            $targetDate->modify("+{$daysToAdd} days");
            $dayWeek['date'] = $targetDate->format('d/m');

            $currentDayOfYear = (int) $targetDate->format('z') + 1;

            $dayWeek['dayYear'] = $currentDayOfYear;
            $dayWeek['year'] = $year;
            $dayWeek['pos'] = $pos;

            unset($dayWeek['event']);
            if (! empty($eventGrid[$year][$currentDayOfYear][$pos])) {
                $dayWeek['event'] = $eventGrid[$year][$currentDayOfYear][$pos];
            }
        }

        $daysWeek[$dayOfWeek]['checked'] = true;

        return $daysWeek;
    }

    protected function getDayNames()
    {
        return [
            0 => ['title' => 'Domingo'],
            1 => ['title' => 'Segunda-feira'],
            2 => ['title' => 'Terça-feira'],
            3 => ['title' => 'Quarta-feira'],
            4 => ['title' => 'Quinta-feira'],
            5 => ['title' => 'Sexta-feira'],
            6 => ['title' => 'Sábado'],
        ];
    }
}