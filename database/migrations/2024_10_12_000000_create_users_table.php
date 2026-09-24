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

            // Identity / profile
            $table->string('phone_number')->unique();
            $table->string('email')->nullable()->unique();
            $table->string('name')->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('profile_img_url')->nullable();
            $table->string('role')->default('user');
            $table->string('gender')->nullable();
            $table->date('birth_date')->nullable();

            // Academic context
            $table->foreignIdFor(Major::class)->nullable()->constrained()->nullOnDelete();

            // Location (used by the geo-scoped leaderboards)
            $table->foreignId('country_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('province_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('city_id')->nullable()->constrained()->nullOnDelete();

            // Gamification
            $table->json('progress')->nullable();
            $table->unsignedInteger('xp')->default(0);
            $table->unsignedInteger('heart')->default(5);
            $table->timestamp('heart_refilled_at')->nullable();
            $table->unsignedInteger('gems')->default(0);
            $table->unsignedInteger('daily_goal')->default(20);
            $table->unsignedInteger('current_streak')->default(0);
            $table->unsignedInteger('longest_streak')->default(0);
            $table->unsignedInteger('streak_freezes_available')->default(0);
            $table->timestamp('last_active_at')->nullable();

            // Auth internals
            $table->string('otp_code')->nullable();
            $table->timestamp('otp_code_expiration')->nullable();
            $table->string('refresh_token')->nullable();
            $table->timestamp('last_logout')->nullable();

            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->rememberToken();
            $table->timestamps();

            $table->index('xp');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
