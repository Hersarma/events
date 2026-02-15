<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->string('guest_list_pin_hash')->nullable()->after('token');
            $table->timestamp('guest_list_pin_set_at')->nullable()->after('guest_list_pin_hash');
        });
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn(['guest_list_pin_hash', 'guest_list_pin_set_at']);
        });
    }
};

