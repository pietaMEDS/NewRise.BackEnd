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
        Schema::create('accesses', function (Blueprint $table) {
            $table->id();
            $table->string('route')->nullable();
            $table->string('fingerprint_m')->nullable();
            $table->string('fingerprint_c')->nullable();
            $table->string('visitor_id')->nullable();
            $table->double('visitor_score')->nullable();
            $table->string('path_to');
            $table->string('path_from');
            $table->string('useragent');
            $table->unsignedBigInteger('primary_account')->nullable();
            $table->timestamps();

            $table->foreign('primary_account')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accesses');
    }
};
