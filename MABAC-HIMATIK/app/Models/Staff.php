<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Staff extends Model {
    protected $table = 'staff';
    protected $fillable = ['divisi_id','nama','nim','jabatan','foto','aktif'];
    protected $casts = ['aktif' => 'boolean'];
    public function divisi() { return $this->belongsTo(Divisi::class); }
    public function penilaian() { return $this->hasMany(Penilaian::class); }
    public function hasilMabac() { return $this->hasMany(HasilMabac::class); }
    public function getFotoUrlAttribute() {
        return $this->foto ? asset('storage/'.$this->foto)
            : 'https://ui-avatars.com/api/?name='.urlencode($this->nama).'&background=5B7FFF&color=fff&size=128';
    }
}
