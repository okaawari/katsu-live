<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VideoWatchProgress extends Model
{
    use HasFactory;

    protected $table = 'video_watch_progress';

    protected $fillable = [
        'user_id',
        'animes_id',
        'episode_id',
        'current_time',
        'duration',
        'progress_percentage',
        'is_completed',
        'is_skipped',
        'watch_count',
        'quality_watched',
        'subtitle_language',
        'playback_speed',
        'device_type',
        'platform',
        'ip_address',
        'user_agent',
        'started_at',
        'completed_at',
        'last_position_update',
    ];

    protected $casts = [
        'current_time' => 'decimal:2',
        'duration' => 'decimal:2',
        'progress_percentage' => 'decimal:2',
        'is_completed' => 'boolean',
        'is_skipped' => 'boolean',
        'watch_count' => 'integer',
        'playback_speed' => 'decimal:2',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'last_position_update' => 'datetime',
    ];

    protected $dates = [
        'started_at',
        'completed_at',
        'last_position_update',
        'created_at',
        'updated_at',
    ];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function anime(): BelongsTo
    {
        return $this->belongsTo(Anime::class, 'animes_id');
    }

    public function episode(): BelongsTo
    {
        return $this->belongsTo(Episode::class);
    }

    // Scopes
    public function scopeCompleted($query)
    {
        return $query->where('is_completed', true);
    }

    public function scopeInProgress($query)
    {
        return $query->where('is_completed', false);
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeByEpisode($query, $episodeId)
    {
        return $query->where('episode_id', $episodeId);
    }

    // Accessors
    public function getFormattedCurrentTimeAttribute()
    {
        return $this->formatTime($this->current_time);
    }

    public function getFormattedDurationAttribute()
    {
        return $this->formatTime($this->duration);
    }

    public function getFormattedProgressAttribute()
    {
        return number_format($this->progress_percentage, 1) . '%';
    }

    // Methods
    public function progressPercentage()
    {
        if ($this->duration <= 0) {
            return 0;
        }
        
        return min(100, ($this->current_time / $this->duration) * 100);
    }

    public function updateProgress($currentTime, $duration = null)
    {
        $this->current_time = $currentTime;
        
        if ($duration) {
            $this->duration = $duration;
        }
        
        if ($this->duration > 0) {
            $this->progress_percentage = min(100, ($currentTime / $this->duration) * 100);
        }
        
        $this->last_position_update = now();
        
        // Mark as completed if progress is 90% or more
        if ($this->progress_percentage >= 90) {
            $this->is_completed = true;
            $this->completed_at = now();
        }
        
        $this->save();
    }

    public function markAsCompleted()
    {
        $this->is_completed = true;
        $this->completed_at = now();
        $this->progress_percentage = 100;
        $this->save();
    }

    public function markAsSkipped()
    {
        $this->is_skipped = true;
        $this->is_completed = true;
        $this->completed_at = now();
        $this->save();
    }

    public function incrementWatchCount()
    {
        $this->increment('watch_count');
    }

    private function formatTime($seconds)
    {
        if (!$seconds) return '00:00';
        
        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds % 3600) / 60);
        $secs = floor($seconds % 60);
        
        if ($hours > 0) {
            return sprintf('%02d:%02d:%02d', $hours, $minutes, $secs);
        }
        
        return sprintf('%02d:%02d', $minutes, $secs);
    }
}
