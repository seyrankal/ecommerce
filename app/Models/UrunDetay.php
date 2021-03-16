<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UrunDetay extends Model
{
    protected $table = "urun_detay";
    public $timestamps = false; // eger urun_detay migration dosyasinda timestamps kullanmadi isek bunu modelde bu sekilde belirtmeliyiz.
    public function urun()
    {
        return $this->belongsTo('App\Models\Urun');
    }
}
