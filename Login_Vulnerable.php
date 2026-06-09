<?php

$host    = "localhost";
$user_db = "root";
$pass_db = "";       
$db_name = "keamanan_db";

$conn = new mysqli($host, $user_db, $pass_db, $db_name);
if ($conn->connect_error) die("Koneksi gagal: " . $conn->connect_error);

$pesan       = "";
$pesan_type  = "";
$query_debug = "";
$timestamp   = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $user_input = $_POST['username'] ?? '';
    $pass_input = $_POST['password'] ?? '';

    $sql = "SELECT * FROM users WHERE username = '$user_input' AND password = '$pass_input'";

    $query_debug = $sql;  
    $timestamp   = date("Y-m-d H:i:s");

    $result = $conn->query($sql);   

    if ($result === false) {
        $pesan      = "Gagal Login! Terjadi kesalahan pada query. Cek kembali payload.";
        $pesan_type = "error";
    } elseif ($result->num_rows > 0) {
        $row        = $result->fetch_assoc();
        $pesan      = "Berhasil Login! Selamat datang, <strong>" . htmlspecialchars($row['username']) . "</strong> &mdash; Role: <strong>" . htmlspecialchars($row['role']) . "</strong>";
        $pesan_type = "success";
    } else {
        $pesan      = "Gagal Login! Username atau password tidak ditemukan.";
        $pesan_type = "error";
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>SQLi Lab — Login Rentan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Sora"', 'sans-serif'],
                        mono: ['"JetBrains Mono"', 'monospace']
                    },
                    animation: { fadeUp: 'fadeUp 0.5s ease both' },
                    keyframes: {
                        fadeUp: {
                            from: { opacity: '0', transform: 'translateY(14px)' },
                            to:   { opacity: '1', transform: 'translateY(0)' },
                        },
                    },
                },
            },
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;600;700&family=Sora:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
    <style>
        body {
            background:
                radial-gradient(ellipse 65% 55% at 75% 88%, rgba(220,50,50,.08) 0%, transparent 60%),
                radial-gradient(ellipse 60% 55% at 15% 15%, rgba(110,50,200,.18) 0%, transparent 55%),
                linear-gradient(140deg, #0e0720 0%, #1a1040 48%, #0d1628 100%);
            min-height: 100vh;
        }
        body::before {
            content: ''; position: fixed; inset: 0; pointer-events: none; z-index: 0;
            background: url("data:image/svg+xml,%3Csvg viewBox='0 0 300 300' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='.72' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='.03'/%3E%3C/svg%3E");
        }
        .glass {
            background: rgba(38,22,80,.72);
            backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(150,110,220,.22);
            box-shadow: 0 12px 60px rgba(0,0,0,.5), 0 1px 0 rgba(255,255,255,.05) inset;
        }
        .input-field {
            width: 100%; padding: 10px 14px; box-sizing: border-box;
            background: rgba(55,35,95,.65); border: 1px solid rgba(150,110,220,.22);
            border-radius: 10px; color: #e9d5ff; font-size: 14px; outline: none;
            transition: border-color .2s, box-shadow .2s; font-family: 'Sora', sans-serif;
        }
        .input-field::placeholder { color: rgba(167,139,250,.4); }
        .input-field:focus { border-color: rgba(239,68,68,.6); box-shadow: 0 0 0 3px rgba(239,68,68,.12); }
        .step-card {
            background: rgba(25,12,55,.6); border: 1px solid rgba(139,92,246,.18);
            border-radius: 14px; padding: 16px;
        }
        .step-card:hover { border-color: rgba(239,68,68,.35); transition: border-color .2s; }
        .output-area {
            background: rgba(10,5,30,.80); border: 1px solid rgba(239,68,68,.25);
            border-radius: 10px; font-family: 'JetBrains Mono', monospace;
            font-size: .76rem; line-height: 1.7; padding: 1rem;
            white-space: pre-wrap; word-break: break-all;
            max-height: 180px; overflow-y: auto; color: #fca5a5;
        }
        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: rgba(239,68,68,.35); border-radius: 10px; }
    </style>
