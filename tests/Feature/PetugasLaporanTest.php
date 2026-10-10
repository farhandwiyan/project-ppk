<?php

use App\Models\Fasilitas;
use App\Models\LaporanKerusakan;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function buatUser(string $role): User
{
    return User::create([
        'nama' => ucfirst($role),
        'email' => $role.uniqid().'@example.com',
        'password' => bcrypt('password'),
        'role' => $role,
        'status' => 'verified',
    ]);
}

function buatFasilitas(): Fasilitas
{
    return Fasilitas::create([
        'nama' => 'Aula Utama',
        'tipe_fasilitas' => 'aula',
        'lokasi' => 'Gedung A',
        'deskripsi' => 'Aula besar',
        'kapasitas' => 100,
        'status' => 'aktif',
    ]);
}

function buatLaporan(User $pelapor, Fasilitas $fasilitas, string $status = 'baru'): LaporanKerusakan
{
    return LaporanKerusakan::create([
        'user_id' => $pelapor->id,
        'fasilitas_id' => $fasilitas->id,
        'nama_pelapor' => 'Joko',
        'email' => 'joko@example.com',
        'nomor_telepon' => '08123456789',
        'deskripsi' => 'Proyektor tidak menyala',
        'status' => $status,
        'bukti_kerusakan' => ['laporan/bukti.jpg'],
    ]);
}

beforeEach(function () {
    $this->petugas = buatUser('petugas');
    $this->pengguna = buatUser('user');
    $this->fasilitas = buatFasilitas();
});

test('petugas dapat melihat daftar laporan yang masuk', function () {
    buatLaporan($this->pengguna, $this->fasilitas);

    $this->actingAs($this->petugas)
        ->get(route('petugas.laporan.index'))
        ->assertOk()
        ->assertSee('Proyektor tidak menyala', false)
        ->assertSee('Aula Utama');
});

test('petugas dapat memfilter laporan berdasarkan status', function () {
    buatLaporan($this->pengguna, $this->fasilitas, 'baru');
    LaporanKerusakan::where('status', 'baru')->update(['deskripsi' => 'Laporan baru saja']);
    buatLaporan($this->pengguna, $this->fasilitas, 'selesai');

    $this->actingAs($this->petugas)
        ->get(route('petugas.laporan.index', ['status' => 'selesai']))
        ->assertOk()
        ->assertDontSee('Laporan baru saja');
});

test('petugas dapat mengubah status menjadi diproses', function () {
    $laporan = buatLaporan($this->pengguna, $this->fasilitas);

    $this->actingAs($this->petugas)
        ->patch(route('petugas.laporan.status', $laporan->id), ['status' => 'diproses'])
        ->assertRedirect(route('petugas.laporan.show', $laporan->id))
        ->assertSessionHas('success');

    $laporan->refresh();
    expect($laporan->status)->toBe('diproses')
        ->and($laporan->diproses_oleh)->toBe($this->petugas->id)
        ->and($laporan->diproses_pada)->not->toBeNull();
});

test('petugas dapat menyelesaikan laporan dengan catatan penyelesaian', function () {
    $laporan = buatLaporan($this->pengguna, $this->fasilitas, 'diproses');

    $this->actingAs($this->petugas)
        ->patch(route('petugas.laporan.status', $laporan->id), [
            'status' => 'selesai',
            'catatan_penyelesaian' => 'Proyektor sudah diganti',
        ])
        ->assertSessionHas('success');

    $laporan->refresh();
    expect($laporan->status)->toBe('selesai')
        ->and($laporan->catatan_penyelesaian)->toBe('Proyektor sudah diganti');
});

test('petugas tidak dapat menyelesaikan laporan tanpa catatan penyelesaian', function () {
    $laporan = buatLaporan($this->pengguna, $this->fasilitas, 'diproses');

    $this->actingAs($this->petugas)
        ->patch(route('petugas.laporan.status', $laporan->id), ['status' => 'selesai'])
        ->assertSessionHasErrors('catatan_penyelesaian');

    expect($laporan->refresh()->status)->toBe('diproses');
});

test('perubahan status tetap tampil setelah halaman dimuat ulang', function () {
    $laporan = buatLaporan($this->pengguna, $this->fasilitas, 'diproses');

    $this->actingAs($this->petugas)
        ->patch(route('petugas.laporan.status', $laporan->id), [
            'status' => 'selesai',
            'catatan_penyelesaian' => 'Sudah diperbaiki teknisi',
        ]);

    $this->actingAs($this->petugas)
        ->get(route('petugas.laporan.show', $laporan->id))
        ->assertOk()
        ->assertSee('Sudah diperbaiki teknisi')
        ->assertSee('selesai');
});

