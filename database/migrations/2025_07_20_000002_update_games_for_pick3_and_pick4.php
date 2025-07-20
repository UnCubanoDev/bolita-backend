<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('games', function (Blueprint $table) {
            $table->dropColumn('winning_number');
            $table->string('pick3_winning_number')->nullable()->after('date');
            $table->string('pick4_winning_number')->nullable()->after('pick3_winning_number');
        });
    }

    public function down(): void
    {
        Schema::table('games', function (Blueprint $table) {
            $table->dropColumn(['pick3_winning_number', 'pick4_winning_number']);
            $table->string('winning_number')->nullable()->after('date');
        });
    }
};
