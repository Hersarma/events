<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('event_rsvps', function (Blueprint $table) {
            $table->id();

            $table->foreignId('event_id')->constrained('events')->cascadeOnDelete();

            $table->string('status', 10); // yes|maybe|no
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();

            $table->unsignedTinyInteger('guests_count')->default(1); // 0/1/2...
            $table->text('note')->nullable();

            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();

            $table->timestamps();

            $table->index(['event_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_rsvps');
    }
};

