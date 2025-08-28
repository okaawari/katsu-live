<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Anime extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'animes';

    protected $fillable = [
        'category_id',
        'author_id',
        'title',
        'title_english',
        'title_japanese',
        'duration',
        'slug',
        'description',
        'status',
        'total_episodes',
        'current_episode',
        'cover_image',
        'banner_image',
        'average_rating',
        'rating_count',
        'view_count',
        'favorite_count',
        'visibility',
        'is_featured',
        'published_at',
        'meta_title',
        'meta_description',
        'meta_keywords',
    ];

    protected $casts = [
        'meta_keywords' => 'array',
        'average_rating' => 'decimal:2',
        'is_featured' => 'boolean',
        'published_at' => 'datetime',
        'total_episodes' => 'integer',
        'current_episode' => 'integer',
        'rating_count' => 'integer',
        'view_count' => 'integer',
        'favorite_count' => 'integer',
    ];

    protected $dates = [
        'published_at',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    // Relationships
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function episodes(): HasMany
    {
        return $this->hasMany(Episode::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'anime_tag');
    }

    public function views(): MorphMany
    {
        return $this->morphMany(View::class, 'viewable');
    }

    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function ratings(): MorphMany
    {
        return $this->morphMany(Rating::class, 'ratable');
    }

    public function scheduledPublication(): MorphOne
    {
        return $this->morphOne(ScheduledPublication::class, 'publishable');
    }

    public function viewAnalytics(): MorphMany
    {
        return $this->morphMany(ViewAnalytics::class, 'viewable');
    }

    public function viewSessions(): MorphMany
    {
        return $this->morphMany(ViewSession::class, 'viewable');
    }

    // Scopes
    public function scopePublic($query)
    {
        return $query->where('visibility', 'public');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopePublished($query)
    {
        return $query->whereNotNull('published_at');
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    // Accessors
    public function getCoverImageUrlAttribute()
    {
        return $this->cover_image ? asset('storage/' . $this->cover_image) : null;
    }

    public function getBannerImageUrlAttribute()
    {
        return $this->banner_image ? asset('storage/' . $this->banner_image) : null;
    }

    public function getFormattedStatusAttribute()
    {
        return ucfirst($this->status ?? 'unknown');
    }

    public function getProgressPercentageAttribute()
    {
        if ($this->total_episodes <= 0) return 0;
        return min(100, ($this->current_episode / $this->total_episodes) * 100);
    }

    // Methods
    public function incrementViewCount()
    {
        $this->increment('view_count');
    }

    public function updateAverageRating()
    {
        $ratings = $this->ratings()->where('status', 'published')->get();
        if ($ratings->count() > 0) {
            $this->average_rating = $ratings->avg('rating');
            $this->rating_count = $ratings->count();
            $this->save();
        }
    }

    public function isPublished()
    {
        return $this->published_at !== null && $this->published_at <= now();
    }

    public function isVisible()
    {
        return $this->visibility === 'public' && $this->isPublished();
    }
}
