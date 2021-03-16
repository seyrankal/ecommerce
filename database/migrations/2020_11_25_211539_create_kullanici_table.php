<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateKullaniciTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kullanici', function (Blueprint $table) {
            $table->increments('id');
            $table->string('adsoyad', 60);
            $table->string('email', 150)->unique(); //email alani kullanici tablosunda tekil olacak
            $table->string('sifre', 60); //sifreleride hashlenmis olarak tutacagimiz icin 60 olarak berlirledik
            $table->string('aktivasyon_anahtari', 60)->nullable(); //haslenmis veri tutacak 60 karekter
            $table->boolean('aktif_mi')->default(0);
            $table->rememberToken();
            $table->timestamp('olusturulma_tarihi')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('guncelleme_tarihi')->default(DB::raw('CURRENT_TIMESTAMP on UPDATE
            CURRENT_TIMESTAMP'));

            $table->timestamp('silinme_tarihi')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kullanici');
    }
}
