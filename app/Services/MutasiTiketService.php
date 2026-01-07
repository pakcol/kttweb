<?php

namespace App\Services;

use App\Models\MutasiTiket;

class MutasiTiketService
{
    public function create(array $data): MutasiTiket
    {
        return MutasiTiket::updateOrCreate(
            [
                'tiket_kode_booking' => $data['tiket_kode_booking'],
                'tgl_issued'         => $data['tgl_issued'],
            ],
            [
                'tgl_bayar'      => $data['tgl_bayar'] ?? null,
                'harga_bayar'    => $data['harga_bayar'],
                'insentif'       => $data['insentif'] ?? 0,
                'jenis_bayar_id' => $data['jenis_bayar_id'],
                'bank_id'        => $data['bank_id'] ?? null,
                'keterangan'     => $data['keterangan'] ?? null,
            ]
        );
    }
}
