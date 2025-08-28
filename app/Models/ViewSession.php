<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ViewSession extends Model
{
    use HasFactory;

    protected $table = 'view_sessions';

    protected $fillable = [
        'viewable_type',
        'viewable_id',
        'session_id',
        'user_id',
        'ip_address',
        'country',
        'device_type',
        'duration_seconds',
        'started_at',
        'last_activity_at',
    ];

    protected $casts = [
        'duration_seconds' => 'integer',
        'started_at' => 'datetime',
        'last_activity_at' => 'datetime',
    ];

    protected $dates = [
        'started_at',
        'last_activity_at',
        'created_at',
        'updated_at',
    ];

    // Relationships
    public function viewable(): MorphTo
    {
        return $this->morphTo();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopeByViewable($query, $viewableType, $viewableId)
    {
        return $query->where('viewable_type', $viewableType)
                    ->where('viewable_id', $viewableId);
    }

    public function scopeBySession($query, $sessionId)
    {
        return $query->where('session_id', $sessionId);
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeByDeviceType($query, $deviceType)
    {
        return $query->where('device_type', $deviceType);
    }

    public function scopeByCountry($query, $country)
    {
        return $query->where('country', $country);
    }

    public function scopeActive($query, $minutes = 30)
    {
        return $query->where('last_activity_at', '>=', now()->subMinutes($minutes));
    }

    public function scopeInactive($query, $minutes = 30)
    {
        return $query->where('last_activity_at', '<', now()->subMinutes($minutes));
    }

    public function scopeLongSessions($query, $minutes = 10)
    {
        return $query->where('duration_seconds', '>=', $minutes * 60);
    }

    public function scopeShortSessions($query, $minutes = 2)
    {
        return $query->where('duration_seconds', '<=', $minutes * 60);
    }

    public function scopeToday($query)
    {
        return $query->whereDate('started_at', today());
    }

    public function scopeThisWeek($query)
    {
        return $query->whereBetween('started_at', [now()->startOfWeek(), now()->endOfWeek()]);
    }

    public function scopeThisMonth($query)
    {
        return $query->whereBetween('started_at', [now()->startOfMonth(), now()->endOfMonth()]);
    }

    public function scopeOrderedByDuration($query)
    {
        return $query->orderBy('duration_seconds', 'desc');
    }

    public function scopeOrderedByStartTime($query)
    {
        return $query->orderBy('started_at', 'desc');
    }

    // Accessors
    public function getFormattedDurationAttribute()
    {
        if (!$this->duration_seconds) return '0s';
        
        $hours = floor($this->duration_seconds / 3600);
        $minutes = floor(($this->duration_seconds % 3600) / 60);
        $seconds = $this->duration_seconds % 60;
        
        if ($hours > 0) {
            return sprintf('%dh %dm %ds', $hours, $minutes, $seconds);
        }
        
        if ($minutes > 0) {
            return sprintf('%dm %ds', $minutes, $seconds);
        }
        
        return sprintf('%ds', $seconds);
    }

    public function getDurationMinutesAttribute()
    {
        return round($this->duration_seconds / 60, 2);
    }

    public function getDurationHoursAttribute()
    {
        return round($this->duration_seconds / 3600, 2);
    }

    public function getIsActiveAttribute()
    {
        return $this->last_activity_at >= now()->subMinutes(30);
    }

    public function getIsLongSessionAttribute()
    {
        return $this->duration_seconds >= 600; // 10 minutes
    }

    public function getIsShortSessionAttribute()
    {
        return $this->duration_seconds <= 120; // 2 minutes
    }

    public function getSessionAgeAttribute()
    {
        return $this->started_at->diffForHumans();
    }

    public function getTimeSinceLastActivityAttribute()
    {
        return $this->last_activity_at->diffForHumans();
    }

    public function getIsAnonymousAttribute()
    {
        return $this->user_id === null;
    }

    public function getIsAuthenticatedAttribute()
    {
        return $this->user_id !== null;
    }

    // Methods
    public static function startSession($viewable, $sessionId, $userId = null, $data = [])
    {
        return static::create([
            'viewable_type' => get_class($viewable),
            'viewable_id' => $viewable->id,
            'session_id' => $sessionId,
            'user_id' => $userId,
            'ip_address' => $data['ip_address'] ?? request()->ip(),
            'country' => $data['country'] ?? null,
            'device_type' => $data['device_type'] ?? static::detectDeviceType(),
            'started_at' => now(),
            'last_activity_at' => now(),
        ]);
    }

    public function updateActivity()
    {
        $this->update([
            'last_activity_at' => now(),
            'duration_seconds' => $this->started_at->diffInSeconds(now()),
        ]);
    }

    public function endSession()
    {
        $this->update([
            'duration_seconds' => $this->started_at->diffInSeconds(now()),
        ]);
    }

    public function isExpired($minutes = 30)
    {
        return $this->last_activity_at < now()->subMinutes($minutes);
    }

    public function getEngagementScore()
    {
        // Simple engagement score based on session duration
        if ($this->duration_seconds < 30) return 1; // Very low
        if ($this->duration_seconds < 120) return 2; // Low
        if ($this->duration_seconds < 300) return 3; // Medium
        if ($this->duration_seconds < 600) return 4; // High
        return 5; // Very high
    }

    public function getEngagementLevel()
    {
        $score = $this->getEngagementScore();
        
        $levels = [
            1 => 'Very Low',
            2 => 'Low',
            3 => 'Medium',
            4 => 'High',
            5 => 'Very High',
        ];
        
        return $levels[$score] ?? 'Unknown';
    }

    private static function detectDeviceType()
    {
        $userAgent = request()->userAgent();
        
        if (preg_match('/(tablet|ipad|playbook)|(android(?!.*(mobi|opera mini)))/i', strtolower($userAgent))) {
            return 'tablet';
        }
        
        if (preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|android|iemobile)/i', strtolower($userAgent))) {
            return 'mobile';
        }
        
        return 'desktop';
    }

    public function getSessionMetrics()
    {
        return [
            'duration_formatted' => $this->formatted_duration,
            'duration_minutes' => $this->duration_minutes,
            'duration_hours' => $this->duration_hours,
            'engagement_score' => $this->getEngagementScore(),
            'engagement_level' => $this->getEngagementLevel(),
            'is_active' => $this->is_active,
            'is_long_session' => $this->is_long_session,
            'is_short_session' => $this->is_short_session,
            'session_age' => $this->session_age,
            'time_since_last_activity' => $this->time_since_last_activity,
        ];
    }
}
