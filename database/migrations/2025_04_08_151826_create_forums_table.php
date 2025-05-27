<?php

use App\Models\Forum;
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
        Schema::create('forums', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('theme_id')->nullable();
            $table->integer('views')->default(0);
            $table->string('status')->nullable();
            $table->integer('posts_count')->default(0);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('theme_id')->references('id')->on('themes');
        });

        Forum::create([
            'name' => 'Добро пожаловать на форум!',
            'description' => 'Представьтесь и расскажите немного о себе.',
            'user_id' => 1,
            'theme_id' => 1,
            'status' => 'active',
        ]);

        Forum::create([
            'name' => 'Отчёт о миссии «Штурм цитадели»',
            'description' => 'Описание событий, произошедших во время операции.',
            'user_id' => 1,
            'theme_id' => 2,
            'status' => 'active',
        ]);

        Forum::create([
            'name' => 'Идея: Тайный мятеж внутри базы',
            'description' => 'Предложение для нового сюжетного поворота.',
            'user_id' => 1,
            'theme_id' => 3,
            'status' => 'active',
        ]);

        Forum::create([
            'name' => 'Ошибка входа в аккаунт',
            'description' => 'После последнего обновления не удаётся авторизоваться.',
            'user_id' => 1,
            'theme_id' => 4,
            'status' => 'active',
        ]);

        Forum::create([
            'name' => 'Жалоба на игрока #145',
            'description' => 'Нарушение правил поведения во время RP-сценария.',
            'user_id' => 1,
            'theme_id' => 5,
            'status' => 'active',
        ]);

        Forum::create([
            'name' => 'Ивент: Оборона станции «Омега»',
            'description' => 'Обсуждение предстоящего ролевого события.',
            'user_id' => 1,
            'theme_id' => 6,
            'status' => 'active',
        ]);

        Forum::create([
            'name' => 'Любимые фильмы по Звёздным Войнам',
            'description' => 'Обсуждение фильмов и сериалов во вселенной Star Wars.',
            'user_id' => 1,
            'theme_id' => 7,
            'status' => 'active',
        ]);
        Forum::create([
            'name' => 'Идеи по улучшению базы',
            'description' => 'Предложения по улучшению функционала и архитектуры базы.',
            'user_id' => 1,
            'theme_id' => 3,
            'views' => 87,
            'posts_count' => 5,
            'status' => 'active',
        ]);

        Forum::create([
            'name' => 'Проблема с отображением интерфейса',
            'description' => 'После последнего патча исчезли иконки интерфейса.',
            'user_id' => 1,
            'theme_id' => 4,
            'views' => 134,
            'posts_count' => 8,
            'status' => 'active',
        ]);

        Forum::create([
            'name' => 'Новое ролевое подразделение',
            'description' => 'Предлагаю создать отряд разведки с особой специализацией.',
            'user_id' => 1,
            'theme_id' => 3,
            'views' => 102,
            'posts_count' => 4,
            'status' => 'active',
        ]);

        Forum::create([
            'name' => 'Вопрос по правилам ПвП',
            'description' => 'Разъяснение по ситуации, связанной с боем между игроками.',
            'user_id' => 1,
            'theme_id' => 5,
            'views' => 59,
            'posts_count' => 3,
            'status' => 'active',
        ]);

        Forum::create([
            'name' => 'Конкурс скриншотов с ивента',
            'description' => 'Публикуем лучшие снимки с прошедшего мероприятия.',
            'user_id' => 1,
            'theme_id' => 6,
            'views' => 210,
            'posts_count' => 12,
            'status' => 'active',
        ]);

        Forum::create([
            'name' => 'Разговоры ни о чём',
            'description' => 'Просто общаемся обо всём подряд.',
            'user_id' => 1,
            'theme_id' => 7,
            'views' => 312,
            'posts_count' => 24,
            'status' => 'active',
        ]);

        Forum::create([
            'name' => 'Новый игрок ищет тиммейтов',
            'description' => 'Ищу игроков для совместных миссий и тренировок.',
            'user_id' => 1,
            'theme_id' => 1,
            'views' => 44,
            'posts_count' => 2,
            'status' => 'active',
        ]);

        Forum::create([
            'name' => 'Миссия «Спасение сенатора» — обзор',
            'description' => 'Обсуждение сильных и слабых сторон сценария.',
            'user_id' => 1,
            'theme_id' => 2,
            'views' => 98,
            'posts_count' => 6,
            'status' => 'active',
        ]);

        Forum::create([
            'name' => 'Задержка на входе в игру',
            'description' => 'Клиент долго загружается при подключении к серверу.',
            'user_id' => 1,
            'theme_id' => 4,
            'views' => 120,
            'posts_count' => 5,
            'status' => 'active',
        ]);

        Forum::create([
            'name' => 'Какие моды вам нравятся?',
            'description' => 'Обсуждаем любимые модификации и аддоны для Garry\'s Mod.',
            'user_id' => 1,
            'theme_id' => 7,
            'views' => 178,
            'posts_count' => 10,
            'status' => 'active',
        ]);


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('forums');
    }
};
