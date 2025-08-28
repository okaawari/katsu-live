<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laratrust\Contracts\LaratrustUser;
use Laratrust\Traits\HasRolesAndPermissions;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Storage;

class User extends Authenticatable implements LaratrustUser
{
    use HasApiTokens, HasFactory, Notifiable;
    use HasRolesAndPermissions;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'profile_picture',
        'cover_image',
        'bio',
        'location',
        'website',
        'birth_date',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Avatar method
    public function avatar()
    {
        return $this->profile_picture
            ? Storage::disk('public')->url('/user/avatar/'.$this->profile_picture)
            : Storage::disk('public')->url('/user/avatar/user.jpg');
    }

    // Cover image method
    public function coverImage()
    {
        return $this->cover_image
            ? Storage::disk('public')->url('/user/cover/'.$this->cover_image)
            : asset('/images/cover.jpg');
    }

    // Anime relationships
    public function anime(): HasMany
    {
        return $this->hasMany(Anime::class, 'author_id');
    }

    public function episodes(): HasMany
    {
        return $this->hasMany(Episode::class, 'uploaded_by');
    }

    // Watch progress and lists
    public function watchProgress(): HasMany
    {
        return $this->hasMany(VideoWatchProgress::class);
    }

    public function episodeLists(): HasMany
    {
        return $this->hasMany(EpisodeList::class);
    }

    // Views and analytics
    public function views(): MorphMany
    {
        return $this->morphMany(View::class, 'viewable');
    }

    public function viewSessions(): MorphMany
    {
        return $this->morphMany(ViewSession::class, 'viewable');
    }

    // Comments and ratings
    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function ratings(): MorphMany
    {
        return $this->morphMany(Rating::class, 'ratable');
    }

    public function userComments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function userRatings(): HasMany
    {
        return $this->hasMany(Rating::class);
    }

    // Badges
    public function userBadges(): HasMany
    {
        return $this->hasMany(UserBadge::class);
    }

    public function badges(): BelongsToMany
    {
        return $this->belongsToMany(Badge::class, 'user_badges')
                    ->withPivot(['awarded_at', 'reason', 'context', 'is_visible', 'is_featured'])
                    ->withTimestamps();
    }

    // Scheduled publications
    public function scheduledPublications(): HasMany
    {
        return $this->hasMany(ScheduledPublication::class, 'scheduled_by');
    }

    // Legacy relationships
    public function animelist(): HasMany
    {
        return $this->hasMany(Animelist::class);
    }

    public function animelistCount()
    {
        return $this->animelist()->count();
    }
    
    /**
     * Get the sessions for the user.
     */
    public function sessions(): HasMany
    {
        return $this->hasMany(UserSession::class);
    }

    // Scopes
    public function scopeWithBadges($query)
    {
        return $query->with(['badges' => function($q) {
            $q->where('is_active', true);
        }]);
    }

    public function scopeWithWatchProgress($query)
    {
        return $query->with(['watchProgress' => function($q) {
            $q->orderBy('last_position_update', 'desc');
        }]);
    }

    // Methods
    public function getTotalWatchTime()
    {
        return $this->watchProgress()->sum('current_time');
    }

    public function getCompletedEpisodesCount()
    {
        return $this->watchProgress()->where('is_completed', true)->count();
    }

    public function getWatchingEpisodesCount()
    {
        return $this->episodeLists()->where('status', 'watching')->count();
    }

    public function getCompletedEpisodesListCount()
    {
        return $this->episodeLists()->where('status', 'completed')->count();
    }

    public function getPlanToWatchCount()
    {
        return $this->episodeLists()->where('status', 'plan_to_watch')->count();
    }

    public function getFavoriteEpisodesCount()
    {
        return $this->episodeLists()->where('is_favorite', true)->count();
    }

    public function getTotalRatingsCount()
    {
        return $this->userRatings()->count();
    }

    public function getAverageRating()
    {
        return $this->userRatings()->avg('rating');
    }

    public function getTotalCommentsCount()
    {
        return $this->userComments()->count();
    }

    public function getActiveBadgesCount()
    {
        return $this->userBadges()->whereNull('revoked_at')->count();
    }

    public function getTotalPoints()
    {
        return $this->userBadges()
                    ->whereNull('revoked_at')
                    ->join('badges', 'user_badges.badge_id', '=', 'badges.id')
                    ->sum('badges.points');
    }

    public function hasBadge($badgeId)
    {
        return $this->userBadges()
                    ->where('badge_id', $badgeId)
                    ->whereNull('revoked_at')
                    ->exists();
    }

    // Note: hasRole() and hasPermission() methods are already provided by Laratrust
    // No need to override them as they're implemented by the HasRolesAndPermissions trait
}
