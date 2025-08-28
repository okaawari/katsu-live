<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'categories';

    protected $fillable = [
        'name',
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
    public function animes(): HasMany
    {
        return $this->hasMany(Anime::class);
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

    // Accessors
    public function getAnimeCountAttribute()
    {
        return $this->animes()->count();
    }

    public function getPublishedAnimeCountAttribute()
    {
        return $this->animes()->whereNotNull('published_at')->count();
    }

    public function getFormattedColorAttribute()
    {
        return $this->color ?: '#3B82F6';
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
        return $this->animes()->with('episodes')->get();
    }

    public function getPublishedAnimes()
    {
        return $this->animes()->whereNotNull('published_at')->get();
    }
}
