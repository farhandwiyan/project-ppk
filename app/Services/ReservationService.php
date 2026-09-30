<?php
namespace App\Services;

use App\Exceptions\PembatalanTidakDiizinkanException;
use App\Models\Reservation;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use App\Exceptions\SlotBentrokException;
use App\Models\Fasilitas;

class ReservationService
{
    public function ajukan(array $data, int $userId, UploadedFile $surat, ?UploadedFile $proposal): Reservation {
        return DB::transaction(function () use ($data, $userId, $surat, $proposal) {
            $facility = Fasilitas::lockForUpdate()->findOrFail($data['fasilitas_id']);
 
            if (!$facility->bisaDireservasi()) {
                throw new SlotBentrokException('Fasilitas ini sedang tidak tersedia untuk direservasi (status: ' . $facility->status . ').');
            }
 
            $this->pastikanSlotTersedia($facility, $data, $data['jumlah_peserta']);
 
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

    public function setujui(Reservation $reservation, int $petugasId): void
    {
        DB::transaction(function () use ($reservation, $petugasId) {
            $facility = Fasilitas::lockForUpdate()->findOrFail($reservation->fasilitas_id);
 
            if (!$facility->bisaDireservasi()) {
                throw new SlotBentrokException('Fasilitas ini sedang tidak tersedia (status: ' . $facility->status . '), tidak bisa disetujui.');
            }
 
            $this->pastikanSlotTersedia(
                $facility,
                [
                    'tanggal' => $reservation->tanggal->format('Y-m-d'),
                    'start_time' => $reservation->start_time,
                    'end_time' => $reservation->end_time,
                ],
                $reservation->jumlah_peserta,
                excludeId: $reservation->id,
                statusYangDihitung: ['disetujui'] 
            );
 
            $reservation->update([
                'status' => 'disetujui',
                'diproses_oleh' => $petugasId,
                'diproses_pada' => now(),
            ]);
        });
    }

    public function tolak(Reservation $reservation, int $petugasId, ?string $alasan = null): void
    {
        $reservation->update([
            'status' => 'ditolak',
            'alasan_pembatalan' => $alasan,
            'diproses_oleh' => $petugasId,
            'diproses_pada' => now(),
        ]);
    }

    public function batalkanOlehUser(Reservation $reservation, string $alasan)
    {
        if (!$reservation->bisaDibatalkanOlehUser()) {
            throw new PembatalanTidakDiizinkanException (
                'Reservasi hanya dapat dibatalkan maksimal H-7 sebelum jadwal kegiatan.'
            );
        }
 
        $reservation->update([
            'status' => 'dibatalkan',
            'dibatalkan_oleh' => 'user',
            'alasan_pembatalan' => $alasan,
        ]);

        return $reservation->fresh();
    }


    public function batalkanOlehPetugas(Reservation $reservation, int $petugasId, string $alasan): void
    {
        $reservation->update([
            'status' => 'dibatalkan',
            'dibatalkan_oleh' => 'petugas',
            'alasan_pembatalan' => $alasan,
            'diproses_oleh' => $petugasId,
            'diproses_pada' => now(),
        ]);
    }

    protected function pastikanSlotTersedia(
        Fasilitas $facility,
        array $slot,
        int $jumlahDiminta,
        ?int $excludeId = null,
        array $statusYangDihitung = ['menunggu', 'disetujui']
    ): void {
        $query = Reservation::where('fasilitas_id', $facility->id)
            ->where('tanggal', $slot['tanggal'])
            ->whereIn('status', $statusYangDihitung)
            ->where('start_time', '<', $slot['end_time'])
            ->where('end_time', '>', $slot['start_time'])
            ->when($excludeId, fn ($q) => $q->where('id', '!=', $excludeId));
 
        if ($facility->isAlat()) {
            // kapasitas = jumlah unit stok
            $terpakai = (int) (clone $query)->sum('jumlah_peserta');
 
            if ($terpakai + $jumlahDiminta > $facility->kapasitas) {
                $sisa = max($facility->kapasitas - $terpakai, 0);
                throw new SlotBentrokException("Stok alat tidak mencukupi pada slot ini. Sisa stok: {$sisa} unit.");
            }
        } else {
            if ((clone $query)->exists()) {
                throw new SlotBentrokException('Slot waktu sudah dipesan/menunggu persetujuan.');
            }
        }
    }
}