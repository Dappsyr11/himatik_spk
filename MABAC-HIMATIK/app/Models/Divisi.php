<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Divisi extends Model {
    protected $table = 'divisi';
    protected $fillable = ['nama','kode','deskripsi','aktif'];
    protected $casts = ['aktif' => 'boolean'];
    public function staff() { return $this->hasMany(Staff::class); }
    public function hasilMabac() { return $this->hasMany(HasilMabac::class); }
    public function staffAktif() { return $this->hasMany(Staff::class)->where('aktif',true); }
}
