<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentHistory extends Model
{
    use HasFactory;

    protected $table = 'payment_history';

    protected $fillable = [
        'amount',
        'user_id',
        'refId',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
} 