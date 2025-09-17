<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Smalot\PdfParser\Parser;
use Illuminate\Support\Facades\Log;
use App\Models\Letter; // pastikan model ini ada
use Barryvdh\DomPDF\Facade\Pdf; // pastikan sudah composer require barryvdh/laravel-dompdf
use Carbon\Carbon;

class PdfTestController extends Controller
{
    public function form()
    {
        return view('pdftest.form');
    }

    // Parse file yang di-upload lewat form
    public function parse(Request $request)
    {
        $request->validate([
            'pdf' => 'required|file|mimes:pdf|max:10240',
            'letter_id' => 'nullable|integer',
        ]);

        $file = $request->file('pdf');
        $path = $file->getRealPath();

        $letterId = $request->input('letter_id');

        return $this->tryParseAndShow($path, $file->getClientOriginalName(), $letterId);
    }

    // Parse file yang sudah ada di storage (contoh: storage/app/public/filled/...)
    public function parseFromStorage($filename)
    {
        $relative = 'filled/' . $filename;
        if (! Storage::disk('public')->exists($relative)) {
            abort(404, 'File tidak ditemukan di storage.');
        }

        $path = Storage::disk('public')->path($relative);

        $letterId = request()->query('letter_id'); // ambil dari query string jika ada

        return $this->tryParseAndShow($path, $filename, $letterId);
    }

    protected function tryParseAndShow(string $path, string $displayName = '', $letterId = null)
    {
        try {
            $parser = new Parser();
            $pdf = $parser->parseFile($path);

            // Ambil seluruh teks
            $text = $pdf->getText();

            // Ambil per-page jika mau:
            $pages = [];
            $pageDotParts = [];
            foreach ($pdf->getPages() as $i => $page) {
                $pText = $page->getText();
                $pages[] = [
                    'page' => $i + 1,
                    'text' => $pText
                ];
            }

            // Ambil placeholders dari teks (seluruh dokumen)
            $placeholders = $this->extractPlaceholders($text);

            // Ambil data dari tabel letters
            $letter = null;
            if ($letterId) {
                $letter = Letter::find($letterId);
            }
            if (! $letter) {
                $letter = Letter::first(); // fallback: ambil baris pertama
            }
            $letterData = $letter ? $letter->toArray() : [];

            // Lakukan penggantian placeholder
            $replaceResult = $this->replacePlaceholders($text, $placeholders, $letterData);

            return view('pdftest.result', [
                'text' => $text,
                'replaced_text' => $replaceResult['text'],
                'replacements' => $replaceResult['replacements'],
                'missing_placeholders' => $replaceResult['missing'],
                'placeholders' => $placeholders,
                'pages' => $pages,
                'displayName' => $displayName,
                'letter' => $letter,
                'pageDotParts' => $pageDotParts,
                'fallbackUsed' => false,
            ]);
        } catch (\Throwable $e) {
            Log::warning('Smalot parse failed: '.$e->getMessage());
            // Fallback: coba pdftotext jika tersedia
            $fallback = $this->fallbackPdftotext($path);
            if ($fallback !== null) {
                $placeholders = $this->extractPlaceholders($fallback);
                $letter = $letter ?? Letter::first();
                $letterData = $letter ? $letter->toArray() : [];
                $replaceResult = $this->replacePlaceholders($fallback, $placeholders, $letterData);
                return view('pdftest.result', [
                    'text' => $fallback,
                    'replaced_text' => $replaceResult['text'],
                    'replacements' => $replaceResult['replacements'],
                    'missing_placeholders' => $replaceResult['missing'],
                    'placeholders' => $placeholders,
                    'pages' => [],
                    'displayName' => $displayName,
                    'letter' => $letter,
                    'pageDotParts' => [],
                    'fallbackUsed' => true,
                ]);
            }

            return view('pdftest.result', [
                'text' => null,
                'replaced_text' => null,
                'replacements' => [],
                'missing_placeholders' => [],
                'placeholders' => [],
                'pages' => [],
                'displayName' => $displayName,
                'letter' => null,
                'error' => 'Parsing PDF gagal: ' . $e->getMessage(),
                'dotParts' => [],
                'pageDotParts' => []
            ]);
        }
    }

    protected function fallbackPdftotext(string $path)
    {
        $cmdCheck = `which pdftotext`;
        if (!trim($cmdCheck)) {
            return null;
        }

        $escaped = escapeshellarg($path);
        $command = "pdftotext -layout $escaped -";
        exec($command, $output, $ret);
        if ($ret === 0) {
            return implode("\n", $output);
        }
        return null;
    }

    /**
     * Extract placeholders like $nama$, $tanggal$, $nomor-kop$
     * Return unique list of placeholder names (without dollar)
     */
    protected function extractPlaceholders(string $text): array
    {
        $placeholders = [];
        if (preg_match_all('/\$([a-zA-Z0-9_\-]+)\$/u', $text, $m)) {
            foreach ($m[1] as $ph) {
                $placeholders[] = $ph;
            }
        }
        return array_values(array_unique($placeholders));
    }

