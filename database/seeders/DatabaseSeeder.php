<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Area;
use App\Models\Report;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Akun Demo Tiga Peran (Password: password123)
        $pelapor = User::create([
            'nama_lengkap' => 'Andi Pratama',
            'username'     => 'andi',
            'password'     => Hash::make('password123'),
            'role'         => 'pelapor',
        ]);

        $relawan = User::create([
            'nama_lengkap' => 'Sari Indah',
            'username'     => 'sari',
            'password'     => Hash::make('password123'),
            'role'         => 'relawan',
        ]);

        $koordinator = User::create([
            'nama_lengkap' => 'Budi Santoso',
            'username'     => 'budi',
            'password'     => Hash::make('password123'),
            'role'         => 'koordinator',
        ]);

        // 2. Data Area Kerawanan
        $areaA = Area::create([
            'nama_area'         => 'Sawit Sektor Timur',
            'deskripsi'         => 'Perkebunan kelapa sawit perbatasan desa, rawan rambatan api saat musim kemarau.',
            'tingkat_kerawanan' => 'tinggi',
        ]);

        $areaB = Area::create([
            'nama_area'         => 'Hutan Lindung Bukit Hijau',
            'deskripsi'         => 'Kawasan lereng bervegetasi lebat dengan akses jalan tanah terbatas.',
            'tingkat_kerawanan' => 'sedang',
        ]);

        $areaC = Area::create([
            'nama_area'         => 'Lahan Gambut Rawa Makmur',
            'deskripsi'         => 'Lahan gambut kering rentan kebakaran bawah tanah (ground fire).',
            'tingkat_kerawanan' => 'tinggi',
        ]);

        // 3. Sampel Laporan Awal untuk Pengujian
        Report::create([
            'user_id'           => $pelapor->id,
            'area_id'           => $areaA->id,
            'judul'             => 'Titik Asap Tebal di Batas Sektor 3',
            'deskripsi_kondisi' => 'Terlihat kepulan asap membubung tinggi disertai bau vegetasi terbakar sejak siang.',
            'waktu_kejadian'    => now()->subHours(2),
            'latitude'          => -0.1234567,
            'longitude'         => 101.4567890,
            'evidence_path'     => null,
            'status'            => 'menunggu_tinjauan',
            'catatan_relawan'   => null,
        ]);

        Report::create([
            'user_id'           => $pelapor->id,
            'area_id'           => $areaC->id,
            'judul'             => 'Bara Api pada Lapisan Gambut Kering',
            'deskripsi_kondisi' => 'Asap putih pekat keluar dari tanah gambut dekat parit kering.',
            'waktu_kejadian'    => now()->subDay(),
            'latitude'          => -0.1543210,
            'longitude'         => 101.4987650,
            'evidence_path'     => null,
            'status'            => 'terverifikasi',
            'catatan_relawan'   => 'Regu darat sudah mengonfirmasi titik dan menyiapkan pompa apung.',
        ]);
    }
}