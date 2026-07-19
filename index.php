<?php
require_once __DIR__ . '/functions.php';

$errors          = [];
$hasil           = null;
$investasiAwal   = $_POST['investasi_awal'] ?? '';
$tingkatDiskonto = $_POST['tingkat_diskonto'] ?? '';
$jumlahTahun     = (int) ($_POST['jumlah_tahun'] ?? $_GET['jumlah_tahun'] ?? 3);
$jumlahTahun     = max(1, min(20, $jumlahTahun)); // batasi 1-20 tahun
$cashFlows       = $_POST['cash_flow'] ?? [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['hitung'])) {
    if ($investasiAwal === '' || !is_numeric($investasiAwal)) {
        $errors[] = 'Investasi awal harus berupa angka.';
    }

    if ($tingkatDiskonto === '' || !is_numeric($tingkatDiskonto)) {
        $errors[] = 'Tingkat return harus berupa angka.';
    }

    foreach ($cashFlows as $cf) {
        if (!is_numeric($cf)) {
            $errors[] = 'Semua cash flow harus berupa angka.';
            break;
        }
    }

    if (empty($errors)) {
        $hasil = hitungNPV(
            (float) $investasiAwal,
            (float) $tingkatDiskonto / 100,
            array_map('floatval', $cashFlows)
        );
    }
}

// === GANTI BACKGROUND DI SINI ===
// type: 'image' atau 'video' — cukup ganti baris ini untuk pindah mode
$background = [
    'type'  => 'image',
    'image' => './gambar/main-img.jpg',
    'video' => './gambar/main-video.mp4',
];

