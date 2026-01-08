<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class RekapPenjualanExport implements FromCollection, WithHeadings
{
    protected $tanggalAwal;
    protected $tanggalAkhir;
    protected $jenisData;

    public function __construct($tanggalAwal, $tanggalAkhir, $jenisData)
    {
        $this->tanggalAwal  = $tanggalAwal;
        $this->tanggalAkhir = $tanggalAkhir;
        $this->jenisData    = $jenisData;
    }

    public function collection()
    {
        if ($this->jenisData === 'tiket') {
            return DB::table('tiket')
                ->whereBetween('tgl_issued', [$this->tanggalAwal, $this->tanggalAkhir])
                ->select('tgl_issued', 'kode_booking', 'nama', 'harga_jual', 'nta')
                ->get();
        }

        return DB::table('ppob_history')
            ->whereBetween('tgl', [$this->tanggalAwal, $this->tanggalAkhir])
            ->select('tgl', 'id_pel', 'harga_jual', 'nta')
            ->get();
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'Kode / ID',
            'Nama',
            'Harga',
            'NTA'
        ];
    }
}
