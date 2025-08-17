<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'my_referral_code',
        'referrer_code',
        'wallet_balance',
        'frozen_balance',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'wallet_balance' => 'decimal:2',
            'frozen_balance' => 'decimal:2',
        ];
    }

    public static function getTotalWalletBalance(): float
    {
        return (float) self::sum('wallet_balance');
    }

    // public static function getTotalFrozenBalance(): float
    // {
    //     return (float) DB::table('users')->sum('frozen_balance');
    // }

    // public static function getTotalAvailableBalance(): float
    // {
    //     return self::getTotalWalletBalance() - self::getTotalFrozenBalance();
    // }

    /**
     * Obtiene el saldo disponible (wallet_balance - frozen_balance)
     */
    public function getAvailableBalanceAttribute()
    {
        return $this->wallet_balance - $this->frozen_balance;
    }

    /**
     * Congela un monto específico del saldo
     */
    public function freezeBalance($amount)
    {
        if ($this->wallet_balance < $amount) {
            throw new \Exception('Saldo insuficiente para congelar');
        }

        $this->increment('frozen_balance', $amount);
        return $this;
    }

    /**
     * Descongela un monto específico del saldo
     */
    public function unfreezeBalance($amount)
    {
        if ($this->frozen_balance < $amount) {
            throw new \Exception('Saldo congelado insuficiente para descongelar');
        }

        $this->decrement('frozen_balance', $amount);
        return $this;
    }

    /**
     * Libera completamente el saldo congelado
     */
    public function releaseFrozenBalance()
    {
        $this->update(['frozen_balance' => 0]);
        return $this;
    }

    public function rechargeRequests()
    {
        return $this->hasMany(RechargeRequest::class);
    }

    public function withdrawalRequests()
    {
        return $this->hasMany(WithdrawalRequest::class);
    }

    public function bets(): HasMany
    {
        return $this->hasMany(Bet::class);
    }

    public function referredUsers()
    {
        return $this->hasMany(User::class, 'referrer_code', 'my_referral_code');
    }

    public function myReferrals()
    {
        $user = auth()->user();

        $referrals = $user->referredUsers()->with('bets')->get();

        $referralPercentage = 0.05; // 5%

        $data = $referrals->map(function ($referral) use ($referralPercentage) {
            $totalWinnings = $referral->bets->sum('total_payout');
            $myEarnings = $totalWinnings * $referralPercentage;

            return [
                'referral_id' => $referral->id,
                'referral_name' => $referral->name,
                'total_winnings' => $totalWinnings,
                'my_earnings' => $myEarnings,
            ];
        });

        return response()->json([
            'referrals' => $data,
        ]);
    }
}
