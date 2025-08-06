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
        Schema::table('referral_bonuses', function (Blueprint $table) {
            // Primero eliminamos las columnas que ya no necesitamos
            $table->dropColumn(['name', 'type', 'date', 'winning_number', 'result_imported_at']);

            // Luego agregamos las nuevas columnas necesarias
            $table->foreignId('referrer_id')->constrained('users')->after('id');
            $table->foreignId('referred_user_id')->constrained('users')->after('referrer_id');
            $table->decimal('bonus_amount', 10, 2)->after('referred_user_id');
            $table->foreignId('bet_id')->nullable()->constrained()->after('bonus_amount');
            $table->timestamp('credited_at')->nullable()->after('bet_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('referral_bonuses', function (Blueprint $table) {
            // Para revertir, primero eliminamos las nuevas columnas
            $table->dropForeign(['referrer_id']);
            $table->dropForeign(['referred_user_id']);
            $table->dropForeign(['bet_id']);
            $table->dropColumn(['referrer_id', 'referred_user_id', 'bonus_amount', 'bet_id', 'credited_at']);

            // Luego recreamos las columnas originales
            $table->string('name')->after('id');
            $table->enum('type', ['pick3', 'pick4'])->after('name');
            $table->date('date')->after('type');
            $table->string('winning_number')->nullable()->after('date');
            $table->timestamp('result_imported_at')->nullable()->after('winning_number');
        });
    }
};
