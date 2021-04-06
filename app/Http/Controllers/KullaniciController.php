<?php

namespace App\Http\Controllers;

use App\Mail\KullaniciKayitMail;
use App\Models\Kullanici;
use App\Models\KullaniciDetay;
use App\Models\SepetUrun;
use Gloudemans\Shoppingcart\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\Sepet;
use Cart;

use Illuminate\Contracts\Auth\Authenticatable;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class KullaniciController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest')->except('oturumukapat');
    }
    public function giris_form()
    {
        return view('kullanici.oturumac');
    }
    public function giris()
    {
        $this->validate(request(), [
            'email' => 'required|email',
            'sifre' => 'required'
        ]);
        if (Auth::attempt(['email' => request('email'), 'password' => request('sifre')], request()->has('benihatirla'))) {
            request()->session()->regenerate();

            $aktif_sepet_id = Sepet::firstOrCreate(['kullanici_id' => auth()->id()])->id;
            session()->put('aktif_sepet_id', $aktif_sepet_id);

            $cart = app(Cart::class);


            if ($cart::count() > 0) {
                /*  dd('count controlune girdi'); */
                foreach ($cart::content() as $cartItem) {

                    SepetUrun::updateOrCreate(
                        ['sepet_id' => $aktif_sepet_id, 'urun_id' => $cartItem->id],
                        ['adet' => $cartItem->qty, 'fiyati' => $cartItem->price, 'durum' => 'Beklemede']

                    );
                }
            }
            $cart::destroy();

            $sepetUrunler = SepetUrun::where('sepet_id', $aktif_sepet_id)->get();

            foreach ($sepetUrunler as $sepetUrun) {

                $cart::add(
                    $sepetUrun->urun->id,
                    $sepetUrun->urun->urun_adi,
                    $sepetUrun->adet,
                    $sepetUrun->fiyati,
                    ['slug' => $sepetUrun->urun->slug]
                );
            }

            return redirect()->intended('/');
        } else {
            $errors = ['email' => 'Hatalı giriş'];

            return back()->withErrors($errors);
        }
    }

    public function kaydol_form()
    {
        return view('kullanici.kaydol');
    }
    public function kaydol(Request $request)
    {
        /*  $kullanici = Kullanici::created([
            'adsoyad' => request('adsoyad'),
            'email' => request('email'),
            'sifre' => Hash::make(request('sifre')),
            'aktivasyon_anahtari' => Str::random(60),
            'aktif_mi' => 0
        ]); */
        $this->validate(request(), [
            'adsoyad' => 'required|min:5|max:60',
            'email' => 'required|email|unique:kullanici',
            'sifre' => 'required|confirmed|min:5|max:15'
        ]);

        $kullanici_al = new Kullanici;
        $kullanici_al->adsoyad = $request->adsoyad;
        $kullanici_al->email = $request->email;
        $kullanici_al->sifre = Hash::make($request->sifre);
        $kullanici_al->aktivasyon_anahtari = Str::random(60);
        $kullanici_al->aktif_mi = 0;
        $kullanici_al->save();

        $kullanici_al->detay()->save(new KullaniciDetay());

        /*  Mail::to(request('email'))->cc()->bcc()->send(new KullaniciKayitMail($kullanici)); */
        Mail::to(request('email'))->send(new KullaniciKayitMail($kullanici_al));

        Auth::login($kullanici_al);
        //return $kullanici;
        /* auth()->login($kullanici); */
        return redirect()->route('anasayfa');
    }
    public function aktiflestir($anahtar)
    {
        $kullanici = Kullanici::where('aktivasyon_anahtari', $anahtar)->first();
        if (!is_null($kullanici)) {
            $kullanici->aktivasyon_anahtari = null;
            $kullanici->aktif_mi = 1;
            $kullanici->save();
            return redirect()->to('/')
                ->with('mesaj', 'Kullanici kaydiniz aktiflestirildi') //bu sesion bilgisi gönderildikten sonra otamatik silinmektedir.
                ->with('mesaj_tur', 'success');
        } else {
            return redirect()->to('/')
                ->with('mesaj', 'Kullanici bulunamadi') //bu sesion bilgisi gönderildikten sonra otamatik silinmektedir.
                ->with('mesaj_tur', 'warning');
        }
    }

    public function oturumukapat()
    {
        auth::logout();
        request()->session()->flush();
        request()->session()->regenerate();
        return redirect()->route('anasayfa');
    }
}
