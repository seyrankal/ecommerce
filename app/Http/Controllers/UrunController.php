<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Urun;

class UrunController extends Controller
{
    public function index($slug_urunadi)
    {
        $urun = Urun::whereSlug($slug_urunadi)->firstOrFail();
        $kategoriler = $urun->kategoriler()->distinct()->get();
        return view('urun', compact('urun', 'kategoriler'));
    }

    public function ara()
    { // 58. videoda olusturuldu

        $aranan = request()->input('aranan');
        $urunler = Urun::where('urun_adi', 'like', "%$aranan%")
            ->orWhere('aciklama', 'like', "%$aranan%")
            //->simplePaginate(2);
            ->paginate(2);

        request()->flash(); // arama kutusunda aramadan sonra da aranan degerin gosterilmesi için navbar.blade de value="{{old('aranan')}}" yazdik
        return view('arama', compact('urunler'));
    }
}
