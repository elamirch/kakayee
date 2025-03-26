<?php

use App\Models\Lesson;
use App\Models\Source;
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
        Schema::create('cards', function (Blueprint $table) {
            $table->id();
            $table->string("title");
            $table->longText("content");
            $table->string("answer");
            $table->longText("explanation");
            $table->foreignIdFor(Source::class)->nullable()->constrained()->nullOnDelete();
            $table->foreignIdFor(Lesson::class)->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cards');
    }
};
