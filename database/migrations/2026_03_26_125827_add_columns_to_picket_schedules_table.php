<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('picket_schedules', function (Blueprint $table) {
            // Cek apakah kolom user_id sudah ada
            if (!Schema::hasColumn('picket_schedules', 'user_id')) {
                $table->foreignId('user_id')->after('id')->constrained()->onDelete('cascade');
            }
            
            // Cek apakah kolom date sudah ada
            if (!Schema::hasColumn('picket_schedules', 'date')) {
                $table->date('date')->after('user_id');
            }
            
            // Cek apakah kolom day sudah ada
            if (!Schema::hasColumn('picket_schedules', 'day')) {
                $table->string('day')->after('date');
            }
            
            // Cek apakah kolom location sudah ada
            if (!Schema::hasColumn('picket_schedules', 'location')) {
                $table->string('location')->nullable()->after('day');
            }
            
            // Cek apakah kolom notes sudah ada
            if (!Schema::hasColumn('picket_schedules', 'notes')) {
                $table->text('notes')->nullable()->after('location');
            }
        });
    }

    public function down(): void
    {
        Schema::table('picket_schedules', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn(['user_id', 'date', 'day', 'location', 'notes']);
        });
    }
};