<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Http\Controllers\PdfTestController;

// =============================
// Test upload (gunakan POST untuk upload file)
// =============================

Route::get('/', [PdfTestController::class, 'form'])->name('pdftest.form');
Route::post('/pdf-test/parse', [PdfTestController::class, 'parse'])->name('pdftest.parse');
Route::post('/pdf-test/from-storage/{filename}', [PdfTestController::class, 'parseFromStorage'])->name('pdftest.storage');

// Pastikan middleware/auth sesuai kebutuhan Anda
Route::post('pdftest/preview', [App\Http\Controllers\PdfTestController::class, 'previewTemplate'])->name('pdftest.preview');
Route::post('pdftest/generate', [App\Http\Controllers\PdfTestController::class, 'generatePdf'])->name('pdftest.generate');

Route::get('/pdf-test', [PdfTestController::class, 'form'])->name('pdftest.form');