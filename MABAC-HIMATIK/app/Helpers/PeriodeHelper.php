<?php

namespace App\Helpers;

class PeriodeHelper
{
    /**
     * Returns all months as an associative array keyed by 'YYYY-MM'.
     * Includes the current year and the previous year.
     */
    public static function daftarBulan(): array
    {
        $bulan = [];
        $namaBulan = [
            '01' => 'Januari', '02' => 'Februari', '03' => 'Maret',
            '04' => 'April',   '05' => 'Mei',       '06' => 'Juni',
            '07' => 'Juli',    '08' => 'Agustus',   '09' => 'September',
            '10' => 'Oktober', '11' => 'November',  '12' => 'Desember',
        ];

        $tahunSekarang = (int) now()->format('Y');

        foreach ([$tahunSekarang - 1, $tahunSekarang] as $tahun) {
            foreach ($namaBulan as $num => $nama) {
                $key = "{$tahun}-{$num}";
                $bulan[$key] = "{$nama} {$tahun}";
            }
        }

        return $bulan;
    }

    /**
     * Returns the current period key in 'YYYY-MM' format.
     */
    public static function periodeDefault(): string
    {
        return now()->format('Y-m');
    }

    /**
     * Returns a human-readable label for a given 'YYYY-MM' period key.
     * e.g. '2025-06' → 'Juni 2025'
     */
    public static function labelBulan(string $periode): string
    {
        $namaBulan = [
            '01' => 'Januari', '02' => 'Februari', '03' => 'Maret',
            '04' => 'April',   '05' => 'Mei',       '06' => 'Juni',
            '07' => 'Juli',    '08' => 'Agustus',   '09' => 'September',
            '10' => 'Oktober', '11' => 'November',  '12' => 'Desember',
        ];

        [$tahun, $bulan] = explode('-', $periode);

        return ($namaBulan[$bulan] ?? $bulan) . ' ' . $tahun;
    }
}