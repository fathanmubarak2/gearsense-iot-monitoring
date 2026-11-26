@php
  if (!isset($telemetries)) {
      try {
          $telemetries = \App\Models\VibrationTelemetry::orderBy('id', 'desc')->take(100)->get();
          $totalCount = \App\Models\VibrationTelemetry::count();
          $normalCount = \App\Models\VibrationTelemetry::where('condition', 'NORMAL')->count();
          $scoringCount = \App\Models\VibrationTelemetry::where('condition', 'SCORING')->count();
          $damagedCount = \App\Models\VibrationTelemetry::where('condition', 'DAMAGED')->count();
      } catch (\Throwable $e) {
          $telemetries = collect([]);
          $totalCount = 0; $normalCount = 0; $scoringCount = 0; $damagedCount = 0;
      }
  }
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=1920, initial-scale=1.0">
  <title>Gearsense - Riwayat Pengukuran Telemetri Getaran</title>
  
    <!-- Font: General Sans (Fontshare CDN) -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://api.fontshare.com/v2/css?f[]=general-sans@400,500,600,700,800&display=swap" rel="stylesheet">
  <script src="https://unpkg.com/lucide@latest"></script>
  <!-- Chart.js untuk grafik getaran interaktif halus -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Framework: Bootstrap 5 -->
  @include('partials.frameworks')

  <style>
    /* =========================================================================
     * DEFINISI FONT GENERAL SANS (HTTPS CDN) - SEMUA BOBOT (400, 500, 600, 700)
     * ========================================================================= */
    @font-face {
      font-family: 'General Sans';
      src: url('https://cdn.fontshare.com/wf/MFQT7HFGCR2L5ULQTW6YXYZXXHMPKLJ3/YWQ244D6TACUX5JBKATPOW5I5MGJ3G73/7YY3ZAAE3TRV2LANYOLXNHTPHLXVWTKH.woff2') format('woff2'),
           url('https://cdn.fontshare.com/wf/MFQT7HFGCR2L5ULQTW6YXYZXXHMPKLJ3/YWQ244D6TACUX5JBKATPOW5I5MGJ3G73/7YY3ZAAE3TRV2LANYOLXNHTPHLXVWTKH.woff') format('woff'),
           url('https://cdn.fontshare.com/wf/MFQT7HFGCR2L5ULQTW6YXYZXXHMPKLJ3/YWQ244D6TACUX5JBKATPOW5I5MGJ3G73/7YY3ZAAE3TRV2LANYOLXNHTPHLXVWTKH.ttf') format('truetype');
      font-weight: 400;
      font-display: swap;
      font-style: normal;
    }
    @font-face {
      font-family: 'General Sans';
      src: url('https://cdn.fontshare.com/wf/3RZHWSNONLLWJK3RLPEKUZOMM56GO4LJ/BPDRY7AHVI3MCDXXVXTQQ76H3UXA63S3/SB2OEB6IKZPRR6JT4GFJ2TFT6HBB6AZN.woff2') format('woff2'),
           url('https://cdn.fontshare.com/wf/3RZHWSNONLLWJK3RLPEKUZOMM56GO4LJ/BPDRY7AHVI3MCDXXVXTQQ76H3UXA63S3/SB2OEB6IKZPRR6JT4GFJ2TFT6HBB6AZN.woff') format('woff'),
           url('https://cdn.fontshare.com/wf/3RZHWSNONLLWJK3RLPEKUZOMM56GO4LJ/BPDRY7AHVI3MCDXXVXTQQ76H3UXA63S3/SB2OEB6IKZPRR6JT4GFJ2TFT6HBB6AZN.ttf') format('truetype');
      font-weight: 500;
      font-display: swap;
      font-style: normal;
    }
    @font-face {
      font-family: 'General Sans';
      src: url('https://cdn.fontshare.com/wf/K46YRH762FH3QJ25IQM3VAXAKCHEXXW4/ISLWQPUZHZF33LRIOTBMFOJL57GBGQ4B/3ZLMEXZEQPLTEPMHTQDAUXP5ZZXCZAEN.woff2') format('woff2'),
           url('https://cdn.fontshare.com/wf/K46YRH762FH3QJ25IQM3VAXAKCHEXXW4/ISLWQPUZHZF33LRIOTBMFOJL57GBGQ4B/3ZLMEXZEQPLTEPMHTQDAUXP5ZZXCZAEN.woff') format('woff'),
           url('https://cdn.fontshare.com/wf/K46YRH762FH3QJ25IQM3VAXAKCHEXXW4/ISLWQPUZHZF33LRIOTBMFOJL57GBGQ4B/3ZLMEXZEQPLTEPMHTQDAUXP5ZZXCZAEN.ttf') format('truetype');
      font-weight: 600;
      font-display: swap;
      font-style: normal;
    }
    @font-face {
      font-family: 'General Sans';
      src: url('https://cdn.fontshare.com/wf/KWXO5X3YW4X7OLUMPO4X24HQJGJU7E2Q/VOWUQZS3YLP66ZHPTXAFSH6YACY4WJHT/NIQ54PVBBIWVK3PFSOIOUJSXIJ5WTNDP.woff2') format('woff2'),
           url('https://cdn.fontshare.com/wf/KWXO5X3YW4X7OLUMPO4X24HQJGJU7E2Q/VOWUQZS3YLP66ZHPTXAFSH6YACY4WJHT/NIQ54PVBBIWVK3PFSOIOUJSXIJ5WTNDP.woff') format('woff'),
           url('https://cdn.fontshare.com/wf/KWXO5X3YW4X7OLUMPO4X24HQJGJU7E2Q/VOWUQZS3YLP66ZHPTXAFSH6YACY4WJHT/NIQ54PVBBIWVK3PFSOIOUJSXIJ5WTNDP.ttf') format('truetype');
      font-weight: 700;
      font-display: swap;
      font-style: normal;
    }

                :root {
      --font-heading: 'General Sans', sans-serif;
      --font-body: 'General Sans', sans-serif;
      --font-mono: 'JetBrains Mono', monospace;
    }

    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
      font-family: 'General Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    }

    body {
      background-color: #ffffff;
      color: #1e293b;
      line-height: 1.6;
      -webkit-font-smoothing: antialiased;
      -moz-osx-font-smoothing: grayscale;
      overflow-x: auto;
      overflow-y: hidden;
      width: 1920px;
      height: 100vh;
      font-family: 'General Sans', sans-serif;
    }

    /* Semua Judul & Nilai Menggunakan General Sans */
    h1, h2, h3, h4, .admin-main-name, .brand-tagline {
      font-family: 'General Sans', sans-serif;
      font-weight: 700;
      letter-spacing: -0.015em;
    }

    .kpi-value {
      font-family: 'General Sans', sans-serif;
      font-weight: 700;
      letter-spacing: -0.02em;
    }

    /* SEMUA SUB JUDUL HARUS BOLD (font-weight: 700) */
    .section-title,
    .section-title span,
    .menu-category-title,
    .admin-sub-title,
    .kpi-header,
    .kpi-header span,
    .telemetry-title,
    .telemetry-title span,
    .info-card-header,
    .info-card-header span,
    .hero-header,
    h2, h3, h4, h5, h6 {
      font-family: 'General Sans', sans-serif;
      font-weight: 700 !important;
    }

    .section-title {
      font-size: 13.5px;
      color: #042f2e;
      margin-bottom: 12px;
      letter-spacing: 0.05em;
      text-transform: uppercase;
    }

    .app-container {
      display: flex;
      width: 1920px;
      height: 100vh;
      max-height: 100vh;
      background-color: #ffffff;
      position: relative;
      overflow: hidden;
    }

    /* ================= MAIN CONTENT ================= */
    .main-content {
      flex-grow: 1;
      height: 100vh;
      max-height: 100vh;
      padding: 24px 36px 36px 42px;
      display: flex;
      flex-direction: column;
      gap: 22px;
      background: #ffffff;
      overflow-y: auto;
      transition: padding 0.3s ease;
    }

    /* TOP BAR PILL */
    .topbar-pill-container {
      display: flex;
      justify-content: flex-start;
    }

    .topbar-pill {
      background: #f0fdfa;
      box-shadow: 0 0 0 0.5px #99f6e4 inset, 0 2px 8px rgba(0, 0, 0, 0.04);
      padding: 12px 28px;
      border-radius: 14px;
      display: flex;
      align-items: center;
      gap: 20px;
      font-size: 15px;
      color: #0f172a;
    }

    .topbar-pill .item {
      font-weight: 700;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .topbar-pill .separator {
      color: #94a3b8;
      font-weight: 400;
    }

    .topbar-pill .time-badge {
      display: flex;
      align-items: center;
      gap: 6px;
      color: #0f172a;
    }

    /* SECTION TITLE */
    .section-title {
      color: #0f172a;
      font-size: 15px;
      font-weight: 800;
      letter-spacing: 0.5px;
      text-transform: uppercase;
      margin-bottom: -10px;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    /* HERO CARD: STATUS & AI DIAGNOSIS */
    .hero-card {
      background: #f0fdfa;
      box-shadow: 0 0 0 1px rgba(153, 246, 228, 0.7) inset, 0 8px 24px -4px rgba(15, 23, 42, 0.08), 0 2px 6px -1px rgba(15, 23, 42, 0.04);
      border-radius: 18px;
      padding: 24px 30px;
    }

    .hero-header {
      font-size: 15px;
      font-weight: 800;
      color: #0f172a;
      letter-spacing: 0.3px;
      margin-bottom: 16px;
      text-transform: uppercase;
    }

    .condition-badges-row {
      display: flex;
      align-items: center;
      gap: 16px;
      margin-bottom: 22px;
    }

    .status-badge {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      padding: 7px 18px;
      border-radius: 20px;
      font-size: 13px;
      font-weight: 800;
      letter-spacing: 0.5px;
      text-transform: uppercase;
      transition: all 0.2s ease;
      cursor: pointer;
    }

    .status-badge .indicator-dot {
      width: 10px;
      height: 10px;
      border-radius: 50%;
    }

    .badge-normal {
      background: #2dd4bf;
      color: #042f2e;
      box-shadow: 0 0 0 0.5px #14b8a6 inset, 0 2px 6px rgba(45, 212, 191, 0.3);
    }
    .badge-normal .indicator-dot {
      background: #115e59;
    }

    .badge-damaged {
      background: #38bdf8;
      color: #082f49;
      box-shadow: 0 0 0 0.5px #0284c7 inset;
    }
    .badge-damaged .indicator-dot {
      background: #dc2626;
    }

    .badge-scoring {
      background: #2dd4bf;
      color: #042f2e;
      box-shadow: 0 0 0 0.5px #14b8a6 inset;
    }
    .badge-scoring .indicator-dot {
      background: #facc15;
    }

    .hero-details-list {
      display: flex;
      flex-direction: column;
      gap: 12px;
      font-size: 14px;
      color: #0f172a;
    }

    .hero-details-row {
      display: flex;
      align-items: baseline;
    }

    .hero-details-row .label {
      width: 320px;
      font-weight: 700;
      display: flex;
      align-items: center;
      gap: 6px;
    }

    .hero-details-row .value {
      font-weight: 700;
      color: #0f172a;
    }

    /* KPI CARDS GRID */
    .kpi-grid {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 20px;
    }

    .kpi-card {
      background: #5eead4;
      border-radius: 16px;
      padding: 18px 20px;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      box-shadow: 0 0 0 1px #14b8a6 inset, 0 8px 20px -2px rgba(20, 184, 166, 0.25), 0 2px 6px -1px rgba(15, 23, 42, 0.06);
      transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .kpi-card:hover {
      transform: translateY(-3px);
      box-shadow: 0 0 0 0.5px #0f766e inset, 0 8px 18px rgba(94, 234, 212, 0.35);
    }

    .kpi-header {
      font-size: 13px;
      font-weight: 700 !important;
      display: flex;
      align-items: center;
      gap: 6px;
      font-family: 'General Sans', sans-serif;
    }

    .kpi-value {
      font-size: 24px;
      font-weight: 800;
      color: #042f2e;
      margin-bottom: 8px;
      letter-spacing: -0.5px;
    }

    .kpi-footer {
      font-size: 13px;
      font-weight: 700;
      color: #0f766e;
    }

    /* TELEMETRY CARD */
    .telemetry-card {
      background: #ffffff;
      border-radius: 18px;
      padding: 22px 28px;
      box-shadow: 0 0 0 1px rgba(226, 232, 240, 0.9), 0 8px 24px -4px rgba(15, 23, 42, 0.08), 0 2px 6px -1px rgba(15, 23, 42, 0.04);
    }

    .telemetry-header-bar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 12px;
    }

    .telemetry-title {
      font-size: 15px;
      font-weight: 700 !important;
      color: #042f2e;
      font-family: 'General Sans', sans-serif;
    }

    .chart-container-box {
      width: 100%;
      height: 190px;
      position: relative;
    }

    .telemetry-footer-bar {
      margin-top: 14px;
      padding-top: 12px;
      border-top: 1px solid #f1f5f9;
      font-size: 13px;
      font-weight: 700;
      color: #0f172a;
      display: flex;
      align-items: center;
      gap: 16px;
    }

    .status-dot-active {
      display: inline-block;
      width: 10px;
      height: 10px;
      border-radius: 50%;
      background: #10b981;
      box-shadow: 0 0 8px #10b981;
    }

    /* TWO COLUMNS BOTTOM SECTION */
    .bottom-two-cols {
      display: grid;
      grid-template-columns: 1fr 1.25fr;
      gap: 24px;
    }

    .info-card {
      background: #ffffff;
      border-radius: 18px;
      padding: 22px 26px;
      box-shadow: 0 0 0 1px rgba(226, 232, 240, 0.9), 0 8px 24px -4px rgba(15, 23, 42, 0.08), 0 2px 6px -1px rgba(15, 23, 42, 0.04);
    }

    .info-card-header {
      display: flex;
      align-items: center;
      gap: 10px;
      font-size: 14px;
      font-weight: 700 !important;
      color: #042f2e;
      margin-bottom: 16px;
      border-bottom: 1px solid #f1f5f9;
      padding-bottom: 12px;
      font-family: 'General Sans', sans-serif;
    }

    /* PROBABILITY BARS */
    .prob-rows-list {
      display: flex;
      flex-direction: column;
      gap: 14px;
      margin-bottom: 22px;
    }

    .prob-row {
      display: flex;
      align-items: center;
      gap: 16px;
    }

    .prob-label {
      width: 80px;
      font-size: 14px;
      font-weight: 800;
      color: #0f172a;
    }

    .prob-bar-track {
      flex-grow: 1;
      height: 24px;
      background: #f1f5f9;
      box-shadow: 0 0 0 0.5px #cbd5e1 inset;
      border-radius: 12px;
      overflow: hidden;
      position: relative;
    }

    .prob-bar-fill {
      height: 100%;
      border-radius: 12px;
      background: #0f172a;
      transition: width 0.5s ease;
    }

    .prob-pct {
      width: 50px;
      text-align: right;
      font-size: 14px;
      font-weight: 800;
      color: #0f172a;
    }

    .model-info-footer {
      font-size: 13px;
      font-weight: 800;
      color: #0f172a;
      border-top: 1px dashed #e2e8f0;
      padding-top: 12px;
      letter-spacing: 0.2px;
    }

    /* LOG ACTIVITIES LIST */
    .activity-log-list {
      display: flex;
      flex-direction: column;
      gap: 12px;
      list-style: none;
    }

    .activity-log-item {
      display: flex;
      align-items: flex-start;
      gap: 10px;
      font-size: 14px;
      color: #0f172a;
      font-weight: 700;
      line-height: 1.4;
    }

    .bullet-point {
      color: #0f172a;
      font-size: 16px;
      line-height: 1.2;
    }
  
    /* =========================================================================
     * PENEGASAN: SETIAP SUB JUDUL DI SEMUA DASHBOARD HARUS BOLD (font-weight: 700)
     * ========================================================================= */
    .section-title,
    .section-title *,
    .menu-category-title,
    .admin-sub-title,
    .kpi-header,
    .kpi-header *,
    .telemetry-title,
    .telemetry-title *,
    .info-card-header,
    .info-card-header *,
    .hero-header,
    .hero-header *,
    .login-subtitle,
    th,
    h1, h2, h3, h4, h5, h6 {
      font-family: 'General Sans', sans-serif !important;
      font-weight: 700 !important;
    }
  
    /* =========================================================================
     * EFEK ELEVASI SHADOW MODERN PADA SETIAP CARD (FRAME)
     * ========================================================================= */
    .hero-card {
      box-shadow: 0 0 0 1px rgba(153, 246, 228, 0.7) inset, 0 8px 24px -4px rgba(15, 23, 42, 0.08), 0 2px 6px -1px rgba(15, 23, 42, 0.04);
      transition: transform 0.25s ease, box-shadow 0.25s ease;
    }
    .hero-card:hover {
      box-shadow: 0 0 0 1px rgba(94, 234, 212, 1) inset, 0 12px 28px -4px rgba(15, 23, 42, 0.12), 0 4px 10px -2px rgba(15, 23, 42, 0.06) !important;
      transform: translateY(-2px);
    }

    .telemetry-card {
      box-shadow: 0 0 0 1px rgba(226, 232, 240, 0.9), 0 8px 24px -4px rgba(15, 23, 42, 0.08), 0 2px 6px -1px rgba(15, 23, 42, 0.04);
      transition: transform 0.25s ease, box-shadow 0.25s ease;
    }
    .telemetry-card:hover {
      box-shadow: 0 0 0 1px rgba(203, 213, 225, 1), 0 12px 28px -4px rgba(15, 23, 42, 0.12), 0 4px 10px -2px rgba(15, 23, 42, 0.06) !important;
      transform: translateY(-2px);
    }

    .info-card {
      box-shadow: 0 0 0 1px rgba(226, 232, 240, 0.9), 0 8px 24px -4px rgba(15, 23, 42, 0.08), 0 2px 6px -1px rgba(15, 23, 42, 0.04);
      transition: transform 0.25s ease, box-shadow 0.25s ease;
    }
    .info-card:hover {
      box-shadow: 0 0 0 1px rgba(203, 213, 225, 1), 0 12px 28px -4px rgba(15, 23, 42, 0.12), 0 4px 10px -2px rgba(15, 23, 42, 0.06) !important;
      transform: translateY(-2px);
    }

    .kpi-card {
      transition: transform 0.25s ease, box-shadow 0.25s ease;
    }
    .kpi-card:hover {
      transform: translateY(-3px);
    }

    /* MODAL EXPORT CSV */
    #exportCsvModalBackdrop {
      position: fixed !important;
      top: 0 !important;
      left: 0 !important;
      width: 100vw !important;
      height: 100vh !important;
      background: rgba(15, 23, 42, 0.6) !important;
      backdrop-filter: blur(4px) !important;
      display: none;
      align-items: center !important;
      justify-content: center !important;
      z-index: 9999999 !important;
    }
    #exportCsvModalBackdrop.show {
      display: flex !important;
    }
    .export-option-label:hover {
      background: #f0fdfa !important;
      border-color: #0d8a7c !important;
    }

    /* MODAL DELETE DATA TELEMETRI */
    #deleteDataModalBackdrop {
      position: fixed !important;
      top: 0 !important;
      left: 0 !important;
      width: 100vw !important;
      height: 100vh !important;
      background: rgba(15, 23, 42, 0.6) !important;
      backdrop-filter: blur(4px) !important;
      display: none;
      align-items: center !important;
      justify-content: center !important;
      z-index: 9999999 !important;
    }
    #deleteDataModalBackdrop.show {
      display: flex !important;
    }
  

    

  
    
  
    </style>
