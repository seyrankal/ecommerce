<?php

namespace App\Http\Controllers;

use App\Mail\KullaniciKayitMail;
use App\Models\Kullanici;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

use Illuminate\Contracts\Auth\Authenticatable;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class KullaniciController extends Controller
{
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
            return redirect()->intended('/');
        } else {
            $errors = ['email' => 'hatali giris'];
            return back()->withErrors($errors);
            /* testtestest */
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
}
