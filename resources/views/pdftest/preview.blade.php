@extends('layouts.app')

@section('content')
{{-- Menampilkan data letter yang disimpan di session --}}
<!-- <h4>Data Letter yang Disimpan di Session:</h4>
<div>
    <p><strong>Nomor Kop:</strong> {{ session('letter_data')['nomor_kop'] ?? 'Tidak ada data' }}</p>
    <p><strong>Tanggal:</strong> {{ session('letter_data')['tanggal'] ?? 'Tidak ada data' }}</p>
    <p><strong>Perihal:</strong> {{ session('letter_data')['perihal'] ?? 'Tidak ada data' }}</p>
    <p><strong>Nama Penerima:</strong> {{ session('letter_data')['nama_penerima'] ?? 'Tidak ada data' }}</p>
    <p><strong>Nomor Pendaftaran:</strong> {{ session('letter_data')['nomor_pendaftaran'] ?? 'Tidak ada data' }}</p>
    <p><strong>Program Studi:</strong> {{ session('letter_data')['program_studi'] ?? 'Tidak ada data' }}</p>
    <p><strong>Nama:</strong> {{ session('letter_data')['nama'] ?? 'Tidak ada data' }}</p>
    <p><strong>Jalur Pendaftaran:</strong> {{ session('letter_data')['jalur_pendaftaran'] ?? 'Tidak ada data' }}</p>
    <p><strong>Email:</strong> {{ session('letter_data')['email'] ?? 'Tidak ada data' }}</p>
    <p><strong>No. Telepon:</strong> {{ session('letter_data')['no_telp'] ?? 'Tidak ada data' }}</p>
    <p><strong>No. WA:</strong> {{ session('letter_data')['no_wa'] ?? 'Tidak ada data' }}</p>
    <p><strong>Bank:</strong> {{ session('letter_data')['bank'] ?? 'Tidak ada data' }}</p>
    <p><strong>Nomor Rekening:</strong> {{ session('letter_data')['nomor_rekening'] ?? 'Tidak ada data' }}</p>
    <p><strong>Biaya:</strong> {{ session('letter_data')['biaya'] ?? 'Tidak ada data' }}</p>
    <p><strong>Batas Pembayaran:</strong> {{ session('letter_data')['batas'] ?? 'Tidak ada data' }}</p>
    <p><strong>Link Pembayaran:</strong> <a href="{{ session('letter_data')['link_pembayaran'] ?? '#' }}" target="_blank">{{ session('letter_data')['link_pembayaran'] ?? 'Tidak ada link pembayaran' }}</a></p>
    <p>{{ $date ?? (session('letter_data.tanggal') ?? \Carbon\Carbon::now()->locale('id')->translatedFormat('d F Y')) }}</p>
