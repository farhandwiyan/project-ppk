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
        'status', 'dibatalkan_oleh', 'alasan_pembatalan',
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

    public function scopeTotalDipinjamAlat($query, int $fasilitasId, string $tanggal, string $start, string $end, ?int $excludeId = null): int {
        return (int) $this->scopeBentrok($query, $fasilitasId, $tanggal, $start, $end, $excludeId)
            ->sum('jumlah_peserta');
    }

    public function waktuMulai(): \Illuminate\Support\Carbon
    {
        return \Illuminate\Support\Carbon::parse($this->tanggal->format('Y-m-d') . ' ' . $this->start_time);
    }

    public function batasPembatalanUser(): \Illuminate\Support\Carbon
    {
        return $this->waktuMulai()->subDays(7);
    }

    public function bisaDibatalkanOlehUser(): bool
    {
        return in_array($this->status, ['menunggu', 'disetujui'])
            && now()->lt($this->batasPembatalanUser());
    }

}
