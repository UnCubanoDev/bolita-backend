<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WithdrawalRequest extends Model
{
    protected $fillable = [
        'user_id',
        'amount',
        'note',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
