<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class SkalaPenilaian extends Model {
    protected $table = 'skala_penilaian';
    protected $fillable = ['label','nilai','deskripsi'];
}
