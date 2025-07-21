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
        $amount = $validated['amount'];

        // Verificar que el usuario tenga saldo disponible (no congelado)
        if ($user->available_balance < $amount) {
            return response()->json([
                'error' => 'Saldo disponible insuficiente para retirar',
                'available_balance' => $user->available_balance,
                'frozen_balance' => $user->frozen_balance,
                'total_balance' => $user->wallet_balance
            ], 400);
        }

        try {
            // Congelar el monto solicitado
            $user->freezeBalance($amount);

            // Crear la solicitud de extracción
        $withdrawal = WithdrawalRequest::create([
            'user_id' => $user->id,
                'amount' => $amount,
            'note' => $validated['note'] ?? null,
            'status' => 'pending',
        ]);

            return response()->json([
                'withdrawal' => $withdrawal,
                'message' => 'Solicitud de extracción creada. El monto ha sido congelado.',
                'available_balance' => $user->available_balance,
                'frozen_balance' => $user->frozen_balance,
                'total_balance' => $user->wallet_balance
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al procesar la solicitud: ' . $e->getMessage()
            ], 500);
        }
    }

    public function index(Request $request)
    {
        $user = auth()->user();
        $withdrawals = $user->withdrawalRequests()->orderBy('created_at', 'desc')->get();

        return response()->json([
            'withdrawals' => $withdrawals,
            'balance_info' => [
                'available_balance' => $user->available_balance,
                'frozen_balance' => $user->frozen_balance,
                'total_balance' => $user->wallet_balance
            ]
        ]);
    }

    public function show($id)
    {
        $user = auth()->user();
        $withdrawal = $user->withdrawalRequests()->find($id);

        if (!$withdrawal) {
            return response()->json(['error' => 'Solicitud de extracción no encontrada'], 404);
        }

        return response()->json([
            'withdrawal' => $withdrawal,
            'balance_info' => [
                'available_balance' => $user->available_balance,
                'frozen_balance' => $user->frozen_balance,
                'total_balance' => $user->wallet_balance
            ]
        ]);
    }

    /**
     * Aprobar una solicitud de extracción
     */
    public function approve($id)
    {
        $withdrawal = WithdrawalRequest::findOrFail($id);

        if ($withdrawal->status !== 'pending') {
            return response()->json(['error' => 'La solicitud ya ha sido procesada'], 400);
        }

        try {
            // Descontar el monto del saldo total y liberar el congelado
            $withdrawal->user->decrement('wallet_balance', $withdrawal->amount);
            $withdrawal->user->unfreezeBalance($withdrawal->amount);

            // Marcar como aprobada
            $withdrawal->update(['status' => 'approved']);

            return response()->json([
                'message' => 'Solicitud de extracción aprobada',
                'withdrawal' => $withdrawal
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al aprobar la solicitud: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Rechazar una solicitud de extracción
     */
    public function reject($id)
    {
        $withdrawal = WithdrawalRequest::findOrFail($id);

        if ($withdrawal->status !== 'pending') {
            return response()->json(['error' => 'La solicitud ya ha sido procesada'], 400);
        }

        try {
            // Liberar el saldo congelado sin descontar del total
            $withdrawal->user->unfreezeBalance($withdrawal->amount);

            // Marcar como rechazada
            $withdrawal->update(['status' => 'rejected']);

            return response()->json([
                'message' => 'Solicitud de extracción rechazada. El saldo ha sido liberado.',
                'withdrawal' => $withdrawal
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al rechazar la solicitud: ' . $e->getMessage()
            ], 500);
        }
    }
}
