<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Token extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     * 
     * @var array
     */
    protected $fillable = [
        'access_token',
        'refresh_token',
    ];

    // OR, if you prefer to allow _all_ columns to be mass assigned (less secure):
    // protected $guarded = [];
}
