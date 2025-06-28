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
        Schema::table('kos', function (Blueprint $table) {
            // Change harga column to allow larger values
            // decimal(15, 2) allows up to 13 digits before decimal point
            // This supports values up to 9,999,999,999,999.99 (almost 10 trillion)
            $table->decimal('harga', 15, 2)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kos', function (Blueprint $table) {
            // Revert back to original precision
            $table->decimal('harga', 10, 2)->change();
        });
    }
};