test('petugas tidak dapat menolak laporan tanpa alasan', function () {
    $laporan = buatLaporan($this->pengguna, $this->fasilitas);

    $this->actingAs($this->petugas)
        ->patch(route('petugas.laporan.status', $laporan->id), ['status' => 'ditolak'])
        ->assertSessionHasErrors('alasan_penolakan');

    expect($laporan->refresh()->status)->toBe('baru');
});

test('petugas dapat menolak laporan dengan alasan yang valid', function () {
    $laporan = buatLaporan($this->pengguna, $this->fasilitas);

    $this->actingAs($this->petugas)
        ->patch(route('petugas.laporan.status', $laporan->id), [
            'status' => 'ditolak',
            'alasan_penolakan' => 'Kerusakan tidak ditemukan',
        ])
        ->assertSessionHas('success');

    $laporan->refresh();
    expect($laporan->status)->toBe('ditolak')
        ->and($laporan->alasan_penolakan)->toBe('Kerusakan tidak ditemukan');
});

test('perubahan status tetap tampil setelah halaman dimuat ulang', function () {
    $laporan = buatLaporan($this->pengguna, $this->fasilitas, 'diproses');

    $this->actingAs($this->petugas)
        ->patch(route('petugas.laporan.status', $laporan->id), [
            'status' => 'selesai',
            'catatan_penyelesaian' => 'Sudah diperbaiki teknisi',
        ]);

    $this->actingAs($this->petugas)
        ->get(route('petugas.laporan.show', $laporan->id))
        ->assertOk()
        ->assertSee('Sudah diperbaiki teknisi')
        ->assertSee('selesai');
});

test('laporan yang sudah final tidak dapat diubah lagi', function () {
    $laporan = buatLaporan($this->pengguna, $this->fasilitas, 'selesai');

    $this->actingAs($this->petugas)
        ->patch(route('petugas.laporan.status', $laporan->id), [
            'status' => 'ditolak',
            'alasan_penolakan' => 'Coba ubah',
        ])
        ->assertSessionHas('error');

    expect($laporan->refresh()->status)->toBe('selesai');
});

test('laporan baru tidak dapat langsung diselesaikan', function () {
    $laporan = buatLaporan($this->pengguna, $this->fasilitas);

    $this->actingAs($this->petugas)
        ->patch(route('petugas.laporan.status', $laporan->id), [
            'status' => 'selesai',
            'catatan_penyelesaian' => 'Langsung selesai',
        ])
        ->assertSessionHas('error');

    expect($laporan->refresh()->status)->toBe('baru');
});

test('pengguna biasa tidak dapat mengubah status laporan', function () {
    $laporan = buatLaporan($this->pengguna, $this->fasilitas);

    $this->actingAs($this->pengguna)
        ->patch(route('petugas.laporan.status', $laporan->id), ['status' => 'diproses'])
        ->assertForbidden();

    $this->actingAs($this->pengguna)
        ->get(route('petugas.laporan.index'))
        ->assertForbidden();

    expect($laporan->refresh()->status)->toBe('baru');
});

test('tamu diarahkan ke halaman login', function () {
    $laporan = buatLaporan($this->pengguna, $this->fasilitas);

    $this->patch(route('petugas.laporan.status', $laporan->id), ['status' => 'diproses'])
        ->assertRedirect('/login');
});

test('petugas tidak dapat membuat laporan kerusakan', function () {
    $this->actingAs($this->petugas)->get(route('laporan.create'))->assertForbidden();
    $this->actingAs($this->petugas)->post(route('laporan.store'), [])->assertForbidden();
});

test('menolak laporan tidak mengubah status reservasi', function () {
    $laporan = buatLaporan($this->pengguna, $this->fasilitas);

    $reservasi = Reservation::create([
        'user_id' => $this->pengguna->id,
        'fasilitas_id' => $this->fasilitas->id,
        'nama_pemohon' => 'Joko',
        'instansi_pemohon' => 'Informatika',
        'nama_kegiatan' => 'Seminar',
        'deskripsi_kegiatan' => 'Seminar jurusan',
        'jumlah_peserta' => 20,
        'tanggal' => now()->addDays(3)->toDateString(),
        'start_time' => '09:00',
        'end_time' => '10:00',
        'surat_peminjaman_path' => 'surat/contoh.pdf',
        'status' => 'disetujui',
    ]);

    $this->actingAs($this->petugas)
        ->patch(route('petugas.laporan.status', $laporan->id), [
            'status' => 'ditolak',
            'alasan_penolakan' => 'Tidak valid',
        ]);

    expect($reservasi->refresh()->status)->toBe('disetujui')
        ->and($this->fasilitas->refresh()->status)->toBe('aktif');
});