<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penjualan extends Model
{
    use HasFactory;

    protected $table = 'penjualan';
    protected $primaryKey = 'ID';
    public $timestamps = false;

    protected $fillable = [
        'TANGGAL','JAM','TTL_PENJUALAN','TU_EVI','PIUTANG','REFUND',
        'STR_BCA','STR_BNI','STR_MDR','STR_BRI','TRF_BCA','TRF_BNI','TRF_MDR','TRF_BRI','TRF_BTN',
        'BIAYA','CASH_FLOW','SOEVI','SOCITILINK','SOGARUDA','SOLION','SOPELNI','SODLU','SOQGCORNER',
        'SOSRIWIJAYA','SOTRANSNUSA','SOAIRASIA','TUCITILINK','TUGARUDA','TULION','TUPELNI','TUDLU',
        'TUQGCORNER','TUSRIWIJAYA','TUTRANSNUSA','TUAIRASIA','PLN','SALDOPLN','USR'
    ];
}
