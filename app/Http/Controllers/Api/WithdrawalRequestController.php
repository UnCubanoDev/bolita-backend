<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WithdrawalRequest;

class WithdrawalRequestController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:1',
            'note' => 'nullable|string',
        ]);

        $user = auth()->user();

        if ($user->wallet_balance < $validated['amount']) {
            return response()->json(['error' => 'Saldo insuficiente para retirar'], 400);
        }

        // Opcional: bloquear el saldo hasta que se apruebe el retiro
        // $user->decrement('wallet_balance', $validated['amount']);

        $withdrawal = WithdrawalRequest::create([
            'user_id' => $user->id,
            'amount' => $validated['amount'],
            'note' => $validated['note'] ?? null,
            'status' => 'pending',
        ]);

        return response()->json(['withdrawal' => $withdrawal], 201);
    }
}
