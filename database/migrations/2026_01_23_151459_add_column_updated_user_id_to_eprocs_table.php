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
        Schema::table('eprocs', function (Blueprint $table) {
            $table->unsignedBigInteger('updated_user_id')->nullable()->after('user_id');
            $table->foreign('updated_user_id')->references('id')->on('users')->noActionOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('eprocs', function (Blueprint $table) {
            $table->dropColumn('updated_user_id');
        });
    }
};