require __DIR__ . '/header.php';
?>
<style>
  :root {
    --ink: #0b0d0a;
    --paper: #f6f3ec;
    --paper-dim: #c9c4b6;
    --gold: #e8a93b;
    --gold-deep: #c98a26;
    --gold-soft: #f3cd82;
    --mint: #3ed9a6;
    --rose: #f2748a;
    --glass-1: rgba(255,255,255,0.09);
    --glass-2: rgba(255,255,255,0.04);
    --glass-border: rgba(255,255,255,0.16);
  }
  .npv-page * { box-sizing: border-box; }
  .npv-page {
    font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
    color: var(--paper);
    min-height: 100vh;
  }
  .bg-layer { position: fixed; inset: 0; z-index: -2; overflow: hidden; background: var(--ink); }
  .bg-media { width: 100%; height: 100%; object-fit: cover; display: block; }
  .bg-scrim {
    position: fixed; inset: 0; z-index: -1;
    background: radial-gradient(circle at 50% 28%, rgba(0,0,0,0.15), rgba(0,0,0,0.6) 82%);
  }
  .npv-wrap { max-width: 640px; margin: 0 auto; padding: 4.5rem 1.5rem 5rem; }
  .npv-intro { text-align: center; margin-bottom: 2.25rem; }
  .npv-eyebrow {
    font-size: 12.5px; letter-spacing: 0.08em; text-transform: uppercase;
    color: var(--gold-soft); font-weight: 600;
  }
  .npv-intro h2 {
    font-family: 'Plus Jakarta Sans', serif; font-size: clamp(2.1rem, 5vw, 2.9rem);
    font-weight: 500; margin: 0.5rem 0 0.75rem; color: var(--paper);
  }
  .npv-intro p { color: var(--paper-dim); font-size: 14.5px; max-width: 420px; margin: 0 auto; line-height: 1.6; }
  .glass-card {
    position: relative; overflow: hidden;
    background: linear-gradient(165deg, var(--glass-1), var(--glass-2));
    backdrop-filter: blur(30px) saturate(150%);
    -webkit-backdrop-filter: blur(30px) saturate(150%);
    border: 1px solid var(--glass-border);
    border-radius: 28px; padding: 2.25rem;
    box-shadow: 0 24px 70px rgba(0,0,0,0.4), inset 0 1px 0 rgba(255,255,255,0.3), inset 0 -1px 0 rgba(255,255,255,0.04);
  }
  .glass-card::before {
    content: ''; position: absolute; top: -60%; left: -25%; width: 60%; height: 220%;
    background: linear-gradient(120deg, rgba(255,255,255,0.12), transparent 60%);
    transform: rotate(15deg); pointer-events: none;
  }
  .step-label {
    display: flex; align-items: center; gap: 10px; font-size: 12.5px; font-weight: 600;
    color: var(--paper-dim); text-transform: uppercase; letter-spacing: 0.04em; margin-bottom: 1rem;
  }
  .step-num {
    width: 22px; height: 22px; border-radius: 50%; background: rgba(232,169,59,0.15);
    color: var(--gold-soft); display: flex; align-items: center; justify-content: center;
    font-size: 11px; font-family: 'Fraunces', serif; flex-shrink: 0;
  }
  .years-form { display: flex; gap: 12px; align-items: flex-end; margin: 0; }
  .years-form .field { flex: 1; margin-bottom: 0; }
  .field { margin-bottom: 1.1rem; }
  .field label { display: block; font-size: 13px; color: var(--paper-dim); margin-bottom: 6px; }
  .field input {
    width: 100%; background: rgba(0,0,0,0.22); border: 1px solid var(--glass-border);
    border-radius: 14px; padding: 0.75rem 1rem; color: var(--paper);
    font-family: inherit; font-size: 15px;
    transition: border-color 0.2s ease, box-shadow 0.2s ease;
  }
  .field input::placeholder { color: rgba(246,243,236,0.3); }
  .field input:focus {
    outline: none; border-color: var(--gold); box-shadow: 0 0 0 3px rgba(232,169,59,0.22);
  }
  .divider { height: 1px; background: linear-gradient(90deg, transparent, var(--glass-border), transparent); margin: 1.75rem 0; }
  .cf-row { display: flex; align-items: center; gap: 10px; margin-bottom: 8px; }
  .cf-row .cf-year { width: 62px; flex-shrink: 0; font-size: 13px; color: var(--paper-dim); }
  .cf-row input { flex: 1; }
  .btn-primary, .btn-secondary {
    border: none; border-radius: 14px; font-family: inherit; font-weight: 700;
    font-size: 15px; cursor: pointer; transition: transform 0.15s ease, box-shadow 0.15s ease;
  }
  .btn-primary {
    width: 100%; background: linear-gradient(135deg, var(--gold-soft), var(--gold-deep));
    color: #241604; padding: 0.9rem 1.5rem; box-shadow: 0 10px 26px rgba(232,169,59,0.3);
    margin-top: 0.5rem;
  }
  .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 14px 32px rgba(232,169,59,0.4); }
  .btn-secondary {
    background: rgba(255,255,255,0.08); color: var(--paper);
    border: 1px solid var(--glass-border); padding: 0.75rem 1.3rem; white-space: nowrap;
  }
  .btn-secondary:hover { background: rgba(255,255,255,0.14); }
  .btn-primary:focus-visible, .btn-secondary:focus-visible { outline: 2px solid var(--gold); outline-offset: 2px; }
  .error-box {
    background: rgba(242,116,138,0.1); border: 1px solid rgba(242,116,138,0.35);
    border-radius: 16px; padding: 1rem 1.25rem; margin-bottom: 1.5rem;
    color: #ffd9df; font-size: 14px;
  }
  .error-box ul { margin: 0; padding-left: 1.1rem; }
  .error-box li { margin-bottom: 4px; }
  .result {
    margin-top: 1.75rem; border-radius: 20px; padding: 1.5rem 1.75rem;
    background: rgba(255,255,255,0.05); border: 1px solid rgba(62,217,166,0.35);
    box-shadow: 0 0 50px rgba(62,217,166,0.1); animation: riseIn 0.5s ease;
  }
  .result.negative { border-color: rgba(242,116,138,0.35); box-shadow: 0 0 50px rgba(242,116,138,0.1); }
  .result-label { font-size: 12px; text-transform: uppercase; letter-spacing: 0.06em; color: var(--paper-dim); }
  .result-value-row { display: flex; align-items: baseline; justify-content: space-between; gap: 12px; margin: 6px 0 8px; }
  .result-value { font-family: 'Fraunces', serif; font-size: clamp(1.5rem, 4vw, 2rem); font-weight: 500; color: var(--mint); }
  .result.negative .result-value { color: var(--rose); }
  .result-verdict { font-size: 13.5px; color: var(--paper-dim); line-height: 1.5; margin: 0; }
  .spark polyline { stroke-dasharray: 220; stroke-dashoffset: 220; animation: draw 0.9s ease forwards 0.15s; }
  @keyframes draw { to { stroke-dashoffset: 0; } }
  @keyframes riseIn { from { opacity: 0; transform: translateY(8px); } to { opacity: 1; transform: translateY(0); } }
  @media (max-width: 480px) {
    .glass-card { padding: 1.5rem; border-radius: 22px; }
    .npv-wrap { padding: 3.5rem 1rem 4rem; }
    .years-form { flex-direction: column; align-items: stretch; }
  }
  @media (prefers-reduced-motion: reduce) {
    .result, .spark polyline { animation: none; }
    .spark polyline { stroke-dashoffset: 0; }
  }
