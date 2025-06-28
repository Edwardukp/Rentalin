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
        Schema::create('kos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pemilik_id')->constrained('users')->onDelete('cascade');
            $table->string('nama_kos');
            $table->text('alamat');
            $table->decimal('harga', 10, 2);
            $table->longText('fasilitas');
            $table->json('foto')->nullable(); // Store multiple photos as JSON array
            $table->boolean('status')->default(1); // 1 = Available, 0 = Not Available
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kos');
    }
};
