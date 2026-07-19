<?php

/**
 * Hitung Net Present Value.
 *
 * @param float $investasiAwal    Nilai investasi di tahun ke-0 (positif)
 * @param float $tingkatDiskonto  Dalam desimal, misal 10% = 0.10
 * @param float[] $cashFlows      Cash flow tiap tahun, index 0 = tahun ke-1
 */
function hitungNPV(float $investasiAwal, float $tingkatDiskonto, array $cashFlows): float
{
    $npv = -$investasiAwal;

    foreach ($cashFlows as $index => $cashFlow) {
        $tahun = $index + 1;
        $npv += $cashFlow / (1 + $tingkatDiskonto) ** $tahun;
    }

    return $npv;
}