<?php

use App\Models\Theme;
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
        Schema::create('themes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();
            $table->string('status')->default('active')->nullable();
            $table->timestamps();
        });

        Theme::create([
            'name' => 'Общие обсуждения',
            'description' => 'Темы общего характера, не относящиеся к игровым событиям.',
            'status' => 'active',
        ]);

        Theme::create([
            'name' => 'Игровые отчёты',
            'description' => 'Отчёты о миссиях, ролевых действиях и событиях в игре.',
            'status' => 'active',
            ]);
        Theme::create([
            'name' => 'Ролевые предложения',
            'description' => 'Предложения по развитию ролевых сценариев и сюжетов.',
            'status' => 'active',
        ]);

        Theme::create([
            'name' => 'Техническая поддержка',
            'description' => 'Сообщения об ошибках, проблемы с доступом и предложенные улучшения.',
            'status' => 'active',
        ]);

        Theme::create([
            'name' => 'Модерация и правила',
            'description' => 'Обсуждение правил сообщества и жалобы на участников.',
            'status' => 'active',
        ]);

        Theme::create([
            'name' => 'События и мероприятия',
            'description' => 'Анонсы, обсуждения и отчёты по внутриигровым и внешним мероприятиям.',
            'status' => 'active',
        ]);

        Theme::create([
            'name' => 'Внеигровое общение',
            'description' => 'Флудилка, юмор, обсуждение фильмов, игр и прочее, не связанное с RP.',
            'status' => 'active',
        ]);

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('themes');
    }
};
