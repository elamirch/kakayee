<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quests', function (Blueprint $table) {
            $table->id();
            $table->string('key');
            $table->string('type', 10); // daily | weekly
            $table->json('title');
            $table->unsignedInteger('target');
            $table->unsignedInteger('xp_reward')->default(0);
            $table->unsignedInteger('gem_reward')->default(0);
            $table->timestamps();

            $table->unique(['key', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quests');
    }
};
