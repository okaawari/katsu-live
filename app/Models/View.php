<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class View extends Model
{
    use HasFactory;

    protected $fillable = [
        'viewable_type',
        'viewable_id',
        'user_id',
        'visitor_id',
        'ip_address',
        'country',
        'device_type',
        'duration_seconds',
        'viewed_at',
    ];

    protected $casts = [
        'duration_seconds' => 'integer',
        'viewed_at' => 'datetime',
    ];

    protected $dates = [
        'viewed_at',
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
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeByVisitor($query, $visitorId)
    {
        return $query->where('visitor_id', $visitorId);
    }

    public function scopeByDeviceType($query, $deviceType)
    {
        return $query->where('device_type', $deviceType);
    }

    public function scopeByCountry($query, $country)
    {
        return $query->where('country', $country);
    }

    public function scopeToday($query)
    {
        return $query->whereDate('viewed_at', today());
    }

    public function scopeThisWeek($query)
    {
        return $query->whereBetween('viewed_at', [now()->startOfWeek(), now()->endOfWeek()]);
    }

    public function scopeThisMonth($query)
    {
        return $query->whereBetween('viewed_at', [now()->startOfMonth(), now()->endOfMonth()]);
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('viewed_at', [$startDate, $endDate]);
    }

    // Methods
    public static function recordView($viewable, $userId = null, $visitorId = null, $data = [])
    {
        return static::create([
            'viewable_type' => get_class($viewable),
            'viewable_id' => $viewable->id,
            'user_id' => $userId,
            'visitor_id' => $visitorId,
            'ip_address' => $data['ip_address'] ?? request()->ip(),
            'country' => $data['country'] ?? null,
            'device_type' => $data['device_type'] ?? static::detectDeviceType(),
            'duration_seconds' => $data['duration_seconds'] ?? null,
            'viewed_at' => $data['viewed_at'] ?? now(),
        ]);
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

    public function getFormattedDurationAttribute()
    {
        if (!$this->duration_seconds) return null;
        
        $hours = floor($this->duration_seconds / 3600);
        $minutes = floor(($this->duration_seconds % 3600) / 60);
        $seconds = $this->duration_seconds % 60;
        
        if ($hours > 0) {
            return sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
        }
        
        return sprintf('%02d:%02d', $minutes, $seconds);
    }

    public function isAnonymous()
    {
        return $this->user_id === null;
    }

    public function isAuthenticated()
    {
        return $this->user_id !== null;
    }
}
