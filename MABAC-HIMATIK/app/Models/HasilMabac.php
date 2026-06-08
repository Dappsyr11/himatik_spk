<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class HasilMabac extends Model {
    protected $table = 'hasil_mabac';
    protected $fillable = ['staff_id','divisi_id','periode','nilai_akhir','peringkat','terbaik','detail_perhitungan'];
    protected $casts = ['terbaik'=>'boolean','detail_perhitungan'=>'array'];
    public function staff() { return $this->belongsTo(Staff::class); }
    public function divisi() { return $this->belongsTo(Divisi::class); }
}