</style>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,450;9..144,560&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

<div class="npv-page">
  <div class="bg-layer">
    <?php if ($background['type'] === 'video'): ?>
      <video class="bg-media" autoplay muted loop playsinline>
        <source src="<?= htmlspecialchars($background['video']) ?>" type="video/mp4">
      </video>
    <?php else: ?>
      <img class="bg-media" src="<?= htmlspecialchars($background['image']) ?>" alt="">
    <?php endif; ?>
  </div>
  <div class="bg-scrim"></div>

  <div class="npv-wrap">
    <div class="npv-intro">
      <span class="npv-eyebrow">Kelayakan investasi</span>
      <h2>Hitung Net Present Value</h2>
      <p>Masukkan investasi awal, tingkat diskonto, dan proyeksi arus kas untuk melihat apakah rencana ini layak dijalankan.</p>
    </div>

    <div class="glass-card">
      <div class="step-label"><span class="step-num">01</span>Durasi proyeksi</div>
      <form method="get" class="years-form">
        <div class="field">
          <label for="jumlah_tahun">Jumlah tahun</label>
          <input type="number" id="jumlah_tahun" name="jumlah_tahun" min="1" max="20"
                 value="<?= htmlspecialchars($jumlahTahun) ?>">
        </div>
        <button type="submit" class="btn-secondary">Atur tahun</button>
      </form>

      <div class="divider"></div>

      <?php if (!empty($errors)): ?>
        <div class="error-box">
          <ul>
            <?php foreach ($errors as $error): ?>
              <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
          </ul>
        </div>
      <?php endif; ?>

      <div class="step-label"><span class="step-num">02</span>Data investasi</div>
      <form method="post">
        <input type="hidden" name="jumlah_tahun" value="<?= htmlspecialchars($jumlahTahun) ?>">

        <div class="field">
          <label for="investasi_awal">Investasi awal (Rp)</label>
          <input type="number" step="any" id="investasi_awal" name="investasi_awal"
                 value="<?= htmlspecialchars($investasiAwal) ?>" placeholder="50000000">
        </div>

        <div class="field">
          <label for="tingkat_diskonto">Tingkat diskonto (%)</label>
          <input type="number" step="any" id="tingkat_diskonto" name="tingkat_diskonto"
                 value="<?= htmlspecialchars($tingkatDiskonto) ?>" placeholder="12">
        </div>

        <div class="field">
          <label>Cash flow per tahun (Rp)</label>
          <?php for ($i = 0; $i < $jumlahTahun; $i++): ?>
            <div class="cf-row">
              <span class="cf-year">Tahun <?= $i + 1 ?></span>
              <input type="number" step="any" name="cash_flow[]"
                     value="<?= htmlspecialchars($cashFlows[$i] ?? '') ?>" placeholder="0">
            </div>
          <?php endfor; ?>
        </div>

        <button type="submit" name="hitung" value="1" class="btn-primary">Hitung NPV</button>
      </form>

      <?php if ($hasil !== null): ?>
        <div class="result <?= $hasil >= 0 ? '' : 'negative' ?>">
          <div class="result-label">Hasil NPV</div>
          <div class="result-value-row">
            <span class="result-value">Rp <?= number_format($hasil, 2, ',', '.') ?></span>
            <svg class="spark" viewBox="0 0 100 32" width="88" height="28">
              <?php if ($hasil >= 0): ?>
                <polyline points="2,28 18,20 34,24 50,12 66,16 82,4 98,8" fill="none" stroke="var(--mint)" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
              <?php else: ?>
                <polyline points="2,4 18,10 34,8 50,18 66,14 82,26 98,22" fill="none" stroke="var(--rose)" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
              <?php endif; ?>
            </svg>
          </div>
          <p class="result-verdict">
            <?= $hasil >= 0
                ? 'Investasi layak dijalankan — total return arus kas melebihi investasi awal.'
                : 'Investasi kurang layak — returnnya masih dibawah investasi awal.' ?>
          </p>
        </div>
      <?php endif; ?>
    </div>
  </div>
</div>

<?php require __DIR__ . '/footer.php'; ?>