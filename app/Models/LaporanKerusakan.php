<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LaporanKerusakan extends Model
{
    protected $table = 'laporan_kerusakan';

    protected $fillable = [
        'user_id',
        'fasilitas_id',
        'nama_pelapor',
        'email',
        'nomor_telepon',
        'deskripsi',
        'status',
        'bukti_kerusakan',
    ];

    protected $casts = [
        'bukti_kerusakan' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function fasilitas(): BelongsTo
    {
        return $this->belongsTo(Fasilitas::class);
    }
}