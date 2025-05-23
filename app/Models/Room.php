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

        while ($start + $interval <= $end) {
            $startTime = date('H:i', $start);
            $endTime = date('H:i', $start + $interval);
            $grids[] = [
                'stars' => $startTime, 
                'ends' => $endTime
            ];
            $start += $interval;
        }

        return $grids;
    }
}