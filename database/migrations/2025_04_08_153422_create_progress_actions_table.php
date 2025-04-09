<?php

use App\Models\progress_actions;
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
        Schema::create('progress_actions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->boolean('method');
            $table->integer('xp');
            $table->timestamps();
        });

        progress_actions::create(['name' => 'MessageSent', 'method' => 1, 'xp' => 1]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('progress_actions');
    }
};
