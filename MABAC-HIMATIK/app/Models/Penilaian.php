<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Penilaian extends Model {
    protected $table = 'penilaian';
    protected $fillable = ['staff_id','kriteria_id','periode','label_nilai','nilai'];
    public function staff() { return $this->belongsTo(Staff::class); }
    public function kriteria() { return $this->belongsTo(Kriteria::class); }
}
