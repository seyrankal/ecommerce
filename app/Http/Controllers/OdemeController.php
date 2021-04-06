<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Cart;

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
        return view('odeme');
    }
}
