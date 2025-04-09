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
            $table->boolean('isAdmin')->default(false);
            $table->boolean('isModerator')->default(false);
            $table->timestamps();
        });

        Role::create(['name' => 'user', 'description' => 'User']);
        Role::create(['name' => 'admin', 'description' => 'Administrator', 'priority' => 20, 'isAdmin' => true, 'isModerator' => true]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};
