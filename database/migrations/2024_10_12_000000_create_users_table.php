<?php

use App\Models\Major;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->foreignIdFor(Major::class)->nullable()->constrained()->nullOnDelete();
            $table->json('progress');
            // {
            //      "10": {
            //          "checkpoint": "2"
            //      }
            // }
            $table->timestamp('email_verified_at')->nullable();
            $table->string('xp');
            $table->string('heart');
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
