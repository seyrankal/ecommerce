<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;
use App\Models\UrunDetay;
use App\Models\Urun;

class AnasayfaController extends Controller
{
    public function index()
    {
        //$kategoriler = Kategori::all()->take(5);//take kac adet kayitin gelecegini soyler
        $kategoriler = Kategori::whereRaw('ust_id is null')->take(5)->get(); //raw komutu ile sql cumleleri kullanabiliyoruz.
        //$urunler_slider = UrunDetay::with('urun')->where('goster_slider', 1)->take(5)->get();

        $urunler_slider = Urun::select('urun.*') //burdan hangi kolonlari cekecegimizi select ile kendimiz belirleyebiliriz
            ->join('urun_detay', 'urun_detay.urun_id', 'urun.id')
            ->where('urun_detay.goster_slider', 1)
            ->orderBy('guncelleme_tarihi', 'desc')
            ->take(4)->get();

        $urun_gunun_firsati = Urun::select('urun.*') //burdan hangi kolonlari cekecegimizi select ile kendimiz belirleyebiliriz
            ->join('urun_detay', 'urun_detay.urun_id', 'urun.id')
            ->where('urun_detay.goster_gunun_firsati', 1)
            ->orderBy('guncelleme_tarihi', 'desc')
            ->first();


        $urunler_one_cikan  = Urun::select('urun.*') //burdan hangi kolonlari cekecegimizi select ile kendimiz belirleyebiliriz
            ->join('urun_detay', 'urun_detay.urun_id', 'urun.id')
            ->where('urun_detay.goster_one_cikan', 1)
            ->orderBy('guncelleme_tarihi', 'desc')
            ->take(4)->get();
        // $urunler_one_cikan = UrunDetay::with('urun')->where('goster_slider', 1)->take(5)->get(); // bu siralama yapisinda urune ait guncelleme tarihi gore bir siralama yapamıyoruz.

        $urunler_cok_satan  = Urun::select('urun.*') //burdan hangi kolonlari cekecegimizi select ile kendimiz belirleyebiliriz
            ->join('urun_detay', 'urun_detay.urun_id', 'urun.id')
            ->where('urun_detay.goster_cok_satan', 1)
            ->orderBy('guncelleme_tarihi', 'desc')
            ->take(4)->get();
        //$urunler_cok_satilan = UrunDetay::with('urun')->where('urunler_cok_satilan', 1)->take(5)->get();

        $urunler_indirimli  = Urun::select('urun.*') //burdan hangi kolonlari cekecegimizi select ile kendimiz belirleyebiliriz
            ->join('urun_detay', 'urun_detay.urun_id', 'urun.id')
            ->where('urun_detay.goster_indirimli', 1)
            ->orderBy('guncelleme_tarihi', 'desc')
            ->take(4)->get();
        //$urunler_indirimler = UrunDetay::with('urun')->where('urunler_indirimler', 1)->take(5)->get();

        return view('anasayfa', compact('kategoriler', 'urunler_slider', 'urun_gunun_firsati', 'urunler_one_cikan', 'urunler_cok_satan', 'urunler_indirimli'));
    }
}
