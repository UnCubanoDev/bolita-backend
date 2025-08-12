<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WithdrawalRequest extends Model
{
    protected $fillable = [
        'user_id',
        'amount',
        'card_number',
        'phone_number',
        'note',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function approve()
    {
        if ($this->status !== 'pending') {
            throw new \Exception('La solicitud ya ha sido procesada');
        }

        $this->user->decrement('wallet_balance', $this->amount);
        $this->user->unfreezeBalance($this->amount);
        $this->status = 'approved';
        $this->save();
    }

    public function reject()
    {
        if ($this->status !== 'pending') {
            throw new \Exception('La solicitud ya ha sido procesada');
        }

        $this->user->unfreezeBalance($this->amount);
        $this->status = 'rejected';
        $this->save();
    }
}
