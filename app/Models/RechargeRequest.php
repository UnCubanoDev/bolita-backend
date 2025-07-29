<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RechargeRequest extends Model
{
    protected $fillable = [
        'user_id',
        'amount',
        'image_path',
        'status',
        'transfer_id',
        // otros campos que necesites
    ];

    protected static function booted()
    {
        static::updating(function (RechargeRequest $rechargeRequest) {
            if (in_array($rechargeRequest->getOriginal('status'), ['approved', 'rejected'])) {
                throw new \Exception('No se puede modificar una solicitud aprobada o rechazada.');
            }
        });
    }

    // ... otras relaciones y métodos del modelo
    public function user()
   {
       return $this->belongsTo(User::class);
   }
}
