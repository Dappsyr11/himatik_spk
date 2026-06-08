<?php
namespace App\Services;

use App\Models\BobotKriteria;
use App\Models\Divisi;
use App\Models\HasilMabac;
use App\Models\Kriteria;
use App\Models\Penilaian;
use App\Models\Staff;
use Illuminate\Support\Facades\DB;

/**
 * Metode MABAC (Multi-Attributive Border Approximation area Comparison)
 * Langkah:
 * 1. Matriks Keputusan X
 * 2. Normalisasi → N
 * 3. Matriks Berbobot V = N*w + w
 * 4. Batas Area G (rata-rata geometri)
 * 5. Jarak Q = V - G
 * 6. Nilai Akhir S = sum(Q)
 * 7. Ranking
 */
class MabacService
{
    public function hitungPerDivisi(Divisi $divisi, string $periode): array
    {
        $staffList = Staff::where('divisi_id', $divisi->id)->where('aktif', true)->get();
        if ($staffList->isEmpty()) {
            return ['error' => 'Tidak ada staff aktif di divisi ' . $divisi->nama];
        }

        $kriteria = Kriteria::orderByRaw('CAST(SUBSTRING(kode, 2) AS UNSIGNED) ASC')->get();

        // Bobot langsung dari kolom kriteria, bukan BobotKriteria
        $bobot = $kriteria->pluck('bobot', 'id')->toArray();

        if (empty(array_filter($bobot))) {
            return ['error' => 'Bobot kriteria belum diatur.'];
        }

        // ... sisa kode tidak berubah sama sekali

        // Step 1: Matriks Keputusan X
        $matriksX = [];
        foreach ($staffList as $staff) {
            foreach ($kriteria as $k) {
                $p = Penilaian::where('staff_id', $staff->id)
                    ->where('kriteria_id', $k->id)
                    ->where('periode', $periode)->first();
                $matriksX[$staff->id][$k->id] = $p ? (float)$p->nilai : 0;
            }
        }

        // Step 2: Normalisasi → N
        $matriksN = [];
        foreach ($kriteria as $k) {
            $kolom  = array_map(fn($r) => $r[$k->id], $matriksX);
            $xMin   = min($kolom);
            $xMax   = max($kolom);
            foreach ($staffList as $staff) {
                $x = $matriksX[$staff->id][$k->id];
                if ($xMax == $xMin) {
                    $n = 1;
                } elseif ($k->tipe === 'benefit') {
                    $n = ($x - $xMin) / ($xMax - $xMin);
                } else {
                    $n = ($xMax - $x) / ($xMax - $xMin);
                }
                $matriksN[$staff->id][$k->id] = $n;
            }
        }

        // Step 3: Matriks Berbobot V = N*w + w
        $matriksV = [];
        foreach ($staffList as $staff) {
            foreach ($kriteria as $k) {
                $w = (float)($bobot[$k->id] ?? 0);
                $matriksV[$staff->id][$k->id] = $matriksN[$staff->id][$k->id] * $w + $w;
            }
        }

        // Step 4: Batas Area G (rata-rata geometri per kriteria)
        $batasG = [];
        $n      = count($staffList);
        foreach ($kriteria as $k) {
            $product = 1.0;
            foreach ($staffList as $staff) {
                $v = $matriksV[$staff->id][$k->id];
                $product *= ($v > 0 ? $v : 0.0001);
            }
            $batasG[$k->id] = pow($product, 1 / $n);
        }

        // Step 5: Q = V - G
        $matriksQ = [];
        foreach ($staffList as $staff) {
            foreach ($kriteria as $k) {
                $matriksQ[$staff->id][$k->id] = $matriksV[$staff->id][$k->id] - $batasG[$k->id];
            }
        }

        // Step 6: S = sum(Q)
        $nilaiS = [];
        foreach ($staffList as $staff) {
            $nilaiS[$staff->id] = array_sum($matriksQ[$staff->id]);
        }

        // Step 7: Ranking
        arsort($nilaiS);
        $ranking = [];
        $rank = 1;
        foreach ($nilaiS as $sid => $s) { $ranking[$sid] = $rank++; }

        // Simpan ke DB
        DB::transaction(function () use ($staffList, $divisi, $periode, $nilaiS, $ranking, $matriksX, $matriksN, $matriksV, $batasG, $matriksQ) {
            HasilMabac::where('divisi_id', $divisi->id)->where('periode', $periode)->delete();
            $terbaik = array_key_first($nilaiS);
            foreach ($staffList as $staff) {
                HasilMabac::create([
                    'staff_id'            => $staff->id,
                    'divisi_id'           => $divisi->id,
                    'periode'             => $periode,
                    'nilai_akhir'         => $nilaiS[$staff->id],
                    'peringkat'           => $ranking[$staff->id],
                    'terbaik'             => ($staff->id === $terbaik),
                    'detail_perhitungan'  => json_encode([
                        'matriks_x' => array_combine(array_map('strval', array_keys($matriksX[$staff->id])), array_values($matriksX[$staff->id])),
                        'matriks_n' => array_combine(array_map('strval', array_keys($matriksN[$staff->id])), array_values($matriksN[$staff->id])),
                        'matriks_v' => array_combine(array_map('strval', array_keys($matriksV[$staff->id])), array_values($matriksV[$staff->id])),
                        'batas_g'   => array_combine(array_map('strval', array_keys($batasG)), array_values($batasG)),
                        'matriks_q' => array_combine(array_map('strval', array_keys($matriksQ[$staff->id])), array_values($matriksQ[$staff->id])),
                    ]),
                ]);
        }
        });

        return compact('divisi','periode','kriteria','staffList','matriksX','matriksN','matriksV','batasG','matriksQ','nilaiS','ranking','bobot');
    }

    public function hitungSemuaDivisi(string $periode): void
    {
        foreach (Divisi::where('aktif', true)->get() as $divisi) {
            $this->hitungPerDivisi($divisi, $periode);
        }
    }
}
