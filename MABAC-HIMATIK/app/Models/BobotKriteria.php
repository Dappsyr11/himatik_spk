<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class BobotKriteria extends Model {
    protected $table = 'bobot_kriteria';
    protected $fillable = ['kriteria_id','periode','bobot'];
    public function kriteria() { return $this->belongsTo(Kriteria::class); }
}
