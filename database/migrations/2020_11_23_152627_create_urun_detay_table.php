<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateUrunDetayTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('urun_detay', function (Blueprint $table) {


            $table->increments('id');


            $table->boolean('goster_slider')->default(0);
            $table->boolean('goster_gunun_firsati')->default(0);
            $table->boolean('goster_one_cikan')->default(0); //default sifir varsayilan olarak gostermesin ayarli default(1) yaparsak gosterir
            $table->boolean('goster_cok_satan')->default(0);
            $table->boolean('goster_indirimli')->default(0);

            $table->integer('urun_id')->unsigned()->unique();
            // $table->unique('urun_id');//uniq degerini yukaridaki gibideveya  bu sekilde de yapabiiriz.
            $table->foreign('urun_id')->references('id')->on('urun')->onDelete('cascade');
            //urun tablosundaki 5 numarali kayit silinirse urun_detay tablosundaki 5 numarali urunde silinecek


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('urun_detay');
    }
}
