<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('laporan_kerusakan', function (Blueprint $table) {
            $table->enum('status', ['baru', 'diproses', 'selesai', 'ditolak'])
                ->default('baru')
                ->change();
        });

        Schema::table('laporan_kerusakan', function (Blueprint $table) {
            $table->text('alasan_penolakan')->nullable()->after('status');
            $table->text('catatan_penyelesaian')->nullable()->after('alasan_penolakan');
            $table->foreignId('diproses_oleh')->nullable()->after('catatan_penyelesaian')->constrained('users');
            $table->timestamp('diproses_pada')->nullable()->after('diproses_oleh');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('laporan_kerusakan', function (Blueprint $table) {
            $table->dropConstrainedForeignId('diproses_oleh');
            $table->dropColumn(['alasan_penolakan', 'catatan_penyelesaian', 'diproses_pada']);

            $table->enum('status', ['diproses', 'selesai', 'ditolak'])
                ->default('diproses')
                ->change();
        });
    }
};