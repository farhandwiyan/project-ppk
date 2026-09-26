<?php

namespace Database\Seeders;

use App\Models\Fasilitas;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FasilitasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminId = User::first()->id ?? null;

        $fasilitas = [
            [
                'nama' => 'Kelas A301',
                'tipe_fasilitas' => 'Ruangan',
                'lokasi' => 'Fakultas Sains dan Matematika',
                'deskripsi' => 'Gedung A Lantai 3',
                'kapasitas' => 40,
                'created_by' => $adminId,
            ],
            [
                'nama' => 'Kelas A302',
                'tipe_fasilitas' => 'Ruangan',
                'lokasi' => 'Fakultas Sains dan Matematika',
                'deskripsi' => 'Gedung A Lantai 3',
                'kapasitas' => 40,
                'created_by' => $adminId,
            ],
            [
                'nama' => 'Kelas A303',
                'tipe_fasilitas' => 'Ruangan',
                'lokasi' => 'Fakultas Sains dan Matematika',
                'deskripsi' => 'Gedung A Lantai 3',
                'kapasitas' => 40,
                'created_by' => $adminId,
            ],
            [
                'nama' => 'Aula Acintya Prasada',
                'tipe_fasilitas' => 'Aula',
                'lokasi' => 'Fakultas Sains dan Matematika',
                'deskripsi' => 'Gedung Acintya Prasada Lantai 6',
                'kapasitas' => 100,
                'created_by' => $adminId,
            ],
            [
                'nama' => 'Lapangan Voli',
                'tipe_fasilitas' => 'Lapangan Olahraga',
                'lokasi' => 'Fakultas Sains dan Matematika',
                'deskripsi' => 'Lapangan Voli Fakultas Sains dan Matematika',
                'kapasitas' => 20,
                'created_by' => $adminId,
            ],
            [
                'nama' => 'Laboratorium Komputer D',
                'tipe_fasilitas' => 'Laboratorium',
                'lokasi' => 'Fakultas Sains dan Matematika',
                'deskripsi' => 'Lab komputer Departemen Informatika',
                'kapasitas' => 35,
                'created_by' => $adminId,
            ],
            [
                'nama' => 'Sound Sistem',
                'tipe_fasilitas' => 'Alat',
                'lokasi' => 'Fakultas Sains dan Matematika',
                'deskripsi' => 'Sound sistem milik Fakultas Sains dan Matematika',
                'kapasitas' => 10,
                'created_by' => $adminId,
            ],
            [
                'nama' => 'Gedung Serba Guna Universitas Diponegoro',
                'tipe_fasilitas' => 'Aula',
                'lokasi' => 'Muladi Dome',
                'deskripsi' => 'Gedung serba guna Universitas Diponegoro',
                'kapasitas' => 4500,
                'created_by' => $adminId,
            ],
        ];

        foreach ($fasilitas as $item) {
            Fasilitas::create($item);
        }
    }
}
