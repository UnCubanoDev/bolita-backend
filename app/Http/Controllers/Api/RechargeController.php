<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Models\RechargeRequest;

/**
 * @OA\Info(
 *     title="API de Recarga",
 *     version="1.0.0",
 *     description="API para gestionar solicitudes de recarga de saldo."
 * )
 */
class RechargeController extends Controller
{
    /**
     * @OA\Post(
     *     path="/recharge",
     *     summary="Solicitar recarga de cuenta",
     *     description="Permite a los usuarios solicitar una recarga de cuenta enviando una imagen de confirmación.",
     *     operationId="recharge",
     *     tags={"Recarga"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="user_id",
     *                     type="integer",
     *                     description="ID del usuario"
     *                 ),
     *                 @OA\Property(
     *                     property="amount",
     *                     type="number",
     *                     format="float",
     *                     description="Monto de la recarga"
     *                 ),
     *                 @OA\Property(
     *                     property="image",
     *                     type="string",
     *                     format="binary",
     *                     description="Imagen de confirmación de la transferencia"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Solicitud de recarga enviada con éxito",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Solicitud de recarga enviada con éxito."),
     *             @OA\Property(property="image_path", type="string", example="recharge_images/imagen.jpg")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Datos no válidos",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Los datos proporcionados no son válidos."),
     *             @OA\Property(property="errors", type="object", example={"user_id": {"El campo user_id es obligatorio."}})
     *         )
     *     )
     * )
     */
    public function store(Request $request)
    {
        // Validar la solicitud
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:1',
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
        ]);

        return response()->json([
            'message' => 'Solicitud de recarga enviada con éxito.',
            'recharge_request' => $rechargeRequest
        ], 201);
    }
}
