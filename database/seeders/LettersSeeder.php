<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class LettersSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        // Membuat 10 data surat
        foreach (range(1, 10) as $index) {
            DB::table('letters')->insert([
                'nomor_kop' => $faker->word,
                'tanggal' => $faker->date,
                'perihal' => $faker->sentence,
                'nama_penerima' => $faker->name,
                'nomor_pendaftaran' => $faker->randomNumber(5),
                'program_studi' => $faker->word,
                'nama' => $faker->name,
                'jalur_pendaftaran' => $faker->word,
                'email' => $faker->email,
                'no_telp' => $faker->phoneNumber,
                'no_wa' => $faker->phoneNumber,
                'bagian' => $faker->word,
                'link_pembayaran' => $faker->url,
                'batas' => $faker->date,
                'biaya' => $faker->randomFloat(2, 1000000, 5000000),
                'nomor_rekening' => $faker->bankAccountNumber,
                'bank' => $faker->company,
                'file_path' => $faker->word,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}

