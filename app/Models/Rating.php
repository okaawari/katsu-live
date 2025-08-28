<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Rating extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'ratable_type',
        'ratable_id',
        'rating',
        'review',
        'criteria_ratings',
        'helpful_count',
        'unhelpful_count',
        'status',
        'moderation_reason',
        'moderated_by',
        'is_spoiler',
        'is_recommended',
        'tags',
    ];

    protected $casts = [
        'rating' => 'decimal:2',
        'criteria_ratings' => 'array',
        'helpful_count' => 'integer',
        'unhelpful_count' => 'integer',
        'is_spoiler' => 'boolean',
        'is_recommended' => 'boolean',
        'tags' => 'array',
        'moderated_at' => 'datetime',
    ];

    protected $dates = [
        'moderated_at',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    // Relationships
    public function ratable(): MorphTo
    {
        return $this->morphTo();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function moderator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'moderated_by');
    }

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeHidden($query)
    {
        return $query->where('status', 'hidden');
    }

    public function scopeFlagged($query)
    {
        return $query->where('status', 'flagged');
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeByRating($query, $minRating, $maxRating = null)
    {
        if ($maxRating) {
            return $query->whereBetween('rating', [$minRating, $maxRating]);
        }
        return $query->where('rating', '>=', $minRating);
    }

    public function scopeRecommended($query)
    {
        return $query->where('is_recommended', true);
    }

    public function scopeNotRecommended($query)
    {
        return $query->where('is_recommended', false);
    }

    public function scopeSpoilerFree($query)
    {
        return $query->where('is_spoiler', false);
    }

    public function scopeWithSpoilers($query)
    {
        return $query->where('is_spoiler', true);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    // Accessors
    public function getFormattedRatingAttribute()
    {
        return number_format($this->rating, 1);
    }

    public function getHelpfulPercentageAttribute()
    {
        $total = $this->helpful_count + $this->unhelpful_count;
        if ($total === 0) return 0;
        return round(($this->helpful_count / $total) * 100);
    }

    public function getIsHelpfulAttribute()
    {
        return $this->helpful_count > $this->unhelpful_count;
    }

    public function getRecommendationTextAttribute()
    {
        if ($this->is_recommended === null) return null;
        return $this->is_recommended ? 'Recommended' : 'Not Recommended';
    }

    // Methods
    public function markAsHelpful()
    {
        $this->increment('helpful_count');
    }

    public function markAsUnhelpful()
    {
        $this->increment('unhelpful_count');
    }

    public function moderate($status, $reason = null, $moderatorId = null)
    {
        $this->status = $status;
        $this->moderation_reason = $reason;
        $this->moderated_by = $moderatorId;
        $this->moderated_at = now();
        $this->save();
    }

    public function isPublished()
    {
        return $this->status === 'published';
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isHidden()
    {
        return $this->status === 'hidden';
    }

    public function isFlagged()
    {
        return $this->status === 'flagged';
    }

    public function canBeEditedBy(User $user)
    {
        return $this->user_id === $user->id && $this->isPublished();
    }

    public function canBeModeratedBy(User $user)
    {
        return $user->hasRole(['admin', 'moderator']);
    }

    public function getAverageCriteriaRating()
    {
        if (!$this->criteria_ratings || !is_array($this->criteria_ratings)) {
            return null;
        }

        $ratings = array_filter($this->criteria_ratings, 'is_numeric');
        return count($ratings) > 0 ? array_sum($ratings) / count($ratings) : null;
    }
}
