<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kategori;

class KategoriController extends Controller
{
    public function index($slug_kategoriadi)
    {
        $kategori = Kategori::where('slug', $slug_kategoriadi)->firstOrFail(); //get demek yerine firstOrFail kullandık
        //veri tabanında bu değere göre bir kategori bulmayı sağlayacak bulamazsa 404 hatasını ekranda gosterecektir.
        $alt_kategoriler = Kategori::where('ust_id', $kategori->id)->get();

        $order = request('order');
        if ($order == 'coksatanlar') {
            $urunler = $kategori->urunler()
                ->distinct() //tekrar eden verileri bire indirir
                ->join('urun_detay', 'urun_detay.urun_id', 'urun.id')
                ->where('urun_detay.goster_cok_satan', 1)
                ->orderBy('urun_detay.goster_cok_satan', 'desc')
                ->paginate(2);
        } else if ($order == 'yeni') {
            $urunler = $kategori->urunler()
                ->distinct()
                ->orderByDesc('guncelleme_tarihi')
                ->paginate(2); //orderBy('guncelleme_tarihi','desc') demek yerine orderByDesc('guncelleme_tarihi') diyebiliriz
        } else {
            $urunler = $kategori->urunler()
                ->distinct()
                ->paginate(2);
        }


        return view('kategori', compact('kategori', 'alt_kategoriler', 'urunler'));
    }
}
