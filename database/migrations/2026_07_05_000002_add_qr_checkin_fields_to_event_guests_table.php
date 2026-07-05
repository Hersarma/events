<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('event_guests', function (Blueprint $table) {
            $table->string('qr_token', 80)->nullable()->unique()->after('phone_normalized');
            $table->timestamp('checked_in_at')->nullable()->after('note');
            $table->unsignedTinyInteger('checked_in_count')->nullable()->after('checked_in_at');
        });

        DB::table('event_guests')
            ->orderBy('id')
            ->select('id')
            ->chunk(200, function ($guests) {
                foreach ($guests as $guest) {
                    DB::table('event_guests')
                        ->where('id', $guest->id)
                        ->update(['qr_token' => Str::lower(Str::random(40))]);
                }
            });
    }

    public function down(): void
    {
        Schema::table('event_guests', function (Blueprint $table) {
            $table->dropColumn([
                'qr_token',
                'checked_in_at',
                'checked_in_count',
            ]);
        });
    }
};
