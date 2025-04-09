<?php

use App\Models\Rank;
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
        Schema::create('ranks', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description');
            $table->integer('priority');
            $table->timestamps();
        });

        Rank::create(['name' => 'Гость', 'description' => 'Гость', 'priority' => -1]);
        Rank::create(['name' => 'Человек', 'description' => 'Живой, и даже не робот', 'priority' => 0]);
        Rank::create(['name' => 'Супер-герой', 'description' => 'Я дальше не придумал', 'priority' => 1]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ranks');
    }
};
