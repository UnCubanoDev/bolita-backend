<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->decimal('frozen_balance', 10, 2)->default(0)->after('wallet_balance');
            $table->decimal('available_balance', 10, 2)->default(0)->after('frozen_balance');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['frozen_balance', 'available_balance']);
        });
    }
};