    /**
     * Replace placeholders in $text using $data (associative array)
     * - tries exact key match (case sensitive), then lowercase, then converts '-' -> '_'
     * Returns array:
     *  - text: replaced text
     *  - replacements: array of ['placeholder' => '$nama$', 'key' => 'nama', 'value' => 'John', 'replaced' => true/false]
     *  - missing: list of placeholders that had no replacement
     */
    protected function replacePlaceholders(string $text, array $placeholders, array $data): array
    {
        $replacements = [];
        $missing = [];

        // Normalisasi data agar dapat dicocokkan tanpa memperhatikan huruf besar-kecil
        $normalizedData = [];
        foreach ($data as $k => $v) {
            // Menyimpan data normal dan dalam bentuk lowercase
            $normalizedData[$k] = $v;
            $normalizedData[strtolower($k)] = $v;
        }

        $replacedText = $text;

        // Loop untuk mengganti setiap placeholder
        foreach ($placeholders as $ph) {
            $rawPlaceholder = '$' . $ph . '$';  // Format placeholder yang ditemukan
            $value = null;
            $foundKey = null;

            // 1) Langsung menggunakan key yang sama
            if (array_key_exists($ph, $data)) {
                $value = $data[$ph];
                $foundKey = $ph;
            }
            // 2) Mencocokkan dengan versi huruf kecil
            elseif (array_key_exists(strtolower($ph), $normalizedData)) {
                $value = $normalizedData[strtolower($ph)];
                $foundKey = strtolower($ph);  // Gunakan versi lowercase
            }
            // 3) Mengganti tanda hubung dengan garis bawah
            else {
                $alt = str_replace('-', '_', $ph);
                if (array_key_exists($alt, $data)) {
                    $value = $data[$alt];
                    $foundKey = $alt;
                } elseif (array_key_exists(strtolower($alt), $normalizedData)) {
                    $value = $normalizedData[strtolower($alt)];
                    $foundKey = strtolower($alt);
                }
            }

            // Memeriksa apakah nilai ditemukan dan menggantinya
            if ($value === null && $value !== 0 && $value !== '0') {
                // Placeholder tidak ditemukan
                $replacements[] = [
                    'placeholder' => $rawPlaceholder,
                    'key' => $foundKey,
                    'value' => null,
                    'replaced' => false,
                ];
                $missing[] = $rawPlaceholder;  // Catat placeholder yang hilang
            } else {
                // Jika nilai ditemukan, ganti placeholder dengan nilai yang sesuai
                $stringValue = is_scalar($value) ? (string) $value : json_encode($value);
                // Ganti semua kemunculan placeholder dengan nilai yang ditemukan
                $replacedText = str_replace($rawPlaceholder, $stringValue, $replacedText);
                $replacements[] = [
                    'placeholder' => $rawPlaceholder,
                    'key' => $foundKey,
                    'value' => $stringValue,
                    'replaced' => true,
                ];
            }
        }

        // Mengembalikan teks yang sudah diganti placeholder-nya
        return [
            'text' => $replacedText,
            'replacements' => $replacements,  // Daftar penggantian yang dilakukan
            'missing' => $missing,  // Placeholder yang tidak ditemukan
        ];
    }

    public function previewTemplate(Request $request)
    {
        // Terima teks yang sudah diganti placeholder dari result page
        $replaced_text = $request->input('replaced_text', '');
        $displayName = $request->input('display_name', 'Surat Preview');

        // Mendapatkan data letter yang dikirim dari form
        $letter = $request->input('letter') ? json_decode($request->input('letter'), true) : null; // Dekode JSON menjadi array

        // Debug: Cek data yang diterima setelah decode
        Log::debug('Decoded letter data:', $letter);

        // Simpan letter ke session
        session(['letter_data' => $letter]);

        // Kirim data ke view coba.blade.php
        return view('pdftest.preview', [
            'title' => $displayName,
            'body' => $replaced_text,
            'letter' => $letter,
            'date' => Carbon::now()->format('d F Y'),
            'replaced_values' => session('replaced_values', []),  // Data yang disimpan di session
        ]);
    }



    // Generate PDF dari template dan stream / download
    public function generatePDF(Request $request)
    {
        // Ambil teks yang sudah diganti placeholder
        $content_without_uploaded_placeholders = $request->input('replaced_text', '');

        // Pastikan placeholder diganti dengan benar
        Log::info('Replaced Text:', ['replaced_text' => $content_without_uploaded_placeholders]);

        // Kirim data ke view
        $data = [
            'content_without_uploaded_placeholders' => $content_without_uploaded_placeholders,
            'displayName' => $request->input('display_name'),
            'letter' => json_decode($request->input('letter'), true),
        ];

        // Generate PDF menggunakan DomPDF
        $pdf = Pdf::loadView('pdftest.final', $data);

        // Kembalikan PDF untuk di-download atau dibuka di browser
        return $pdf->download('surat.pdf');
    }


}
