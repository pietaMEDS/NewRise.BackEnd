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

        Rank::create(['name' => 'Гость', 'description' => 'Гость', 'priority' => 0]);
        Rank::create(['name' => 'Житель', 'description' => 'Обычный обитатель галактики', 'priority' => 1]);
        Rank::create(['name' => 'Путник', 'description' => 'Следующий среди звёзд', 'priority' => 2]);
        Rank::create(['name' => 'Падаван', 'description' => 'Ученик Джедая', 'priority' => 3]);
        Rank::create(['name' => 'Рекрут', 'description' => 'Начинающий солдат в рядах армии', 'priority' => 4]);
        Rank::create(['name' => 'Пират', 'description' => 'Охотник за наживой в неизведанных системах', 'priority' => 5]);
        Rank::create(['name' => 'Наёмник', 'description' => 'Сражается за кредиты, а не за идеалы', 'priority' => 6]);
        Rank::create(['name' => 'Инженер', 'description' => 'Умелец, работающий с техникой и кораблями', 'priority' => 7]);
        Rank::create(['name' => 'Рыцарь Джедай', 'description' => 'Хранитель мира и справедливости', 'priority' => 8]);
        Rank::create(['name' => 'Ассасин Ситов', 'description' => 'Тихий и смертельно опасный', 'priority' => 9]);
        Rank::create(['name' => 'Медик', 'description' => 'Спасает жизни на поле боя', 'priority' => 10]);
        Rank::create(['name' => 'Техник', 'description' => 'Поддерживает технику и дроидов в бою', 'priority' => 11]);
        Rank::create(['name' => 'Командир отряда', 'description' => 'Лидер небольшого подразделения', 'priority' => 12]);
        Rank::create(['name' => 'Мастер Джедай', 'description' => 'Наставник для новых поколений', 'priority' => 13]);
        Rank::create(['name' => 'Офицер Империи', 'description' => 'Часть Имперского командования', 'priority' => 14]);
        Rank::create(['name' => 'Снайпер', 'description' => 'Меткий и скрытный стрелок', 'priority' => 15]);
        Rank::create(['name' => 'Дипломат', 'description' => 'Решает конфликты без оружия', 'priority' => 16]);
        Rank::create(['name' => 'Капитан Клонов', 'description' => 'Командует ротами в бою', 'priority' => 17]);
        Rank::create(['name' => 'Лорд Ситхов', 'description' => 'Тёмный владыка Силы', 'priority' => 18]);
        Rank::create(['name' => 'Штурмовик-ветеран', 'description' => 'Боец, прошедший сотни сражений', 'priority' => 19]);
        Rank::create(['name' => 'Дроид-аналитик', 'description' => 'Специалист по данным и разведке', 'priority' => 20]);
        Rank::create(['name' => 'Сенатор', 'description' => 'Голос системы в Галактическом Сенате', 'priority' => 21]);
        Rank::create(['name' => 'Мастер Совета', 'description' => 'Член Высшего Совета Джедаев', 'priority' => 22]);
        Rank::create(['name' => 'Архивариус', 'description' => 'Хранитель знаний и истории Ордена', 'priority' => 23]);
        Rank::create(['name' => 'Инквизитор', 'description' => 'Охотник на джедаев', 'priority' => 24]);
        Rank::create(['name' => 'Генерал', 'description' => 'Высший военный ранг в армии', 'priority' => 25]);
        Rank::create(['name' => 'Старший Тактик', 'description' => 'Планирует крупные военные операции', 'priority' => 26]);
        Rank::create(['name' => 'Темный Пророк', 'description' => 'Загадочный провидец Тьмы', 'priority' => 27]);
        Rank::create(['name' => 'Верховный Канцлер', 'description' => 'Лидер Галактической Республики', 'priority' => 28]);
        Rank::create(['name' => 'Император', 'description' => 'Абсолютная власть над Империей', 'priority' => 29]);
        Rank::create(['name' => 'Владыка Галактики', 'description' => 'Тот, кто правит звездами... или мечтает об этом', 'priority' => 30]);

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ranks');
    }
};
