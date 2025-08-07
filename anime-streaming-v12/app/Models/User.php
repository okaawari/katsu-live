<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laratrust\Contracts\LaratrustUser;
use Laratrust\Traits\HasRolesAndPermissions;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
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

    public function avatar()
    {
        return $this->avatar
            ? Storage::disk('public')->url('/user/avatar/'.$this->avatar)
            : Storage::disk('public')->url('/user/avatar/user.jpg');
    }

    public function anime()
    {
        return $this->hasMany('App\Models\Anime');
    }

    public function animelist()
    {
        return $this->hasMany('App\Models\Animelist');
    }

    public function animelistCount()
    {
        return $this->animelist()->count();
    }
    
    /**
     * Get the sessions for the user.
     */
    public function sessions()
    {
        return $this->hasMany(UserSession::class);
    }
}
