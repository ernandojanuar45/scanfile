<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Letter extends Model
{
    // Pastikan nama model diawali dengan huruf kapital sesuai konvensi Laravel
    protected $table = 'letters';  // Jika nama tabel tidak mengikuti konvensi Laravel, tentukan nama tabel

    // Menentukan kolom-kolom yang dapat diisi (fillable)
    protected $fillable = [
        'nomor_kop',
        'tanggal',
        'perihal',
        'nama_penerima',
        'nomor_pendaftaran',
        'program_studi',
        'nama',
        'jalur_pendaftaran',
        'email',
        'no_telp',
        'no_wa',
        'bagian',
        'link_pembayaran',
        'batas',
        'biaya',
        'nomor_rekening',
        'bank',
        'file_path',
    ];

    // Jika kamu ingin mengonfigurasi format tanggal (misal 'tanggal') supaya otomatis di-cast ke tipe Date atau DateTime
    protected $dates = ['tanggal'];

    // Jika menggunakan timestamp untuk created_at dan updated_at, kamu bisa mengaktifkannya dengan otomatis
    public $timestamps = true;
}
