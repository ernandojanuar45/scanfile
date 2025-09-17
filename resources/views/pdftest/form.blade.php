@extends('layouts.app')

@section('content')
<style>
    :root{
        --bg:#f4f7fb;
        --card:#ffffff;
        --muted:#6b7280;
        --accent:#0051B4;
        --accent-2:#01B0EC;
        --success:#10b981;
        --shadow: 0 6px 18px rgba(15,23,42,0.08);
        --radius:12px;
        --max-width:720px;
        font-family: Inter, ui-sans-serif, system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial;
    }

    .modern-wrap{
        min-height:60vh;
        display:flex;
        align-items:center;
        justify-content:center;
        padding:40px 16px;
        background: linear-gradient(180deg, rgba(79,70,229,0.04), rgba(124,58,237,0.02));
    }

    .card {
        width:100%;
        max-width:var(--max-width);
        background:var(--card);
        border-radius:var(--radius);
        box-shadow:var(--shadow);
        padding:28px;
        box-sizing:border-box;
    }

    .card-head{
        display:flex;
        align-items:center;
        gap:14px;
        margin-bottom:18px;
    }

    .logo-badge{
        width:46px;
        height:46px;
        border-radius:10px;
        background:linear-gradient(135deg,var(--accent),var(--accent-2));
        display:flex;
        align-items:center;
        justify-content:center;
        color:white;
        font-weight:700;
        font-size:18px;
        box-shadow: 0 4px 10px rgba(124,58,237,0.18);
    }

    h3.title{
        margin:0;
        font-size:18px;
        color:#0f172a;
    }
    p.lead{
        margin:4px 0 0 0;
        color:var(--muted);
        font-size:13px;
    }

    form.upload-form{
        margin-top:18px;
    }

    .file-row{
        display:flex;
        gap:12px;
        align-items:center;
        flex-wrap:wrap;
    }

    /* custom file input */
    .file-input{
        position:relative;
        flex:1 1 360px;
    }

    .file-input input[type="file"]{
        width:100%;
        height:56px;
        opacity:0;
        position:absolute;
        left:0;top:0;
        cursor:pointer;
    }

    .file-ui{
        display:flex;
        align-items:center;
        gap:12px;
        padding:12px 14px;
        border-radius:10px;
        background: linear-gradient(180deg, #fff, #fbfdff);
        border:1px dashed rgba(15,23,42,0.06);
        min-height:56px;
        box-sizing:border-box;
    }

    .file-ui .icon{
        width:44px;height:44px;border-radius:8px;
        background:linear-gradient(180deg, rgba(79,70,229,0.12), rgba(124,58,237,0.06));
        display:flex;align-items:center;justify-content:center;color:var(--accent);
        font-weight:600;
    }

    .file-meta{
        display:flex;
        flex-direction:column;
        min-width:0;
    }

    .file-meta .label{
        font-size:14px;
        color:#0f172a;
        font-weight:600;
        white-space:nowrap;
        overflow:hidden;
        text-overflow:ellipsis;
    }
    .file-meta .hint{
        font-size:12px;
        color:var(--muted);
        margin-top:2px;
    }

    .actions{
        display:flex;
        align-items:center;
        gap:10px;
    }

    .btn{
        appearance:none;
        border:0;
        cursor:pointer;
        padding:10px 18px;
        border-radius:10px;
        font-weight:600;
        color:white;
        background: linear-gradient(90deg,var(--accent),var(--accent-2));
        box-shadow: 0 8px 20px rgba(79,70,229,0.12);
        transition:transform .12s ease, box-shadow .12s ease;
    }
    .btn:active{ transform:translateY(1px); }
    .btn:disabled{ opacity:0.6; cursor:not-allowed; transform:none; }

    /* small helper */
    .note{
        font-size:13px;
        color:var(--muted);
        margin-top:12px;
    }

    @media (max-width:580px){
        .file-row{ flex-direction:column; align-items:stretch; }
        .actions{ justify-content:flex-end; width:100%; }
    }
</style>

<div class="modern-wrap">
    <div class="card" role="region" aria-label="PDF Parser Upload">
        <div class="card-head">
            <div class="logo-badge">PDF</div>
            <div>
                <h3 class="title">PDF Text Extract — Upload untuk test</h3>
                <p class="lead">Unggah berkas PDF untuk mengekstrak teks dan mencoba parser.</p>
            </div>
        </div>

        <form class="upload-form" action="{{ route('pdftest.parse') }}" method="post" enctype="multipart/form-data" novalidate>
            @csrf

            <div class="file-row" style="margin-bottom:12px;">
                <div class="file-input" title="Pilih file PDF">
                    <div class="file-ui" id="fileUi">
                        <div class="icon" aria-hidden="true">
                            <!-- simple PDF icon -->
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M6 2h7l5 5v13a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2z" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M14 2v6h6" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>

                        <div class="file-meta">
                            <div class="label" id="fileLabel">Pilih file PDF atau klik untuk memilih</div>
                            <div class="hint">Hanya *.pdf — Maks 20MB</div>
                        </div>

                        <!-- actual input (kept name and accept as original) -->
                        <input type="file" name="pdf" id="pdfInput" accept="application/pdf" required aria-describedby="fileLabel">
                    </div>
                </div>

                <div class="actions">
                    <button type="submit" class="btn" id="submitBtn">Parse</button>
                </div>
            </div>

            <div class="note">Tip: Anda dapat mencoba file PDF dari dokumen resmi untuk melihat hasil ekstraksi. Jika tidak muncul, periksa ukuran/format file.</div>
        </form>
    </div>
</div>

<script>
    // Menampilkan nama file yang dipilih (UX kecil)
    (function(){
        const input = document.getElementById('pdfInput');
        const label = document.getElementById('fileLabel');
        const submitBtn = document.getElementById('submitBtn');

        input.addEventListener('change', function(e){
            const file = this.files && this.files[0];
            if(!file) {
                label.textContent = 'Pilih file PDF atau klik untuk memilih';
                return;
            }
            label.textContent = file.name + ' • ' + Math.round(file.size/1024) + ' KB';
            // optional: enable submit if disabled
            if(submitBtn) submitBtn.disabled = false;
        });

        // improve keyboard/enter accessibility: when pressing Enter on label, trigger input click
        label.parentElement.addEventListener('keydown', function(ev){
            if(ev.key === 'Enter' || ev.key === ' ') {
                input.click();
                ev.preventDefault();
            }
        });
    })();
</script>
@endsection
