<h1>{{config('app.name')}}</h1>
<p> Merhaba {{ $kullanici->adsoyad }}, Kaydiniz basarıli bir sekilde.</p>
<p> Kaydinizi aktifleştirmek için <a href="{{config('app.url')}}/kullanici/aktiflestir/{{$kullanici->aktivasyon_anahtari}}"> tiklayiniz</a> veya asagidaki baglantıyı tarayıcınızda aciniz.</p>
<p>{{config('app.url')}}/kullanici/aktiflestir/{{$kullanici->aktivasyon_anahtari}}</p>