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
        $bets = $request->user()->bets()
                ->orderBy('created_at', 'desc')
                ->get();

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

        $referrals = $user->referredUsers()->with('bets')->get();

        $referralPercentage = 0.05; // 5%

        $data = $referrals->map(function ($referral) use ($referralPercentage) {
            $totalWinnings = $referral->bets->sum('total_payout');
            $totalBetting = $referral->bets->sum('total_amount');
            $myEarnings = $totalBetting * $referralPercentage;

            return [
                'referral_id' => $referral->id,
                'referral_name' => $referral->name,
                'total_winnings' => $totalWinnings,
                'total_betting' => $totalBetting,
                'my_earnings' => $myEarnings,
            ];
        });

        return response()->json([
            'referrals' => $data,
        ]);
    }
}
