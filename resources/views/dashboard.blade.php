<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=1920, initial-scale=1.0">
  <title>Gearsense - Dashboard Monitoring</title>
  
    <!-- Font: General Sans (Fontshare CDN) -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://api.fontshare.com/v2/css?f[]=general-sans@400,500,600,700,800&display=swap" rel="stylesheet">
  <script src="https://unpkg.com/lucide@latest"></script>
  <!-- Chart.js untuk grafik getaran interaktif halus -->
  
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
      --font-body: 'General Sans', -apple-system, BlinkMacSystemFont, sans-serif;
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
      color: #1e293b; /* Slate 800: Berwibawa & tegas, bukan abu-abu pudar */
      line-height: 1.6;
      -webkit-font-smoothing: antialiased;
      -moz-osx-font-smoothing: grayscale;
      overflow-x: auto;
      overflow-y: hidden;
      width: 1920px;
      height: 100vh;
    }

    /* Preset 2 (The Prestige Editorial): Headings elegan bergaya riset & publikasi ilmiah */
    h1, h2, h3, h4, .kpi-header, .admin-main-name, .telemetry-title, .info-card-header span, .model-info-footer strong {
      font-family: var(--font-heading);
      letter-spacing: -0.01em;
      font-weight: 600;
    }

    .kpi-value {
      font-family: var(--font-heading);
      letter-spacing: -0.02em;
      font-weight: 600;
    }

    .section-title {
      font-size: 14px;
      font-weight: 600;
      color: #042f2e;
      margin-bottom: 12px;
      letter-spacing: 0.04em;
      text-transform: uppercase;
      font-family: var(--font-heading);
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
      background: #2dd4bf;
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
      display: flex;
      align-items: center;
      gap: 8px;
      font-size: 14px;
      font-weight: 800;
      color: #042f2e;
      text-transform: uppercase;
      margin-bottom: 10px;
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
      font-size: 14px;
      font-weight: 800;
      color: #0f172a;
      text-transform: uppercase;
      letter-spacing: 0.3px;
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
      font-size: 14px;
      font-weight: 800;
      color: #0f172a;
      text-transform: uppercase;
      letter-spacing: 0.3px;
      margin-bottom: 18px;
      display: flex;
      align-items: center;
      gap: 8px;
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
      gap: 10px;
      list-style: none;
      margin: 0;
      padding: 0;
      max-height: 235px;
      overflow-y: auto;
    }

    .activity-log-list::-webkit-scrollbar {
      width: 4px;
    }
    .activity-log-list::-webkit-scrollbar-thumb {
      background: #cbd5e1;
      border-radius: 4px;
    }

    .activity-log-item {
      display: flex;
      align-items: flex-start;
      gap: 10px;
      font-size: 13px;
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
        <span>BANNER STATUS UTAMA & REKOMENDASI TINDAKAN</span>
      </div>

      <!-- HERO STATUS CARD -->
      <div class="hero-card">
        <div class="hero-header">KONDISI RODA GIGI SAAT INI :</div>
        
        <div class="condition-badges-row">
          <div class="status-badge badge-normal" id="badgeNormal" style="opacity: 0.35;">
            <span class="indicator-dot"></span>
            <span>NORMAL</span>
          </div>
          <div class="status-badge badge-damaged" id="badgeDamaged" style="opacity: 0.35;">
            <span class="indicator-dot"></span>
            <span>DAMAGED</span>
          </div>
          <div class="status-badge badge-scoring" id="badgeScoring" style="opacity: 0.35;">
            <span class="indicator-dot"></span>
            <span>SCORING</span>
          </div>
        </div>

        <div class="hero-details-list">
          <div class="hero-details-row">
            <span class="label">Tingkat Keyakinan (AI Confidence) :</span>
            <span class="value" id="aiConfidenceVal">100.0% (Random Forest Model)</span>
          </div>
          <div class="hero-details-row">
            <span class="label">Terakhir Diperbarui :</span>
            <span class="value" id="lastUpdatedVal">1 detik yang lalu (14:15:19 WIB)</span>
          </div>
          <div class="hero-details-row">
            <span class="label">Rekomendasi Preskriptif (CBM) :</span>
            <span class="value" id="prescriptiveAdviceVal">Kondisi transmisi gear beroperasi optimal. Tidak diperlukan tindakan perbaikan saat ini.</span>
          </div>
        </div>
      </div>

      <!-- KPI SECTION TITLE -->
      <div class="section-title">
        <span>KARTU METRIK UTAMA (4 KPI CARDS)</span>
      </div>

      <!-- 4 KPI CARDS -->
      <div class="kpi-grid">
        <!-- CARD 1 -->
        <div class="kpi-card">
          <div class="kpi-header">
            <i data-lucide="activity" style="width: 16px; height: 16px; color: #0d8a7c;"></i><span>RMS GETARAN</span>
          </div>
          <div class="kpi-value" id="kpiRmsVal">0.0262 g</div>
          <div class="kpi-footer" id="kpiRmsFooter">Status: Sangat Baik</div>
        </div>

        <!-- CARD 2 -->
        <div class="kpi-card">
          <div class="kpi-header">
            <i data-lucide="zap" style="width: 16px; height: 16px; color: #0d8a7c;"></i><span>PEAK-TO-PEAK</span>
          </div>
          <div class="kpi-value" id="kpiP2pVal">0.1911 g</div>
          <div class="kpi-footer" id="kpiP2pFooter">Beban Kejut: Rendah</div>
        </div>

        <!-- CARD 3 -->
        <div class="kpi-card">
          <div class="kpi-header">
            <i data-lucide="clock" style="width: 16px; height: 16px; color: #0d8a7c;"></i><span>UPTIME KONVEYOR</span>
          </div>
          <div class="kpi-value">04j : 25m : 12d</div>
          <div class="kpi-footer">Target Monitoring: 8 Jam</div>
        </div>

        <!-- CARD 4 -->
        <div class="kpi-card">
          <div class="kpi-header">
            <i data-lucide="bell" style="width: 16px; height: 16px; color: #0d8a7c;"></i><span>ANOMALI HARI INI</span>
          </div>
          <div class="kpi-value">0 Insiden</div>
          <div class="kpi-footer">Semua Aman</div>
        </div>
      </div>

      <!-- TELEMETRY SECTION TITLE -->
      <div class="section-title">
        <span>STATUS &amp; DETAIL KLASIFIKASI KERUSAKAN RODA GIGI (REAL-TIME CLASSIFICATION)</span>
      </div>

      <!-- TELEMETRY PREVIEW CARD -->
      <div class="telemetry-card">
        <div class="telemetry-header-bar">
          <div class="telemetry-title" style="display: flex; align-items: center; gap: 10px;">
            <i data-lucide="shield-check" style="width: 20px; height: 20px; color: #0d8a7c;"></i>
            <span>Evaluasi Kondisi Transmisi Roda Gigi (Standar ISO 10816-3 &amp; AI Edge)</span>
          </div>
                    <div id="classificationLiveStatusBadge" style="font-size: 13px; font-weight: 700; background: #ecfdf5; color: #065f46; padding: 4px 14px; border-radius: 8px; box-shadow: 0 0 0 0.5px #10b981 inset; display: flex; align-items: center; gap: 8px;">
            <span id="classificationLiveDot" style="width: 8px; height: 8px; border-radius: 50%; background: #10b981; box-shadow: 0 0 8px #10b981;"></span>
            <span id="classificationLiveBadgeText">MEMERIKSA KONEKSI SENSOR...</span>
          </div>
        </div>

        <!-- 3 KLASIFIKASI CARDS BERDAMPINGAN -->
        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 18px; margin: 18px 0;">
          
          <!-- KELAS 1: NORMAL -->
          <div id="cardClassNormal" style="border-radius: 14px; padding: 22px; border: 1px solid #cbd5e1; background: #ffffff; opacity: 0.45; transition: all 0.25s ease;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
              <span style="font-size: 12.5px; font-weight: 800; color: #065f46; letter-spacing: 0.05em; text-transform: uppercase;">&bull; NORMAL</span>
              <span id="dotClassNormal" style="width: 12px; height: 12px; border-radius: 50%; background: #94a3b8;"></span>
            </div>
            <div style="font-size: 26px; font-weight: 800; color: #065f46; margin-bottom: 8px;">NORMAL</div>
            <div style="font-size: 13px; color: #166534; font-weight: 600; line-height: 1.5; margin-bottom: 14px;">Kondisi Baru / Beroperasi Sangat Optimal (Zona A ISO 10816-3). Bebas keausan dan beban kejut rendah.</div>
            <div style="font-size: 11.5px; font-weight: 700; color: #047857; background: #dcfce7; padding: 4px 10px; border-radius: 6px; display: inline-block;">Ambang: Z_RMS &lt; 0.06g | P2P &lt; 0.12g</div>
          </div>

          <!-- KELAS 2: SCORING / TERKIKIS -->
          <div id="cardClassScoring" style="border-radius: 14px; padding: 22px; border: 1px solid #cbd5e1; background: #ffffff; opacity: 0.45; transition: all 0.25s ease;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
              <span style="font-size: 12.5px; font-weight: 800; color: #92400e; letter-spacing: 0.05em; text-transform: uppercase;">&bull; TERKIKIS</span>
              <span id="dotClassScoring" style="width: 12px; height: 12px; border-radius: 50%; background: #f59e0b;"></span>
            </div>
            <div style="font-size: 26px; font-weight: 800; color: #92400e; margin-bottom: 8px;">SCORING (TERKIKIS)</div>
            <div style="font-size: 13px; color: #78350f; font-weight: 600; line-height: 1.5; margin-bottom: 14px;">Peringatan Dini: Erosi addendum &amp; goresan permukaan gigi. Butuh pembersihan debris &amp; pelumasan ulang.</div>
            <div style="font-size: 11.5px; font-weight: 700; color: #b45309; background: #fef3c7; padding: 4px 10px; border-radius: 6px; display: inline-block;">Ambang: 0.06g &le; Z_RMS &lt; 0.12g | P2P &ge; 0.12g</div>
          </div>

          <!-- KELAS 3: DAMAGED / RUSAK -->
          <div id="cardClassDamaged" style="border-radius: 14px; padding: 22px; border: 1px solid #cbd5e1; background: #ffffff; opacity: 0.45; transition: all 0.25s ease;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
              <span style="font-size: 12.5px; font-weight: 800; color: #991b1b; letter-spacing: 0.05em; text-transform: uppercase;">&bull; RUSAK</span>
              <span id="dotClassDamaged" style="width: 12px; height: 12px; border-radius: 50%; background: #ef4444;"></span>
            </div>
            <div style="font-size: 26px; font-weight: 800; color: #991b1b; margin-bottom: 8px;">DAMAGED (RUSAK)</div>
            <div style="font-size: 13px; color: #7f1d1d; font-weight: 600; line-height: 1.5; margin-bottom: 14px;">Bahaya Kritis: Retak gigi atau patah pada dedendum. Konveyor harus segera dihentikan untuk ganti gear!</div>
            <div style="font-size: 11.5px; font-weight: 700; color: #b91c1c; background: #fee2e2; padding: 4px 10px; border-radius: 6px; display: inline-block;">Ambang: Z_RMS &ge; 0.12g | P2P &ge; 0.30g</div>
          </div>

        </div>

        <div class="telemetry-footer-bar">
          <span id="sensorStatusLabel">Indikator Sensor: <span id="sensorStatusDot" style="display:inline-block; width:8px; height:8px; border-radius:50%; background:#10b981; box-shadow:0 0 8px #10b981;"></span> <strong id="sensorStatusText" style="color: #0d8a7c;">ADXL345 (ONLINE - Hardware ESP32 Terhubung &amp; Streaming)</strong></span>
          <span>|</span>
          <span id="sensorSamplingRate">Sampling: 50 Hz</span>
          <span>|</span>
          <span id="sensorBufferWindow">Buffer: 50 Sampel / 1.0 Detik</span>
        </div>
      </div>

      <!-- BOTTOM SECTION TITLE -->
      <div class="section-title">
        <span>DUA KOLOM INFORMASI BAWAH:</span>
      </div>

      <!-- TWO COLUMNS BOTTOM -->
      <div class="bottom-two-cols">
        
        <!-- KOLOM KIRI: DISTRIBUSI PROBABILITAS -->
        <div class="info-card">
          <div class="info-card-header">
            <i data-lucide="cpu" style="width: 18px; height: 18px; color: #0d8a7c;"></i><span>DISTRIBUSI PROBABILITAS MODEL AI</span>
          </div>

          <div class="prob-rows-list">
            <!-- Normal -->
            <div class="prob-row">
              <span class="prob-label">Normal</span>
              <div class="prob-bar-track">
                <div class="prob-bar-fill" id="probNormalBar" style="width: 100%;"></div>
              </div>
              <span class="prob-pct" id="probNormalPct">100%</span>
            </div>

            <!-- Terkikis -->
            <div class="prob-row">
              <span class="prob-label">Terkikis</span>
              <div class="prob-bar-track">
                <div class="prob-bar-fill" id="probScoringBar" style="width: 0%;"></div>
              </div>
              <span class="prob-pct" id="probScoringPct">0%</span>
            </div>

            <!-- Rusak -->
            <div class="prob-row">
              <span class="prob-label">Rusak</span>
              <div class="prob-bar-track">
                <div class="prob-bar-fill" id="probDamagedBar" style="width: 0%;"></div>
              </div>
              <span class="prob-pct" id="probDamagedPct">0%</span>
            </div>
          </div>

          <div class="model-info-footer">
            Model: Random Forest | Latensi: 0.0086 ms
          </div>
        </div>

        <!-- KOLOM KANAN: LOG AKTIVITAS -->
        <div class="info-card">
          <div class="info-card-header">
            <i data-lucide="clipboard-list" style="width: 18px; height: 18px; color: #0d8a7c;"></i><span>LOG AKTIVITAS &amp; PERINGATAN TERAKHIR</span>
          </div>

          <ul class="activity-log-list">
            @php
              $dbIncidents = \App\Models\IncidentLog::orderBy('id', 'desc')->take(5)->get();
            @endphp
            @forelse($dbIncidents as $inc)
              <li class="activity-log-item">
                <span class="bullet-point" style="color: {{ $inc->severity === 'CRITICAL' ? '#ef4444' : '#f59e0b' }}; font-weight: bold;">&bull;</span>
                <span>{{ $inc->created_at ? $inc->created_at->format('H:i:s') : date('H:i:s') }} - [{{ $inc->condition_detected }}] Anomali {{ $inc->incident_code }} (Peak: {{ number_format($inc->peak_acceleration_g, 3) }}g) &bull; {{ \Illuminate\Support\Str::limit($inc->prescriptive_action, 55) }}</span>
              </li>
            @empty
              <li class="activity-log-item" id="emptyActivityLogItem">
                <span class="bullet-point" style="color: #10b981; font-weight: bold;">&bull;</span>
                <span>{{ date('H:i:s') }} - Sistem Gearsense aktif &bull; Transmisi beroperasi optimal tanpa insiden</span>
              </li>
            @endforelse
          </ul>
        </div>

      </div>

    </main>
  </div>

  <script>
    /**
     * =========================================================================
     * MODUL DASHBOARD UTAMA - GEARSENSE MONITORING
     * =========================================================================
     * STATUS INTEGRASI IOT:
     * - Hardware ESP32 & Modul Sensor ADXL345: DINONAKTIFKAN SEMENTARA (STANDBY)
     * - Data grafik dan telemetri saat ini beroperasi dalam mode simulasi lokal.
     * - Endpoint WebSocket/MQTT akan dihubungkan saat integrasi hardware telah selesai.
     * =========================================================================
     */

    // Inisialisasi ikon Lucide di seluruh dokumen
    // FUNGSI: lucide.createIcons()
    // TUJUAN: Merender ikon SVG berbasis atribut data-lucide pada elemen HTML.
    lucide.createIcons();

    /**
     * FUNGSI: Toggle Flip / Collapse Sidebar
     * TUJUAN: Membuka atau melipat bilah navigasi samping (sidebar) antara mode lebar (280px) dan mode ikon (84px).
     * CARA KERJA: Menambahkan atau menghapus kelas 'collapsed' pada elemen #mainSidebar,
     *            kemudian memanggil vibrationChart.resize() setelah transisi CSS selesai (310ms).
     */
    const mainSidebar = document.getElementById('mainSidebar');
    const flipSidebarBtn = document.getElementById('flipSidebarBtn');

    /**
     * FUNGSI: Toggle Dropdown Profil Pengguna
     * TUJUAN: Menampilkan atau menyembunyikan menu profil pengguna saat diklik.
     * CARA KERJA: Menambahkan kelas 'show' pada #userDropdownMenu dan menutupnya jika pengguna mengklik area luar.
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

      // Menutup dropdown jika pengguna mengklik di luar area menu profil
      document.addEventListener('click', (e) => {
        if (!userDropdownBtn.contains(e.target)) {
          userDropdownMenu.classList.remove('show');
          dropdownArrow.classList.remove('open');
        }
      });
    }

    /**
     * FUNGSI: handleLogout()
     * TUJUAN: Menangani konfirmasi dan proses keluar (logout) dari sesi pengguna Gearsense.
     * CARA KERJA: Menghapus token lokal dan melakukan submit form logout ke controller autentikasi.
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
     * FUNGSI: Inisialisasi Grafik Gelombang Getaran Real-Time Chart.js
     * TUJUAN: Menggambar canvas grafik getaran 3 sumbu (Z Radial, X Aksial, Y Tangensial).
     * STATUS IOT: Menampilkan kurva benchmark getaran konveyor normal.
     */
    /**
     * STATE & PIPELINE KLASIFIKASI WAKTU NYATA GEARSENSE
     * Fokus murni pada evaluasi klasifikasi kondisi roda gigi (Normal, Scoring, Damaged)
     */
    let latestTelemetryState = {
      isOnline: false,
      condition: 'NORMAL',
      confidence: 98.4,
      rmsZ: 0.026,
      p2pZ: 0.19
    };

    let previousCondition = '';

    /**
     * FUNGSI: fetchLiveTelemetryDashboard()
     * TUJUAN: Mengambil data telemetri getaran riil dari ESP32 & ADXL345 via API /api/telemetry/latest (RAM Cache <1ms)
     */
    let isFetchingDashboardTelemetry = false;

    async function fetchLiveTelemetryDashboard() {
      if (isFetchingDashboardTelemetry || document.hidden) return;
      isFetchingDashboardTelemetry = true;
      try {
        const response = await fetch('/api/telemetry/latest');
        if (!response.ok) return;
        const data = await response.json();

        const isOnline = (data && data.status === 'ONLINE' && data.is_online !== false);
        latestTelemetryState.isOnline = isOnline;

        const dot = document.getElementById('sensorStatusDot');
        const txt = document.getElementById('sensorStatusText');
        const liveDot = document.getElementById('classificationLiveDot');
        const liveBadge = document.getElementById('classificationLiveStatusBadge');
        const liveBadgeTxt = document.getElementById('classificationLiveBadgeText');

        if (isOnline) {
          latestTelemetryState.condition = (data.condition || 'NORMAL').toUpperCase();
          latestTelemetryState.confidence = parseFloat(data.confidence_score) || 95.0;
          latestTelemetryState.rmsZ = parseFloat(data.rms_z) || 0.026;
          latestTelemetryState.p2pZ = parseFloat(data.p2p_z) || 0.19;

          if (dot && txt) {
            dot.style.background = '#10b981';
            dot.style.boxShadow = '0 0 8px #10b981';
            txt.innerText = 'ADXL345 (ONLINE - Hardware ESP32 Streaming)';
            txt.style.color = '#0d8a7c';
          }

          if (liveDot && liveBadge && liveBadgeTxt) {
            liveDot.style.background = '#10b981';
            liveDot.style.boxShadow = '0 0 8px #10b981';
            liveBadge.style.background = '#ecfdf5';
            liveBadge.style.color = '#065f46';
            liveBadgeTxt.innerText = 'KLASIFIKASI AKTIF: ' + latestTelemetryState.condition;
          }

          // Update KPI Cards
          const kpiRms = document.getElementById('kpiRmsVal');
          const kpiRmsFoot = document.getElementById('kpiRmsFooter');
          const kpiP2p = document.getElementById('kpiP2pVal');
          const kpiP2pFoot = document.getElementById('kpiP2pFooter');

          if (kpiRms) kpiRms.innerText = latestTelemetryState.rmsZ.toFixed(4) + ' g';
          if (kpiP2p) kpiP2p.innerText = latestTelemetryState.p2pZ.toFixed(4) + ' g';

          // Update sampling rate dan buffer window secara dinamis dari sensor ESP32
          const sRate = document.getElementById('sensorSamplingRate');
          if (sRate) {
            sRate.innerText = 'Sampling: ' + (data.sampling_rate_hz || 50) + ' Hz';
          }
          const sBuf = document.getElementById('sensorBufferWindow');
          if (sBuf) {
            sBuf.innerText = 'Buffer: ' + (data.sampling_rate_hz || 50) + ' Sampel / 1.0 Detik';
          }

          // Update Condition Badges di Hero Banner
          const bNormal = document.getElementById('badgeNormal');
          const bScoring = document.getElementById('badgeScoring');
          const bDamaged = document.getElementById('badgeDamaged');
          const aiConf = document.getElementById('aiConfidenceVal');
          const lastUp = document.getElementById('lastUpdatedVal');
          const presc = document.getElementById('prescriptiveAdviceVal');

          if (aiConf) aiConf.innerText = latestTelemetryState.confidence.toFixed(1) + '% (Standar ISO 10816-3 & FIS Edge)';
          if (lastUp) lastUp.innerText = 'Real-Time (' + (data.formatted_time || 'Baru Saja') + ' WIB)';

          // 3 Kartu Klasifikasi Utama di Tengah Dashboard
          const cNormal = document.getElementById('cardClassNormal');
          const cScoring = document.getElementById('cardClassScoring');
          const cDamaged = document.getElementById('cardClassDamaged');

          // Reset status aktif
          if (bNormal) { bNormal.style.opacity = '0.35'; bNormal.style.boxShadow = 'none'; }
          if (bScoring) { bScoring.style.opacity = '0.35'; bScoring.style.boxShadow = 'none'; }
          if (bDamaged) { bDamaged.style.opacity = '0.35'; bDamaged.style.boxShadow = 'none'; }

          if (cNormal) { cNormal.style.opacity = '0.4'; cNormal.style.transform = 'none'; cNormal.style.boxShadow = 'none'; cNormal.style.border = '1px solid #cbd5e1'; cNormal.style.background = '#ffffff'; }
          if (cScoring) { cScoring.style.opacity = '0.4'; cScoring.style.transform = 'none'; cScoring.style.boxShadow = 'none'; cScoring.style.border = '1px solid #cbd5e1'; cScoring.style.background = '#ffffff'; }
          if (cDamaged) { cDamaged.style.opacity = '0.4'; cDamaged.style.transform = 'none'; cDamaged.style.boxShadow = 'none'; cDamaged.style.border = '1px solid #cbd5e1'; cDamaged.style.background = '#ffffff'; }

          if (latestTelemetryState.condition === 'DAMAGED') {
            if (bDamaged) { bDamaged.style.opacity = '1'; bDamaged.style.boxShadow = '0 0 14px rgba(239, 68, 68, 0.45)'; }
            if (cDamaged) {
              cDamaged.style.opacity = '1';
              cDamaged.style.transform = 'translateY(-3px)';
              cDamaged.style.boxShadow = '0 8px 24px rgba(239, 68, 68, 0.35)';
              cDamaged.style.border = '2px solid #ef4444';
              cDamaged.style.background = '#fef2f2';
            }
            if (kpiRmsFoot) kpiRmsFoot.innerText = 'Status: KRITIS (Getaran Tinggi)';
            if (kpiP2pFoot) kpiP2pFoot.innerText = 'Beban Kejut: TINGGI';
            if (presc) presc.innerText = 'Peringatan kerusakan parah roda gigi! Hentikan konveyor segera dan ganti modul gigi.';
            updateProbBars(2, 8, 90);
          } else if (latestTelemetryState.condition === 'SCORING') {
            if (bScoring) { bScoring.style.opacity = '1'; bScoring.style.boxShadow = '0 0 14px rgba(245, 158, 11, 0.45)'; }
            if (cScoring) {
              cScoring.style.opacity = '1';
              cScoring.style.transform = 'translateY(-3px)';
              cScoring.style.boxShadow = '0 8px 24px rgba(245, 158, 11, 0.35)';
              cScoring.style.border = '2px solid #f59e0b';
              cScoring.style.background = '#fffbeb';
            }
            if (kpiRmsFoot) kpiRmsFoot.innerText = 'Status: PERINGATAN (Keausan Gigi)';
            if (kpiP2pFoot) kpiP2pFoot.innerText = 'Beban Kejut: SEDANG';
            if (presc) presc.innerText = 'Peringatan keausan roda gigi terdeteksi. Lakukan pembersihan debris dan pelumasan ulang NLGI #2.';
            updateProbBars(12, 82, 6);
          } else {
            if (bNormal) { bNormal.style.opacity = '1'; bNormal.style.boxShadow = '0 0 14px rgba(16, 185, 129, 0.45)'; }
            if (cNormal) {
              cNormal.style.opacity = '1';
              cNormal.style.transform = 'translateY(-3px)';
              cNormal.style.boxShadow = '0 8px 24px rgba(16, 185, 129, 0.35)';
              cNormal.style.border = '2px solid #10b981';
              cNormal.style.background = '#f0fdf4';
            }
            if (kpiRmsFoot) kpiRmsFoot.innerText = 'Status: Sangat Baik (Zona A ISO)';
            if (kpiP2pFoot) kpiP2pFoot.innerText = 'Beban Kejut: Rendah';
            if (presc) presc.innerText = 'Kondisi transmisi gear beroperasi optimal. Tidak diperlukan tindakan perbaikan saat ini.';
            updateProbBars(96, 3, 1);
          }

          // Catat ke activity log jika kondisi berubah
          if (previousCondition && previousCondition !== latestTelemetryState.condition) {
            appendActivityLog('Klasifikasi Roda Gigi berubah: ' + previousCondition + '  &rarr;  ' + latestTelemetryState.condition + ' (Z_RMS: ' + latestTelemetryState.rmsZ.toFixed(3) + 'g)');
          }
          previousCondition = latestTelemetryState.condition;

        } else {
          // Hardware OFFLINE / STANDBY: Redupkan semua kartu kondisi (tidak ada yang aktif)
          const dot = document.getElementById('sensorStatusDot');
          const txt = document.getElementById('sensorStatusText');
          const liveDot = document.getElementById('classificationLiveDot');
          const liveBadge = document.getElementById('classificationLiveStatusBadge');
          const liveBadgeTxt = document.getElementById('classificationLiveBadgeText');

          if (dot && txt) {
            dot.style.background = '#ef4444';
            dot.style.boxShadow = '0 0 8px #ef4444';
            txt.innerText = 'ADXL345 (OFFLINE - Hardware ESP32 / Sensor Terputus)';
            txt.style.color = '#ef4444';
          }
          if (liveDot && liveBadge && liveBadgeTxt) {
            liveDot.style.background = '#ef4444';
            liveDot.style.boxShadow = '0 0 8px #ef4444';
            liveBadge.style.background = '#fef2f2';
            liveBadge.style.color = '#991b1b';
            liveBadgeTxt.innerText = 'STANDBY: SENSOR / ESP32 BELUM TERHUBUNG';
          }

          // Matikan glow pada seluruh kartu 3 status klasifikasi
          const cNormal = document.getElementById('cardClassNormal');
          const cScoring = document.getElementById('cardClassScoring');
          const cDamaged = document.getElementById('cardClassDamaged');
          const dNormal = document.getElementById('dotClassNormal');
          const dScoring = document.getElementById('dotClassScoring');
          const dDamaged = document.getElementById('dotClassDamaged');

          if (cNormal) { cNormal.style.opacity = '0.35'; cNormal.style.transform = 'none'; cNormal.style.boxShadow = 'none'; cNormal.style.border = '1px solid #cbd5e1'; cNormal.style.background = '#ffffff'; }
          if (cScoring) { cScoring.style.opacity = '0.35'; cScoring.style.transform = 'none'; cScoring.style.boxShadow = 'none'; cScoring.style.border = '1px solid #cbd5e1'; cScoring.style.background = '#ffffff'; }
          if (cDamaged) { cDamaged.style.opacity = '0.35'; cDamaged.style.transform = 'none'; cDamaged.style.boxShadow = 'none'; cDamaged.style.border = '1px solid #cbd5e1'; cDamaged.style.background = '#ffffff'; }

          if (dNormal) { dNormal.style.background = '#94a3b8'; dNormal.style.boxShadow = 'none'; }
          if (dScoring) { dScoring.style.background = '#94a3b8'; dScoring.style.boxShadow = 'none'; }
          if (dDamaged) { dDamaged.style.background = '#94a3b8'; dDamaged.style.boxShadow = 'none'; }

          // Redupkan badge hero banner
          const bNormal = document.getElementById('badgeNormal');
          const bScoring = document.getElementById('badgeScoring');
          const bDamaged = document.getElementById('badgeDamaged');
          const aiConf = document.getElementById('aiConfidenceVal');
          const lastUp = document.getElementById('lastUpdatedVal');
          const presc = document.getElementById('prescriptiveAdviceVal');

          if (bNormal) { bNormal.style.opacity = '0.3'; bNormal.style.boxShadow = 'none'; }
          if (bScoring) { bScoring.style.opacity = '0.3'; bScoring.style.boxShadow = 'none'; }
          if (bDamaged) { bDamaged.style.opacity = '0.3'; bDamaged.style.boxShadow = 'none'; }

          if (aiConf) aiConf.innerText = '-- (Menunggu Sinyal Sensor)';
          if (lastUp) lastUp.innerText = 'Sensor Terputus (Standby)';
          if (presc) presc.innerText = 'Perangkat keras ESP32 dan sensor ADXL345 belum terhubung. Silakan pasang kabel dan nyalakan ESP32 untuk memulai klasifikasi getaran.';

          // Reset KPI cards ke status Standby
          const kpiRms = document.getElementById('kpiRmsVal');
          const kpiRmsFoot = document.getElementById('kpiRmsFooter');
          const kpiP2p = document.getElementById('kpiP2pVal');
          const kpiP2pFoot = document.getElementById('kpiP2pFooter');

          if (kpiRms) kpiRms.innerText = '-- g';
          if (kpiRmsFoot) kpiRmsFoot.innerText = 'Status: Sensor Standby';
          if (kpiP2p) kpiP2p.innerText = '-- g';
          if (kpiP2pFoot) kpiP2pFoot.innerText = 'Beban Kejut: --';

          // Reset bar probabilitas
          updateProbBars(0, 0, 0);
        }
      } catch (err) {
      } finally {
        isFetchingDashboardTelemetry = false;
      }
    }

    function appendActivityLog(msg) {
      const list = document.querySelector('.activity-log-list');
      if (!list) return;

      // Hapus status default kosong jika ada
      const emptyItem = document.getElementById('emptyActivityLogItem');
      if (emptyItem) {
        emptyItem.remove();
      }

      const now = new Date();
      const timeStr = ('0'+now.getHours()).slice(-2) + ':' + ('0'+now.getMinutes()).slice(-2) + ':' + ('0'+now.getSeconds()).slice(-2);
      const li = document.createElement('li');
      li.className = 'activity-log-item';
      li.innerHTML = '<span class="bullet-point" style="color: #0d8a7c; font-weight: bold;">&bull;</span><span>' + timeStr + ' - ' + msg + '</span>';
      if (list.firstChild) {
        list.insertBefore(li, list.firstChild);
      } else {
        list.appendChild(li);
      }

      // Batasi tepat 5 data log terakhir agar tidak menumpuk ke bawah
      while (list.children.length > 5) {
        list.removeChild(list.lastChild);
      }
    }

    function updateProbBars(n, s, d) {
      const bn = document.getElementById('probNormalBar'), pn = document.getElementById('probNormalPct');
      const bs = document.getElementById('probScoringBar'), ps = document.getElementById('probScoringPct');
      const bd = document.getElementById('probDamagedBar'), pd = document.getElementById('probDamagedPct');
      if (bn && pn) { bn.style.width = n + '%'; pn.innerText = n + '%'; }
      if (bs && ps) { bs.style.width = s + '%'; ps.innerText = s + '%'; }
      if (bd && pd) { bd.style.width = d + '%'; pd.innerText = d + '%'; }
    }

    // Inisialisasi ikon Lucide
    if (typeof lucide !== 'undefined' && typeof lucide.createIcons === 'function') {
      lucide.createIcons();
    }

    // Eksekusi kilat saat pertama kali halaman dimuat
    fetchLiveTelemetryDashboard();
    // Polling kilat klasifikasi telemetri setiap 450ms (Snappy & Instant Response)
    setInterval(fetchLiveTelemetryDashboard, 450);

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
  </script>

  
</body>
</html>
