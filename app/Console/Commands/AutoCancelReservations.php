<?php

namespace App\Console\Commands;

use App\Models\Reservation;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class AutoCancelReservations extends Command
{
    protected $signature = 'reservations:auto-cancel';
    protected $description = 'Batalkan otomatis reservasi berstatus menunggu yang sudah lebih dari 3x24 jam sejak diajukan dan belum diproses petugas';

    public function handle(): void
    {
        $batasWaktu = Carbon::now()->subHours(72);

        $reservasiExpired = Reservation::where('status', 'menunggu')
            ->where('created_at', '<=', $batasWaktu)
            ->get();

        foreach ($reservasiExpired as $reservasi) {
            $reservasi->update([
                'status' => 'dibatalkan',
                'dibatalkan_oleh' => 'sistem',
                'alasan_pembatalan' => 'Otomatis dibatalkan: belum diproses petugas dalam waktu 3x24 jam sejak reservasi diajukan.',
            ]);
        }

        $this->info("{$reservasiExpired->count()} reservasi dibatalkan otomatis.");
    }
}