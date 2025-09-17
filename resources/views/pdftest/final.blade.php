<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $title ?? 'Surat' }}</title>
    <style>
        @page {
            size: A4;
            margin: 20mm;
        }

        html, body {
            width: 210mm;
            height: 297mm;
            margin: 0;
            padding: 0;
            font-family: "Times New Roman", Times, serif;
            color: #000;
            font-size: 10pt;
        }

        /* Jika $bg_image berisi data URI atau absolute path (file:// atau http(s)://) */
        body {
            background-image: url("file://{{ public_path('storage/image.png') }}");
            background-repeat: no-repeat;
            background-position: center top;
            background-size: cover;
        }

        .page-wrapper {
            /* memberikan padding agar konten tidak menempel ke tepi */
            padding: 18mm;
        }

        .kop {
            text-align: center;
        }
        .kop h3 {
            margin: 0;
            font-size: 10pt;
            font-weight: bold;
        }
        .kop p { 
            margin: 2px 0; 
        }
        hr.sep {
            border: 1px solid #000;
            margin: 12px 0;
        }
        .tanggal { 
            text-align: right; 
        }
        .meta { 
            margin-top: 5px; 
        }
        .penerima { 
            margin-top: 5px; 
        }
        .isi { 
            line-height: 1.6; 
            text-align: justify; 
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
            margin-top: 12px; 
        }
        .signature {
            margin-top: 5px;
            text-align: right;
        }
        .small {
            font-size: 10pt;
        }
        a { 
            color: #0000EE; 
            text-decoration: underline; 
        }
    </style>
</head>
<body>
    <div class="page-wrapper">
        <!-- Kop Surat -->
        <div class="kop">
            <h3>
                {{ session('letter_data.nomor_kop') ?? session('letter_data.kop_nama') ?? 'Nama Organisasi/Perusahaan' }}
            </h3>
            <p>{{ session('letter_data.alamat_kantor') ?? 'Alamat Kantor' }}</p>
            <p>
                {{ session('letter_data.telepon') ?? 'Nomor Telepon' }}
                @if(session('letter_data.email_kantor') || session('letter_data.email'))
                    • {{ session('letter_data.email_kantor') ?? session('letter_data.email') }}
                @endif
            </p>
        </div>

        <hr class="sep">

        <!-- Tanggal Surat -->
        <div class="tanggal">
            <p>
                {{ session('letter_data.kota') ?? 'Surabaya' }}, 
                {{ $date ?? session('letter_data.tanggal') ?? \Carbon\Carbon::now()->locale('id')->translatedFormat('d F Y') }}
            </p>
        </div>

        <!-- Nomor Surat dan Perihal -->
        <div class="meta">
            <p> Nomor :  {{ session('letter_data.nomor_surat') ?? session('letter_data.nomor_kop') ?? 'Nomor Surat' }}</p>
            <p> Perihal :  {{ session('letter_data.perihal') ?? 'Perihal Surat' }}</p>
        </div>

        <!-- Penerima Surat -->
        <div class="penerima">
            <p>Kepada Yth,</p>
            <p> {{ session('letter_data.nama_penerima') ?? 'Nama Penerima' }} </p>
            <p>{{ session('letter_data.alamat_penerima') ?? 'Alamat Penerima' }}</p>
        </div>

        <!-- Isi Surat -->
        <div class="isi">
            <p>Dengan hormat,</p>
            <p>
                Dengan ini kami mengucapkan terima kasih Saudara telah berminat mendaftar sebagai calon mahasiswa baru Universitas Hayam Wuruk Perbanas. Tahapan selanjutnya silahkan saudara melakukan pembayaran biaya pendaftaran (Formulir) sebagaimana informasi berikut:
            </p>
            <p>Demikian pemberitahuan ini kami sampaikan. Atas perhatian dan kerjasama Saudara, kami ucapkan terima kasih.</p>
        </div>

        <!-- Informasi Pendaftaran -->
        <div class="info-pendaftaran">
            <h4>Informasi Pendaftaran</h4>
            <table class="info-table">
                <tr>
                    <td style="width:35%"> Nama </td>
                    <td style="width:2%">:</td>
                    <td>{{ session('letter_data.nama') ?? 'Nama Pendaftar' }}</td>
                </tr>
                <tr>
                    <td> Nomor Pendaftaran </td>
                    <td>:</td>
                    <td>{{ session('letter_data.nomor_pendaftaran') ?? 'Nomor Pendaftaran' }}</td>
                </tr>
                <tr>
                    <td> Program Studi </td>
                    <td>:</td>
                    <td>{{ session('letter_data.program_studi') ?? 'Program Studi' }}</td>
                </tr>
                <tr>
                    <td> Jalur Pendaftaran </td>
                    <td>:</td>
                    <td>{{ session('letter_data.jalur_pendaftaran') ?? 'Jalur Pendaftaran' }}</td>
                </tr>
                <tr>
                    <td> Email </td>
                    <td>:</td>
                    <td>{{ session('letter_data.email') ?? 'Email' }}</td>
                </tr>
                <tr>
                    <td> No. Telepon </td>
                    <td>:</td>
                    <td>{{ session('letter_data.no_telp') ?? 'Nomor Telepon' }}</td>
                </tr>
                <tr>
                    <td> No. HP / WA </td>
                    <td>:</td>
                    <td>{{ session('letter_data.no_wa') ?? session('letter_data.no_hp') ?? 'Nomor WhatsApp' }}</td>
                </tr>
                <tr>
                    <td> Bank </td>
                    <td>:</td>
                    <td>{{ session('letter_data.bank') ?? 'Bank' }}</td>
                </tr>
                <tr>
                    <td> Nomor Rekening </td>
                    <td>:</td>
                    <td>{{ session('letter_data.nomor_rekening') ?? 'Nomor Rekening' }}</td>
                </tr>
                <tr>
                    <td> Biaya Pendaftaran </td>
                    <td>:</td>
                    <td>{{ session('letter_data.biaya') ?? 'Biaya Pendaftaran' }}</td>
                </tr>
                <tr>
                    <td> Batas Pembayaran </td>
                    <td>:</td>
                    <td>{{ session('letter_data.batas') ?? 'Tanggal Batas Pembayaran' }}</td>
                </tr>
            </table>
        </div>

        <hr class="sep">

        <!-- Info Pembayaran -->
        <div class="notes">
            <p>Info pembayaran uang pendaftaran silahkan baca berikut:
                <a href="{{ session('letter_data.link_pembayaran') ?? '#' }}" target="_blank">
                    {{ session('letter_data.link_pembayaran') ?? 'Link Pembayaran' }}
                </a>
            </p>

            <h4>Catatan Penting:</h4>
            <ul class="small">
                <li>Setelah saudara melakukan pembayaran, silahkan login ke akun SPMB saudara untuk melakukan proses selanjutnya.</li>
            </ul>
        </div>

        <!-- Tanda Tangan -->
        <div class="signature">
            <p>{{ session('letter_data.sender_name') ?? 'Nama Pengirim' }}</p>
            <p>{{ session('letter_data.sender_title') ?? 'Jabatan Pengirim' }}</p>
            <p class="small">{{ session('letter_data.sender_unit') ?? '' }}</p>
        </div>
    </div>
</body>
</html>