</head>

<body class="font-sans text-purple-100 relative">

    <div class="fixed inset-0 pointer-events-none z-0 overflow-hidden">
        <div class="absolute -top-32 -left-32 w-96 h-96 rounded-full" style="background:radial-gradient(circle,rgba(239,68,68,.10) 0%,transparent 70%)"></div>
        <div class="absolute bottom-12 -right-24 w-80 h-80 rounded-full" style="background:radial-gradient(circle,rgba(139,92,246,.10) 0%,transparent 70%)"></div>
    </div>

    <div class="relative z-10 max-w-xl mx-auto px-4 py-12 pb-20">

        <!-- HEADER -->
        <div class="text-center mb-10 animate-fadeUp">
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-xs font-semibold tracking-widest uppercase mb-5"
                style="background:rgba(239,68,68,.18);border:1px solid rgba(239,68,68,.5);color:#fca5a5">
                <i class="fa-solid fa-bug"></i> SQL Injection Lab &mdash; Langkah 2 &amp; 3
            </div>
            <h1 class="text-3xl md:text-4xl font-bold tracking-tight leading-tight mb-3">
                Form Login <span class="text-red-400">Rentan</span>
            </h1>
            <p class="text-sm text-purple-400 max-w-sm mx-auto leading-relaxed">
                Demonstrasi SQL Injection &mdash; variabel
                <code class="text-red-300 bg-red-900/30 px-1 rounded">$_POST</code>
                digabung langsung ke dalam query SQL tanpa sanitasi
            </p>
        </div>

        <!-- MAIN CARD -->
        <div class="glass rounded-2xl p-8 mb-5 animate-fadeUp">
            <h2 class="text-base font-bold text-purple-100 mb-5 flex items-center gap-3">
                <span class="block w-1 h-5 rounded-full" style="background:linear-gradient(180deg,#ef4444,#f87171)"></span>
                <i class="fa-solid fa-triangle-exclamation text-red-400 text-sm"></i>
                Bukti Kerentanan &mdash; Skenario Hack
            </h2>

            <?php if ($pesan): ?>
            <div class="flex items-start gap-3 px-4 py-3 rounded-xl text-sm font-semibold mb-5 animate-fadeUp"
                style="<?= $pesan_type === 'success'
                    ? 'background:rgba(74,222,128,.12);border:1px solid rgba(74,222,128,.35);color:#86efac'
                    : 'background:rgba(239,68,68,.12);border:1px solid rgba(239,68,68,.35);color:#fca5a5' ?>">
                <i class="fa-solid <?= $pesan_type === 'success' ? 'fa-circle-check' : 'fa-circle-xmark' ?> text-lg mt-0.5 shrink-0"></i>
                <div>
                    <span><?= $pesan ?></span>
                    <?php if ($timestamp): ?>
                    <p class="text-xs mt-1 opacity-70 font-normal font-mono"><?= $timestamp ?></p>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="mb-4">
                    <label class="block text-xs font-medium text-purple-400 mb-2 tracking-wide">
                        <i class="fa-solid fa-user mr-1 opacity-70"></i> Username
                    </label>
                    <input type="text" name="username" class="input-field"
                        placeholder="Coba payload: ' OR '1'='1"
                        value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
                </div>
                <div class="mb-6">
                    <label class="block text-xs font-medium text-purple-400 mb-2 tracking-wide">
                        <i class="fa-solid fa-key mr-1 opacity-70"></i> Password
                    </label>
                    <input type="text" name="password" class="input-field"
                        placeholder="Isi bebas saat menyerang..."
                        value="<?= htmlspecialchars($_POST['password'] ?? '') ?>">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <button type="submit"
                        class="flex items-center justify-center gap-2 py-3 rounded-xl font-bold text-sm text-white transition-all duration-200 hover:-translate-y-0.5"
                        style="background:linear-gradient(135deg,#b91c1c,#ef4444);box-shadow:0 4px 18px rgba(239,68,68,.4);">
                        <i class="fa-solid fa-right-to-bracket"></i> Masuk
                    </button>
                    <a href="?" class="flex items-center justify-center gap-2 py-3 rounded-xl font-semibold text-sm text-violet-300 transition-all duration-200 hover:-translate-y-0.5 hover:text-violet-200"
                        style="background:rgba(139,92,246,.12);border:1px solid rgba(139,92,246,.35);">
                        <i class="fa-solid fa-rotate-left"></i> Reset
                    </a>
                </div>
            </form>

            <!-- Payload hint -->
            <div class="mt-5 rounded-xl p-4 text-xs leading-6"
                style="background:rgba(25,12,55,.6);border:1px solid rgba(239,68,68,.25);border-left:3px solid #ef4444">
                <p class="font-semibold mb-2" style="color:#fca5a5"><i class="fa-solid fa-flask-vial mr-1.5"></i>Payload Serangan SQLi</p>
                <p class="text-purple-400 mb-1">Isi <strong class="text-purple-300">Username</strong> dengan:</p>
                <code class="block rounded-lg px-3 py-2 font-mono mb-2" style="background:rgba(153,27,27,.3);border:1px solid rgba(239,68,68,.3);color:#fca5a5">' OR '1'='1</code>
                <p class="text-purple-400">Isi <strong class="text-purple-300">Password</strong> dengan apa saja (misal: <code class="font-mono text-purple-300">hahaha</code>), lalu klik <strong class="text-purple-300">Masuk</strong>.</p>
            </div>

            <!-- Debug query — ditampilkan setelah submit -->
            <?php if ($query_debug): ?>
            <div class="mt-4 animate-fadeUp">
                <p class="text-xs font-semibold text-purple-400 uppercase tracking-widest mb-2">
                    <i class="fa-solid fa-terminal mr-1 text-red-400"></i> Query yang Dieksekusi oleh MySQL (Bukti Debug):
                </p>
                <div class="output-area"><?= htmlspecialchars($query_debug) ?></div>
                <p class="text-xs text-purple-600 mt-1.5 font-mono">
                    <i class="fa-solid fa-clock mr-1"></i><?= $timestamp ?>
                </p>
            </div>
            <?php endif; ?>
        </div>

        <!-- ANALISIS CELAH -->
        <div class="glass rounded-2xl p-7 mb-5 animate-fadeUp" style="animation-delay:.08s">
            <h2 class="text-base font-bold text-purple-100 mb-4 flex items-center gap-3">
                <span class="block w-1 h-5 rounded-full" style="background:linear-gradient(180deg,#ef4444,#f87171)"></span>
                <i class="fa-solid fa-magnifying-glass-chart text-red-400 text-sm"></i>
                Analisis Celah Keamanan
            </h2>
            <div class="mb-4 step-card">
                <p class="text-xs font-bold mb-2" style="color:#fca5a5"><i class="fa-solid fa-code mr-1.5"></i>Kode Rentan &amp; Cara Kerjanya</p>
                <div class="rounded-lg p-3 font-mono text-xs leading-6 overflow-x-auto"
                    style="background:rgba(10,5,30,.70);border:1px solid rgba(239,68,68,.20)">
