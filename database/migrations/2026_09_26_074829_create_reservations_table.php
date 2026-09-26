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
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('fasilitas_id')->constrained('fasilitas')->onDelete('cascade');

            // sata pemohon
            $table->string('nama_pemohon');
            $table->string('instansi_pemohon');

            // data kegiatan
            $table->string('nama_kegiatan');
            $table->text('deskripsi_kegiatan');
            $table->unsignedInteger('jumlah_peserta');

            // jadwal
            $table->date('tanggal');
            $table->time('start_time');
            $table->time('end_time');

            // berkas
            $table->string('surat_peminjaman_path');
            $table->string('proposal_kegiatan_path')->nullable();

            // status dan proses
            $table->enum('status', ['menunggu', 'disetujui', 'ditolak', 'dibatalkan'])->default('menunggu');
            $table->string('dibatalkan_oleh')->nullable();
            $table->text('alasan_pembatalan')->nullable();
            $table->foreignId('diproses_oleh')->nullable()->constrained('users');
            $table->timestamp('diproses_pada')->nullable();

            $table->timestamps();
            $table->index(['fasilitas_id', 'tanggal', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
