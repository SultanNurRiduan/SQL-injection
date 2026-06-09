<?php
// ============================================
// TUGAS 9: SQLi Lab — Index / Landing Page
// Simpan: C:\xampp\htdocs\sqli_lab\index.php
// Akses : http://localhost/sqli_lab/
// Mahasiswa: Sultan | NIM: 231220005
// ============================================
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>SQLi Lab — Tugas 9</title>
<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>
<script>
tailwind.config = {
  theme: {
    extend: {
      fontFamily: { sans:['"Sora"','sans-serif'], mono:['"JetBrains Mono"','monospace'] },
      animation: {
        fadeUp:  'fadeUp 0.5s ease both',
        fadeUp2: 'fadeUp 0.5s ease .1s both',
        fadeUp3: 'fadeUp 0.5s ease .2s both',
        fadeUp4: 'fadeUp 0.5s ease .3s both',
      },
      keyframes: {
        fadeUp:{
          from:{opacity:'0',transform:'translateY(16px)'},
          to:{opacity:'1',transform:'translateY(0)'},
        },
      },
    },
  },
}
</script>
<link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;600&family=Sora:wght@300;400;500;600;700&display=swap" rel="stylesheet"/>
<style>
  body {
    background:
      radial-gradient(ellipse 65% 55% at 80% 90%, rgba(20,210,180,.09) 0%, transparent 60%),
      radial-gradient(ellipse 60% 55% at 15% 15%, rgba(110,50,200,.18) 0%, transparent 55%),
      linear-gradient(140deg,#0e0720 0%,#1a1040 48%,#0d1628 100%);
    min-height:100vh;
  }
  body::before {
    content:''; position:fixed; inset:0; pointer-events:none; z-index:0;
    background:url("data:image/svg+xml,%3Csvg viewBox='0 0 300 300' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='.72' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='.03'/%3E%3C/svg%3E");
  }
  .glass {
    background:rgba(38,22,80,.72);
    backdrop-filter:blur(20px); -webkit-backdrop-filter:blur(20px);
    border:1px solid rgba(150,110,220,.22);
    box-shadow:0 12px 60px rgba(0,0,0,.5),0 1px 0 rgba(255,255,255,.05) inset;
  }
  .card-vuln {
    background:rgba(60,15,15,.55);
    backdrop-filter:blur(16px);
    border:1px solid rgba(239,68,68,.28);
    box-shadow:0 8px 40px rgba(0,0,0,.4),0 0 0 0 rgba(239,68,68,0);
    transition:border-color .25s,box-shadow .25s,transform .25s;
  }
  .card-vuln:hover {
    border-color:rgba(239,68,68,.6);
    box-shadow:0 12px 48px rgba(239,68,68,.18);
    transform:translateY(-4px);
  }
  .card-secure {
    background:rgba(5,40,25,.55);
    backdrop-filter:blur(16px);
    border:1px solid rgba(16,185,129,.28);
    box-shadow:0 8px 40px rgba(0,0,0,.4);
    transition:border-color .25s,box-shadow .25s,transform .25s;
  }
  .card-secure:hover {
    border-color:rgba(16,185,129,.6);
    box-shadow:0 12px 48px rgba(16,185,129,.18);
    transform:translateY(-4px);
  }
  .step-pill {
    display:inline-flex; align-items:center; gap:6px;
    padding:4px 12px; border-radius:999px;
    font-size:.7rem; font-weight:600; letter-spacing:.04em;
  }
  .timeline-line {
    position:absolute; left:19px; top:36px; bottom:0;
    width:2px;
    background:linear-gradient(180deg,rgba(139,92,246,.4),transparent);
  }
</style>
</head>
<body class="font-sans text-purple-100 relative">

<!-- Blobs -->
<div class="fixed inset-0 pointer-events-none z-0 overflow-hidden">
  <div class="absolute -top-40 -left-40 w-[28rem] h-[28rem] rounded-full"
       style="background:radial-gradient(circle,rgba(139,92,246,.13) 0%,transparent 70%)"></div>
  <div class="absolute bottom-0 -right-32 w-96 h-96 rounded-full"
       style="background:radial-gradient(circle,rgba(20,210,180,.09) 0%,transparent 70%)"></div>
</div>

<div class="relative z-10 max-w-2xl mx-auto px-4 py-14 pb-24">

  <!-- ══ HEADER ══ -->
  <div class="text-center mb-12 animate-fadeUp">
    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-xs font-semibold tracking-widest uppercase mb-6"
         style="background:rgba(139,92,246,.16);border:1px solid rgba(139,92,246,.4);color:#c4b5fd">
      <i class="fa-solid fa-flask-vial"></i> Tugas 9 &mdash; Praktikum Keamanan Web
    </div>
    <h1 class="text-4xl md:text-5xl font-bold tracking-tight leading-tight mb-4">
      SQL Injection<br/>
      <span class="text-violet-300">Lab</span>
    </h1>
    <p class="text-sm text-purple-400 max-w-md mx-auto leading-relaxed">
      Simulasi lengkap serangan <strong class="text-purple-300">SQL Injection</strong> dan penerapan mitigasi
      <strong class="text-purple-300">Prepared Statements</strong> menggunakan PHP &amp; MySQL di XAMPP.
    </p>
  </div>

  <!-- ══ PETA JALAN ══ -->
  <div class="glass rounded-2xl p-7 mb-6 animate-fadeUp2">
    <h2 class="text-base font-bold text-purple-100 mb-5 flex items-center gap-3">
      <span class="block w-1 h-5 rounded-full" style="background:linear-gradient(180deg,#a78bfa,#c084fc)"></span>
      <i class="fa-solid fa-map text-violet-400 text-sm"></i>
      Peta Jalan Praktikum
    </h2>

    <div class="relative pl-10 space-y-5">
      <div class="timeline-line"></div>

      <?php
      $steps = [
        ['num'=>'1','icon'=>'fa-database',         'color'=>'#8b5cf6','label'=>'Setup Database',    'desc'=>'Buat tabel <code class="text-purple-300 bg-purple-900/30 px-1 rounded">users</code> di phpMyAdmin dengan dua tingkat pengguna: <em>admin</em> dan <em>user biasa</em>.'],
        ['num'=>'2','icon'=>'fa-code',              'color'=>'#ef4444','label'=>'Koding Vulnerable', 'desc'=>'Tulis query PHP bergaya 2005 — variabel <code class="text-red-300 bg-red-900/30 px-1 rounded">$_POST</code> digabung langsung ke string SQL.'],
        ['num'=>'3','icon'=>'fa-user-secret',       'color'=>'#f59e0b','label'=>'Skenario Hack',     'desc'=>'Sisipkan payload <code class="text-amber-300 bg-amber-900/20 px-1 rounded">\' OR \'1\'=\'1</code> ke form dan buktikan bypass otentikasi.'],
        ['num'=>'4','icon'=>'fa-shield-halved',     'color'=>'#10b981','label'=>'Patching (Mitigasi)','desc'=>'Rekonstruksi kode login menggunakan <strong class="text-emerald-300">Prepared Statements</strong> agar kebal injeksi.'],
      ];
      foreach ($steps as $s): ?>
      <div class="flex items-start gap-4">
        <div class="absolute left-0 w-10 h-10 rounded-full flex items-center justify-center shrink-0 z-10"
             style="background:<?= $s['color'] ?>22;border:1.5px solid <?= $s['color'] ?>66">
          <i class="fa-solid <?= $s['icon'] ?> text-sm" style="color:<?= $s['color'] ?>"></i>
        </div>
        <div class="ml-2">
          <p class="text-sm font-bold text-purple-100 mb-0.5">
            <span class="text-xs mr-1.5" style="color:<?= $s['color'] ?>">0<?= $s['num'] ?></span>
            <?= $s['label'] ?>
          </p>
          <p class="text-xs text-purple-400 leading-relaxed"><?= $s['desc'] ?></p>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>

  <!-- ══ NAVIGASI UTAMA ══ -->
  <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-6">

    <!-- Vulnerable -->
    <a href="login_vulnerable.php" class="card-vuln rounded-2xl p-6 block group animate-fadeUp3">
      <div class="flex items-center gap-3 mb-4">
        <div class="w-11 h-11 rounded-xl flex items-center justify-center shrink-0"
             style="background:rgba(239,68,68,.20);border:1px solid rgba(239,68,68,.45)">
          <i class="fa-solid fa-bug text-red-400 text-lg"></i>
        </div>
        <div>
          <span class="step-pill" style="background:rgba(239,68,68,.18);border:1px solid rgba(239,68,68,.4);color:#fca5a5">
            Langkah 2 &amp; 3
          </span>
          <p class="text-xs text-purple-500 mt-0.5">Koding Rentan &amp; Hack</p>
        </div>
      </div>
      <h3 class="text-lg font-bold text-red-300 mb-2 group-hover:text-red-200 transition-colors">
        Login Rentan <i class="fa-solid fa-arrow-right text-sm ml-1 opacity-60 group-hover:translate-x-1 transition-transform inline-block"></i>
      </h3>
      <p class="text-xs text-purple-400 leading-relaxed mb-4">
        Form login dengan query SQL yang rentan. Gunakan payload <code class="text-red-300 bg-red-900/25 px-1 rounded">' OR '1'='1</code>
        untuk membuktikan bypass otentikasi tanpa password.
      </p>
      <div class="flex flex-wrap gap-2">
        <span class="step-pill" style="background:rgba(239,68,68,.12);border:1px solid rgba(239,68,68,.25);color:#f87171">
          <i class="fa-solid fa-triangle-exclamation text-xs"></i> SQL Injection
        </span>
        <span class="step-pill" style="background:rgba(245,158,11,.10);border:1px solid rgba(245,158,11,.25);color:#fbbf24">
          <i class="fa-solid fa-terminal text-xs"></i> Debug Query
        </span>
      </div>
    </a>

    <!-- Secure -->
    <a href="login_secure.php" class="card-secure rounded-2xl p-6 block group animate-fadeUp4">
      <div class="flex items-center gap-3 mb-4">
        <div class="w-11 h-11 rounded-xl flex items-center justify-center shrink-0"
             style="background:rgba(16,185,129,.20);border:1px solid rgba(16,185,129,.45)">
          <i class="fa-solid fa-shield-halved text-emerald-400 text-lg"></i>
        </div>
        <div>
          <span class="step-pill" style="background:rgba(16,185,129,.18);border:1px solid rgba(16,185,129,.4);color:#6ee7b7">
            Langkah 4
          </span>
          <p class="text-xs text-purple-500 mt-0.5">Patching &amp; Mitigasi</p>
        </div>
      </div>
      <h3 class="text-lg font-bold text-emerald-300 mb-2 group-hover:text-emerald-200 transition-colors">
        Login Aman <i class="fa-solid fa-arrow-right text-sm ml-1 opacity-60 group-hover:translate-x-1 transition-transform inline-block"></i>
      </h3>
      <p class="text-xs text-purple-400 leading-relaxed mb-4">
        Form login yang sudah dipatch dengan <strong class="text-emerald-300">Prepared Statements</strong>.
        Payload yang sama akan ditolak — MySQL memperlakukannya sebagai teks biasa.
      </p>
      <div class="flex flex-wrap gap-2">
        <span class="step-pill" style="background:rgba(16,185,129,.12);border:1px solid rgba(16,185,129,.25);color:#34d399">
          <i class="fa-solid fa-shield-halved text-xs"></i> Prepared Stmt
        </span>
        <span class="step-pill" style="background:rgba(6,182,212,.10);border:1px solid rgba(6,182,212,.25);color:#67e8f9">
          <i class="fa-solid fa-lock text-xs"></i> bind_param
        </span>
      </div>
    </a>

  </div>

  <!-- ══ SETUP DATABASE INFO ══ -->
  <div class="glass rounded-2xl p-7 animate-fadeUp" style="animation-delay:.25s">
    <h2 class="text-base font-bold text-purple-100 mb-4 flex items-center gap-3">
      <span class="block w-1 h-5 rounded-full" style="background:linear-gradient(180deg,#a78bfa,#c084fc)"></span>
      <i class="fa-solid fa-database text-violet-400 text-sm"></i>
      Langkah 1 &mdash; Setup Database
    </h2>
    <p class="text-xs text-purple-400 leading-relaxed mb-4">
      Sebelum menjalankan lab, pastikan XAMPP menyala dan database sudah disiapkan.
      Buka <a href="http://localhost/phpmyadmin" target="_blank" class="text-violet-300 hover:underline">phpMyAdmin</a>,
      pilih tab <strong class="text-purple-300">SQL</strong>, lalu jalankan script berikut:
    </p>
    <div class="rounded-xl p-4 font-mono text-xs leading-6 overflow-x-auto"
         style="background:rgba(10,5,30,.75);border:1px solid rgba(139,92,246,.20)">
<pre class="text-purple-200 whitespace-pre"><span class="text-purple-500">-- Buat database</span>
<span class="text-fuchsia-400">CREATE DATABASE IF NOT EXISTS</span> <span class="text-amber-300">keamanan_db</span>;
<span class="text-fuchsia-400">USE</span> <span class="text-amber-300">keamanan_db</span>;

<span class="text-purple-500">-- Buat tabel users</span>
<span class="text-fuchsia-400">CREATE TABLE</span> users (
  id       <span class="text-violet-300">INT AUTO_INCREMENT PRIMARY KEY</span>,
  username <span class="text-violet-300">VARCHAR(50) NOT NULL</span>,
  password <span class="text-violet-300">VARCHAR(50) NOT NULL</span>,
  role     <span class="text-violet-300">VARCHAR(20) DEFAULT 'user'</span>
);

<span class="text-purple-500">-- Insert data</span>
<span class="text-fuchsia-400">INSERT INTO</span> users (username, password, role) <span class="text-fuchsia-400">VALUES</span>
  (<span class="text-green-400">'admin'</span>, <span class="text-green-400">'admin123'</span>, <span class="text-green-400">'admin'</span>),
  (<span class="text-green-400">'budi'</span>,<span class="text-green-400">'budi123'</span>,<span class="text-green-400">'user'</span>);</pre>
    </div>
    <div class="flex flex-wrap gap-2 mt-4">
      <span class="step-pill" style="background:rgba(139,92,246,.14);border:1px solid rgba(139,92,246,.3);color:#c4b5fd">
        <i class="fa-solid fa-server text-xs"></i> MySQL / MariaDB
      </span>
      <span class="step-pill" style="background:rgba(139,92,246,.10);border:1px solid rgba(139,92,246,.22);color:#a78bfa">
        <i class="fa-solid fa-table text-xs"></i> Tabel: users
      </span>
      <span class="step-pill" style="background:rgba(139,92,246,.10);border:1px solid rgba(139,92,246,.22);color:#a78bfa">
        <i class="fa-solid fa-users text-xs"></i> 2 Role: admin &amp; user
      </span>
    </div>
  </div>

</div>

<!-- ══ FOOTER ══ -->
<footer class="relative z-10 pb-10">
  <div class="max-w-2xl mx-auto px-4">
    <div class="glass rounded-2xl px-8 py-6 flex flex-col sm:flex-row items-center justify-between gap-4"
         style="background:rgba(30,15,65,.70);border:1px solid rgba(150,110,220,.20);">
      <div class="flex items-center gap-4">
        <div class="w-12 h-12 rounded-full flex items-center justify-center shrink-0"
             style="background:linear-gradient(135deg,#7c3aed,#8b5cf6);box-shadow:0 0 18px rgba(139,92,246,.45);">
          <i class="fa-solid fa-user-graduate text-white text-lg"></i>
        </div>
        <div>
          <p class="text-sm font-bold text-purple-100 tracking-wide">Sultan &mdash; NIM: 231220005</p>
          <p class="text-xs text-purple-400 mt-0.5"><i class="fa-solid fa-book-open mr-1 opacity-70"></i>Tugas 9 — SQL Injection Lab</p>
          <p class="text-xs text-purple-400 mt-0.5"><i class="fa-solid fa-code mr-1 opacity-70"></i>PHP MySQLi + XAMPP</p>
        </div>
      </div>
      <div class="hidden sm:block h-10 w-px" style="background:rgba(150,110,220,.25)"></div>
      <div class="text-center sm:text-right">
        <p class="text-xs font-semibold text-violet-300 mb-1">
          <i class="fa-solid fa-flask-vial mr-1"></i>Hack &amp; Patch SQLi
        </p>
        <p class="text-xs text-purple-500">Praktikum Keamanan Web</p>
        <p class="text-xs text-purple-600 mt-1">Hanya untuk keperluan edukasi lab</p>
      </div>
    </div>
  </div>
</footer>

</body>
</html>