<pre class="text-purple-200 whitespace-pre"><span class="text-purple-500">// ⚠️ $_POST digabung langsung ke SQL — TIDAK AMAN!</span>
<span class="text-fuchsia-400">$sql</span> = <span class="text-amber-300">"SELECT * FROM users
        WHERE username = '<span class="text-red-400">$user_input</span>'
          AND password  = '<span class="text-red-400">$pass_input</span>'"</span>;

<span class="text-purple-500">// Setelah payload ' OR '1'='1 disisipkan, SQL menjadi:</span>
<span class="text-red-400">SELECT * FROM users
  WHERE username = '' OR '1'='1'
    AND password  = 'hahaha'</span>
<span class="text-green-500">-- '1'='1' selalu TRUE → semua baris dikembalikan!</span></pre>
                </div>
            </div>
            <div class="grid grid-cols-3 gap-3">
                <?php foreach ([
                    ['fa-user-shield','#ef4444','Bypass Auth',     'Login tanpa password yang benar'],
                    ['fa-database',   '#f59e0b','Data Exposed',    'Seluruh tabel users terekspos'],
                    ['fa-skull-crossbones','#8b5cf6','Priv. Escalation','Langsung dapat role admin'],
                ] as [$ic,$cl,$lb,$ds]): ?>
                <div class="step-card text-center">
                    <div class="w-9 h-9 rounded-full flex items-center justify-center mx-auto mb-2"
                        style="background:<?=$cl?>22;border:1px solid <?=$cl?>55">
                        <i class="fa-solid <?=$ic?> text-sm" style="color:<?=$cl?>"></i>
                    </div>
                    <p class="text-xs font-bold text-purple-100 mb-1"><?=$lb?></p>
                    <p class="text-xs text-purple-500 leading-tight"><?=$ds?></p>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- NAVIGASI -->
        <div class="glass rounded-2xl p-5 animate-fadeUp flex items-center justify-between gap-4" style="animation-delay:.12s">
            <div>
                <p class="text-xs text-purple-400 mb-1"><i class="fa-solid fa-circle-info mr-1"></i>Setelah screenshot, lanjut ke patching:</p>
                <p class="text-xs font-semibold text-violet-300">Buka <code class="font-mono">login_secure.php</code> untuk versi aman</p>
            </div>
            <div class="flex gap-2 shrink-0">
                <a href="index.php"
                    class="flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold text-violet-400 transition-all hover:-translate-y-0.5"
                    style="background:rgba(139,92,246,.10);border:1px solid rgba(139,92,246,.3)">
                    <i class="fa-solid fa-house"></i>
                </a>
                <a href="login_secure.php"
                    class="flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold text-violet-300 transition-all hover:-translate-y-0.5"
                    style="background:rgba(139,92,246,.15);border:1px solid rgba(139,92,246,.4)">
                    <i class="fa-solid fa-shield-halved"></i> Versi Aman
                </a>
            </div>
        </div>

    </div>

    <!-- FOOTER -->
    <footer class="relative z-10 mt-4 pb-10">
        <div class="max-w-xl mx-auto px-4">
            <div class="glass rounded-2xl px-8 py-6 flex flex-col sm:flex-row items-center justify-between gap-4"
                style="background:rgba(30,15,65,.70);border:1px solid rgba(150,110,220,.20);">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-full flex items-center justify-center shrink-0"
                        style="background:linear-gradient(135deg,#b91c1c,#ef4444);box-shadow:0 0 18px rgba(239,68,68,.35);">
                        <i class="fa-solid fa-user-graduate text-white text-lg"></i>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-purple-100 tracking-wide">Sultan Nur Riduan &mdash; 231220005</p>
                        <p class="text-xs text-purple-400 mt-0.5"><i class="fa-solid fa-book-open mr-1 opacity-70"></i>Tugas 9 — SQL Injection Lab</p>
                        <p class="text-xs text-purple-400 mt-0.5"><i class="fa-solid fa-code mr-1 opacity-70"></i>PHP MySQLi + Laragon</p>
                    </div>
                </div>
                <div class="hidden sm:block h-10 w-px" style="background:rgba(150,110,220,.25)"></div>
                <div class="text-center sm:text-right">
                    <p class="text-xs font-semibold mb-1" style="color:#fca5a5">
                        <i class="fa-solid fa-triangle-exclamation mr-1"></i>File 1 of 3 — Vulnerable
                    </p>
                    <p class="text-xs text-purple-500">Hanya untuk keperluan edukasi lab</p>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>