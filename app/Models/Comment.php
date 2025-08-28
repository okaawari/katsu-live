<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Comment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'parent_id',
        'commentable_type',
        'commentable_id',
        'content',
        'content_html',
        'status',
        'moderation_reason',
        'moderated_by',
        'moderated_at',
        'likes_count',
        'dislikes_count',
        'replies_count',
        'ip_address',
        'user_agent',
        'is_edited',
        'edited_at',
        'published_at',
    ];

    protected $casts = [
        'likes_count' => 'integer',
        'dislikes_count' => 'integer',
        'replies_count' => 'integer',
        'is_edited' => 'boolean',
        'moderated_at' => 'datetime',
        'edited_at' => 'datetime',
        'published_at' => 'datetime',
    ];

    protected $dates = [
        'moderated_at',
        'edited_at',
        'published_at',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    // Relationships
    public function commentable(): MorphTo
    {
        return $this->morphTo();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    public function replies(): HasMany
    {
        return $this->hasMany(Comment::class, 'parent_id');
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

    public function scopeSpam($query)
    {
        return $query->where('status', 'spam');
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeTopLevel($query)
    {
        return $query->whereNull('parent_id');
    }

    public function scopeReplies($query)
    {
        return $query->whereNotNull('parent_id');
    }

    public function scopeRecent($query, $days = 7)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    public function scopePopular($query)
    {
        return $query->orderBy('likes_count', 'desc');
    }

    // Accessors
    public function getFormattedContentAttribute()
    {
        return $this->content_html ?: $this->content;
    }

    public function getLikePercentageAttribute()
    {
        $total = $this->likes_count + $this->dislikes_count;
        if ($total === 0) return 0;
        return round(($this->likes_count / $total) * 100);
    }

    public function getIsLikedAttribute()
    {
        return $this->likes_count > $this->dislikes_count;
    }

    public function getIsTopLevelAttribute()
    {
        return $this->parent_id === null;
    }

    public function getIsReplyAttribute()
    {
        return $this->parent_id !== null;
    }

    public function getDepthAttribute()
    {
        $depth = 0;
        $comment = $this;
        
        while ($comment->parent) {
            $depth++;
            $comment = $comment->parent;
            
            // Prevent infinite loops
            if ($depth > 10) break;
        }
        
        return $depth;
    }

    // Methods
    public function like()
    {
        $this->increment('likes_count');
    }

    public function dislike()
    {
        $this->increment('dislikes_count');
    }

    public function moderate($status, $reason = null, $moderatorId = null)
    {
        $this->status = $status;
        $this->moderation_reason = $reason;
        $this->moderated_by = $moderatorId;
        $this->moderated_at = now();
        $this->save();
    }

    public function updateContent($content, $contentHtml = null)
    {
        $this->content = $content;
        $this->content_html = $contentHtml;
        $this->is_edited = true;
        $this->edited_at = now();
        $this->save();
    }

    public function incrementRepliesCount()
    {
        $this->increment('replies_count');
    }

    public function decrementRepliesCount()
    {
        $this->decrement('replies_count');
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

    public function isSpam()
    {
        return $this->status === 'spam';
    }

    public function canBeEditedBy(User $user)
    {
        return $this->user_id === $user->id && $this->isPublished();
    }

    public function canBeModeratedBy(User $user)
    {
        return $user->hasRole(['admin', 'moderator']);
    }

    public function canBeRepliedTo()
    {
        return $this->isPublished() && $this->depth < 5; // Limit nesting depth
    }

    public function getAncestors()
    {
        $ancestors = collect();
        $comment = $this->parent;
        
        while ($comment) {
            $ancestors->push($comment);
            $comment = $comment->parent;
            
            // Prevent infinite loops
            if ($ancestors->count() > 10) break;
        }
        
        return $ancestors->reverse();
    }

    public function getAllReplies()
    {
        return $this->replies()->with('user')->orderBy('created_at', 'asc')->get();
    }
}
