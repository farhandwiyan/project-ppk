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
        Schema::create('laporan_kerusakan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('fasilitas_id')->constrained('fasilitas')->onDelete('cascade');

            #data pelapor
            $table->string("nama_pelapor", 25)->nullable(false);
            $table->string("email", 50)->nullable(false);
            $table->string("nomor_telepon", 13)->nullable(false);

            #data laporan
            $table->text("deskripsi")->nullable(false);
            $table->enum("status", ["diproses", "selesai", "ditolak"])->default("diproses");
            $table->json("bukti_kerusakan")->nullable(false);

            #timestamp
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporan_kerusakan');
    }
};
