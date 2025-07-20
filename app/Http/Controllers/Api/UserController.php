<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;

class UserController extends Controller
{
    public function show(Request $request)
    {
        $user = $request->user();
        return response()->json($user);
    }

    public function update(Request $request)
    {
        $user = $request->user();
        $user->update($request->only(['name', 'email']));
        return response()->json($user);
    }

    public function getWallet(Request $request)
    {
        $user = $request->user();
        return response()->json([
            'wallet_balance' => $user->wallet_balance,
            'frozen_balance' => $user->frozen_balance,
            'available_balance' => $user->available_balance,
        ]);
    }

    public function getBets(Request $request)
    {
        $bets = $request->user()->bets;
        return response()->json($bets);
    }

    public function getReferrals(Request $request)
    {
        $referrals = $request->user()->referrals;
        return response()->json($referrals);
    }

    public function myReferrals(Request $request)
    {
        $user = auth()->user();

        $period = $request->input('period', 'weekly'); // Por defecto semanal

        // Calcula el rango de fechas según el periodo
        switch ($period) {
            case 'monthly':
                $from = Carbon::now()->startOfMonth();
                break;
            case 'yearly':
                $from = Carbon::now()->startOfYear();
                break;
            case 'weekly':
            default:
                $from = Carbon::now()->startOfWeek();
                break;
        }

        $to = Carbon::now()->endOfDay();

        $referrals = $user->referredUsers()->with(['bets' => function ($query) use ($from, $to) {
            $query->whereBetween('created_at', [$from, $to]);
        }])->get();

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
