<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Template;

class TemplatesTableSeeder extends Seeder
{
    public function run()
    {
        Template::truncate();

        Template::create([
            'name' => 'Surat Resmi - Kop & Isi',
            'content' => "
Nomor: {{nomor_surat}}
Tanggal: {{tanggal}}

Kepada Yth:
{{nama_pengirim}}
{{alamat}}

Perihal: Pemberitahuan

Dengan hormat,

{{isi_surat}}

Hormat kami,
{{jabatan}}
",
        ]);
    }
}
