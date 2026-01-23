<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            $table->string('template', 30)->default('wedding'); // wedding|kids|celebration
            $table->string('language', 10)->default('sr');       // sr|hr|en...

            $table->string('title');
            $table->string('slug')->index();
            $table->string('token', 64)->unique();

            $table->boolean('is_active')->default(true);
            $table->timestamp('expires_at')->nullable();

            // Core info
            $table->timestamp('date_at')->nullable();
            $table->string('location_name')->nullable();
            $table->string('location_address')->nullable();
            $table->string('location_url')->nullable();
            $table->string('rsvp_email')->nullable();

            // Media
            $table->string('hero_type', 10)->default('video'); // video|image
            $table->string('hero_video_path')->nullable();
            $table->string('hero_image_path')->nullable();
            $table->string('map_image_path')->nullable();
            $table->string('footer_logo_path')->nullable();

            // Flexible configs
            $table->json('content')->nullable();
            $table->json('style')->nullable();

            $table->timestamps();

            $table->unique(['slug', 'token']); // optional, token je već unique
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
