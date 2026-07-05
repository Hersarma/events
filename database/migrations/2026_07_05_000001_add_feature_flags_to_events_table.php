<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->boolean('enable_rsvp')->default(true)->after('is_active');
            $table->boolean('enable_guest_list')->default(true)->after('enable_rsvp');
            $table->boolean('enable_qr_codes')->default(false)->after('enable_guest_list');
        });
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn([
                'enable_rsvp',
                'enable_guest_list',
                'enable_qr_codes',
            ]);
        });
    }
};
