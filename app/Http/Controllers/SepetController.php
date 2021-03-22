<?php

namespace App\Http\Controllers;

use App\Models\Sepet;
use App\Models\SepetUrun;
use App\Models\Urun;
use Cart;
use Illuminate\Http\Request;

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
        $cart::add($urun->id, $urun->urun_adi, 1, $urun->fiyati);
        return redirect()->route('sepet')
            ->with('mesaj_tur', 'success')
            ->with('mesaj', 'Ürün sepete eklendi.');
    }
}
