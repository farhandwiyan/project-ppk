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
        'created_by',
    ];

    protected $casts = [
        'kapasitas' => 'integer',
    ];

    public function creator() {
        return $this->belongsTo(User::class, 'created_by');
    }
}
