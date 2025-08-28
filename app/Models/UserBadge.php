<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserBadge extends Model
{
    use HasFactory;

    protected $table = 'user_badges';

    protected $fillable = [
        'user_id',
        'badge_id',
        'awarded_by',
        'reason',
        'context',
        'awarded_at',
        'revoked_at',
        'revoke_reason',
        'is_visible',
        'is_featured',
        'display_order',
        'progress_current',
        'progress_target',
        'progress_percentage',
    ];

    protected $casts = [
        'context' => 'array',
        'awarded_at' => 'datetime',
        'revoked_at' => 'datetime',
        'is_visible' => 'boolean',
        'is_featured' => 'boolean',
        'display_order' => 'integer',
        'progress_current' => 'integer',
        'progress_target' => 'integer',
        'progress_percentage' => 'decimal:2',
    ];

    protected $dates = [
        'awarded_at',
        'revoked_at',
        'created_at',
        'updated_at',
    ];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function badge(): BelongsTo
    {
        return $this->belongsTo(Badge::class);
    }

    public function awardedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'awarded_by');
    }

    // Scopes
    public function scopeVisible($query)
    {
        return $query->where('is_visible', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeActive($query)
    {
        return $query->whereNull('revoked_at');
    }

    public function scopeRevoked($query)
    {
        return $query->whereNotNull('revoked_at');
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeByBadge($query, $badgeId)
    {
        return $query->where('badge_id', $badgeId);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('display_order', 'asc')
                    ->orderBy('awarded_at', 'desc');
    }

    public function scopeRecent($query, $days = 30)
    {
        return $query->where('awarded_at', '>=', now()->subDays($days));
    }

    // Accessors
    public function getIsActiveAttribute()
    {
        return $this->revoked_at === null;
    }

    public function getIsRevokedAttribute()
    {
        return $this->revoked_at !== null;
    }

    public function getFormattedProgressAttribute()
    {
        if ($this->progress_target <= 0) return '0%';
        return number_format($this->progress_percentage, 1) . '%';
    }

    public function getProgressRatioAttribute()
    {
        if ($this->progress_target <= 0) return 0;
        return min(1, $this->progress_current / $this->progress_target);
    }

    public function getTimeSinceAwardedAttribute()
    {
        return $this->awarded_at ? $this->awarded_at->diffForHumans() : null;
    }

    public function getTimeSinceRevokedAttribute()
    {
        return $this->revoked_at ? $this->revoked_at->diffForHumans() : null;
    }

    // Methods
    public function revoke($reason = null, $revokedBy = null)
    {
        $this->update([
            'revoked_at' => now(),
            'revoke_reason' => $reason,
        ]);
    }

    public function restore()
    {
        $this->update([
            'revoked_at' => null,
            'revoke_reason' => null,
        ]);
    }

    public function toggleVisibility()
    {
        $this->update(['is_visible' => !$this->is_visible]);
    }

    public function toggleFeatured()
    {
        $this->update(['is_featured' => !$this->is_featured]);
    }

    public function updateProgress($current, $target = null)
    {
        $this->progress_current = $current;
        
        if ($target) {
            $this->progress_target = $target;
        }
        
        if ($this->progress_target > 0) {
            $this->progress_percentage = min(100, ($current / $this->progress_target) * 100);
        }
        
        $this->save();
    }

    public function incrementProgress($amount = 1)
    {
        $this->progress_current += $amount;
        
        if ($this->progress_target > 0) {
            $this->progress_percentage = min(100, ($this->progress_current / $this->progress_target) * 100);
        }
        
        $this->save();
    }

    public function isCompleted()
    {
        return $this->progress_current >= $this->progress_target;
    }

    public function getProgressStatus()
    {
        if ($this->progress_target <= 0) return 'no_target';
        
        $percentage = ($this->progress_current / $this->progress_target) * 100;
        
        if ($percentage >= 100) return 'completed';
        if ($percentage >= 75) return 'near_complete';
        if ($percentage >= 50) return 'halfway';
        if ($percentage >= 25) return 'quarter';
        return 'just_started';
    }
}
