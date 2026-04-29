<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('event_guests', function (Blueprint $table) {
            $table->id();

            $table->foreignId('event_id')
                ->constrained('events')
                ->cascadeOnDelete();

            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('full_name');

            // kako je klijent uneo
            $table->string('phone');

            // za poređenje, npr. 381631234567
            $table->string('phone_normalized');

            // da li gost sme da potvrdi za dvoje
            $table->unsignedTinyInteger('max_guests')->default(2);

            $table->text('note')->nullable();

            $table->timestamps();

            $table->unique(['event_id', 'phone_normalized']);
            $table->index(['event_id', 'full_name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_guests');
    }
};
