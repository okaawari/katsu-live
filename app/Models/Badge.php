<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Badge extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'icon',
        'color',
        'background_color',
        'border_color',
        'image',
        'tier',
        'points',
        'order',
        'requirements',
        'metadata',
        'is_active',
        'is_visible',
        'is_revokable',
        'is_stackable',
        'is_automatic',
        'rarity',
        'available_from',
        'available_until',
        'max_recipients',
    ];

    protected $casts = [
        'requirements' => 'array',
        'metadata' => 'array',
        'is_active' => 'boolean',
        'is_visible' => 'boolean',
        'is_revokable' => 'boolean',
        'is_stackable' => 'boolean',
        'is_automatic' => 'boolean',
        'points' => 'integer',
        'order' => 'integer',
        'max_recipients' => 'integer',
        'available_from' => 'datetime',
        'available_until' => 'datetime',
    ];

    protected $dates = [
        'available_from',
        'available_until',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    // Relationships
    public function userBadges(): HasMany
    {
        return $this->hasMany(UserBadge::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_badges')
                    ->withPivot(['awarded_at', 'reason', 'context', 'is_visible', 'is_featured'])
                    ->withTimestamps();
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeVisible($query)
    {
        return $query->where('is_visible', true);
    }

    public function scopeByTier($query, $tier)
    {
        return $query->where('tier', $tier);
    }

    public function scopeByRarity($query, $rarity)
    {
        return $query->where('rarity', $rarity);
    }

    public function scopeAutomatic($query)
    {
        return $query->where('is_automatic', true);
    }

    public function scopeManual($query)
    {
        return $query->where('is_automatic', false);
    }

    public function scopeAvailable($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('available_from')
              ->orWhere('available_from', '<=', now());
        })->where(function ($q) {
            $q->whereNull('available_until')
              ->orWhere('available_until', '>=', now());
        });
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order', 'asc');
    }

    // Accessors
    public function getFormattedPointsAttribute()
    {
        return number_format($this->points);
    }

    public function getTierColorAttribute()
    {
        $colors = [
            'bronze' => '#CD7F32',
            'silver' => '#C0C0C0',
            'gold' => '#FFD700',
            'platinum' => '#E5E4E2',
            'diamond' => '#B9F2FF',
            'special' => '#FF6B6B',
        ];

        return $colors[$this->tier] ?? '#3B82F6';
    }

    public function getRarityColorAttribute()
    {
        $colors = [
            'common' => '#6B7280',
            'uncommon' => '#10B981',
            'rare' => '#3B82F6',
            'epic' => '#8B5CF6',
            'legendary' => '#F59E0B',
        ];

        return $colors[$this->rarity] ?? '#6B7280';
    }

    public function getIsAvailableAttribute()
    {
        $now = now();
        
        if ($this->available_from && $this->available_from > $now) {
            return false;
        }
        
        if ($this->available_until && $this->available_until < $now) {
            return false;
        }
        
        return $this->is_active;
    }

    public function getRecipientCountAttribute()
    {
        return $this->userBadges()->count();
    }

    public function getIsLimitedAttribute()
    {
        return $this->max_recipients !== null;
    }

    public function getIsFullAttribute()
    {
        if (!$this->is_limited) return false;
        return $this->recipient_count >= $this->max_recipients;
    }

    // Methods
    public function awardToUser(User $user, $reason = null, $context = null, $awardedBy = null)
    {
        // Check if badge is available
        if (!$this->is_available) {
            throw new \Exception('Badge is not available for awarding');
        }

        // Check if user already has this badge (unless stackable)
        if (!$this->is_stackable && $this->users()->where('user_id', $user->id)->exists()) {
            throw new \Exception('User already has this badge');
        }

        // Check if badge is full (limited edition)
        if ($this->is_full) {
            throw new \Exception('Badge has reached maximum recipients');
        }

        return $this->userBadges()->create([
            'user_id' => $user->id,
            'awarded_by' => $awardedBy,
            'reason' => $reason,
            'context' => $context,
            'awarded_at' => now(),
        ]);
    }

    public function revokeFromUser(User $user, $reason = null, $revokedBy = null)
    {
        if (!$this->is_revokable) {
            throw new \Exception('This badge cannot be revoked');
        }

        $userBadge = $this->userBadges()->where('user_id', $user->id)->first();
        
        if (!$userBadge) {
            throw new \Exception('User does not have this badge');
        }

        $userBadge->update([
            'revoked_at' => now(),
            'revoke_reason' => $reason,
        ]);

        return $userBadge;
    }

    public function checkRequirements(User $user)
    {
        if (!$this->requirements) return true;

        // This is a basic implementation - you can extend this based on your requirements
        foreach ($this->requirements as $requirement) {
            if (!$this->checkRequirement($user, $requirement)) {
                return false;
            }
        }

        return true;
    }

    private function checkRequirement(User $user, $requirement)
    {
        $type = $requirement['type'] ?? null;
        $value = $requirement['value'] ?? null;

        switch ($type) {
            case 'episodes_watched':
                return $user->watchProgress()->count() >= $value;
            case 'comments_made':
                return $user->comments()->count() >= $value;
            case 'ratings_given':
                return $user->ratings()->count() >= $value;
            case 'days_registered':
                return $user->created_at->diffInDays(now()) >= $value;
            default:
                return false;
        }
    }

    public function getProgressForUser(User $user)
    {
        if (!$this->requirements) return null;

        $totalRequirements = count($this->requirements);
        $completedRequirements = 0;

        foreach ($this->requirements as $requirement) {
            if ($this->checkRequirement($user, $requirement)) {
                $completedRequirements++;
            }
        }

        return [
            'current' => $completedRequirements,
            'target' => $totalRequirements,
            'percentage' => $totalRequirements > 0 ? ($completedRequirements / $totalRequirements) * 100 : 0,
        ];
    }
}
