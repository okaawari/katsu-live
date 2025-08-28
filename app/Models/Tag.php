<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tag extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'name_mn',
        'slug',
        'description',
        'color',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    // Relationships
    public function anime(): BelongsToMany
    {
        return $this->belongsToMany(Anime::class, 'anime_tag');
    }

    public function episodes(): BelongsToMany
    {
        return $this->belongsToMany(Episode::class, 'episode_tag');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('name', 'asc');
    }

    public function scopeByLanguage($query, $language = 'en')
    {
        if ($language === 'mn') {
            return $query->orderBy('name_mn', 'asc');
        }
        return $query->orderBy('name', 'asc');
    }

    // Accessors
    public function getAnimeCountAttribute()
    {
        return $this->anime()->count();
    }

    public function getEpisodeCountAttribute()
    {
        return $this->episodes()->count();
    }

    public function getFormattedColorAttribute()
    {
        return $this->color ?: '#10B981';
    }

    public function getDisplayNameAttribute()
    {
        return $this->name_mn ?: $this->name;
    }

    // Methods
    public function isActive()
    {
        return $this->is_active;
    }

    public function toggleActive()
    {
        $this->update(['is_active' => !$this->is_active]);
    }

    public function getAnimesWithEpisodes()
    {
        return $this->anime()->with('episodes')->get();
    }

    public function getPublishedAnimes()
    {
        return $this->anime()->whereNotNull('published_at')->get();
    }

    public function getPublishedEpisodes()
    {
        return $this->episodes()->where('status', 'published')->get();
    }
}