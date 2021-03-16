<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KategoriTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        //47-4:36
        DB::table('kategori')->truncate(); //komutu veritabanındaki verileri otamatik siler

        $id = DB::table('kategori')->insertGetId(['kategori_adi' => 'Elektronik', 'slug' => 'elektronik']);
        DB::table('kategori')->insert([
            'kategori_adi' => 'Bigisayar/Tablet', 'slug' => 'bilgisayar-tablet',
            'ust_id' => $id
        ]);

        DB::table('kategori')->insert([
            'kategori_adi' => 'Telefon', 'slug' => 'telefon',
            'ust_id' => $id
        ]);
        DB::table('kategori')->insert([
            'kategori_adi' => 'Tv ve Ses Sistemleri', 'slug' => 'tv-ses-sistemleri',
            'ust_id' => $id
        ]);

        $id = DB::table('kategori')->insertGetId(['kategori_adi' => 'Kitap', 'slug' => 'kitap']);

        DB::table('kategori')->insert([
            'kategori_adi' => 'Edebiyat', 'slug' => 'edebiyat', 'ust_id' => $id
        ]);
        DB::table('kategori')->insert([
            'kategori_adi' => 'Dergi', 'slug' => 'dergi', 'ust_id' => $id
        ]);
        DB::table('kategori')->insert([
            'kategori_adi' => 'Cocuk', 'slug' => 'cocuk', 'ust_id' => $id
        ]);
        DB::table('kategori')->insert([
            'kategori_adi' => 'Bilgisayar', 'slug' => 'bilgisayar', 'ust_id' => $id
        ]);
        DB::table('kategori')->insert([
            'kategori_adi' => 'Sinavlara Hazirlik', 'slug' => 'sinavlara-hazirlik', 'ust_id' => $id
        ]);

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        //47-4:36
    }
}
