<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Cart;
use App\Models\Kullanici;
use App\Models\KullaniciDetay;
use App\Models\Siparis;

class OdemeController extends Controller
{
    public function index()
    {
        $cart = app(Cart::class);

        if (!auth()->check()) {
            return redirect()->route('kullanici.oturumac')
                ->with('mesaj_tur', 'info')
                ->with('mesaj', 'Odeme işlemi icin oturum açmanız veya kullanici kaydi yapmaniz gerekmektedir.');
        } else if (count($cart::content()) == 0) {
            return redirect()->route('anasayfa')
                ->with('mesaj_tur', 'info')
                ->with('mesaj', 'Odeme İslemi icin sepetinizde bir ürün bulunmalıdır.');
        }
        $kullanici_detay = auth()->user()->detay;
        return view('odeme', compact('kullanici_detay'));
    }
    public function odemeyap(Request $request)
    {

        $cart = app(Cart::class);
        /*  $siparis = request()->all;
        $siparis['sepet_id'] = session('aktif_sepet_id');
        $siparis['banka'] = "Ziraat";
        $siparis['taksit_sayisi'] = 1;
        $siparis['durum'] = "Siparisiniz Alindi";
        $siparis['siparis_tutari'] = $cart::subtotal();

        Siparis::create($siparis); */

        /* $siparis2 = request()->all;
        dd($siparis2); */
        $siparis = new Siparis;
        $siparis->sepet_id = session('aktif_sepet_id');
        $siparis->banka = "Ziraat";
        $siparis->taksit_sayisi = 1;
        $siparis->durum = "Siparisiniz Alindi";
        $siparis->siparis_tutari = $cart::subtotal();
        $siparis->adsoyad = $request->adsoyad;
        $siparis->adres = $request->adres;
        $siparis->telefon = $request->telefon;
        $siparis->ceptelefonu = $request->ceptelefonu;

        $siparis->save();

        $cart::destroy();
        session()->forget('aktif_sepet_id');

        return redirect()->route('siparisler')
            ->with('mesaj_tur', 'success')
            ->with('mesaj', 'Odemeniz Basarili bir sekilde gerceklestirildi');
    }
}
