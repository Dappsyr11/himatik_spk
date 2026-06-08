<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Kriteria extends Model {
    protected $table = 'kriteria';
    protected $fillable = ['kode', 'nama', 'tipe', 'deskripsi', 'bobot']; // tambah bobot
    
    public function penilaian() { return $this->hasMany(Penilaian::class); }
    // relasi bobot() dihapus
}