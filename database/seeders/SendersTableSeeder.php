<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Sender;

class SendersTableSeeder extends Seeder
{
    public function run()
    {
        Sender::truncate();

        Sender::create([
            'nama' => 'PT. Contoh Nusantara',
            'alamat' => "Jl. Kemerdekaan No. 10\nJakarta Pusat, 10110",
            'jabatan' => 'Manager Operasional',
            'email' => 'contact@contoh.co.id',
            'phone' => '+62 21 1234 5678',
        ]);

        Sender::create([
            'nama' => 'Budi Santoso',
            'alamat' => "Jl. Mawar No. 5\nBandung, 40123",
            'jabatan' => 'Direktur',
            'email' => 'budi@pribadi.id',
            'phone' => '+62 813 9999 0000',
        ]);
    }
}
