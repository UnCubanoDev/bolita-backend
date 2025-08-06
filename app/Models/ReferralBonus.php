<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReferralBonus extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'referrer_id',
        'referred_user_id',
        'bonus_amount',
        'credited_at',
        // otros campos que necesites
    ];

    // ... otras relaciones y métodos del modelo
}
