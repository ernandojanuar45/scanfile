<?php

namespace App\Services;

use Smalot\PdfParser\Parser;
use setasign\Fpdi\Tcpdf\Fpdi;
use App\Models\Sender;
use Illuminate\Support\Str;

class PdfService
{
    /**
     * Ekstrak teks dari PDF menggunakan Smalot\PdfParser.
     */
    public function extractData(string $pdfPath): array
    {
        $parser = new Parser();
        $pdf = $parser->parseFile($pdfPath);
        $text = $pdf->getText();

        // Normalisasi spasi dan baris untuk regex lebih mudah.
        $norm = preg_replace('/\r\n|\r/', "\n", $text);
        $norm = preg_replace('/\n{2,}/', "\n", $norm);
        $norm = trim($norm);

        // Contoh regex: sesuaikan dengan pola pada dokumen Anda.
        preg_match('/(?:Nomor|No\.|No)\s*[:\-]?\s*(.+)/i', $norm, $noMatch);
        preg_match('/(?:Nama|Pengirim)\s*[:\-]?\s*(.+)/i', $norm, $nameMatch);
        preg_match('/(?:Alamat)\s*[:\-]?\s*(.+)/i', $norm, $addrMatch);
        preg_match('/(?:Tanggal)\s*[:\-]?\s*(.+)/i', $norm, $tglMatch);

        return [
            'raw_text'      => $norm,
            'nomor_surat'   => isset($noMatch[1]) ? trim($noMatch[1]) : null,
            'nama_pengirim' => isset($nameMatch[1]) ? trim($nameMatch[1]) : null,
            'alamat'        => isset($addrMatch[1]) ? trim($addrMatch[1]) : null,
            'tanggal'       => isset($tglMatch[1]) ? trim($tglMatch[1]) : now()->format('d-m-Y'),
            'isi_surat'     => $norm,
        ];
    }

    /**
     * Map hasil ekstraksi dengan database (contoh tabel senders).
     */
    public function mapWithDatabase(array $data): array
    {
        if (!empty($data['nama_pengirim'])) {
            $nama = trim($data['nama_pengirim']);
            // Pencarian case-insensitive, gunakan ILIKE jika DB PostgreSQL
            $sender = Sender::whereRaw('LOWER(nama) LIKE ?', ['%' . mb_strtolower($nama) . '%'])->first();
            if ($sender) {
                $data['nama_pengirim'] = $sender->nama ?? $data['nama_pengirim'];
                $data['alamat'] = $sender->alamat ?? $data['alamat'];
                $data['jabatan'] = $sender->jabatan ?? null;
                // tambahkan field lain bila perlu
            }
        }

        return $data;
    }

    /**
     * Overlay data ke PDF asli menggunakan FPDI+TCPDF.
     *
     * @param string $originalPath path ke file PDF asli (storage_path('app/...'))
     * @param array $fields data yang akan di-overlay (nomor_kop, nama, alamat, tanggal, isi)
     * @param string $outputPath path file output (contoh: storage_path('app/temp/filled.pdf'))
     * @param array $coords konfigurasi koordinat per field, lihat default di bawah
     */
    public function overlayDataOnPdf(string $originalPath, array $fields, string $outputPath, array $coords = []): string
    {
        // default koordinat (mm) — sesuaikan dengan template Anda
        $defaultCoords = [
            'nomor_kop' => ['page' => 1, 'x' => 140, 'y' => 20, 'font_size' => 10],
            'nama'      => ['page' => 1, 'x' => 30,  'y' => 60, 'font_size' => 11],
            'alamat'    => ['page' => 1, 'x' => 30,  'y' => 64, 'font_size' => 10, 'width' => 120],
            'tanggal'   => ['page' => 1, 'x' => 140, 'y' => 60, 'font_size' => 10],
            'isi'       => ['page' => 1, 'x' => 20,  'y' => 90, 'font_size' => 11, 'width' => 170, 'line_height' => 6],
        ];
        $coords = array_merge($defaultCoords, $coords);

        $pdf = new Fpdi();
        $pageCount = $pdf->setSourceFile($originalPath);

        for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
            $tplId = $pdf->importPage($pageNo);
            $size = $pdf->getTemplateSize($tplId);
            $orientation = ($size['width'] > $size['height']) ? 'L' : 'P';
            // Set ukuran halaman sesuai template asli (satuan mm)
            $pdf->AddPage($orientation, [$size['width'], $size['height']]);
            $pdf->useTemplate($tplId);

            // Atur font default
            $pdf->SetFont('helvetica', '', 10);
            $pdf->SetTextColor(0, 0, 0);

            // Untuk setiap field: jika page cocok, tulis di koordinat
            foreach ($coords as $field => $cfg) {
                if (($cfg['page'] ?? 1) !== $pageNo) {
                    continue;
                }

                $value = $fields[$field] ?? ($fields[str_replace('nomor_kop', 'nomor_surat', $field)] ?? null);
                if (empty($value)) {
                    continue;
                }

                $x = $cfg['x'] ?? 10;
                $y = $cfg['y'] ?? 10;
                $fontSize = $cfg['font_size'] ?? 10;
                $pdf->SetFont('helvetica', '', $fontSize);

                // Jika multi-line / lebar terpasang, gunakan MultiCell
                if (!empty($cfg['width'])) {
                    $width = $cfg['width'];
                    $lineHeight = $cfg['line_height'] ?? ($fontSize * 0.5);
                    $pdf->SetXY($x, $y);
                    $pdf->MultiCell($width, $lineHeight, $value, 0, 'L', false);
                } else {
                    $pdf->SetXY($x, $y);
                    $pdf->Write(0, $value);
                }
            }
        }

        // Simpan file output
        $pdf->Output($outputPath, 'F');

        return $outputPath;
    }

    /**
     * Utility: buat PDF overlay grid untuk kalibrasi koordinat.
     * Simpan file di $outputPath; buka di viewer dan catat koordinat.
     */
    public function createCalibrationPdf(string $originalPath, string $outputPath)
    {
        $pdf = new Fpdi();
        $pageCount = $pdf->setSourceFile($originalPath);

        for ($p = 1; $p <= $pageCount; $p++) {
            $tpl = $pdf->importPage($p);
            $size = $pdf->getTemplateSize($tpl);
            $orientation = ($size['width'] > $size['height']) ? 'L' : 'P';
            $pdf->AddPage($orientation, [$size['width'], $size['height']]);
            $pdf->useTemplate($tpl);

            // Gambar grid (setiap 10 mm)
            $pdf->SetDrawColor(200, 200, 200);
            $pdf->SetLineWidth(0.1);

            for ($x = 10; $x < $size['width']; $x += 10) {
                $pdf->Line($x, 0, $x, $size['height']);
                $pdf->SetXY($x + 0.5, 2);
                $pdf->SetFont('helvetica', '', 6);
                $pdf->Write(0, (string)$x);
            }
            for ($y = 10; $y < $size['height']; $y += 10) {
                $pdf->Line(0, $y, $size['width'], $y);
                $pdf->SetXY(1, $y + 0.5);
                $pdf->SetFont('helvetica', '', 6);
                $pdf->Write(0, (string)$y);
            }
        }

        $pdf->Output($outputPath, 'F');
        return $outputPath;
    }
}
