<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EpisodeList extends Model
{
    use HasFactory;

    protected $table = 'episode_lists';

    protected $fillable = [
        'user_id',
        'episode_id',
        'status',
        'watch_count',
        'started_at',
        'completed_at',
        'last_watched_at',
        'user_rating',
        'review',
        'notes',
        'is_favorite',
        'is_private',
        'custom_tags',
        'priority',
    ];

    protected $casts = [
        'watch_count' => 'integer',
        'user_rating' => 'decimal:2',
        'is_favorite' => 'boolean',
        'is_private' => 'boolean',
        'custom_tags' => 'array',
        'priority' => 'integer',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'last_watched_at' => 'datetime',
    ];

    protected $dates = [
        'started_at',
        'completed_at',
        'last_watched_at',
        'created_at',
        'updated_at',
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

    // Scopes
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeByEpisode($query, $episodeId)
    {
        return $query->where('episode_id', $episodeId);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopePlanToWatch($query)
    {
        return $query->where('status', 'plan_to_watch');
    }

    public function scopeWatching($query)
    {
        return $query->where('status', 'watching');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeOnHold($query)
    {
        return $query->where('status', 'on_hold');
    }

    public function scopeDropped($query)
    {
        return $query->where('status', 'dropped');
    }

    public function scopeFavorites($query)
    {
        return $query->where('is_favorite', true);
    }

    public function scopePublic($query)
    {
        return $query->where('is_private', false);
    }

    public function scopePrivate($query)
    {
        return $query->where('is_private', true);
    }

    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    public function scopeHighPriority($query)
    {
        return $query->where('priority', '>', 0);
    }

    public function scopeRecentlyWatched($query, $days = 30)
    {
        return $query->where('last_watched_at', '>=', now()->subDays($days));
    }

    public function scopeByRating($query, $minRating, $maxRating = null)
    {
        if ($maxRating) {
            return $query->whereBetween('user_rating', [$minRating, $maxRating]);
        }
        return $query->where('user_rating', '>=', $minRating);
    }

    // Accessors
    public function getFormattedRatingAttribute()
    {
        return $this->user_rating ? number_format($this->user_rating, 1) : null;
    }

    public function getStatusTextAttribute()
    {
        $statuses = [
            'plan_to_watch' => 'Plan to Watch',
            'watching' => 'Watching',
            'completed' => 'Completed',
            'on_hold' => 'On Hold',
            'dropped' => 'Dropped',
        ];

        return $statuses[$this->status] ?? ucfirst($this->status);
    }

    public function getStatusColorAttribute()
    {
        $colors = [
            'plan_to_watch' => '#6B7280',
            'watching' => '#3B82F6',
            'completed' => '#10B981',
            'on_hold' => '#F59E0B',
            'dropped' => '#EF4444',
        ];

        return $colors[$this->status] ?? '#6B7280';
    }

    public function getIsCompletedAttribute()
    {
        return $this->status === 'completed';
    }

    public function getIsWatchingAttribute()
    {
        return $this->status === 'watching';
    }

    public function getIsPlanToWatchAttribute()
    {
        return $this->status === 'plan_to_watch';
    }

    public function getIsOnHoldAttribute()
    {
        return $this->status === 'on_hold';
    }

    public function getIsDroppedAttribute()
    {
        return $this->status === 'dropped';
    }

    public function getTimeSinceLastWatchedAttribute()
    {
        return $this->last_watched_at ? $this->last_watched_at->diffForHumans() : null;
    }

    public function getWatchTimeAttribute()
    {
        if (!$this->started_at || !$this->completed_at) return null;
        return $this->started_at->diffInDays($this->completed_at);
    }

    // Methods
    public function updateStatus($status)
    {
        $this->status = $status;
        
        if ($status === 'watching' && !$this->started_at) {
            $this->started_at = now();
        }
        
        if ($status === 'completed' && !$this->completed_at) {
            $this->completed_at = now();
        }
        
        $this->last_watched_at = now();
        $this->save();
    }

    public function markAsWatched()
    {
        $this->updateStatus('completed');
        $this->incrementWatchCount();
    }

    public function markAsWatching()
    {
        $this->updateStatus('watching');
    }

    public function markAsPlanToWatch()
    {
        $this->updateStatus('plan_to_watch');
    }

    public function markAsOnHold()
    {
        $this->updateStatus('on_hold');
    }

    public function markAsDropped()
    {
        $this->updateStatus('dropped');
    }

    public function incrementWatchCount()
    {
        $this->increment('watch_count');
        $this->last_watched_at = now();
        $this->save();
    }

    public function toggleFavorite()
    {
        $this->update(['is_favorite' => !$this->is_favorite]);
    }

    public function togglePrivacy()
    {
        $this->update(['is_private' => !$this->is_private]);
    }

    public function addCustomTag($tag)
    {
        $tags = $this->custom_tags ?? [];
        if (!in_array($tag, $tags)) {
            $tags[] = $tag;
            $this->update(['custom_tags' => $tags]);
        }
    }

    public function removeCustomTag($tag)
    {
        $tags = $this->custom_tags ?? [];
        $tags = array_filter($tags, function($t) use ($tag) {
            return $t !== $tag;
        });
        $this->update(['custom_tags' => array_values($tags)]);
    }

    public function hasCustomTag($tag)
    {
        $tags = $this->custom_tags ?? [];
        return in_array($tag, $tags);
    }

    public function setPriority($priority)
    {
        $this->update(['priority' => max(0, min(10, $priority))]);
    }

    public function getPriorityText()
    {
        if ($this->priority === 0) return 'Normal';
        if ($this->priority <= 3) return 'Low';
        if ($this->priority <= 7) return 'Medium';
        return 'High';
    }
}
