<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

/**
 * @OA\Tag(
 *     name="Usuario",
 *     description="Endpoints para gestionar la información del usuario"
 * )
 */
class UserController extends Controller
{
    /**
     * @OA\Get(
     *     path="/user",
     *     summary="Obtener información del usuario",
     *     description="Obtiene la información del usuario autenticado.",
     *     operationId="getUser",
     *     tags={"Usuario"},
     *     security={{"sanctum": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Información del usuario",
     *         @OA\JsonContent(ref="#/components/schemas/User")
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autenticado",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="No autenticado.")
     *         )
     *     )
     * )
     */
    public function show(Request $request)
    {
        $user = $request->user();
        return response()->json($user);
    }

    /**
     * @OA\Put(
     *     path="/user",
     *     summary="Actualizar información del usuario",
     *     description="Permite a los usuarios actualizar su información.",
     *     operationId="updateUser",
     *     tags={"Usuario"},
     *     security={{"sanctum": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "email"},
     *             @OA\Property(property="name", type="string", example="Usuario Ejemplo"),
     *             @OA\Property(property="email", type="string", format="email", example="usuario@example.com")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Información del usuario actualizada con éxito",
     *         @OA\JsonContent(ref="#/components/schemas/User")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Datos no válidos",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Los datos proporcionados no son válidos."),
     *             @OA\Property(property="errors", type="object", example={"email": {"El campo email es obligatorio."}})
     *         )
     *     )
     * )
     */
    public function update(Request $request)
    {
        $user = $request->user();
        $user->update($request->only(['name', 'email']));
        return response()->json($user);
    }

    /**
     * @OA\Get(
     *     path="/user/wallet",
     *     summary="Obtener saldo de la wallet",
     *     description="Obtiene el saldo de la wallet del usuario autenticado.",
     *     operationId="getWallet",
     *     tags={"Usuario"},
     *     security={{"sanctum": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Saldo de la wallet",
     *         @OA\JsonContent(
     *             @OA\Property(property="wallet_balance", type="number", format="float", example="100.50")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autenticado",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="No autenticado.")
     *         )
     *     )
     * )
     */
    public function getWallet(Request $request)
    {
        $user = $request->user();
        return response()->json(['wallet_balance' => $user->wallet_balance]);
    }

    /**
     * @OA\Get(
     *     path="/user/bets",
     *     summary="Obtener apuestas del usuario",
     *     description="Obtiene las apuestas realizadas por el usuario autenticado.",
     *     operationId="getBets",
     *     tags={"Usuario"},
     *     security={{"sanctum": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de apuestas",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Bet")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autenticado",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="No autenticado.")
     *         )
     *     )
     * )
     */
    public function getBets(Request $request)
    {
        $bets = $request->user()->bets;
        return response()->json($bets);
    }

    /**
     * @OA\Get(
     *     path="/user/referrals",
     *     summary="Obtener referidos del usuario",
     *     description="Obtiene los usuarios referidos por el usuario autenticado.",
     *     operationId="getReferrals",
     *     tags={"Usuario"},
     *     security={{"sanctum": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de referidos",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/User")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autenticado",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="No autenticado.")
     *         )
     *     )
     * )
     */
    public function getReferrals(Request $request)
    {
        $referrals = $request->user()->referrals;
        return response()->json($referrals);
    }
}
