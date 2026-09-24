<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('achievements', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->json('title');
            $table->json('description');
            $table->json('condition'); // e.g. {"type": "total_xp", "value": 500}
            $table->unsignedInteger('xp_reward')->default(0);
            $table->unsignedInteger('gem_reward')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('achievements');
    }
};
