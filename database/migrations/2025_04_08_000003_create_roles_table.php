<?php

use App\Models\Role;
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
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();
            $table->integer('priority')->default(0);
            $table->string('icon')->nullable();
            $table->boolean('isAdmin')->default(false);
            $table->boolean('isModerator')->default(false);
            $table->timestamps();
        });

        Role::create([
            'name' => 'user',
            'description' => 'Пользователь',
            'icon' => 'mdi-account',
        ]);

        Role::create([
            'name' => 'admin',
            'description' => 'Администратор',
            'priority' => 20,
            'isAdmin' => true,
            'isModerator' => true,
            'icon' => 'mdi-account-tie-hat',
        ]);

        Role::create([
            'name' => 'moderator',
            'description' => 'Модератор',
            'priority' => 15,
            'isModerator' => true,
            'icon' => 'mdi-shield-account',
        ]);

        Role::create([
            'name' => 'Ветеран',
            'description' => 'Ветеран',
            'priority' => 5,
            'icon' => 'mdi-star-circle-outline',
        ]);

        Role::create([
            'name' => 'Организатор событий',
            'description' => 'Организатор событий',
            'priority' => 10,
            'isModerator' => true,
            'icon' => 'mdi-calendar-star',
        ]);

        Role::create([
            'name' => 'Разработчик',
            'description' => 'Разработчик',
            'priority' => 18,
            'isModerator' => true,
            'isAdmin' => true,
            'icon' => 'mdi-code-tags',
        ]);

        Role::create([
            'name' => 'Тестировщик',
            'description' => 'Тестировщик',
            'priority' => 3,
            'icon' => 'mdi-flask-outline',
        ]);

        Role::create([
            'name' => 'Гость',
            'description' => 'Гость',
            'priority' => 1,
            'icon' => 'mdi-account-question',
        ]);

        Role::create([
            'name' => 'Командир отделения',
            'description' => 'Командир отделения',
            'priority' => 7,
            'icon' => 'mdi-account-group-outline',
        ]);

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};
