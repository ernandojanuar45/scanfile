@extends('layouts.app')

@section('content')
<div class="space-y-6">

  {{-- Header --}}
  <div class="bg-white shadow rounded-lg p-6">
    <div class="flex items-start justify-between">
      <div>
        <h2 class="text-2xl font-semibold text-primary-dark">Debug PDF</h2>
        <p class="text-sm text-gray-500 mt-1">Informasi hasil replace placeholder dan tindakan selanjutnya.</p>
      </div>
      <div class="text-right">
        <p class="text-xs text-gray-400">File target:</p>
        <p class="font-medium">{{ $displayName ?? '-' }}</p>
      </div>
    </div>
  </div>

  {{-- Hasil Penggantian Placeholder --}}
  <div class="bg-white shadow rounded-lg p-6">
    <h3 class="text-lg font-medium mb-4 text-primary-dark">Hasil penggantian placeholder</h3>

    @if(!empty($replacements))
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Placeholder</th>
              <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Key (kolom)</th>
              <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Value</th>
              <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Replaced?</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-100">
            @foreach($replacements as $r)
              <tr>
                <td class="px-4 py-3 text-sm text-gray-700">{{ $r['placeholder'] }}</td>
                <td class="px-4 py-3 text-sm text-gray-600">{{ $r['key'] ?? '-' }}</td>
                <td class="px-4 py-3 text-sm text-gray-700">{{ $r['value'] ?? '-' }}</td>
                <td class="px-4 py-3 text-sm">
                  @if($r['replaced'])
                    <span class="inline-flex items-center px-2 py-1 rounded text-xs bg-green-100 text-green-800">Ya</span>
                  @else
                    <span class="inline-flex items-center px-2 py-1 rounded text-xs bg-gray-100 text-gray-700">Tidak</span>
                  @endif
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    @else
      <div class="text-gray-600">Tidak ada placeholder ditemukan.</div>
    @endif
  </div>

  {{-- Placeholder hilang --}}
  @if(!empty($missing_placeholders))
    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded">
      <h4 class="font-semibold text-yellow-700">Placeholder yang tidak ditemukan di tabel letters:</h4>
      <ul class="list-disc ml-5 mt-2 text-sm text-yellow-800">
        @foreach($missing_placeholders as $mp)
          <li>{{ $mp }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  {{-- Actions (Preview / Generate) --}}
  <div class="flex flex-wrap gap-3">
    <!-- <form action="{{ route('pdftest.preview') }}" method="post" target="_blank">
        @csrf
        <input type="hidden" name="replaced_text" value="{{ old('replaced_text', $replaced_text) }}">
        <input type="hidden" name="display_name" value="{{ $displayName }}">
        <input type="hidden" name="letter" value='@json($letter)'>
        <button type="submit" class="inline-flex items-center gap-2 bg-white text-accent border border-accent px-4 py-2 rounded-md shadow hover:bg-accent/5">
          🔍 Lihat Template (Preview)
        </button>
    </form> -->

    <form action="{{ route('pdftest.generate') }}" method="post" class="ml-2">
    @csrf
    <input type="hidden" name="replaced_text" value="{{ old('replaced_text', $replaced_text) }}">
    <input type="hidden" name="display_name" value="{{ pathinfo($displayName, PATHINFO_FILENAME) }}">
    <input type="hidden" name="letter" value='@json($letter)'>
    <button type="submit" formtarget="_blank" class="inline-flex items-center gap-2 bg-primary-dark text-white px-4 py-2 rounded-md shadow hover:bg-primary" style="background-color: #0051B4;">
      ⬇️ Download / Buka PDF
    </button>
  </form>

  </div>

  {{-- Error / Alert --}}
  @if(isset($error))
    <div class="bg-red-50 border-l-4 border-red-400 p-4 rounded">
      <p class="text-red-700">{{ $error }}</p>
    </div>
  @endif
</div>
@endsection
