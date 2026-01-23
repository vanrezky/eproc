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
        Schema::create('eproc_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('eproc_id')->constrained('eprocs')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('action'); // create, update, delete
            $table->json('old_data')->nullable(); // Data sebelum diubah
            $table->json('new_data')->nullable(); // Data setelah diubah
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('eproc_logs');
    }
};
