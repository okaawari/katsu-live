<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Animelist extends Model
{

    public function anime()
    {
        return $this->belongsTo('App\Models\Anime');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function animelist(){
        return $this->hasMany('App\Models\Animelist');
    }

}
