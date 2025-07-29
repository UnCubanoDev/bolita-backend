<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Models\RechargeRequest;

class RechargeController extends Controller
{
    public function store(Request $request)
    {
        // Validar la solicitud
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:1',
            'transfer_id' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Validar que sea una imagen
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Los datos proporcionados no son válidos.',
                'errors' => $validator->errors()
            ], 422);
        }

        // Almacenar la imagen en el servidor
        $imagePath = $request->file('image')->store('recharge_images', 'public');

        // Aquí puedes guardar la información de la recarga en la base de datos
        $rechargeRequest = RechargeRequest::create([
            'user_id' => $request->user_id,
            'amount' => $request->amount,
            'image_path' => $imagePath,
            'transfer_id' => $request->transfer_id,
        ]);

        return response()->json([
            'message' => 'Solicitud de recarga enviada con éxito.',
            'recharge_request' => $rechargeRequest
        ], 201);
    }
    public function approveRecharge(Request $request, $rechargeRequestId)
{
    $rechargeRequest = RechargeRequest::find($rechargeRequestId);

    if (!$rechargeRequest) {
        return response()->json(['message' => 'Solicitud de recarga no encontrada'], 404);
    }

    // Verificar si la solicitud ya ha sido aprobada
    if ($rechargeRequest->status === 'approved') {
        return response()->json(['message' => 'La solicitud ya ha sido aprobada'], 400);
    }

    $user = $rechargeRequest->user;

    if (!$user) {
        return response()->json(['message' => 'Usuario no encontrado'], 404);
    }

    // Incrementar el saldo del usuario
    $user->increment('wallet_balance', $rechargeRequest->amount);

    // Marcar la solicitud como aprobada
    $rechargeRequest->status = 'approved';
    $rechargeRequest->save();

    return response()->json(['message' => 'Recarga aprobada con éxito']);
}

public function rejectRecharge(Request $request, $rechargeRequestId)
{
    $rechargeRequest = RechargeRequest::find($rechargeRequestId);

    if (!$rechargeRequest) {
        return response()->json(['message' => 'Solicitud de recarga no encontrada'], 404);
    }

    // Verificar si la solicitud ya ha sido rechazada
    if ($rechargeRequest->status === 'rejected') {
        return response()->json(['message' => 'La solicitud ya ha sido rechazada'], 400);
    }

    // Marcar la solicitud como rechazada
    $rechargeRequest->status = 'rejected';
    $rechargeRequest->save();

    return response()->json(['message' => 'Recarga rechazada con éxito']);
}
}
