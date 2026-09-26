<?php
namespace App\Services;

use App\Models\Reservation;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use App\Exceptions\SlotBentrokException;

class ReservationService
{
    public function ajukan(array $data, int $userId, UploadedFile $surat, ?UploadedFile $proposal): Reservation {
        return DB::transaction(function () use ($data, $userId, $surat, $proposal) {
            $bentrok = Reservation::bentrok(
                $data['fasilitas_id'], $data['tanggal'],
                $data['start_time'], $data['end_time']
            )->lockForUpdate()->exists();

            if ($bentrok) {
                throw new SlotBentrokException('Slot waktu sudah dipesan/menunggu persetujuan');
            }

            // simpan file ke storage
            $suratPath = $surat->store('reservasi/surat', 'public');
            $proposalPath = $proposal?->store('reservasi/proposal', 'public');

            return Reservation::create([
                ...$data,
                'user_id' => $userId,
                'status' => 'menunggu',
                'surat_peminjaman_path' => $suratPath,
                'proposal_kegiatan_path' => $proposalPath,
            ]);
        });
    }
}