<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LaporanKerusakan extends Model
{
    public const STATUS_BARU = 'baru';

    public const STATUS_DIPROSES = 'diproses';

    public const STATUS_SELESAI = 'selesai';

    public const STATUS_DITOLAK = 'ditolak';

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
        'alasan_penolakan',
        'catatan_penyelesaian',
        'diproses_oleh',
        'diproses_pada',
    ];

    protected $casts = [
        'bukti_kerusakan' => 'array',
        'diproses_pada' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function fasilitas(): BelongsTo
    {
        return $this->belongsTo(Fasilitas::class);
    }

    public function petugas(): BelongsTo
    {
        return $this->belongsTo(User::class, 'diproses_oleh');
    }

    /**
     * Status tujuan yang diizinkan dari status saat ini.
     *
     * @return array<int, string>
     */
    public function statusTujuan(): array
    {
        return match ($this->status) {
            self::STATUS_BARU => [self::STATUS_DIPROSES, self::STATUS_DITOLAK],
            self::STATUS_DIPROSES => [self::STATUS_SELESAI, self::STATUS_DITOLAK],
            default => [],
        };
    }

    public function bisaDiubahKe(string $status): bool
    {
        return in_array($status, $this->statusTujuan(), true);
    }
}