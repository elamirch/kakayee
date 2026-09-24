<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_quests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('quest_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('progress')->default(0);
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('claimed_at')->nullable();
            $table->string('period_start', 10);
            $table->string('period_end', 10);
            $table->timestamps();

            $table->unique(['user_id', 'quest_id', 'period_start'], 'uq_user_quest_period');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_quests');
    }
};
