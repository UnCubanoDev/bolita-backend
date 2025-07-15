<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

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
        return response()->json(['wallet_balance' => $user->wallet_balance]);
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
}
