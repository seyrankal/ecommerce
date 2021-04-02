<?php

namespace App\Http\Controllers;

use App\Models\Kullanici;
use App\Models\Sepet;
use App\Models\SepetUrun;
use App\Models\Urun;
use Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class SepetController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('sepet');
    }

    public function ekle()
    {
        $urun = Urun::find(request('id'));

        /* Cart::add($urun->id, $urun->urun_adi, 1, $urun->fiyati); */

        /* $cart = app(Cart::class);
        $cart::add($urun->id, $urun->urun_adi, 1, $urun->fiyati);
        return redirect()->route('sepet')
            ->with('mesaj_tur', 'success')
            ->with('mesaj', 'Urun sepete Eklendi'); */

        $cart = app(Cart::class);
        $cartItem = $cart::add($urun->id, $urun->urun_adi, 1, $urun->fiyati, ['slug' => $urun->slug]);

        if (auth()->check()) {
            $aktif_sepet_id = session('aktif_sepet_id');

            if (!isset($aktif_sepet_id)) {
                $aktif_sepet = Sepet::create([
                    'kullanici_id' => auth()->id()
                ]);
                $aktif_sepet_id = $aktif_sepet->id;


                session()->put('aktif_sepet_id', $aktif_sepet_id); //sessionda sakliyoruz.
            }
            SepetUrun::updateOrCreate(
                ['sepet_id' => $aktif_sepet_id, 'urun_id' => $urun->id],
                ['adet' => $cartItem->qty, 'fiyati' => $urun->fiyati, 'durum' => 'Beklemede']
            );
        }


        return redirect()->route('sepet')
            ->with('mesaj_tur', 'success')
            ->with('mesaj', 'Ürün sepete eklendi.');
    }

    public function kaldir($rowid)
    {
        dd($rowid);
        if (auth()->check()) {
            $aktif_sepet_id = session('aktif_sepet_id');
            $cart = app(Cart::class);
            $cartItem = $cart::get($rowid);
            SepetUrun::where('sepet_id', $aktif_sepet_id)->where('urun_id', $cartItem->id)->delete();
        }
        $cart = app(Cart::class);
        $cart::remove($rowid);
        return redirect()->route('sepet')
            ->with('mesaj_tur', 'success')
            ->with('mesaj', 'Urun sepetten kaldirildi');
    }
    public function bosalt()
    {

        $cart = app(Cart::class);
        $cart::destroy();
        return redirect()->route('sepet')
            ->with('mesaj_tur', 'success')
            ->with('mesaj', 'Sepetiniz Bosaltildi');
    }
    public function guncelle($rowid)
    {
        $validator = Validator::make(request()->all(), [
            'adet' => 'required|numeric|between:0,5'
        ]);

        if ($validator->fails()) {
            session()->flash('mesaj_tur', 'danger');
            session()->flash('mesaj', 'Adet değeri en az 1 en fazla 5 olabilir!');

            return response()->json(['success' => false]);
        }

        $cart = app(Cart::class);
        $cart::update($rowid, request('adet'));

        session()->flash('mesaj_tur', 'success');
        session()->flash('mesaj', 'Adet bilgisi güncellendi');

        return response()->json(['success' => true]);
    }
}
