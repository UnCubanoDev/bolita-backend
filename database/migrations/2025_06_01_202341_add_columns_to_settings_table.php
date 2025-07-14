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
        Schema::table('settings', function (Blueprint $table) {
            // Añadir las columnas que faltan si no existen
            if (!Schema::hasColumn('settings', 'key')) {
                $table->string('key')->unique();
            }
            if (!Schema::hasColumn('settings', 'value')) {
                $table->text('value');
            }
            if (!Schema::hasColumn('settings', 'group')) {
                $table->string('group')->default('general');
            }
            if (!Schema::hasColumn('settings', 'type')) {
                $table->string('type')->default('string');
            }
            if (!Schema::hasColumn('settings', 'label')) {
                $table->string('label');
            }
            if (!Schema::hasColumn('settings', 'description')) {
                $table->text('description')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn(['key', 'value', 'group', 'type', 'label', 'description']);
        });
    }
};
