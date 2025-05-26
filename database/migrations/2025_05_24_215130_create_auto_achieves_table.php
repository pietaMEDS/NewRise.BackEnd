<?php

use App\Models\Achievement;
use App\Models\Auto_achieve;
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
        Schema::create('auto_achieves', function (Blueprint $table) {
            $table->id();
            $table->string('email');
            $table->unsignedBigInteger('achievement_id');
            $table->timestamps();

            $table->foreign('achievement_id')->references('id')->on('achievements');
        });

        $designer = Achievement::where('name', 'Дизайнер')->first()->id;

        Auto_achieve::create(['email' => 'maxibanp@gmail.com', 'achievement_id' => $designer]);
        Auto_achieve::create(['email' => 'saclorum@gmail.com', 'achievement_id' => $designer]);
        Auto_achieve::create(['email' => 'typolust@vk.com', 'achievement_id' => $designer]);
        Auto_achieve::create(['email' => 'malaysiahiki@gmail.com', 'achievement_id' => $designer]);
        Auto_achieve::create(['email' => 'artemsaenko8@mail.com', 'achievement_id' => $designer]);
        Auto_achieve::create(['email' => 'takagi.san.14@mail.ru', 'achievement_id' => $designer]);
        Auto_achieve::create(['email' => 'fedotovskihartem@gmail.com', 'achievement_id' => $designer]);
        Auto_achieve::create(['email' => 'tvytimofey2005@gmail.com', 'achievement_id' => $designer]);
        Auto_achieve::create(['email' => 'achuganin@yandex.ru', 'achievement_id' => $designer]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('auto_achieves');
    }
};
