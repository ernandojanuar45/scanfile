<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>@yield('title', 'Aplikasi Surat Kampus')</title>

    {{-- Konfigurasi Tailwind Play CDN dengan warna kustom --}}
    <script>
      tailwind.config = {
        theme: {
          extend: {
            colors: {
              'brand-lime': '#98D103',
              'brand-lime-dark': '#2B8F01',
              'brand-cyan': '#01B0EC',
              'brand-blue': '#0051B4',
            }
          }
        }
      }
    </script>
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
      @media print {
        nav, footer { display: none; }
      }
    </style>
</head>
<body class="bg-slate-50 text-slate-900 antialiased">

  {{-- Navbar modern dengan warna pallet --}}
    <nav class="backdrop-blur-sm border-b border-slate-200 sticky top-0 z-40 bg-brand-cyan text-white" style="background-color: #01B0EC;">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
          <div class="flex items-center gap-3">
              <div class="bg-brand-lime text-white rounded-md w-10 h-10 flex items-center justify-center shadow-sm ring-1 ring-brand-lime-dark/20">
                <span class="text-lg">📄</span>
              </div>
              <div class="leading-tight">
                <div class="font-semibold text-sm text-slate-900">Aplikasi Surat Kampus</div>
                <div class="text-xs text-slate-200">Kelola & cetak surat resmi</div>
              </div>
            </a>
          </div>

          <div class="flex items-center gap-4">
            <div class="hidden sm:flex items-center bg-slate-100 border border-slate-200 rounded-lg px-3 py-1 gap-2">
              <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M21 21l-4.35-4.35M11 19a8 8 0 100-16 8 8 0 000 16z" />
              </svg>
              <input type="text" aria-label="Cari surat" placeholder="Cari surat, nama, NIM..." class="bg-transparent outline-none text-sm w-56" />
            </div>

            <a href="{{ route('pdftest.form') }}" class="hidden sm:inline-flex items-center gap-2 bg-brand-cyan text-white text-sm px-3 py-2 rounded-md shadow hover:bg-brand-blue transition-colors" role="button">
              ✚ Buat Surat
            </a>

            <button id="mobileMenuBtn" aria-controls="mobileMenu" aria-expanded="false" class="sm:hidden p-2 rounded-md text-slate-100 hover:bg-slate-200/10 focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-brand-lime">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M4 6h16M4 12h16M4 18h16"/>
              </svg>
              <span class="sr-only">Buka menu</span>
            </button>
          </div>
        </div>
      </div>

      <div id="mobileMenu" class="sm:hidden border-t border-slate-100 hidden">
        <div class="px-4 py-3 space-y-2">
          <a href="{{ route('pdftest.form') }}" class="block text-sm text-slate-200" >Buat Surat</a>
        </div>
      </div>
    </nav>

  <main class="py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <header class="mb-6 flex items-center justify-between">
        <div>
          <h1 class="text-2xl font-semibold text-slate-900">@yield('title', 'Aplikasi Surat Kampus')</h1>
          <p class="text-sm text-slate-500 mt-1">Kelola template, pratinjau, dan cetak surat resmi kampus dengan mudah.</p>
        </div>
        <div class="hidden sm:flex items-center gap-3">
          <div class="text-sm text-slate-500">Status: <span class="font-medium text-slate-700">Siap</span></div>
        </div>
      </header>

      <section class="bg-white shadow-sm rounded-lg border border-slate-100 p-6">
        {{-- Konten halaman akan muncul di sini --}}
        @yield('content')
      </section>
    </div>
  </main>

  <footer class="bg-white border-t border-slate-100 mt-12">
    <div class="max-w-7xl mx-auto px-4 py-6 flex flex-col sm:flex-row items-center justify-between gap-4">
      <div class="text-sm text-slate-600">© {{ date('Y') }} Aplikasi Surat Kampus — All rights reserved.</div>
      <div class="text-sm text-slate-500">Made with ❤️ — <span class="text-brand-lime-dark font-medium">Brand Accent</span></div>
    </div>
  </footer>

  <script>
    document.getElementById('mobileMenuBtn').addEventListener('click', function() {
      const m = document.getElementById('mobileMenu');
      if (!m) return;
      m.classList.toggle('hidden');
    });

      (function() {
        const btn = document.getElementById('mobileMenuBtn');
        const mobileMenu = document.getElementById('mobileMenu');
        if (!btn || !mobileMenu) return;

        btn.addEventListener('click', function() {
          const expanded = btn.getAttribute('aria-expanded') === 'true';
          btn.setAttribute('aria-expanded', String(!expanded));
          mobileMenu.classList.toggle('hidden');
        });

        // Optional: close mobile menu when clicking outside (simple)
        document.addEventListener('click', function(e) {
          const target = e.target;
          if (!mobileMenu.contains(target) && !btn.contains(target) && !mobileMenu.classList.contains('hidden')) {
            mobileMenu.classList.add('hidden');
            btn.setAttribute('aria-expanded', 'false');
          }
        });
      })();
  </script>
</body>
</html>
