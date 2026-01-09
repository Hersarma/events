<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();

            $table->string('title');
            $table->string('slug')->index();

            $table->dateTime('date_at')->nullable();

            $table->string('location_name')->nullable();
            $table->string('location_url')->nullable();

            $table->string('video_path')->nullable(); // storage path

            $table->string('primary_color', 20)->default('#111827');   // fallback
            $table->string('secondary_color', 20)->default('#6B7280');

            $table->string('rsvp_email')->nullable();

            $table->string('token', 40)->unique(); // random token
            $table->boolean('is_active')->default(true);
            $table->dateTime('expires_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
