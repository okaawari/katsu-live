<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Episode extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'episodes';

    protected $fillable = [
        'anime_id',
        'uploaded_by',
        'episode_number',
        'title',
        'title_english',
        'title_japanese',
        'duration',
        'synopsis',
        'slug',
        'poster_image',
        'thumbnail_image',
        'preview_images',
        'video_480p',
        'video_720p',
        'video_1080p',
        'video_4k',
        'subtitle_english',
        'subtitle_mongolian',
        'subtitle_tracks',
        'duration_formatted',
        'duration_seconds',
        'sprite_vtt',
        'sprite_image',
        'sprite_columns',
        'sprite_rows',
        'sprite_interval',
        'video_codec',
        'audio_codec',
        'file_size',
        'bitrate',
        'resolution',
        'fps',
        'status',
        'visibility',
        'scheduled_at',
        'published_at',
        'is_featured',
        'is_premium',
        'view_count',
        'average_rating',
        'rating_count',
        'favorite_count',
        'server_location',
        'cdn_urls',
        'meta_title',
        'meta_description',
        'meta_keywords',
    ];

    protected $casts = [
        'preview_images' => 'array',
        'subtitle_tracks' => 'array',
        'cdn_urls' => 'array',
        'meta_keywords' => 'array',
        'duration_seconds' => 'integer',
        'file_size' => 'integer',
        'bitrate' => 'integer',
        'sprite_columns' => 'integer',
        'sprite_rows' => 'integer',
        'sprite_interval' => 'decimal:2',
        'fps' => 'decimal:2',
        'average_rating' => 'decimal:2',
        'is_featured' => 'boolean',
        'is_premium' => 'boolean',
        'view_count' => 'integer',
        'rating_count' => 'integer',
        'favorite_count' => 'integer',
        'scheduled_at' => 'datetime',
        'published_at' => 'datetime',
    ];

    protected $dates = [
        'scheduled_at',
        'published_at',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    // Relationships
    public function anime(): BelongsTo
    {
        return $this->belongsTo(Anime::class);
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
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

    public function watchProgress(): HasMany
    {
        return $this->hasMany(VideoWatchProgress::class);
    }

    public function episodeLists(): HasMany
    {
        return $this->hasMany(EpisodeList::class);
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

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'episode_tag');
    }

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopePublic($query)
    {
        return $query->where('visibility', 'public');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopePremium($query)
    {
        return $query->where('is_premium', true);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByVisibility($query, $visibility)
    {
        return $query->where('visibility', $visibility);
    }

    public function scopeScheduled($query)
    {
        return $query->where('status', 'scheduled')
                    ->where('scheduled_at', '<=', now());
    }

    // Accessors
    public function getPosterImageUrlAttribute()
    {
        return $this->poster_image ? asset('storage/' . $this->poster_image) : null;
    }

    public function getThumbnailImageUrlAttribute()
    {
        return $this->thumbnail_image ? asset('storage/' . $this->thumbnail_image) : null;
    }

    public function getFormattedDurationAttribute()
    {
        if ($this->duration_formatted) {
            return $this->duration_formatted;
        }
        
        if ($this->duration_seconds) {
            $hours = floor($this->duration_seconds / 3600);
            $minutes = floor(($this->duration_seconds % 3600) / 60);
            $seconds = $this->duration_seconds % 60;
            
            if ($hours > 0) {
                return sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
            }
            return sprintf('%02d:%02d', $minutes, $seconds);
        }
        
        return $this->duration ?? 'Unknown';
    }

    public function getFormattedFileSizeAttribute()
    {
        if (!$this->file_size) return null;
        
        $units = ['B', 'KB', 'MB', 'GB'];
        $size = $this->file_size;
        $unit = 0;
        
        while ($size >= 1024 && $unit < count($units) - 1) {
            $size /= 1024;
            $unit++;
        }
        
        return round($size, 2) . ' ' . $units[$unit];
    }

    public function getVideoUrlsAttribute()
    {
        return [
            '480p' => $this->video_480p,
            '720p' => $this->video_720p,
            '1080p' => $this->video_1080p,
            '4k' => $this->video_4k,
        ];
    }

    public function getSubtitleUrlsAttribute()
    {
        return [
            'english' => $this->subtitle_english,
            'mongolian' => $this->subtitle_mongolian,
            'tracks' => $this->subtitle_tracks,
        ];
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
        return $this->status === 'published' && 
               $this->published_at !== null && 
               $this->published_at <= now();
    }

    public function isVisible()
    {
        return $this->visibility === 'public' && $this->isPublished();
    }

    public function isScheduled()
    {
        return $this->status === 'scheduled' && 
               $this->scheduled_at !== null && 
               $this->scheduled_at > now();
    }

    public function canBeWatchedBy(User $user)
    {
        if ($this->is_premium && !$user->hasRole('premium')) {
            return false;
        }
        
        return $this->isVisible();
    }

    public function getNextEpisode()
    {
        return $this->anime->episodes()
            ->where('episode_number', '>', $this->episode_number)
            ->orderBy('episode_number')
            ->first();
    }

    public function getPreviousEpisode()
    {
        return $this->anime->episodes()
            ->where('episode_number', '<', $this->episode_number)
            ->orderBy('episode_number', 'desc')
            ->first();
    }
}
