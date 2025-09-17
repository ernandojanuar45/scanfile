<?php

namespace App\Services;

use setasign\Fpdi\Tcpdf\Fpdi;

class PdfOverlayService
{
    /**
     * Overlay data ke PDF asli dan simpan ke $outputPath
     *
     * @param string $originalPath path file PDF asli (local storage full path)
     * @param array $fields ['nomor_kop' => ..., 'nama' => ..., 'alamat' => ..., 'tanggal' => ...]
     * @param string $outputPath full path untuk menyimpan output
     */
    public function overlayDataOnPdf(string $originalPath, array $fields, string $outputPath)
    {
        $pdf = new Fpdi();

        $pageCount = $pdf->setSourceFile($originalPath);
        for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
            $tplId = $pdf->importPage($pageNo);
            $size = $pdf->getTemplateSize($tplId);
            $orientation = ($size['width'] > $size['height']) ? 'L' : 'P';
            $pdf->AddPage($orientation, [$size['width'], $size['height']]);
            $pdf->useTemplate($tplId);

            // Set font dan warna (sesuaikan)
            $pdf->SetFont('helvetica', '', 10);
            $pdf->SetTextColor(0, 0, 0);

            // Contoh koordinat (mm). Ubah sesuai layout Anda.
            if ($pageNo === 1) {
                // Nomor kop (contoh posisi kanan atas)
                $pdf->SetXY(140, 20);
                $pdf->Write(0, $fields['nomor_kop'] ?? '');

                // Nama pengirim (contoh kiri)
                $pdf->SetXY(30, 60);
                $pdf->Write(0, $fields['nama'] ?? '');

                // Alamat (multi-line)
                $pdf->SetXY(30, 64);
                $pdf->MultiCell(120, 5, $fields['alamat'] ?? '');

                // Tanggal (kanan)
                $pdf->SetXY(140, 60);
                $pdf->Write(0, $fields['tanggal'] ?? '');
            }

            // Jika perlu overlay isi surat, sesuaikan koordinat dan gunakan MultiCell untuk wrap.
        }

        // Simpan hasil
        $pdf->Output($outputPath, 'F');
    }
}
