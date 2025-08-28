<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ScheduledPublication extends Model
{
    use HasFactory;

    protected $table = 'scheduled_publications';

    protected $fillable = [
        'publishable_type',
        'publishable_id',
        'scheduled_by',
        'scheduled_for',
        'status',
        'published_at',
        'visibility',
        'notify_subscribers',
        'send_notifications',
        'failure_reason',
        'retry_count',
        'next_retry_at',
        'publication_settings',
        'notes',
    ];

    protected $casts = [
        'scheduled_for' => 'datetime',
        'published_at' => 'datetime',
        'next_retry_at' => 'datetime',
        'notify_subscribers' => 'boolean',
        'send_notifications' => 'boolean',
        'retry_count' => 'integer',
        'publication_settings' => 'array',
    ];

    protected $dates = [
        'scheduled_for',
        'published_at',
        'next_retry_at',
        'created_at',
        'updated_at',
    ];

    // Relationships
    public function publishable(): MorphTo
    {
        return $this->morphTo();
    }

    public function scheduledBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'scheduled_by');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeDueForPublication($query)
    {
        return $query->where('status', 'pending')
                    ->where('scheduled_for', '<=', now());
    }

    public function scopeUpcoming($query, $hours = 24)
    {
        return $query->where('status', 'pending')
                    ->where('scheduled_for', '>', now())
                    ->where('scheduled_for', '<=', now()->addHours($hours));
    }

    public function scopeByScheduledBy($query, $userId)
    {
        return $query->where('scheduled_by', $userId);
    }

    public function scopeByVisibility($query, $visibility)
    {
        return $query->where('visibility', $visibility);
    }

    public function scopeWithNotifications($query)
    {
        return $query->where('notify_subscribers', true)
                    ->orWhere('send_notifications', true);
    }

    // Accessors
    public function getIsPendingAttribute()
    {
        return $this->status === 'pending';
    }

    public function getIsPublishedAttribute()
    {
        return $this->status === 'published';
    }

    public function getIsFailedAttribute()
    {
        return $this->status === 'failed';
    }

    public function getIsCancelledAttribute()
    {
        return $this->status === 'cancelled';
    }

    public function getIsOverdueAttribute()
    {
        return $this->is_pending && $this->scheduled_for < now();
    }

    public function getTimeUntilPublicationAttribute()
    {
        if (!$this->is_pending) return null;
        return $this->scheduled_for->diffForHumans();
    }

    public function getFormattedScheduledForAttribute()
    {
        return $this->scheduled_for ? $this->scheduled_for->format('Y-m-d H:i:s') : null;
    }

    public function getFormattedPublishedAtAttribute()
    {
        return $this->published_at ? $this->published_at->format('Y-m-d H:i:s') : null;
    }

    public function getRetryStatusAttribute()
    {
        if ($this->retry_count === 0) return 'no_retries';
        if ($this->retry_count >= 3) return 'max_retries_reached';
        return 'retries_remaining';
    }

    // Methods
    public function markAsPublished()
    {
        $this->update([
            'status' => 'published',
            'published_at' => now(),
        ]);
    }

    public function markAsFailed($reason = null)
    {
        $this->update([
            'status' => 'failed',
            'failure_reason' => $reason,
            'retry_count' => $this->retry_count + 1,
        ]);
    }

    public function cancel($reason = null)
    {
        $this->update([
            'status' => 'cancelled',
            'failure_reason' => $reason,
        ]);
    }

    public function reschedule($newDateTime)
    {
        $this->update([
            'scheduled_for' => $newDateTime,
            'status' => 'pending',
            'failure_reason' => null,
            'retry_count' => 0,
            'next_retry_at' => null,
        ]);
    }

    public function setNextRetry($minutes = 5)
    {
        $this->update([
            'next_retry_at' => now()->addMinutes($minutes),
        ]);
    }

    public function canBeRetried()
    {
        return $this->is_failed && $this->retry_count < 3;
    }

    public function shouldRetry()
    {
        return $this->can_be_retried && 
               (!$this->next_retry_at || $this->next_retry_at <= now());
    }

    public function getPublicationSettings($key = null)
    {
        if (!$this->publication_settings) return null;
        
        if ($key) {
            return $this->publication_settings[$key] ?? null;
        }
        
        return $this->publication_settings;
    }

    public function setPublicationSettings($settings)
    {
        $this->update(['publication_settings' => $settings]);
    }

    public function getPublishableTitle()
    {
        if (!$this->publishable) return 'Unknown';
        
        // Try to get title from different possible attributes
        $publishable = $this->publishable;
        
        if (isset($publishable->title)) {
            return $publishable->title;
        }
        
        if (isset($publishable->name)) {
            return $publishable->name;
        }
        
        return class_basename($publishable) . ' #' . $publishable->id;
    }
}
