<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;




class Kullanici extends Authenticatable
{
    use SoftDeletes;

    protected $table = "kullanici";

    protected $fillable = [ // doldurulmasina izin veriyoruz
        'adsoyad', 'email', 'sifre', 'aktivasyon_anahtari', 'aktif_mi'
    ];


    protected $hidden = [ //gorunmez yapiyoruz.
        'sifre', 'aktivasyon_anahtari',
    ];
    const CREATED_AT = 'olusturulma_tarihi';
    const UPDATED_AT = 'guncelleme_tarihi';
    const DELETED_AT = 'silinme_tarihi';

    public function getAuthPassword()
    {
        return $this->sifre;
    }
    public function detay()
    {
        return $this->hasOne('App\Models\KullaniciDetay');
    }
}
