<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fasilitas extends Model
{
    use HasFactory;

    protected $table = 'fasilitas';

    protected $fillable = [
        'nama',
        'tipe_fasilitas',
        'lokasi',
        'deskripsi',
        'kapasitas',
        'status',
        'created_by',
    ];

    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'fasilitas_id');
    }

    public function isAlat(): bool
    {
        return strtolower($this->tipe_fasilitas) === 'alat';
    }

    public function bisaDireservasi(): bool
    {
        return $this->status === 'aktif';
    }

    protected $casts = [
        'kapasitas' => 'integer',
    ];

    public function creator() {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function laporanKerusakan()
    {
        return $this->hasMany(LaporanKerusakan::class, 'fasilitas_id');
    }

}