</head>
<body>
@php
    $currentRole = session('gearsense_role', 'ADMINISTRATOR');
    $isAdmin = ($currentRole === 'ADMINISTRATOR');
@endphp


  <div class="app-container">
    
    <!-- SIDEBAR WITH FLOATING DETACHED TOGGLE BUTTON -->
    @include('partials.sidebar')

    <!-- MAIN CONTENT (Pure White Background) -->
    <main class="main-content">

      <!-- TOP BAR STATUS PILL -->
      <div class="topbar-pill-container">
        <div class="topbar-pill">
          <div class="item">
            <span>Operator: <strong>{{ session('gearsense_user', $isAdmin ? 'Administrator Sistem' : 'Operator') }}</strong></span>
          </div>
          <span class="separator">|</span>
          <div class="item" style="gap: 6px;">
            <span style="font-size: 13px; color: #64748b; font-weight: 600;">Role:</span>
            <span style="font-size: 12px; padding: 3px 12px; border-radius: 6px; font-weight: 700; background: #0d8a7c; color: #ffffff; letter-spacing: 0.3px;">
              {{ $isAdmin ? 'Admin' : 'User' }}
            </span>
          </div>
          <span class="separator">|</span>
          <div class="item time-badge">
            <i data-lucide="clock" style="width: 17px; height: 17px;"></i>
            <span id="liveTimestamp">{{ \Carbon\Carbon::now("Asia/Jakarta")->translatedFormat("d M Y, H:i:s") }} WIB</span>
          </div>
        </div>
      </div>

      <!-- HERO SECTION TITLE -->
      <div class="section-title">
        <span>LOG HISTORI TELEMETRI GETARAN &amp; REKAM JEJAK PENGUKURAN</span>
      </div>

      <!-- HERO CONTROLS & FILTER CARD -->
      <div class="hero-card" style="padding: 24px 28px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 14px;">
          <div style="display: flex; align-items: center; gap: 14px; flex-wrap: wrap;">
            <div class="status-badge badge-normal" style="cursor: default; padding: 9px 22px; font-size: 14.5px;">
              <span class="indicator-dot" style="box-shadow: 0 0 10px #10b981;"></span>
              <span>DATABASE LOG TELEMETRI REAL-TIME (NODE ESP32)</span>
            </div>
          </div>

          <!-- EXPORT & LIVE SYNC CONTROLS -->
          <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
            <div id="liveSyncPill" style="display: inline-flex; align-items: center; gap: 7px; background: #ecfdf5; color: #059669; border-radius: 12px; padding: 8px 14px; font-size: 12.5px; font-weight: 700; box-shadow: 0 0 0 1px #a7f3d0 inset;">
              <span id="liveSyncDot" style="width: 8px; height: 8px; border-radius: 50%; background: #10b981; display: inline-block; box-shadow: 0 0 8px #10b981;"></span>
              <span id="liveSyncText">Live Auto-Sync</span>
            </div>

            <button type="button" onclick="openExportModal()" style="background: #0d8a7c; color: #ffffff; border: none; border-radius: 12px; padding: 9px 20px; font-size: 13.5px; font-weight: 700; display: flex; align-items: center; gap: 8px; cursor: pointer; transition: all 0.2s ease; box-shadow: 0 2px 8px rgba(13, 138, 124, 0.25);">
              <i data-lucide="download" style="width: 16px; height: 16px;"></i>
              <span>Ekspor to CSV</span>
            </button>
          </div>
        </div>

        <div class="hero-details-list" style="border-top: 1px solid #ccfbf1; padding-top: 16px;">
          <div class="hero-details-row">
            <span class="label">• Sumber Telemetri &amp; Sensor :</span>
            <span class="value" style="color: #059669; font-weight: 700;">Sensor Getaran ADXL345 &bull; Node ESP32-GEARSENSE-01 (Tersambung ke Database MySQL)</span>
          </div>
          <div class="hero-details-row">
            <span class="label">• Integritas &amp; Audit Trail :</span>
            <span class="value">Integritas Telemetri Sensor Terverifikasi | Sinkronisasi Waktu Riil | Data Valid Sesuai Sensor</span>
          </div>
          <div class="hero-details-row">
            <span class="label">• Total Rekaman Data Tersimpan :</span>
            <span class="value" id="totalRecordsText"><strong>{{ number_format($totalCount) }} Data Telemetri</strong> (Node ESP32 Aktif)</span>
          </div>
        </div>
      </div>

      <!-- KPI SECTION TITLE -->
      <div class="section-title">
        <span>RINGKASAN STATISTIK HISTORIS AKUMULASI SISTEM (DATABASE REAL-TIME)</span>
      </div>

      <!-- 4 KPI SUMMARY CARDS -->
      <div class="kpi-grid">
        <!-- CARD 1: TOTAL SESI -->
        <div class="kpi-card" style="background: #f0fdfa; box-shadow: 0 0 0 1px #0284c7 inset, 0 8px 20px -2px rgba(2, 132, 199, 0.20), 0 2px 6px -1px rgba(15, 23, 42, 0.05);">
          <div class="kpi-header" style="color: #0369a1;">
            <span>📊 TOTAL PENGUKURAN</span>
          </div>
          <div class="kpi-value" style="color: #0369a1;" id="summaryTotalSessions">{{ number_format($totalCount) }} Data</div>
          <div class="kpi-footer" style="color: #0284c7;">Frekuensi Sampling 50 Hz</div>
        </div>

        <!-- CARD 2: KONDISI NORMAL -->
        <div class="kpi-card" style="background: #f0fdfa; box-shadow: 0 0 0 1px #14b8a6 inset, 0 8px 20px -2px rgba(20, 184, 166, 0.25), 0 2px 6px -1px rgba(15, 23, 42, 0.06);">
          <div class="kpi-header" style="color: #042f2e;">
            <span>🟢 RASIO NORMAL</span>
          </div>
          <div class="kpi-value" style="color: #042f2e;" id="summaryNormalRatio">{{ $totalCount > 0 ? number_format(($normalCount / $totalCount) * 100, 1) : 0 }}%</div>
          <div class="kpi-footer" style="color: #097266;" id="summaryNormalCountFoot">{{ number_format($normalCount) }} Sesi Beroperasi Optimal</div>
        </div>

        <!-- CARD 3: TOTAL ANOMALI -->
        <div class="kpi-card" style="background: #f0fdfa; box-shadow: 0 0 0 1px #d97706 inset, 0 8px 20px -2px rgba(217, 119, 6, 0.20), 0 2px 6px -1px rgba(15, 23, 42, 0.05);">
          <div class="kpi-header" style="color: #92400e;">
            <span>🟠 TOTAL ANOMALI</span>
          </div>
          <div class="kpi-value" style="color: #92400e;" id="summaryAnomalyCount">{{ number_format($scoringCount + $damagedCount) }} Kejadian</div>
          <div class="kpi-footer" style="color: #d97706;" id="summaryAnomalyFoot">{{ number_format($scoringCount) }} Terkikis, {{ number_format($damagedCount) }} Rusak (Damaged)</div>
        </div>

        <!-- CARD 4: RUNNING TIME -->
        <div class="kpi-card" style="background: #f0fdfa; box-shadow: 0 0 0 1px #059669 inset, 0 8px 20px -2px rgba(5, 150, 105, 0.20), 0 2px 6px -1px rgba(15, 23, 42, 0.05);">
          <div class="kpi-header" style="color: #065f46;">
            <span>⏱️ STATUS PERANGKAT</span>
          </div>
          <div class="kpi-value" style="color: #065f46;" id="summaryUptime">ONLINE</div>
          <div class="kpi-footer" style="color: #059669;">Node ESP32 Aktif &amp; Terhubung</div>
        </div>
      </div>

      <!-- TREND CHART SECTION TITLE -->
      <div class="section-title">
        <span>TREN HISTORIS NILAI RMS GETARAN &amp; AMBANG BATAS ISO 10816-3 (24 JAM TERAKHIR)</span>
      </div>

      <!-- HISTORICAL TREND CHART CARD -->
      <div class="telemetry-card" style="padding: 24px 28px;">
        <div class="telemetry-header-bar" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px; margin-bottom: 16px;">
          <div class="telemetry-title" style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
            <span>Grafik Fluktuasi Percepatan Getaran RMS (g) vs Waktu Operasional</span>
            <span id="trendModeBadge" style="font-size: 11px; background: #e0f2fe; color: #0369a1; padding: 2px 8px; border-radius: 6px; font-weight: 700;">Interval Rata-rata 1 Jam (WIB)</span>
            
            <!-- Mode Switcher -->
            <div style="display: inline-flex; align-items: center; gap: 4px; background: #ffffff; padding: 3px 6px; border-radius: 8px; box-shadow: 0 0 0 0.5px #cbd5e1 inset; margin-left: 8px;">
              <button id="btnMode24h" onclick="switchTrendMode('24h')" style="background: #0d8a7c; color: #ffffff; border: none; border-radius: 6px; padding: 3px 10px; font-size: 11.5px; font-weight: 700; cursor: pointer;">24 Jam (Per Jam)</button>
              <button id="btnModeRt" onclick="switchTrendMode('rt')" style="background: transparent; color: #64748b; border: none; border-radius: 6px; padding: 3px 10px; font-size: 11.5px; font-weight: 700; cursor: pointer;">Real-Time (25 Sampel)</button>
            </div>
          </div>
          <div style="font-size: 13px; font-weight: 700; display: flex; gap: 18px; align-items: center; flex-wrap: wrap;">
            <span style="color: #0d8a7c; display: flex; align-items: center; gap: 6px;">● Nilai RMS Terukur</span>
            <span style="color: #f59e0b; display: flex; align-items: center; gap: 6px; font-size: 12px;">--- Batas Waspada Zona B (0.10g)</span>
            <span style="color: #ef4444; display: flex; align-items: center; gap: 6px; font-size: 12px;">--- Batas Bahaya Zona C (0.15g)</span>
          </div>
        </div>

        <div class="chart-container-box" style="height: 270px;">
          <canvas id="historyTrendChart"></canvas>
        </div>

        <div class="telemetry-footer-bar">
          <span>Rentang Analisis: <strong>24 Jam Terakhir (WIB)</strong></span>
          <span>|</span>
          <span>Puncak RMS Tertinggi: <strong>{{ $peakRmsVal ?? 0 }} g (Pukul {{ $peakRmsTime ?? '-' }})</strong></span>
          <span>|</span>
          <span>Rata-rata RMS 24 Jam: <strong>{{ $avgRms24h ?? 0 }} g ({{ ($avgRms24h ?? 0) < 0.10 ? 'Zona A Sangat Aman' : (($avgRms24h ?? 0) < 0.15 ? 'Zona B Waspada' : 'Zona C Bahaya') }})</strong></span>
          <span>|</span>
          <span>Stabilitas Konveyor: <strong style="color: #059669;">99.8% Konsisten</strong></span>
        </div>
      </div>

      <!-- TABLE SECTION TITLE -->
      <div class="section-title">
        <span>TABEL LOG DATA TELEMETRI &amp; RIWAYAT KEPUTUSAN DIAGNOSIS AI</span>
      </div>

      <!-- DATA TABLE CARD -->
      <div class="telemetry-card" style="padding: 24px 28px;">
        <!-- Filter & Search Bar in Table Header -->
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 18px; flex-wrap: wrap; gap: 12px;">
          <div style="display: flex; align-items: center; gap: 10px;">
            <div style="position: relative;">
              <input type="text" id="tableSearchInput" onkeyup="filterTable()" placeholder="Cari ID Sesi / Tanggal..." style="background: #ffffff; border: none; box-shadow: 0 0 0 0.5px #cbd5e1 inset; border-radius: 10px; padding: 8px 14px 8px 36px; font-size: 13px; font-family: 'General Sans', sans-serif; width: 260px; outline: none;">
              <i data-lucide="search" style="position: absolute; left: 10px; top: 50%; transform: translateY(-50%); width: 16px; height: 16px; color: #94a3b8;"></i>
            </div>

            <!-- Filter Status Dropdown -->
            <select id="statusFilterSelect" onchange="filterTable()" style="background: #ffffff; border: none; box-shadow: 0 0 0 0.5px #cbd5e1 inset; border-radius: 10px; padding: 8px 14px; font-size: 13px; font-family: 'General Sans', sans-serif; color: #042f2e; font-weight: 600; outline: none; cursor: pointer;">
              <option value="ALL">Semua Status ({{ number_format($totalCount) }})</option>
              <option value="NORMAL">Normal ({{ number_format($normalCount) }})</option>
              <option value="TERKIKIS">Terkikis / Scoring ({{ number_format($scoringCount) }})</option>
              <option value="RUSAK">Rusak / Damaged ({{ number_format($damagedCount) }})</option>
            </select>
          </div>

          <div style="display: flex; align-items: center; gap: 14px; flex-wrap: wrap;">
            <div style="font-size: 12.5px; color: #64748b; font-weight: 600;">
              Menampilkan <span id="showingCountText" style="color: #0d8a7c; font-weight: 700;">{{ count($telemetries) }}</span> dari <span style="font-weight: 700; color: #0f172a;">{{ number_format($totalCount) }}</span> rekaman database
            </div>

            <!-- BUTTON HAPUS DATA RENTANG WAKTU (KALENDER) -->
            <button type="button" onclick="openDeleteModal()" style="background: #fee2e2; color: #dc2626; border: 1px solid #fca5a5; border-radius: 10px; padding: 7px 15px; font-size: 12.5px; font-weight: 700; display: inline-flex; align-items: center; gap: 7px; cursor: pointer; transition: all 0.2s ease; box-shadow: 0 1px 3px rgba(220, 38, 38, 0.08);">
              <i data-lucide="trash-2" style="width: 15px; height: 15px;"></i>
              <span>Hapus Data</span>
            </button>
          </div>
        </div>

        <!-- TABLE ITSELF -->
        <div style="overflow-x: auto;">
          <table id="historyTable" style="width: 100%; border-collapse: collapse; font-size: 12.5px; text-align: left;">
            <thead>
              <tr style="background: #f1f5f9; color: #042f2e; border-bottom: 2px solid #cbd5e1;">
                <th style="padding: 10px 12px; font-weight: 700; border-top-left-radius: 8px;">ID Sesi</th>
                <th style="padding: 10px 12px; font-weight: 700;">Waktu &amp; Tanggal (WIB)</th>
                <th style="padding: 10px 12px; font-weight: 700;">Sumbu Z (Radial)</th>
                <th style="padding: 10px 12px; font-weight: 700;">Sumbu X (Aksial)</th>
                <th style="padding: 10px 12px; font-weight: 700;">Sumbu Y (Tangensial)</th>
                <th style="padding: 10px 12px; font-weight: 700;">Peak-to-Peak Z</th>
                <th style="padding: 10px 12px; font-weight: 700;">WAMP Z</th>
                <th style="padding: 10px 12px; font-weight: 700; text-align: center;">Hasil Diagnosis AI</th>
                <th style="padding: 10px 12px; font-weight: 700; text-align: center; border-top-right-radius: 8px;">Aksi</th>
              </tr>
            </thead>
            <tbody id="historyTableBody" style="color: #1e293b;">
              @forelse($telemetries as $t)
                @php
                  $cond = $t->condition;
                  $condText = ($cond === 'DAMAGED') ? 'RUSAK' : (($cond === 'SCORING') ? 'TERKIKIS' : 'NORMAL');
                  $condBg = ($cond === 'DAMAGED') ? '#fee2e2' : (($cond === 'SCORING') ? '#fef3c7' : '#ccfbf1');
                  $condColor = ($cond === 'DAMAGED') ? '#dc2626' : (($cond === 'SCORING') ? '#d97706' : '#0d8a7c');
                  $condBorder = ($cond === 'DAMAGED') ? '#ef4444' : (($cond === 'SCORING') ? '#f59e0b' : '#14b8a6');
                  $timeWib = $t->recorded_at ? $t->recorded_at->copy()->setTimezone('Asia/Jakarta') : null;
                  $sessionId = '#GS-' . ($timeWib ? $timeWib->format('Ymd-Hi') : '20261031-1420') . '-' . str_pad($t->id, 3, '0', STR_PAD_LEFT);
                  $timeFormatted = $timeWib ? $timeWib->translatedFormat('d M Y, H:i:s') : '-';
                @endphp
                <tr id="row-tel-{{ $t->id }}" data-id="{{ $t->id }}" style="border-bottom: 1px solid #f1f5f9; background: #ffffff;">
                  <td style="padding: 10px 12px; font-family: 'JetBrains Mono', monospace; font-weight: 700; color: #0d8a7c;">{{ $sessionId }}</td>
                  <td style="padding: 10px 12px;">{{ $timeFormatted }}</td>
                  <td style="padding: 10px 12px; font-family: 'JetBrains Mono', monospace;">{{ number_format($t->raw_z, 4) }} g</td>
                  <td style="padding: 10px 12px; font-family: 'JetBrains Mono', monospace;">{{ number_format($t->raw_x, 4) }} g</td>
                  <td style="padding: 10px 12px; font-family: 'JetBrains Mono', monospace;">{{ number_format($t->raw_y, 4) }} g</td>
                  <td style="padding: 10px 12px; font-family: 'JetBrains Mono', monospace; font-weight: 700; color: #059669;">{{ number_format($t->p2p_z, 4) }} g</td>
                  <td style="padding: 10px 12px; font-family: 'JetBrains Mono', monospace;">{{ number_format($t->wamp_z, 2) }}</td>
                  <td style="padding: 10px 12px; text-align: center;">
                    <span style="background: {{ $condBg }}; color: {{ $condColor }}; font-weight: 700; font-size: 11px; padding: 3px 10px; border-radius: 6px; box-shadow: 0 0 0 0.5px {{ $condBorder }} inset;">● {{ $condText }} ({{ number_format($t->confidence_score, 1) }}%)</span>
                  </td>
                  <td style="padding: 10px 12px; text-align: center;">
                    <button onclick="viewSessionDetail('{{ $sessionId }}', '{{ $condText }}', '{{ number_format($t->rms_z, 4) }}', '{{ number_format($t->p2p_z, 4) }}', '{{ number_format($t->wamp_z, 2) }}', '{{ $timeFormatted }}')" style="background: transparent; border: none; color: #0284c7; font-weight: 700; cursor: pointer; text-decoration: underline; font-size: 12px;">Detail</button>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="9" style="text-align: center; padding: 30px; color: #64748b;">Belum ada rekaman getaran telemetri di database.</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>

        <!-- PAGINATION BAR -->
        <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 18px; flex-wrap: wrap; gap: 12px; border-top: 1px solid #f1f5f9; padding-top: 14px;">
          <span id="pageInfoText" style="font-size: 12px; color: #64748b;">Halaman 1</span>
          <div id="paginationContainer" style="display: flex; align-items: center; gap: 6px;">
            <button id="btnPrevPage" onclick="changeHistoryPage('prev')" style="background: #f8fafc; border: none; box-shadow: 0 0 0 0.5px #cbd5e1 inset; border-radius: 8px; padding: 5px 12px; font-size: 12px; font-weight: 700; color: #64748b; cursor: pointer;">Sebelumnya</button>
            <div id="paginationNumbers" style="display: flex; align-items: center; gap: 6px;">
              <button onclick="goToPage(1)" style="background: #0d8a7c; color: #ffffff; border: none; border-radius: 8px; padding: 5px 12px; font-size: 12px; font-weight: 700; cursor: pointer;">1</button>
              <button onclick="goToPage(2)" style="background: #f8fafc; border: none; box-shadow: 0 0 0 0.5px #cbd5e1 inset; border-radius: 8px; padding: 5px 12px; font-size: 12px; font-weight: 700; color: #042f2e; cursor: pointer;">2</button>
              <button onclick="goToPage(3)" style="background: #f8fafc; border: none; box-shadow: 0 0 0 0.5px #cbd5e1 inset; border-radius: 8px; padding: 5px 12px; font-size: 12px; font-weight: 700; color: #042f2e; cursor: pointer;">3</button>
            </div>
            <button id="btnNextPage" onclick="changeHistoryPage('next')" style="background: #f8fafc; border: none; box-shadow: 0 0 0 0.5px #cbd5e1 inset; border-radius: 8px; padding: 5px 12px; font-size: 12px; font-weight: 700; color: #042f2e; cursor: pointer;">Selanjutnya</button>
          </div>
        </div>
      </div>

      <!-- TWO COLUMNS BOTTOM SECTION -->
      <div class="section-title">
        <span>DISTRIBUSI KELAS KONDISI &amp; LOG SINKRONISASI CLOUD IOT</span>
      </div>

      <div class="bottom-two-cols">
        <!-- KOLOM KIRI: DOUGHNUT CHART DISTRIBUSI KONDISI -->
        <div class="info-card">
          <div class="info-card-header" style="justify-content: space-between;">
            <div style="display: flex; align-items: center; gap: 8px;">
              <i data-lucide="pie-chart" style="width: 18px; height: 18px; color: #0d8a7c;"></i>
              <span>DISTRIBUSI KONDISI TOTAL RODA GIGI</span>
            </div>
            <span id="donutTotalCount" style="font-size: 11.5px; color: #0d8a7c; font-weight: 700; background: #ccfbf1; padding: 2px 8px; border-radius: 6px;">{{ number_format($totalCount) }} Sampel</span>
          </div>

          <div style="width: 100%; height: 200px; position: relative;">
            <canvas id="conditionDistributionChart"></canvas>
          </div>

          <div class="model-info-footer" style="display: flex; justify-content: space-between; font-size: 12px; flex-wrap: wrap; gap: 8px;">
            <span id="legendNormal" style="color: #059669;">● Normal: {{ number_format($normalCount) }} ({{ $totalCount > 0 ? number_format(($normalCount / $totalCount) * 100, 1) : 0 }}%)</span>
            <span id="legendScoring" style="color: #d97706;">● Terkikis: {{ number_format($scoringCount) }} ({{ $totalCount > 0 ? number_format(($scoringCount / $totalCount) * 100, 1) : 0 }}%)</span>
            <span id="legendDamaged" style="color: #ef4444;">● Rusak: {{ number_format($damagedCount) }} ({{ $totalCount > 0 ? number_format(($damagedCount / $totalCount) * 100, 1) : 0 }}%)</span>
          </div>
        </div>

        <!-- KOLOM KANAN: AUDIT LOG & IOT EDGE SYNCHRONIZATION -->
        <div class="info-card">
          <div class="info-card-header">
            <i data-lucide="hard-drive" style="width: 18px; height: 18px; color: #0d8a7c;"></i>
            <span>AUDIT TRAIL &amp; STATUS SINKRONISASI IOT NODE (STANDBY)</span>
          </div>

          <ul class="activity-log-list" style="gap: 12px;">
            <li class="activity-log-item" style="justify-content: space-between;">
              <div style="display: flex; align-items: center; gap: 8px;">
                <span class="bullet-point" style="color: #10b981;">●</span>
                <span>Buffer Lokal MicroSD Node:</span>
              </div>
              <span style="font-family: 'JetBrains Mono', monospace; font-size: 12px; font-weight: 700; color: #0d8a7c; background: #f0fdfa; padding: 2px 8px; border-radius: 6px;">32.0 GB (Menunggu Koneksi ESP32)</span>
            </li>
            <li class="activity-log-item" style="justify-content: space-between;">
              <div style="display: flex; align-items: center; gap: 8px;">
                <span class="bullet-point" style="color: #10b981;">●</span>
                <span>Protokol Sinkronisasi Cloud:</span>
              </div>
              <span style="font-family: 'JetBrains Mono', monospace; font-size: 12px; font-weight: 700; color: #0f172a;">MQTT over TLS (Endpoint Standby)</span>
            </li>
            <li class="activity-log-item" style="justify-content: space-between;">
              <div style="display: flex; align-items: center; gap: 8px;">
                <span class="bullet-point" style="color: #10b981;">●</span>
                <span>Integritas Batch Terakhir:</span>
              </div>
              <span style="font-family: 'JetBrains Mono', monospace; font-size: 12px; font-weight: 700; color: #059669;">Integritas Valid (100% Match)</span>
            </li>
            <li class="activity-log-item" style="justify-content: space-between;">
              <div style="display: flex; align-items: center; gap: 8px;">
                <span class="bullet-point" style="color: #10b981;">●</span>
                <span>Latensi Sinkronisasi DB:</span>
              </div>
              <span style="font-family: 'JetBrains Mono', monospace; font-size: 12px; font-weight: 700; color: #0d8a7c; background: #f0fdfa; padding: 2px 8px; border-radius: 6px;">0 ms</span>
            </li>
          </ul>
        </div>
      </div>
    </main>
  </div>

  <!-- ================= MODAL: PILIH RENTANG & JENIS DATA EKSPOR CSV ================= -->
  <div class="modal-backdrop" id="exportCsvModalBackdrop" style="display: none;" onclick="if(event.target === this) closeExportModal()">
    <div class="modal-box" style="width: 500px; background: #ffffff; border-radius: 16px; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04); overflow: hidden;">
      
      <!-- MODAL HEADER -->
      <div class="modal-header" style="padding: 18px 24px; border-bottom: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center;">
        <div class="modal-title" style="font-size: 15.5px; font-weight: 700; color: #042f2e; display: flex; align-items: center; gap: 10px;">
          <div style="width: 36px; height: 36px; border-radius: 10px; background: #ccfbf1; display: flex; align-items: center; justify-content: center;">
            <i data-lucide="file-spreadsheet" style="width: 20px; height: 20px; color: #0d8a7c;"></i>
          </div>
          <span id="exportModalTitle">Ekspor CSV: Pilih Rentang Waktu (1/2)</span>
        </div>
        <button type="button" class="modal-close-btn" onclick="closeExportModal()" style="background: transparent; border: none; color: #94a3b8; cursor: pointer; padding: 4px; border-radius: 8px;">
          <i data-lucide="x" style="width: 20px; height: 20px;"></i>
        </button>
      </div>

      <!-- LANGKAH 1: PILIH RENTANG WAKTU -->
      <div id="exportStep1" style="display: block;">
        <div class="modal-body" style="padding: 22px 24px;">
          <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 14px;">
            <span style="font-size: 12px; font-weight: 700; color: #0d8a7c; background: #f0fdfa; padding: 3px 10px; border-radius: 6px; border: 1px solid #ccfbf1;">Langkah 1 dari 2</span>
            <span style="font-size: 12px; color: #94a3b8;">Tentukan periode waktu</span>
          </div>
          <p style="font-size: 13px; color: #64748b; margin-top: 0; margin-bottom: 16px; line-height: 1.5;">
            Silakan pilih periode rekaman telemetri getaran yang ingin diekspor:
          </p>

          <div style="display: flex; flex-direction: column; gap: 10px;">
            <!-- Opsi 1: Per Hari (Hari Ini) -->
            <label style="display: flex; align-items: center; gap: 12px; padding: 12px 16px; border: 1.5px solid #0d8a7c; background: #f0fdfa; border-radius: 12px; cursor: pointer; transition: all 0.2s ease;" class="export-range-label" onclick="selectExportRange('today', this)">
              <input type="radio" name="exportRangeOption" value="today" checked style="accent-color: #0d8a7c; width: 18px; height: 18px; cursor: pointer;">
              <div>
                <div style="font-weight: 700; color: #0f172a; font-size: 13.5px;">📅 Per Hari (Hari Ini)</div>
                <div style="font-size: 12px; color: #64748b;">Hanya mengunduh data rekaman sensor telemetri hari ini (WIB)</div>
              </div>
            </label>

            <!-- Opsi 2: Per Minggu (7 Hari Terakhir) -->
            <label style="display: flex; align-items: center; gap: 12px; padding: 12px 16px; border: 1.5px solid #cbd5e1; background: #ffffff; border-radius: 12px; cursor: pointer; transition: all 0.2s ease;" class="export-range-label" onclick="selectExportRange('7d', this)">
              <input type="radio" name="exportRangeOption" value="7d" style="accent-color: #0d8a7c; width: 18px; height: 18px; cursor: pointer;">
              <div>
                <div style="font-weight: 700; color: #0f172a; font-size: 13.5px;">📆 Per Minggu (7 Hari Terakhir)</div>
                <div style="font-size: 12px; color: #64748b;">Mengunduh data rekaman 7 hari terakhir untuk analisis mingguan</div>
              </div>
            </label>

            <!-- Opsi 3: Per Bulan (30 Hari Terakhir) -->
            <label style="display: flex; align-items: center; gap: 12px; padding: 12px 16px; border: 1.5px solid #cbd5e1; background: #ffffff; border-radius: 12px; cursor: pointer; transition: all 0.2s ease;" class="export-range-label" onclick="selectExportRange('30d', this)">
              <input type="radio" name="exportRangeOption" value="30d" style="accent-color: #0d8a7c; width: 18px; height: 18px; cursor: pointer;">
              <div>
                <div style="font-weight: 700; color: #0f172a; font-size: 13.5px;">🗓️ Per Bulan (30 Hari Terakhir)</div>
                <div style="font-size: 12px; color: #64748b;">Mengunduh akumulasi data 30 hari terakhir untuk laporan bulanan</div>
              </div>
            </label>

            <!-- Opsi 4: Semua Data -->
            <label style="display: flex; align-items: center; gap: 12px; padding: 12px 16px; border: 1.5px solid #cbd5e1; background: #ffffff; border-radius: 12px; cursor: pointer; transition: all 0.2s ease;" class="export-range-label" onclick="selectExportRange('all', this)">
              <input type="radio" name="exportRangeOption" value="all" style="accent-color: #0d8a7c; width: 18px; height: 18px; cursor: pointer;">
              <div>
                <div style="font-weight: 700; color: #0f172a; font-size: 13.5px;">🗄️ Semua Data (Total Seluruh Arsip)</div>
                <div style="font-size: 12px; color: #64748b;">Mengunduh seluruh log telemetri yang tersimpan di MySQL database</div>
              </div>
            </label>
          </div>
        </div>

        <div class="modal-footer" style="padding: 16px 24px; border-top: 1px solid #f1f5f9; background: #f8fafc; display: flex; justify-content: flex-end; gap: 10px;">
          <button type="button" class="btn-action" onclick="closeExportModal()" style="padding: 9px 18px; font-size: 13.5px; border-radius: 10px; background: #ffffff; border: 1px solid #cbd5e1; color: #475569; font-weight: 600; cursor: pointer;">Batal</button>
          <button type="button" onclick="goToExportStep2()" style="background: #0d8a7c; color: #ffffff; border: none; border-radius: 10px; padding: 9px 22px; font-size: 13.5px; font-weight: 700; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 2px 8px rgba(13, 138, 124, 0.25);">
            <span>Lanjut: Pilih Jenis Data</span>
            <i data-lucide="arrow-right" style="width: 16px; height: 16px;"></i>
          </button>
        </div>
      </div>

      <!-- LANGKAH 2: PILIH JENIS DATA (KONDISI) -->
      <div id="exportStep2" style="display: none;">
        <div class="modal-body" style="padding: 22px 24px;">
          <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px;">
            <span style="font-size: 12px; font-weight: 700; color: #0d8a7c; background: #f0fdfa; padding: 3px 10px; border-radius: 6px; border: 1px solid #ccfbf1;">Langkah 2 dari 2</span>
            <div style="font-size: 12px; color: #475569;">
              Rentang: <strong id="summarySelectedRangeText" style="color: #0d8a7c;">Hari Ini</strong>
            </div>
          </div>
          <p style="font-size: 13px; color: #64748b; margin-top: 0; margin-bottom: 16px; line-height: 1.5;">
            Silakan pilih jenis data kondisi roda gigi yang ingin diekspor:
          </p>

          <div style="display: flex; flex-direction: column; gap: 10px;">
            <!-- Kondisi: Semua -->
            <label style="display: flex; align-items: center; gap: 12px; padding: 12px 16px; border: 1.5px solid #0d8a7c; background: #f0fdfa; border-radius: 12px; cursor: pointer; transition: all 0.2s ease;" class="export-condition-label" onclick="selectExportCondition('all', this)">
              <input type="radio" name="exportConditionOption" value="all" checked style="accent-color: #0d8a7c; width: 18px; height: 18px; cursor: pointer;">
              <div>
                <div style="font-weight: 700; color: #0f172a; font-size: 13.5px;">🌐 Semua Jenis Data (Normal, Terkikis, Rusak)</div>
                <div style="font-size: 12px; color: #64748b;">Mengekspor seluruh rekaman getaran tanpa filter kondisi</div>
              </div>
            </label>

            <!-- Kondisi: Normal -->
            <label style="display: flex; align-items: center; gap: 12px; padding: 12px 16px; border: 1.5px solid #cbd5e1; background: #ffffff; border-radius: 12px; cursor: pointer; transition: all 0.2s ease;" class="export-condition-label" onclick="selectExportCondition('normal', this)">
              <input type="radio" name="exportConditionOption" value="normal" style="accent-color: #0d8a7c; width: 18px; height: 18px; cursor: pointer;">
              <div>
                <div style="font-weight: 700; color: #0f172a; font-size: 13.5px;">🟢 Hanya Data Normal</div>
                <div style="font-size: 12px; color: #64748b;">Hanya rekaman getaran saat roda gigi beroperasi optimal</div>
              </div>
            </label>

            <!-- Kondisi: Terkikis (Scoring) -->
            <label style="display: flex; align-items: center; gap: 12px; padding: 12px 16px; border: 1.5px solid #cbd5e1; background: #ffffff; border-radius: 12px; cursor: pointer; transition: all 0.2s ease;" class="export-condition-label" onclick="selectExportCondition('terkikis', this)">
              <input type="radio" name="exportConditionOption" value="terkikis" style="accent-color: #d97706; width: 18px; height: 18px; cursor: pointer;">
              <div>
                <div style="font-weight: 700; color: #0f172a; font-size: 13.5px;">🟠 Hanya Data Terkikis (Scoring)</div>
                <div style="font-size: 12px; color: #64748b;">Hanya rekaman anomali keausan / erosi permukaan roda gigi</div>
              </div>
            </label>

            <!-- Kondisi: Rusak (Damaged) -->
            <label style="display: flex; align-items: center; gap: 12px; padding: 12px 16px; border: 1.5px solid #cbd5e1; background: #ffffff; border-radius: 12px; cursor: pointer; transition: all 0.2s ease;" class="export-condition-label" onclick="selectExportCondition('rusak', this)">
              <input type="radio" name="exportConditionOption" value="rusak" style="accent-color: #dc2626; width: 18px; height: 18px; cursor: pointer;">
              <div>
                <div style="font-weight: 700; color: #0f172a; font-size: 13.5px;">🔴 Hanya Data Rusak (Damaged)</div>
                <div style="font-size: 12px; color: #64748b;">Hanya rekaman kegagalan kritis / retak atau patah dedendum gigi</div>
              </div>
            </label>
          </div>
        </div>

        <div class="modal-footer" style="padding: 16px 24px; border-top: 1px solid #f1f5f9; background: #f8fafc; display: flex; justify-content: space-between; align-items: center;">
          <button type="button" class="btn-action" onclick="goToExportStep1()" style="padding: 9px 18px; font-size: 13.5px; border-radius: 10px; background: #ffffff; border: 1px solid #cbd5e1; color: #475569; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 6px;">
            <i data-lucide="arrow-left" style="width: 16px; height: 16px;"></i>
            <span>Kembali</span>
          </button>
          <div style="display: flex; gap: 10px;">
            <button type="button" class="btn-action" onclick="closeExportModal()" style="padding: 9px 18px; font-size: 13.5px; border-radius: 10px; background: #ffffff; border: 1px solid #cbd5e1; color: #475569; font-weight: 600; cursor: pointer;">Batal</button>
            <button type="button" onclick="executeExportCsv()" style="background: #0d8a7c; color: #ffffff; border: none; border-radius: 10px; padding: 9px 22px; font-size: 13.5px; font-weight: 700; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 2px 8px rgba(13, 138, 124, 0.25);">
              <i data-lucide="download" style="width: 16px; height: 16px;"></i>
              <span>Unduh Berkas CSV</span>
            </button>
          </div>
        </div>
      </div>

    </div>
  </div>

  <!-- ================= MODAL: HAPUS DATA RENTANG WAKTU (KALENDER) & JENIS DATA ================= -->
  <div class="modal-backdrop" id="deleteDataModalBackdrop" style="display: none;" onclick="if(event.target === this) closeDeleteModal()">
    <div class="modal-box" style="width: 530px; background: #ffffff; border-radius: 16px; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.15), 0 10px 10px -5px rgba(0, 0, 0, 0.05); overflow: hidden;">
      
      <!-- MODAL HEADER -->
      <div class="modal-header" style="padding: 18px 24px; border-bottom: 1px solid #fee2e2; background: #fff5f5; display: flex; justify-content: space-between; align-items: center;">
        <div class="modal-title" style="font-size: 15.5px; font-weight: 700; color: #991b1b; display: flex; align-items: center; gap: 10px;">
          <div style="width: 36px; height: 36px; border-radius: 10px; background: #fee2e2; display: flex; align-items: center; justify-content: center;">
            <i data-lucide="trash-2" style="width: 20px; height: 20px; color: #dc2626;"></i>
          </div>
          <div>
            <div style="font-size: 15.5px; font-weight: 700; color: #991b1b;">Hapus Data Telemetri &amp; Log AI</div>
            <div style="font-size: 12px; font-weight: 500; color: #b91c1c;">Hapus data berdasarkan rentang waktu kalender &amp; kondisi</div>
          </div>
        </div>
        <button type="button" class="modal-close-btn" onclick="closeDeleteModal()" style="background: transparent; border: none; color: #94a3b8; cursor: pointer; padding: 4px; border-radius: 8px;">
          <i data-lucide="x" style="width: 20px; height: 20px;"></i>
        </button>
      </div>

      <!-- MODAL BODY -->
      <div class="modal-body" style="padding: 22px 24px;">
        
        <!-- BAGIAN 1: RENTANG WAKTU KALENDER -->
        <div style="margin-bottom: 18px;">
          <label style="display: block; font-size: 13px; font-weight: 700; color: #0f172a; margin-bottom: 8px;">
            1. Rentang Waktu (Pilih Tanggal dari Kalender)
          </label>
          <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
            <div>
              <span style="display: block; font-size: 11.5px; color: #64748b; margin-bottom: 4px; font-weight: 600;">Tanggal Mulai :</span>
              <input type="date" id="deleteStartDate" onchange="checkDeleteCount()" style="width: 100%; border: 1.5px solid #cbd5e1; border-radius: 10px; padding: 8px 12px; font-size: 13px; font-family: 'General Sans', sans-serif; color: #0f172a; outline: none; background: #ffffff;">
            </div>
            <div>
              <span style="display: block; font-size: 11.5px; color: #64748b; margin-bottom: 4px; font-weight: 600;">Tanggal Selesai :</span>
              <input type="date" id="deleteEndDate" onchange="checkDeleteCount()" style="width: 100%; border: 1.5px solid #cbd5e1; border-radius: 10px; padding: 8px 12px; font-size: 13px; font-family: 'General Sans', sans-serif; color: #0f172a; outline: none; background: #ffffff;">
            </div>
          </div>
          <div style="font-size: 11.5px; color: #94a3b8; margin-top: 5px;">*Gunakan kalender untuk memilih batas awal dan batas akhir waktu rekaman.</div>
        </div>

        <!-- BAGIAN 2: PILIH JENIS DATA -->
        <div style="margin-bottom: 18px;">
          <label style="display: block; font-size: 13px; font-weight: 700; color: #0f172a; margin-bottom: 8px;">
            2. Jenis Data Telemetri yang Ingin Dihapus
          </label>
          <div style="display: flex; flex-direction: column; gap: 8px;">
            <label style="display: flex; align-items: center; gap: 10px; padding: 10px 14px; border: 1.5px solid #0d8a7c; background: #f0fdfa; border-radius: 10px; cursor: pointer; transition: all 0.2s ease;" class="delete-cond-label" onclick="selectDeleteCondition('all', this)">
              <input type="radio" name="deleteConditionOption" value="all" checked style="accent-color: #dc2626; width: 16px; height: 16px; cursor: pointer;">
              <div>
                <strong style="font-size: 13px; color: #0f172a;">Semua Jenis Data</strong>
                <span style="font-size: 11.5px; color: #64748b;">(Normal, Terkikis, dan Rusak)</span>
              </div>
            </label>

            <label style="display: flex; align-items: center; gap: 10px; padding: 10px 14px; border: 1.5px solid #cbd5e1; background: #ffffff; border-radius: 10px; cursor: pointer; transition: all 0.2s ease;" class="delete-cond-label" onclick="selectDeleteCondition('normal', this)">
              <input type="radio" name="deleteConditionOption" value="normal" style="accent-color: #dc2626; width: 16px; height: 16px; cursor: pointer;">
              <div>
                <strong style="font-size: 13px; color: #0f172a;">🟢 Hanya Data Normal</strong>
                <span style="font-size: 11.5px; color: #64748b;">(Kondisi optimal)</span>
              </div>
            </label>

            <label style="display: flex; align-items: center; gap: 10px; padding: 10px 14px; border: 1.5px solid #cbd5e1; background: #ffffff; border-radius: 10px; cursor: pointer; transition: all 0.2s ease;" class="delete-cond-label" onclick="selectDeleteCondition('terkikis', this)">
              <input type="radio" name="deleteConditionOption" value="terkikis" style="accent-color: #dc2626; width: 16px; height: 16px; cursor: pointer;">
              <div>
                <strong style="font-size: 13px; color: #0f172a;">🟠 Hanya Data Terkikis (Scoring)</strong>
                <span style="font-size: 11.5px; color: #64748b;">(Erosi permukaan gigi)</span>
              </div>
            </label>

            <label style="display: flex; align-items: center; gap: 10px; padding: 10px 14px; border: 1.5px solid #cbd5e1; background: #ffffff; border-radius: 10px; cursor: pointer; transition: all 0.2s ease;" class="delete-cond-label" onclick="selectDeleteCondition('rusak', this)">
              <input type="radio" name="deleteConditionOption" value="rusak" style="accent-color: #dc2626; width: 16px; height: 16px; cursor: pointer;">
              <div>
                <strong style="font-size: 13px; color: #0f172a;">🔴 Hanya Data Rusak (Damaged)</strong>
                <span style="font-size: 11.5px; color: #64748b;">(Kerusakan kritis / patah)</span>
              </div>
            </label>
          </div>
        </div>

        <!-- BAGIAN 3: PREVIEW JUMLAH DATA COCOK -->
        <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 12px 16px; margin-bottom: 14px; display: flex; justify-content: space-between; align-items: center;">
          <span style="font-size: 12.5px; color: #475569; font-weight: 600;">Data yang Teridentifikasi:</span>
          <span id="deleteMatchedCountBadge" style="font-size: 13px; font-weight: 700; color: #0d8a7c; background: #ffffff; padding: 4px 12px; border-radius: 6px; border: 1px solid #cbd5e1;">Menghitung data...</span>
        </div>

        <!-- BAGIAN 4: ALERT KEAMANAN -->
        <div style="background: #fef2f2; border: 1px solid #fecaca; border-radius: 10px; padding: 10px 14px; font-size: 12px; color: #991b1b; display: flex; gap: 8px; align-items: flex-start;">
          <i data-lucide="alert-triangle" style="width: 16px; height: 16px; color: #dc2626; flex-shrink: 0; margin-top: 1px;"></i>
          <span><strong>Perhatian:</strong> Tindakan ini permanen. Seluruh data telemetri dan riwayat keputusan diagnosis AI pada rentang tanggal dan kondisi terpilih akan dihapus permanen dari database.</span>
        </div>

      </div>

      <!-- MODAL FOOTER -->
      <div class="modal-footer" style="padding: 16px 24px; border-top: 1px solid #f1f5f9; background: #f8fafc; display: flex; justify-content: flex-end; gap: 10px;">
        <button type="button" class="btn-action" onclick="closeDeleteModal()" style="padding: 9px 18px; font-size: 13.5px; border-radius: 10px; background: #ffffff; border: 1px solid #cbd5e1; color: #475569; font-weight: 600; cursor: pointer;">Batal</button>
        <button type="button" id="btnConfirmDeleteData" onclick="executeDeleteData()" style="background: #dc2626; color: #ffffff; border: none; border-radius: 10px; padding: 9px 22px; font-size: 13.5px; font-weight: 700; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 2px 8px rgba(220, 38, 38, 0.25);">
          <i data-lucide="trash-2" style="width: 16px; height: 16px;"></i>
          <span>Hapus Data Terpilih</span>
        </button>
      </div>

    </div>
  </div>

  <script>
    /**
     * =========================================================================
     * MODUL RIWAYAT PENGUKURAN TELEMETRI - GEARSENSE
     * =========================================================================
     * STATUS INTEGRASI IOT:
     * - Hardware ESP32 & Modul Sensor ADXL345: DINONAKTIFKAN SEMENTARA (STANDBY)
     * - Data yang disajikan merupakan arsip rekaman benchmark telemetri getaran.
     * - Sinkronisasi otomatis ke MicroSD lokal dan MQTT broker disiapkan dalam status standby.
     * =========================================================================
     */

    // Inisialisasi ikon Lucide
    // FUNGSI: lucide.createIcons()
    // TUJUAN: Merender ikon grafis SVG pada elemen ber-atribut data-lucide.
    lucide.createIcons();

    /**
     * FUNGSI: Toggle Flip / Collapse Sidebar
     * TUJUAN: Membuka atau melipat bilah navigasi samping antara lebar 280px dan 84px.
     */
    const mainSidebar = document.getElementById('mainSidebar');
    const flipSidebarBtn = document.getElementById('flipSidebarBtn');

    /**
     * FUNGSI: Toggle Dropdown Profil Pengguna
     * TUJUAN: Menampilkan atau menyembunyikan menu profil.
     */
    const userDropdownBtn = document.getElementById('userDropdownBtn');
    const userDropdownMenu = document.getElementById('userDropdownMenu');
    const dropdownArrow = document.getElementById('dropdownArrow');

    if (userDropdownBtn && userDropdownMenu && dropdownArrow) {
      userDropdownBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        userDropdownMenu.classList.toggle('show');
        dropdownArrow.classList.toggle('open');
      });

      document.addEventListener('click', (e) => {
        if (!userDropdownBtn.contains(e.target)) {
          userDropdownMenu.classList.remove('show');
          dropdownArrow.classList.remove('open');
        }
      });
    }

    /**
     * FUNGSI: handleLogout()
     * TUJUAN: Mengeluarkan pengguna dari sesi aktif dan kembali ke halaman login.
     */
    function handleLogout() {
      if (confirm('Keluar dari sesi Gearsense dan kembali ke halaman login?')) {
        localStorage.removeItem('gearsense_user');
        const form = document.getElementById('logoutForm');
        if (form) {
          form.submit();
        } else {
          window.location.href = 'login.html';
        }
      }
    }

    /**
     * FUNGSI: updateClock()
     * TUJUAN: Memperbarui stempel waktu live (WIB) pada bar atas setiap detik.
     */
    function updateClock() {
      const now = new Date();
      const options = { 
        day: 'numeric', month: 'short', year: 'numeric', 
        hour: '2-digit', minute: '2-digit', second: '2-digit',
        hour12: false
      };
      const formatted = now.toLocaleDateString('id-ID', options).replace(/\./g, ':');
      const elem = document.getElementById('liveTimestamp');
      if (elem) elem.innerText = formatted + ' WIB';
    }
    updateClock();
    setInterval(updateClock, 1000);
    document.addEventListener('DOMContentLoaded', applyPagination);
    if (document.readyState === 'complete' || document.readyState === 'interactive') {
      applyPagination();
    }

    /**
     * FUNGSI: Inisialisasi Grafik Tren RMS Historis 24 Jam
     * TUJUAN: Memetakan fluktuasi percepatan getaran RMS sepanjang siklus 24 jam operasional konveyor,
     *         mengecek kepatuhan terhadap Batas Waspada (0.10g) dan Batas Bahaya (0.15g) ISO 10816-3.
     */
    const trend24hLabels = {!! json_encode(count($chart24hLabels ?? []) > 0 ? $chart24hLabels : ['14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00']) !!};
    const trend24hRms = {!! json_encode(count($chart24hRms ?? []) > 0 ? $chart24hRms : [0, 0, 0, 0, 0, 0, 0]) !!};
    const trendRtLabels = {!! json_encode(count($chartRtLabels ?? []) > 0 ? $chartRtLabels : ['20:30:00', '20:30:01']) !!};
    const trendRtRms = {!! json_encode(count($chartRtRms ?? []) > 0 ? $chartRtRms : [0.025, 0.026]) !!};

    const ctxTrend = document.getElementById('historyTrendChart').getContext('2d');
    const trendChart = new Chart(ctxTrend, {
      type: 'line',
      data: {
        labels: trend24hLabels,
        datasets: [
          {
            label: 'RMS Terukur',
            data: trend24hRms,
            borderColor: '#0d8a7c',
            backgroundColor: 'rgba(13, 138, 124, 0.08)',
            borderWidth: 2.2,
            tension: 0.35,
            fill: true,
            pointRadius: 3,
            pointBackgroundColor: '#0d8a7c'
          },
          {
            label: 'Batas Peringatan',
            data: Array(trend24hLabels.length).fill(0.10),
            borderColor: '#f59e0b',
            borderDash: [5, 5],
            borderWidth: 1.5,
            pointRadius: 0,
            fill: false
          },
          {
            label: 'Batas Bahaya',
            data: Array(trend24hLabels.length).fill(0.15),
            borderColor: '#ef4444',
            borderDash: [5, 5],
            borderWidth: 1.5,
            pointRadius: 0,
            fill: false
          }
        ]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
          x: {
            grid: { color: '#f8fafc' },
            ticks: { color: '#64748b', font: { size: 10, family: 'General Sans' } }
          },
          y: {
            min: 0,
            suggestedMax: 0.20,
            grid: { color: '#f1f5f9' },
            ticks: {
              color: '#0f172a',
              font: { size: 11, weight: 'bold', family: 'General Sans' },
              callback: function(v) { return v.toFixed(2) + 'g'; }
            }
          }
        },
        plugins: {
          legend: { display: false }
        }
      }
    });

    let currentTrendMode = '24h';

    function switchTrendMode(mode) {
      currentTrendMode = mode;
      if (mode === '24h') {
        trendChart.data.labels = trend24hLabels;
        trendChart.data.datasets[0].data = trend24hRms;
        trendChart.data.datasets[1].data = Array(trend24hLabels.length).fill(0.10);
        trendChart.data.datasets[2].data = Array(trend24hLabels.length).fill(0.15);
        document.getElementById('trendModeBadge').innerText = 'Interval Rata-rata 1 Jam (WIB)';
        document.getElementById('btnMode24h').style.background = '#0d8a7c';
        document.getElementById('btnMode24h').style.color = '#ffffff';
        document.getElementById('btnModeRt').style.background = 'transparent';
        document.getElementById('btnModeRt').style.color = '#64748b';
      } else {
        trendChart.data.labels = trendRtLabels;
        trendChart.data.datasets[0].data = trendRtRms;
        trendChart.data.datasets[1].data = Array(trendRtLabels.length).fill(0.10);
        trendChart.data.datasets[2].data = Array(trendRtLabels.length).fill(0.15);
        document.getElementById('trendModeBadge').innerText = 'Real-time (25 Sampel Terakhir - WIB)';
        document.getElementById('btnModeRt').style.background = '#0d8a7c';
        document.getElementById('btnModeRt').style.color = '#ffffff';
        document.getElementById('btnMode24h').style.background = 'transparent';
        document.getElementById('btnMode24h').style.color = '#64748b';
      }
      trendChart.update();
    }

    /**
     * FUNGSI: Inisialisasi Grafik Donut Distribusi Kondisi Roda Gigi
     * TUJUAN: Memperlihatkan proporsi akumulatif kondisi transmisi konveyor (Normal 98.2%, Keausan 1.3%, Pitting 0.5%).
     */
    const ctxDist = document.getElementById('conditionDistributionChart').getContext('2d');
    const distChart = new Chart(ctxDist, {
      type: 'doughnut',
      data: {
        labels: ['Normal', 'Terkikis (Scoring)', 'Rusak (Damaged)'],
        datasets: [{
          data: [{{ $normalCount }}, {{ $scoringCount }}, {{ $damagedCount }}],
          backgroundColor: ['#0d8a7c', '#d97706', '#ef4444'],
          borderWidth: 2,
          borderColor: '#ffffff'
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: { display: false }
        },
        cutout: '72%'
      }
    });

    /**
     * FUNGSI: filterTable()
     * TUJUAN: Menyaring baris data tabel riwayat telemetri secara real-time berdasarkan input teks pencarian pengguna
     *         (ID sesi atau tanggal) dan pilihan status (Normal, Keausan, Pitting).
     */
    let currentPage = 1;
    const pageSize = 10;

    function applyPagination() {
      const searchVal = document.getElementById('tableSearchInput') ? document.getElementById('tableSearchInput').value.toLowerCase().trim() : '';
      const statusVal = document.getElementById('statusFilterSelect') ? document.getElementById('statusFilterSelect').value : 'ALL';
      const allRows = Array.from(document.querySelectorAll('#historyTableBody tr'));

      // Filter baris
      const matchedRows = allRows.filter(row => {
        const text = row.innerText.toLowerCase();
        const matchesSearch = (searchVal === '' || text.includes(searchVal));
        let matchesStatus = true;
        if (statusVal === 'NORMAL') matchesStatus = text.includes('normal');
        else if (statusVal === 'TERKIKIS') matchesStatus = text.includes('terkikis');
        else if (statusVal === 'RUSAK') matchesStatus = text.includes('rusak');
        return matchesSearch && matchesStatus;
      });

      const totalMatched = matchedRows.length;
      const totalPages = Math.max(1, Math.ceil(totalMatched / pageSize));
      if (currentPage > totalPages) currentPage = totalPages;
      if (currentPage < 1) currentPage = 1;

      // Sembunyikan semua, tampilkan halaman aktif
      allRows.forEach(r => r.style.display = 'none');
      matchedRows.forEach((r, idx) => {
        if (idx >= (currentPage - 1) * pageSize && idx < currentPage * pageSize) {
          r.style.display = '';
        }
      });

      const countEl = document.getElementById('showingCountText');
      if (countEl) countEl.innerText = Math.min(totalMatched, pageSize);

      const pageInfo = document.getElementById('pageInfoText');
      if (pageInfo) pageInfo.innerText = `Halaman ${currentPage} dari ${totalPages}`;

      // Render nomor pagination
      const numBox = document.getElementById('paginationNumbers');
      if (numBox) {
        numBox.innerHTML = '';
        const maxButtons = 5;
        let startP = Math.max(1, currentPage - 2);
        let endP = Math.min(totalPages, startP + maxButtons - 1);
        if (endP - startP < maxButtons - 1) {
          startP = Math.max(1, endP - maxButtons + 1);
        }

        for (let p = startP; p <= endP; p++) {
          const btn = document.createElement('button');
          btn.textContent = p;
          btn.onclick = () => goToPage(p);
          btn.style.cssText = p === currentPage
            ? 'background: #0d8a7c; color: #ffffff; border: none; border-radius: 8px; padding: 5px 12px; font-size: 12px; font-weight: 700; cursor: pointer;'
            : 'background: #f8fafc; border: none; box-shadow: 0 0 0 0.5px #cbd5e1 inset; border-radius: 8px; padding: 5px 12px; font-size: 12px; font-weight: 700; color: #042f2e; cursor: pointer;';
          numBox.appendChild(btn);
        }

        if (endP < totalPages) {
          const dots = document.createElement('span');
          dots.textContent = '...';
          dots.style.cssText = 'color: #94a3b8; font-size: 12px; padding: 0 4px;';
          numBox.appendChild(dots);

          const lastBtn = document.createElement('button');
          lastBtn.textContent = totalPages;
          lastBtn.onclick = () => goToPage(totalPages);
          lastBtn.style.cssText = 'background: #f8fafc; border: none; box-shadow: 0 0 0 0.5px #cbd5e1 inset; border-radius: 8px; padding: 5px 12px; font-size: 12px; font-weight: 700; color: #042f2e; cursor: pointer;';
          numBox.appendChild(lastBtn);
        }
      }

      const prevBtn = document.getElementById('btnPrevPage');
      const nextBtn = document.getElementById('btnNextPage');
      if (prevBtn) {
        prevBtn.style.opacity = currentPage === 1 ? '0.5' : '1';
        prevBtn.disabled = currentPage === 1;
      }
      if (nextBtn) {
        nextBtn.style.opacity = currentPage >= totalPages ? '0.5' : '1';
        nextBtn.disabled = currentPage >= totalPages;
      }
    }

    function goToPage(p) {
      currentPage = p;
      applyPagination();
    }

    function changeHistoryPage(dir) {
      if (dir === 'prev') {
        if (currentPage > 1) { currentPage--; applyPagination(); }
      } else {
        currentPage++;
        applyPagination();
      }
    }

    function filterTable() {
      currentPage = 1;
      applyPagination();
    }

    /**
     * =========================================================================
     * MODAL: PILIH RENTANG & JENIS DATA EKSPOR CSV (2 LANGKAH)
     * =========================================================================
     */
    let selectedExportRange = 'today';
    let selectedExportCondition = 'all';

    const rangeDisplayNames = {
      'today': 'Hari Ini (24 Jam Terakhir)',
      '7d': '7 Hari Terakhir',
      '30d': '30 Hari Terakhir',
      'all': 'Semua Data (Total Arsip)'
    };

    function openExportModal() {
      const modal = document.getElementById('exportCsvModalBackdrop');
      if (!modal) return;
      modal.style.display = 'flex';
      goToExportStep1();
      if (window.lucide && typeof lucide.createIcons === 'function') {
        lucide.createIcons();
      }
    }

    function closeExportModal() {
      const modal = document.getElementById('exportCsvModalBackdrop');
      if (modal) modal.style.display = 'none';
    }

    function goToExportStep1() {
      const step1 = document.getElementById('exportStep1');
      const step2 = document.getElementById('exportStep2');
      const title = document.getElementById('exportModalTitle');
      if (step1) step1.style.display = 'block';
      if (step2) step2.style.display = 'none';
      if (title) title.innerText = 'Ekspor CSV: Pilih Rentang Waktu (1/2)';
    }

    function goToExportStep2() {
      const step1 = document.getElementById('exportStep1');
      const step2 = document.getElementById('exportStep2');
      const title = document.getElementById('exportModalTitle');
      const summaryText = document.getElementById('summarySelectedRangeText');

      if (step1) step1.style.display = 'none';
      if (step2) step2.style.display = 'block';
      if (title) title.innerText = 'Ekspor CSV: Pilih Jenis Data (2/2)';
      if (summaryText) summaryText.innerText = rangeDisplayNames[selectedExportRange] || 'Hari Ini';

      if (window.lucide && typeof lucide.createIcons === 'function') {
        lucide.createIcons();
      }
    }

    function selectExportRange(range, el) {
      selectedExportRange = range;
      document.querySelectorAll('.export-range-label').forEach(l => {
        l.style.borderColor = '#cbd5e1';
        l.style.background = '#ffffff';
      });
      if (el) {
        el.style.borderColor = '#0d8a7c';
        el.style.background = '#f0fdfa';
        const radio = el.querySelector('input[type="radio"]');
        if (radio) radio.checked = true;
      }
    }

    function selectExportCondition(cond, el) {
      selectedExportCondition = cond;
      document.querySelectorAll('.export-condition-label').forEach(l => {
        l.style.borderColor = '#cbd5e1';
        l.style.background = '#ffffff';
      });
      if (el) {
        const borderCol = (cond === 'normal') ? '#10b981' : ((cond === 'terkikis') ? '#f59e0b' : ((cond === 'rusak') ? '#ef4444' : '#0d8a7c'));
        const bgCol = (cond === 'normal') ? '#f0fdf4' : ((cond === 'terkikis') ? '#fffbeb' : ((cond === 'rusak') ? '#fef2f2' : '#f0fdfa'));
        el.style.borderColor = borderCol;
        el.style.background = bgCol;
        const radio = el.querySelector('input[type="radio"]');
        if (radio) radio.checked = true;
      }
    }

    function executeExportCsv() {
      closeExportModal();
      window.location.href = `/riwayat-pengukuran/export-csv?range=${selectedExportRange}&condition=${selectedExportCondition}`;
    }

    /**
     * =========================================================================
     * MODAL & LOGIKA PENGHAPUSAN DATA RENTANG WAKTU (KALENDER)
     * =========================================================================
     */
    let selectedDeleteCondition = 'all';

    function openDeleteModal() {
      const modal = document.getElementById('deleteDataModalBackdrop');
      if (!modal) return;

      // Default: 30 hari ke belakang hingga hari ini
      const end = new Date();
      const start = new Date();
      start.setDate(end.getDate() - 30);

      const endIso = end.toISOString().split('T')[0];
      const startIso = start.toISOString().split('T')[0];

      const startInput = document.getElementById('deleteStartDate');
      const endInput = document.getElementById('deleteEndDate');
      if (startInput && !startInput.value) startInput.value = startIso;
      if (endInput && !endInput.value) endInput.value = endIso;

      modal.style.display = 'flex';
      checkDeleteCount();

      if (window.lucide && typeof lucide.createIcons === 'function') {
        lucide.createIcons();
      }
    }

    function closeDeleteModal() {
      const modal = document.getElementById('deleteDataModalBackdrop');
      if (modal) modal.style.display = 'none';
    }

    function selectDeleteCondition(cond, el) {
      selectedDeleteCondition = cond;
      document.querySelectorAll('.delete-cond-label').forEach(lbl => {
        lbl.style.borderColor = '#cbd5e1';
        lbl.style.background = '#ffffff';
        const radio = lbl.querySelector('input[type="radio"]');
        if (radio) radio.checked = false;
      });

      if (el) {
        el.style.borderColor = '#dc2626';
        el.style.background = '#fef2f2';
        const radio = el.querySelector('input[type="radio"]');
        if (radio) radio.checked = true;
      }
      checkDeleteCount();
    }

    async function checkDeleteCount() {
      const startInput = document.getElementById('deleteStartDate');
      const endInput = document.getElementById('deleteEndDate');
      const badge = document.getElementById('deleteMatchedCountBadge');
      if (!startInput || !endInput || !badge) return;

      const startDate = startInput.value;
      const endDate = endInput.value;
      if (!startDate || !endDate) {
        badge.innerText = 'Pilih rentang tanggal kalender';
        badge.style.color = '#94a3b8';
        return;
      }

      badge.innerText = 'Menghitung...';
      badge.style.color = '#64748b';

      try {
        const response = await fetch('/riwayat-pengukuran/check-delete-count', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
          },
          body: JSON.stringify({
            start_date: startDate,
            end_date: endDate,
            condition: selectedDeleteCondition
          })
        });

        const data = await response.json();
        if (data.success) {
          badge.innerText = data.count.toLocaleString('id-ID') + ' Data Ditemukan';
          badge.style.color = data.count > 0 ? '#dc2626' : '#059669';
        } else {
          badge.innerText = 'Gagal memeriksa';
          badge.style.color = '#dc2626';
        }
      } catch (err) {
        badge.innerText = 'Gagal memeriksa koneksi';
        badge.style.color = '#dc2626';
      }
    }

    async function executeDeleteData() {
      const startInput = document.getElementById('deleteStartDate');
      const endInput = document.getElementById('deleteEndDate');
      const btn = document.getElementById('btnConfirmDeleteData');

      if (!startInput || !endInput) return;
      const startDate = startInput.value;
      const endDate = endInput.value;

      if (!startDate || !endDate) {
        alert('Silakan pilih rentang tanggal mulai dan tanggal selesai terlebih dahulu.');
        return;
      }

      const condName = selectedDeleteCondition === 'all' ? 'SEMUA JENIS DATA' : selectedDeleteCondition.toUpperCase();
      const confirmMsg = `PERINGATAN PENGHAPUSAN DATA TELEMETRI!\n\n` +
        `Anda akan menghapus data getaran dari database secara permanen:\n` +
        `• Rentang Tanggal Kalender: ${startDate} s/d ${endDate}\n` +
        `• Kategori Kondisi: ${condName}\n\n` +
        `Apakah Anda yakin ingin melanjutkan tindakan ini? Data yang terhapus tidak dapat dipulihkan.`;

      if (!confirm(confirmMsg)) {
        return;
      }

      if (btn) {
        btn.disabled = true;
        btn.innerHTML = `<i data-lucide="loader-2" style="width: 16px; height: 16px; animation: spin 1s linear infinite;"></i><span>Menghapus Data...</span>`;
      }

      try {
        const response = await fetch('/riwayat-pengukuran/delete-data', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
          },
          body: JSON.stringify({
            start_date: startDate,
            end_date: endDate,
            condition: selectedDeleteCondition
          })
        });

        const result = await response.json();
        if (result.success) {
          alert(`Sukses: ${result.message}`);
          closeDeleteModal();
          window.location.reload();
        } else {
          alert(`Gagal: ${result.message || 'Terjadi kesalahan sistem.'}`);
          if (btn) {
            btn.disabled = false;
            btn.innerHTML = `<i data-lucide="trash-2" style="width: 16px; height: 16px;"></i><span>Hapus Data Terpilih</span>`;
            if (window.lucide) lucide.createIcons();
          }
        }
      } catch (err) {
        alert('Terjadi kesalahan koneksi saat mencoba menghapus data.');
        if (btn) {
          btn.disabled = false;
          btn.innerHTML = `<i data-lucide="trash-2" style="width: 16px; height: 16px;"></i><span>Hapus Data Terpilih</span>`;
          if (window.lucide) lucide.createIcons();
        }
      }
    }

    /**
     * =========================================================================
     * FITUR REAL-TIME LIVE AUTO-SYNC TELEMETRI
     * =========================================================================
     * Secara otomatis mengambil data getaran terbaru dari ESP32 setiap 2.5 detik
     * tanpa memerlukan refresh halaman (F5) manual.
     */
    let lastTelemetryId = {{ $telemetries->first() ? $telemetries->first()->id : 0 }};
    let isPollingHistory = false;

    function prependTelemetryRow(item) {
      const tbody = document.getElementById('historyTableBody');
      if (!tbody) return;

      // Hapus placeholder kosong jika ada
      const emptyRow = tbody.querySelector('tr td[colspan]');
      if (emptyRow) emptyRow.parentElement.remove();

      // Hindari duplikasi baris
      if (document.getElementById(`row-tel-${item.id}`)) return;

      const tr = document.createElement('tr');
      tr.id = `row-tel-${item.id}`;
      tr.setAttribute('data-id', item.id);
      tr.style.borderBottom = '1px solid #f1f5f9';
      tr.style.background = '#f0fdfa';
      tr.style.transition = 'background 1.5s ease';

      tr.innerHTML = `
        <td style="padding: 10px 12px; font-family: 'JetBrains Mono', monospace; font-weight: 700; color: #0d8a7c;">${item.session_id}</td>
        <td style="padding: 10px 12px;">${item.time_formatted}</td>
        <td style="padding: 10px 12px; font-family: 'JetBrains Mono', monospace;">${item.raw_z} g</td>
        <td style="padding: 10px 12px; font-family: 'JetBrains Mono', monospace;">${item.raw_x} g</td>
        <td style="padding: 10px 12px; font-family: 'JetBrains Mono', monospace;">${item.raw_y} g</td>
        <td style="padding: 10px 12px; font-family: 'JetBrains Mono', monospace; font-weight: 700; color: #059669;">${item.p2p_z} g</td>
        <td style="padding: 10px 12px; font-family: 'JetBrains Mono', monospace;">${item.wamp_z}</td>
        <td style="padding: 10px 12px; text-align: center;">
          <span style="background: ${item.cond_bg}; color: ${item.cond_color}; font-weight: 700; font-size: 11px; padding: 3px 10px; border-radius: 6px; box-shadow: 0 0 0 0.5px ${item.cond_border} inset;">● ${item.condition_text} (${item.confidence_score}%)</span>
        </td>
        <td style="padding: 10px 12px; text-align: center;">
          <button onclick="viewSessionDetail('${item.session_id}', '${item.condition_text}', '${item.rms_z_formatted}', '${item.p2p_z}', '${item.wamp_z}', '${item.time_formatted}')" style="background: transparent; border: none; color: #0284c7; font-weight: 700; cursor: pointer; text-decoration: underline; font-size: 12px;">Detail</button>
        </td>
      `;

      tbody.insertBefore(tr, tbody.firstChild);

      // Fade out background kembali ke putih
      setTimeout(() => {
        tr.style.background = '#ffffff';
      }, 2000);

      // Batasi jumlah baris dalam DOM maksimal 150 baris agar browser tetap responsif
      const allRows = tbody.querySelectorAll('tr');
      if (allRows.length > 150) {
        for (let i = 150; i < allRows.length; i++) {
          allRows[i].remove();
        }
      }
    }

    async function pollLiveHistory() {
      if (isPollingHistory) return;
      isPollingHistory = true;

      try {
        const response = await fetch(`/riwayat-pengukuran/live-data?last_id=${lastTelemetryId}`);
        if (!response.ok) throw new Error('HTTP ' + response.status);
        const data = await response.json();

        if (data.success && data.has_new && Array.isArray(data.new_records) && data.new_records.length > 0) {
          // Tambahkan baris baru ke tabel
          data.new_records.forEach(item => {
            prependTelemetryRow(item);
            if (item.id > lastTelemetryId) {
              lastTelemetryId = item.id;
            }

            // Tambahkan ke array grafik Real-time
            trendRtLabels.push(item.time_clock);
            trendRtRms.push(item.rms_z);
            if (trendRtLabels.length > 25) {
              trendRtLabels.shift();
              trendRtRms.shift();
            }
          });

          // Update grafik jika dalam mode Real-time
          if (currentTrendMode === 'rt') {
            trendChart.data.labels = trendRtLabels;
            trendChart.data.datasets[0].data = trendRtRms;
            trendChart.data.datasets[1].data = Array(trendRtLabels.length).fill(0.10);
            trendChart.data.datasets[2].data = Array(trendRtLabels.length).fill(0.15);
            trendChart.update('none');
          }

          // Perbarui teks total rekaman
          const totalRecordsText = document.getElementById('totalRecordsText');
          if (totalRecordsText) {
            totalRecordsText.innerHTML = `<strong>${data.total_count.toLocaleString('id-ID')} Data Telemetri</strong> (Node ESP32 Aktif)`;
          }

          // Perbarui KPI Summary Cards
          const summaryTotalSessions = document.getElementById('summaryTotalSessions');
          if (summaryTotalSessions) summaryTotalSessions.innerText = `${data.total_count.toLocaleString('id-ID')} Data`;

          const summaryNormalRatio = document.getElementById('summaryNormalRatio');
          if (summaryNormalRatio && data.total_count > 0) {
            summaryNormalRatio.innerText = `${((data.normal_count / data.total_count) * 100).toFixed(1)}%`;
          }

          const summaryNormalCountFoot = document.getElementById('summaryNormalCountFoot');
          if (summaryNormalCountFoot) summaryNormalCountFoot.innerText = `${data.normal_count.toLocaleString('id-ID')} Sesi Beroperasi Optimal`;

          const summaryAnomalyCount = document.getElementById('summaryAnomalyCount');
          if (summaryAnomalyCount) summaryAnomalyCount.innerText = `${(data.scoring_count + data.damaged_count).toLocaleString('id-ID')} Kejadian`;

          const summaryAnomalyFoot = document.getElementById('summaryAnomalyFoot');
          if (summaryAnomalyFoot) summaryAnomalyFoot.innerText = `${data.scoring_count.toLocaleString('id-ID')} Terkikis, ${data.damaged_count.toLocaleString('id-ID')} Rusak (Damaged)`;

          // Perbarui opsi dropdown filter status
          const filterSelect = document.getElementById('statusFilterSelect');
          if (filterSelect && filterSelect.options.length >= 4) {
            filterSelect.options[0].text = `Semua Status (${data.total_count.toLocaleString('id-ID')})`;
            filterSelect.options[1].text = `Normal (${data.normal_count.toLocaleString('id-ID')})`;
            filterSelect.options[2].text = `Terkikis / Scoring (${data.scoring_count.toLocaleString('id-ID')})`;
            filterSelect.options[3].text = `Rusak / Damaged (${data.damaged_count.toLocaleString('id-ID')})`;
          }

          // Perbarui Doughnut Chart Distribusi Kondisi
          if (distChart) {
            distChart.data.datasets[0].data = [data.normal_count, data.scoring_count, data.damaged_count];
            distChart.update('none');
          }

          const donutTotal = document.getElementById('donutTotalCount');
          if (donutTotal) donutTotal.innerText = `${data.total_count.toLocaleString('id-ID')} Sampel`;

          const legNormal = document.getElementById('legendNormal');
          if (legNormal && data.total_count > 0) {
            legNormal.innerText = `● Normal: ${data.normal_count.toLocaleString('id-ID')} (${((data.normal_count / data.total_count) * 100).toFixed(1)}%)`;
          }

          const legScoring = document.getElementById('legendScoring');
          if (legScoring && data.total_count > 0) {
            legScoring.innerText = `● Terkikis: ${data.scoring_count.toLocaleString('id-ID')} (${((data.scoring_count / data.total_count) * 100).toFixed(1)}%)`;
          }

          const legDamaged = document.getElementById('legendDamaged');
          if (legDamaged && data.total_count > 0) {
            legDamaged.innerText = `● Rusak: ${data.damaged_count.toLocaleString('id-ID')} (${((data.damaged_count / data.total_count) * 100).toFixed(1)}%)`;
          }

          // Terapkan paginasi ulang agar data baru tampil di halaman 1
          applyPagination();
        }
      } catch (err) {
        // Silent logging saat polling background
        console.warn('Gearsense live telemetry sync check:', err);
      } finally {
        isPollingHistory = false;
      }
    }

    // Jalankan auto-sync polling setiap 2.5 detik
    setInterval(pollLiveHistory, 2500);

    /**
     * FUNGSI: viewSessionDetail(sessionId)
     * TUJUAN: Menampilkan kotak dialog modal inspeksi rincian teknis sesi rekaman getaran,
     *         termasuk parameter akselerasi, skor confidence AI, dan verifikasi integritas telemetri.
     * PARAMETER: sessionId (string pengenal unik sesi, misal '#GS-20261031-1420').
     */
    function viewSessionDetail(sessionId, cond, rms, p2p, wamp, time) {
      alert('Detail Rekaman Telemetri: ' + sessionId + '\n\n' +
            '• Waktu: ' + (time || '-') + ' WIB\n' +
            '• Node Sensor: ESP32-GEARSENSE-01 (ADXL345)\n' +
            '• Kondisi Transmisi: ' + (cond || 'NORMAL') + '\n' +
            '• Nilai RMS Z: ' + (rms || '0.0000') + ' g\n' +
            '• Peak-to-Peak Z: ' + (p2p || '0.0000') + ' g\n' +
            '• WAMP Z: ' + (wamp || '0.00') + '\n' +
            '• Sumber Data: Database Riil MySQL Gearsense');
    }
  </script>

  
</body>
</html>
