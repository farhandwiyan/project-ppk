<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $fillable = [
        'user_id', 'fasilitas_id', 
        'nama_pemohon', 'instansi_pemohon', 
        'nama_kegiatan', 'deskripsi_kegiatan', 'jumlah_peserta',
        'tanggal', 'start_time', 'end_time', 
        'surat_peminjaman_path', 'proposal_kegiatan_path',
        'status', 'dibatalkan_oleh', 'alasaan_pembatalan',
        'diproses_oleh', 'diproses_pada',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'diproses_pada' => 'datetime',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function fasilitas() {
        return $this->belongsTo(Fasilitas::class);
    }

    public function scopeBentrok($query, int $fasilitasId, string $tanggal, string $start, string $end, ?int $excludeId = null) {
        return $query->where('fasilitas_id', $fasilitasId)
        ->where('tanggal', $tanggal)
        ->whereIn('status', ['menunggu', 'disetujui'])
        ->where('start_time', '<', $end)
        ->where('end_time', '>', $start)
        ->when($excludeId, fn ($q) => $q->where('id', '!=', $excludeId));
    }
}
