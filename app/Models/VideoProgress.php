<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VideoProgress extends Model
{
    use HasFactory;

    protected $table = 'video_progress';

    protected $fillable = [
        'user_id',
        'episode_id',
        'current_time',
        'duration',
    ];

    protected $casts = [
        'current_time' => 'decimal:2',
        'duration' => 'decimal:2',
    ];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function episode(): BelongsTo
    {
        return $this->belongsTo(Episode::class);
    }

    // Methods
    public function getProgressPercentage(): float
    {
        if ($this->duration <= 0) {
            return 0;
        }
        
        return min(100, ($this->current_time / $this->duration) * 100);
    }

    public function progressPercentage(): float
    {
        return $this->getProgressPercentage();
    }

    public function isCompleted(): bool
    {
        return $this->getProgressPercentage() >= 90;
    }
}
