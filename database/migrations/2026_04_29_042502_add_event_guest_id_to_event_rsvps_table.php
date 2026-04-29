<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('event_rsvps', function (Blueprint $table) {
            $table->foreignId('event_guest_id')
                ->nullable()
                ->after('event_id')
                ->constrained('event_guests')
                ->nullOnDelete();

            $table->timestamp('responded_at')->nullable()->after('guests_count');

            $table->index(['event_id', 'phone']);
        });
    }

    public function down(): void
    {
        Schema::table('event_rsvps', function (Blueprint $table) {
            $table->dropConstrainedForeignId('event_guest_id');
            $table->dropColumn('responded_at');
        });
    }
};