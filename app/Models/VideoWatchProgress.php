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
}
