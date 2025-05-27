<?php

use App\Models\Achievement;
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
        Schema::create('achievements', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description');
            $table->string('icon');
            $table->timestamps();
        });

        Achievement::create(['name'=>'First message', 'description'=>'Вы написали ваше первое сообщение', 'icon'=>'mdi-new-box']);
        Achievement::create(['name'=>'5 сообщений', 'description'=>'Вы написали 5 сообщений', 'icon'=>'mdi-comment-multiple']);
        Achievement::create(['name'=>'10 сообщений', 'description'=>'Вы написали 10 сообщений', 'icon'=>'mdi-comment-text-multiple']);
        Achievement::create(['name'=>'50 сообщений', 'description'=>'Вы написали 50 сообщений', 'icon'=>'mdi-forum']);
        Achievement::create(['name'=>'100 сообщений', 'description'=>'Вы написали 100 сообщений', 'icon'=>'mdi-forum-outline']);

        Achievement::create(['name'=>'Первая битва', 'description'=>'Вы поучаствовали в первой битве', 'icon'=>'mdi-sword']);
        Achievement::create(['name'=>'5 битв', 'description'=>'Вы поучаствовали в 5 битвах', 'icon'=>'mdi-crossed-swords']);
        Achievement::create(['name'=>'10 битв', 'description'=>'Вы поучаствовали в 10 битвах', 'icon'=>'mdi-shield-sword']);
        Achievement::create(['name'=>'50 битв', 'description'=>'Вы поучаствовали в 50 битвах', 'icon'=>'mdi-shield-star-outline']);
        Achievement::create(['name'=>'100 битв', 'description'=>'Вы поучаствовали в 100 битвах', 'icon'=>'mdi-sword-cross']);

        Achievement::create(['name'=>'Первая победа', 'description'=>'Вы выиграли первую битву', 'icon'=>'mdi-trophy']);
        Achievement::create(['name'=>'5 побед', 'description'=>'Вы одержали 5 побед', 'icon'=>'mdi-trophy-outline']);
        Achievement::create(['name'=>'10 побед', 'description'=>'Вы одержали 10 побед', 'icon'=>'mdi-medal']);
        Achievement::create(['name'=>'50 побед', 'description'=>'Вы одержали 50 побед', 'icon'=>'mdi-medal-outline']);
        Achievement::create(['name'=>'100 побед', 'description'=>'Вы одержали 100 побед', 'icon'=>'mdi-star-circle']);

        Achievement::create(['name'=>'Первая реакция', 'description'=>'Ваше сообщение получило первую реакцию', 'icon'=>'mdi-thumb-up-outline']);
        Achievement::create(['name'=>'10 реакций', 'description'=>'Вы получили 10 реакций на ваши сообщения', 'icon'=>'mdi-thumb-up']);
        Achievement::create(['name'=>'50 реакций', 'description'=>'Вы получили 50 реакций на ваши сообщения', 'icon'=>'mdi-thumb-up-bold']);
        Achievement::create(['name'=>'100 реакций', 'description'=>'Вы получили 100 реакций на ваши сообщения', 'icon'=>'mdi-emoticon-happy-outline']);
        Achievement::create(['name'=>'250 реакций', 'description'=>'Вы получили 250 реакций на ваши сообщения', 'icon'=>'mdi-emoticon-star']);

        Achievement::create(['name'=>'Полный профиль', 'description'=>'Вы заполнили все поля профиля', 'icon'=>'mdi-account-check']);

        Achievement::create(['name'=>'Активист', 'description'=>'Вы заходили на сайт 7 дней подряд', 'icon'=>'mdi-calendar-multiselect']);
        Achievement::create(['name'=>'Завсегдатай', 'description'=>'Вы заходили на сайт 30 дней подряд', 'icon'=>'mdi-calendar-check']);
        Achievement::create(['name'=>'Ночной гость', 'description'=>'Вы зашли на сайт после полуночи', 'icon'=>'mdi-weather-night']);
        Achievement::create(['name'=>'Профи', 'description'=>'Вы достигли максимального ранга', 'icon'=>'mdi-diamond-stone']);
        Achievement::create(['name'=>'Коллекционер', 'description'=>'Вы собрали 10 достижений', 'icon'=>'mdi-cards']);

        Achievement::create(['name'=>'Дизайнер', 'description'=>'Помощь сообществу неоценима', 'icon'=>'mdi-brush']);

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('achievements');
    }
};
