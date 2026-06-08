<?php

namespace Database\Seeders;

use App\Models\BobotKriteria;
use App\Models\Divisi;
use App\Models\Kriteria;
use App\Models\Penilaian;
use App\Models\SkalaPenilaian;
use App\Models\Staff;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin & Pimpinan
        User::create([
            'name'     => 'Administrator',
            'username' => 'admin',
            'email'    => 'admin@himatik.ac.id',
            'password' => Hash::make('password'),
            'role'     => 'admin',
        ]);

        User::create([
            'name'     => 'Ketua HIMATIK',
            'username' => 'ketua',
            'email'    => 'ketua@himatik.ac.id',
            'password' => Hash::make('password'),
            'role'     => 'pimpinan',
        ]);

        // Skala Penilaian
        $skala = [
            ['label' => 'Sangat Baik',   'nilai' => 5, 'deskripsi' => 'Performa sangat memuaskan dan melebihi ekspektasi'],
            ['label' => 'Baik',           'nilai' => 4, 'deskripsi' => 'Performa di atas rata-rata'],
            ['label' => 'Cukup',          'nilai' => 3, 'deskripsi' => 'Performa sesuai standar minimal'],
            ['label' => 'Kurang',         'nilai' => 2, 'deskripsi' => 'Performa di bawah standar dan perlu perbaikan'],
            ['label' => 'Sangat Kurang',  'nilai' => 1, 'deskripsi' => 'Performa sangat rendah'],
        ];
        foreach ($skala as $s) SkalaPenilaian::create($s);

        // Kriteria (10 kriteria sesuai diagram)
        $kriteriaData = [
            ['kode' => 'C1',  'nama' => 'Integritas',           'tipe' => 'benefit', 'deskripsi' => 'Kejujuran, tanggung jawab, dan komitmen terhadap nilai-nilai organisasi'],
            ['kode' => 'C2',  'nama' => 'Komunikasi',           'tipe' => 'benefit', 'deskripsi' => 'Kemampuan berkomunikasi secara efektif dengan anggota dan pihak luar'],
            ['kode' => 'C3',  'nama' => 'Kehandalan',           'tipe' => 'benefit', 'deskripsi' => 'Kemampuan diandalkan dalam menyelesaikan tugas dengan konsisten'],
            ['kode' => 'C4',  'nama' => 'Penyelesaian Masalah', 'tipe' => 'benefit', 'deskripsi' => 'Kemampuan mengidentifikasi dan menyelesaikan permasalahan secara efektif'],
            ['kode' => 'C5',  'nama' => 'Etos Kerja',           'tipe' => 'benefit', 'deskripsi' => 'Semangat, disiplin, dan dedikasi dalam bekerja'],
            ['kode' => 'C6',  'nama' => 'Skill Teknis',         'tipe' => 'benefit', 'deskripsi' => 'Penguasaan keahlian teknis yang relevan dengan bidang tugasnya'],
            ['kode' => 'C7',  'nama' => 'Kreativitas',          'tipe' => 'benefit', 'deskripsi' => 'Kemampuan menghasilkan ide-ide inovatif dan solusi kreatif'],
            ['kode' => 'C8',  'nama' => 'Adaptasi',             'tipe' => 'benefit', 'deskripsi' => 'Kemampuan beradaptasi dengan perubahan dan situasi baru'],
            ['kode' => 'C9',  'nama' => 'Produktivitas',        'tipe' => 'benefit', 'deskripsi' => 'Jumlah dan kualitas hasil kerja yang dicapai'],
            ['kode' => 'C10', 'nama' => 'Kehadiran',            'tipe' => 'benefit', 'deskripsi' => 'Tingkat kehadiran dan partisipasi dalam kegiatan himpunan'],
        ];
        foreach ($kriteriaData as $k) Kriteria::create($k);

        // Bobot kriteria untuk periode 2024-1 (total = 1.0)
        $bobot = [0.15, 0.12, 0.10, 0.12, 0.10, 0.10, 0.08, 0.08, 0.10, 0.05];
        $periode = '2024';
        foreach (Kriteria::all() as $i => $k) {
            BobotKriteria::create([
                'kriteria_id' => $k->id,
                'periode'     => $periode,
                'bobot'       => $bobot[$i],
            ]);
        }

        // Divisi
        $divisiData = [
            ['nama' => 'Badan Pengurus Harian', 'kode' => 'BPH',     'deskripsi' => 'Badan inti pengurus himpunan'],
            ['nama' => 'Divisi Akademik',        'kode' => 'AKD',     'deskripsi' => 'Mengelola kegiatan akademik dan pendidikan'],
            ['nama' => 'Divisi Riset & Teknologi','kode' => 'RISTEK', 'deskripsi' => 'Berfokus pada penelitian dan inovasi teknologi'],
            ['nama' => 'Divisi Kewirausahaan',   'kode' => 'WIRA',    'deskripsi' => 'Mengelola kegiatan kewirausahaan'],
            ['nama' => 'Divisi Sosial & Pengabdian','kode' => 'SOSMAS','deskripsi' => 'Kegiatan sosial dan pengabdian masyarakat'],
            ['nama' => 'Divisi Media & Komunikasi','kode' => 'MEDKOM','deskripsi' => 'Mengelola media informasi dan komunikasi'],
            ['nama' => 'Divisi Olahraga & Seni', 'kode' => 'OLASEN', 'deskripsi' => 'Mengelola kegiatan olahraga dan seni'],
        ];
        foreach ($divisiData as $d) Divisi::create($d);

        // Staff per divisi
        $allStaff = [
            'BPH'    => [
                ['nama' => 'Ahmad Rizki Pratama',  'nim' => '2021001001', 'jabatan' => 'Ketua Umum'],
                ['nama' => 'Siti Nurhaliza',        'nim' => '2021001002', 'jabatan' => 'Wakil Ketua'],
                ['nama' => 'Budi Santoso',          'nim' => '2021001003', 'jabatan' => 'Sekretaris Umum'],
            ],
            'AKD'    => [
                ['nama' => 'Dewi Rahayu',           'nim' => '2021002001', 'jabatan' => 'Kepala Divisi'],
                ['nama' => 'Eko Wahyudi',           'nim' => '2021002002', 'jabatan' => 'Koordinator Tutorial'],
                ['nama' => 'Fitri Handayani',       'nim' => '2021002003', 'jabatan' => 'Koordinator Beasiswa'],
            ],
            'RISTEK' => [
                ['nama' => 'Galih Permana',         'nim' => '2021003001', 'jabatan' => 'Kepala Divisi'],
                ['nama' => 'Hana Kusuma',           'nim' => '2021003002', 'jabatan' => 'Koordinator Riset'],
                ['nama' => 'Irfan Hidayat',         'nim' => '2021003003', 'jabatan' => 'Koordinator Pengembangan'],
            ],
            'WIRA'   => [
                ['nama' => 'Jasmine Putri',         'nim' => '2021004001', 'jabatan' => 'Kepala Divisi'],
                ['nama' => 'Kevin Prasetyo',        'nim' => '2021004002', 'jabatan' => 'Koordinator Merchandise'],
                ['nama' => 'Lina Marlina',          'nim' => '2021004003', 'jabatan' => 'Koordinator Event Bisnis'],
            ],
            'SOSMAS' => [
                ['nama' => 'Muhammad Farhan',       'nim' => '2021005001', 'jabatan' => 'Kepala Divisi'],
                ['nama' => 'Nadia Sari',            'nim' => '2021005002', 'jabatan' => 'Koordinator Pengabdian'],
                ['nama' => 'Oscar Firmansyah',      'nim' => '2021005003', 'jabatan' => 'Koordinator Donasi'],
            ],
            'MEDKOM' => [
                ['nama' => 'Putri Anggraini',       'nim' => '2021006001', 'jabatan' => 'Kepala Divisi'],
                ['nama' => 'Rizal Maulana',         'nim' => '2021006002', 'jabatan' => 'Koordinator Desain'],
                ['nama' => 'Sari Dewi',             'nim' => '2021006003', 'jabatan' => 'Koordinator Konten'],
            ],
            'OLASEN' => [
                ['nama' => 'Taufik Hidayat',        'nim' => '2021007001', 'jabatan' => 'Kepala Divisi'],
                ['nama' => 'Uswatun Hasanah',       'nim' => '2021007002', 'jabatan' => 'Koordinator Seni'],
                ['nama' => 'Vino Ramadhan',         'nim' => '2021007003', 'jabatan' => 'Koordinator Olahraga'],
            ],
        ];

        // Nilai penilaian contoh (1-5 acak realistis)
        $nilaiContoh = [
            [5,4,5,4,5,3,4,4,5,5],
            [4,5,4,5,4,4,3,5,4,4],
            [3,4,3,4,3,5,4,3,3,5],
        ];
        $labelMap = [5 => 'Sangat Baik', 4 => 'Baik', 3 => 'Cukup', 2 => 'Kurang', 1 => 'Sangat Kurang'];

        foreach ($allStaff as $kode => $StaffList) {
            $divisi = Divisi::where('kode', $kode)->first();
            foreach ($StaffList as $idx => $sd) {
                $Staff = Staff::create([
                    'divisi_id' => $divisi->id,
                    'nama'      => $sd['nama'],
                    'nim'       => $sd['nim'],
                    'jabatan'   => $sd['jabatan'],
                    'aktif'     => true,
                ]);

                // Input penilaian contoh
                $nilaiStaff = $nilaiContoh[$idx % 3];
                foreach (Kriteria::all() as $ki => $k) {
                    $nilai = $nilaiStaff[$ki];
                    Penilaian::create([
                        'Staff_id'    => $Staff->id,
                        'kriteria_id' => $k->id,
                        'periode'     => $periode,
                        'label_nilai' => $labelMap[$nilai],
                        'nilai'       => $nilai,
                    ]);
                }
            }
        }
    }
}