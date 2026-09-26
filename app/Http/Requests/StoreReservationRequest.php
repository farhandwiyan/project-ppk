<?php

namespace App\Http\Requests;

use App\Models\Fasilitas;
use App\Rules\MinimumH2;
use App\Rules\ValidTimeSlot;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreReservationRequest extends FormRequest
{
    public function rules(): array 
    {
        return [
            'fasilitas_id' => ['required', 'exists:fasilitas,id'],
            
            // nama pemohon dibikin max 100 atau 25 aja sama kayak nama user?
            'nama_pemohon' => ['required', 'string', 'max:100'],
            'instansi_pemohon' => ['required', 'string', 'max:100'],

            'nama_kegiatan' => ['required', 'string', 'max:150'],
            'deskripsi_kegiatan' => ['required', 'string', 'max:1000'],
            'jumlah_peserta' => ['required', 'integer', 'min:1'],

            'tanggal' => ['required', 'date', new MinimumH2],
            'start_time' => ['required', 'date_format:H:i', new ValidTimeSlot],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time', new ValidTimeSlot],

            'surat_peminjaman' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:2048'],
            'proposal_kegiatan' => ['nullable', 'file', 'mimes:pdf', 'max:5120'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $fasilitas = Fasilitas::find($this->fasilitas_id);
            if ($fasilitas && $this->jumlah_peserta > $fasilitas->kapasitas) {
                $validator->errors()->add(
                    'jumlah_peserta',
                    "Jumlah peserta melebihi kapasias fasilitas ({$fasilitas->kapasitas} orang)"
                );
            }
        });
    }

    public function messages(): array
    {
        return [
            'surat_peminjaman.required' => 'Surat peminjaman ruangan wajib diunggah',
            'surat_peminjaman.mimes' => 'Surat peminjaman harus berformat PDF/JPG/PNG',
            'proposal_kegiatan.mimes' => 'Proposal kegiatan harus berformat PDF',
        ];
    }
}