</div> -->

 <!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $title ?? 'Surat' }}</title>
    <style>
        body {
            font-family: "Times New Roman", Times, serif;
            color: #000;
            font-size: 12pt;
            margin: 0;
            padding: 0;
            /* background-image: url("{{ asset('storage/image') }}"); Menambahkan background image */
            background-size: cover;
            background-position: center;
        }
        .container {
            width: 80%;
            margin: 0 auto;
            padding: 20px;
            background-color: rgba(255, 255, 255, 0.9); /* Menambahkan latar belakang putih transparan */
            border-radius: 10px;
        }
        .kop {
            text-align: center;
        }
        .kop h3 {
            margin: 0;
            font-size: 16pt;
            font-weight: bold;
        }
        .kop p { 
            margin: 2px 0; 
        }
        hr.sep {
            border: 1px solid #000;
            margin: 18px 0;
        }
        .tanggal { 
            text-align: right; 
        }
        .meta { 
            margin-top: 18px; 
        }
        .penerima { 
            margin-top: 18px; 
        }
        .isi { 
            margin-top: 28px; 
            line-height: 1.6; 
            text-align: justify; 
        }
        .info-pendaftaran { 
            margin-top: 28px; 
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 6px;
        }
        .info-table td {
            vertical-align: top;
            padding: 3px 6px;
        }
        .notes { 
            margin-top: 18px; 
        }
        .signature {
            margin-top: 50px;
            text-align: right;
        }
        a { 
            color: #0000EE; 
            text-decoration: underline; 
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Kop Surat -->
        <div class="kop">
            <h3>{{ session('letter_data')['nomor_kop'] ?? session('letter_data')['kop_nama'] ?? 'Nama Organisasi/Perusahaan' }}</h3>
            <p>{{ session('letter_data')['alamat_kantor'] ?? 'Alamat Kantor' }}</p>
            <p>{{ session('letter_data')['telepon'] ?? 'Nomor Telepon' }} • {{ session('letter_data')['email_kantor'] ?? session('letter_data')['email'] ?? 'Email' }}</p>
        </div>

        <hr class="sep">

        <!-- Tanggal Surat -->
        <div class="tanggal">
            <p>Surabaya, {{ $date ?? (session('letter_data.tanggal') ?? \Carbon\Carbon::now()->locale('id')->translatedFormat('d F Y')) }}</p>
        </div>

        <!-- Nomor Surat dan Perihal -->
        <div class="meta">
            <p><strong>Nomor :</strong> {{ session('letter_data')['nomor_surat'] ?? session('letter_data')['nomor_kop'] ?? 'Nomor Surat' }}</p>
            <p><strong>Perihal :</strong> {{ session('letter_data')['perihal'] ?? 'Perihal Surat' }}</p>
        </div>

        <!-- Penerima Surat -->
        <div class="penerima">
            <p>Kepada Yth,</p>
            <p><strong>{{ session('letter_data')['nama_penerima'] ?? 'Nama Penerima' }}</strong></p>
            <p>{{ session('letter_data')['alamat_penerima'] ?? 'Alamat Penerima' }}</p>
        </div>

        <!-- Isi Surat -->
        <div class="isi">
            <p>Dengan hormat,</p>
            <p>
                Dengan ini kami mengucapkan terimakasih Saudara telah berminat mendaftar sebagai calon mahasiswa baru Universitas Hayam Wuruk Perbanas. Tahapan selanjutnya silahkan saudara melakukan pembayaran biaya pendaftaran (Formulir) sebagaimana informasi berikut:
            </p>
            <p>Demikian pemberitahuan ini kami sampaikan. Atas perhatian dan kerjasama Saudara, kami ucapkan terima kasih.</p>
        </div>

        <!-- Informasi Pendaftaran -->
        <div class="info-pendaftaran">
            <h4>Informasi Pendaftaran</h4>
            <table class="info-table">
                <tr>
                    <td style="width:35%"><strong>Nama</strong></td>
                    <td>:</td>
                    <td>{{ session('letter_data')['nama'] ?? 'Nama Pendaftar' }}</td>
                </tr>
                <tr>
                    <td><strong>Nomor Pendaftaran</strong></td>
                    <td>:</td>
                    <td>{{ session('letter_data')['nomor_pendaftaran'] ?? 'Nomor Pendaftaran' }}</td>
                </tr>
                <tr>
                    <td><strong>Program Studi</strong></td>
                    <td>:</td>
                    <td>{{ session('letter_data')['program_studi'] ?? 'Program Studi' }}</td>
                </tr>
                <tr>
                    <td><strong>Jalur Pendaftaran</strong></td>
                    <td>:</td>
                    <td>{{ session('letter_data')['jalur_pendaftaran'] ?? 'Jalur Pendaftaran' }}</td>
                </tr>
                <tr>
                    <td><strong>Email</strong></td>
                    <td>:</td>
                    <td>{{ session('letter_data')['email'] ?? 'Email' }}</td>
                </tr>
                <tr>
                    <td><strong>No. Telepon</strong></td>
                    <td>:</td>
                    <td>{{ session('letter_data')['no_telp'] ?? 'Nomor Telepon' }}</td>
                </tr>
                <tr>
                    <td><strong>No. HP / WA</strong></td>
                    <td>:</td>
                    <td>{{ session('letter_data')['no_wa'] ?? session('letter_data')['no_hp'] ?? 'Nomor WhatsApp' }}</td>
                </tr>
                <tr>
                    <td><strong>Bank</strong></td>
                    <td>:</td>
                    <td>{{ session('letter_data')['bank'] ?? 'Bank' }}</td>
                </tr>
                <tr>
                    <td><strong>Nomor Rekening</strong></td>
                    <td>:</td>
                    <td>{{ session('letter_data')['nomor_rekening'] ?? 'Nomor Rekening' }}</td>
                </tr>
                <tr>
                    <td><strong>Biaya Pendaftaran</strong></td>
                    <td>:</td>
                    <td>{{ session('letter_data')['biaya'] ?? 'Biaya Pendaftaran' }}</td>
                </tr>
                <tr>
                    <td><strong>Batas Pembayaran</strong></td>
                    <td>:</td>
                    <td>{{ session('letter_data')['batas'] ?? 'Tanggal Batas Pembayaran' }}</td>
                </tr>
            </table>
        </div>

        <hr class="sep">

        <!-- Info Pembayaran -->
        <div class="notes">
            <p>Info cara pembayaran uang pendaftaran silahkan baca link panduan berikut: 
                <a href="{{ session('letter_data')['link_pembayaran'] ?? '#' }}" target="_blank">
                    {{ session('letter_data')['link_pembayaran'] ?? 'Link Pembayaran' }}
                </a>
            </p>

            <h4>Catatan Penting:</h4>
            <ul>
                <li>Setelah saudara melakukan pembayaran, silahkan login ke akun SPMB saudara untuk melakukan proses selanjutnya.</li>
                <li>Apabila pada akun SPMB status 'Membayar uang Pendaftaran' tertera 'Belum dikonfirmasi' silahkan menghubungi chat WhatsApp official PMB 08113212117.</li>
                <li>Apabila pada akun SPMB status 'Membayar uang Pendaftaran' tertera 'Sudah Dikonfirmasi', maka saudara memiliki waktu untuk melengkapi data pendaftaran pada akun SPMB anda paling lambat 1 bulan setelah melakukan pembayaran.</li>
                <li>Untuk memudahkan proses pembayaran, sebaiknya informasi ini dicetak. Surat pemberitahuan ini dapat Saudara akses kembali setelah login ke website SPMB Universitas Hayam Wuruk Perbanas pada bagian "Surat Pemberitahuan Berminat".</li>
                <li>Simpan selalu bukti transfer pembayaran saudara dengan baik.</li>
            </ul>
        </div>

        <!-- Tanda Tangan -->
        <div style="margin-top: 50px; text-align: right;">
            <p>{{ session('letter_data')['sender_name'] ?? 'Nama Pengirim' }}</p>
            <p>{{ session('letter_data')['sender_position'] ?? 'Jabatan Pengirim' }}</p>
        </div>

        <div style="text-align: center; margin-top: 40px; font-size: 12px; color: #666;">
            <p>© {{ $date ?? (session('letter_data.tanggal') ?? \Carbon\Carbon::now()->locale('id')->translatedFormat(' Y')) }} - Universitas Hayam Wuruk Perbanas</p>
        </div>
    </div>
</body>
</html>


@endsection
