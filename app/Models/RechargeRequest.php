<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RechargeRequest extends Model
{
    protected $fillable = [
        'user_id',
        'amount',
        'image_path',
        // otros campos que necesites
    ];

    // ... otras relaciones y métodos del modelo
}
