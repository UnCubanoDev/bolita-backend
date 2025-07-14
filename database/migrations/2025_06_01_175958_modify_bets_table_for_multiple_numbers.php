<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('bets', function (Blueprint $table) {
            // Eliminar columnas antiguas
            $table->dropColumn(['numbers']);

            // Añadir nueva columna para almacenar los números y montos
            $table->json('bet_details')->after('type')->comment('Array de números y montos');
            $table->decimal('total_amount', 10, 2)->after('bet_details')->comment('Monto total de la apuesta');
            $table->decimal('total_payout', 10, 2)->nullable()->after('total_amount')->comment('Premio total');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bets', function (Blueprint $table) {
            $table->string('numbers')->after('type');
            $table->decimal('amount', 10, 2)->after('numbers');
            $table->dropColumn(['bet_details', 'total_amount', 'total_payout']);
        });
    }
};
