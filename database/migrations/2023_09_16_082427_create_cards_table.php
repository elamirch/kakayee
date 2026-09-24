<?php

use App\Models\Lesson;
use App\Models\Source;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cards', function (Blueprint $table) {
            $table->id();
            $table->json('title');
            $table->json('content');
            $table->json('answer');
            $table->string('date')->nullable();
            $table->json('explanation')->nullable();
            $table->unsignedInteger('order')->default(0);
            $table->foreignIdFor(Source::class)->nullable()->constrained()->nullOnDelete();
            $table->foreignIdFor(Lesson::class)->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cards');
    }
};
