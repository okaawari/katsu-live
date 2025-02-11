<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VideoWatchProgress extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'animes_id', 'current_time'];

    public function anime()
    {
        return $this->belongsTo(Anime::class, 'animes_id');
    }

    public function progressPercentage(): float
    {
        // Add null safety checks
        if (!$this->anime || !$this->anime->duration) {
            return 0.0;
        }

        $totalSeconds = $this->anime->duration * 60;
        return round(($this->current_time / $totalSeconds) * 100, 2);
    }

}
