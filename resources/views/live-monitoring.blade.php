<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=1920, initial-scale=1.0">
  <title>Gearsense - Live Monitoring Getaran Real-Time</title>
  
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
        <span>KONTROL AKUISISI &amp; STATUS TELEMETRI LIVE</span>
      </div>

      <!-- HERO STATUS CARD: STREAM CONTROLS -->
      <div class="hero-card" style="padding: 22px 28px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 18px; flex-wrap: wrap; gap: 14px;">
          <div style="display: flex; align-items: center; gap: 12px;">
            <div id="streamStatusBadge" class="status-badge" style="cursor: default; padding: 8px 20px; background: #f8fafc; color: #475569; box-shadow: 0 0 0 1px #cbd5e1 inset;">
              <span class="indicator-dot" style="background: #94a3b8; box-shadow: none;"></span>
              <span id="streamStatusText">MEMERIKSA KONEKSI SENSOR ESP32...</span>
            </div>
            <button id="pauseResumeBtn" onclick="toggleStream()" style="background: #0d8a7c; color: #ffffff; border: none; border-radius: 12px; padding: 8px 18px; font-size: 13.5px; font-weight: 700; display: flex; align-items: center; gap: 8px; cursor: pointer; transition: all 0.2s ease; box-shadow: 0 2px 8px rgba(13, 138, 124, 0.25);">
              <i data-lucide="pause" id="pauseResumeIcon" style="width: 16px; height: 16px;"></i>
              <span id="pauseResumeText">Jeda Live Stream</span>
            </button>
          </div>

          <!-- INDIKATOR TEKNIS AKUISISI -->
          <div style="display: flex; align-items: center; gap: 12px; flex-wrap: wrap;">
            <div style="display: flex; align-items: center; gap: 8px; background: #ffffff; padding: 6px 14px; border-radius: 10px; box-shadow: 0 0 0 1px #ccfbf1 inset; font-size: 13px;">
              <span style="font-weight: 700; color: #042f2e;">Frekuensi Sampling:</span>
              <strong id="heroSamplingRateText" style="color: #0d8a7c; font-family: 'JetBrains Mono', monospace;">50 Hz</strong>
            </div>
            <div style="display: flex; align-items: center; gap: 8px; background: #ffffff; padding: 6px 14px; border-radius: 10px; box-shadow: 0 0 0 1px #ccfbf1 inset; font-size: 13px;">
              <span style="font-weight: 700; color: #042f2e;">Buffer Window:</span>
              <strong id="heroBufferWindowText" style="color: #0d8a7c; font-family: 'JetBrains Mono', monospace;">50 Sampel / 1.0 Detik</strong>
            </div>
          </div>
        </div>

        <div class="hero-details-list" style="border-top: 1px solid #ccfbf1; padding-top: 14px;">
          <div class="hero-details-row">
            <span class="label">Standar Getaran ISO 10816-3 :</span>
            <span class="value" id="heroIsoStandard" style="color: #64748b;">Memeriksa evaluasi getaran ISO...</span>
          </div>
          <div class="hero-details-row">
            <span class="label">Parameter Akuisisi Sensor :</span>
            <span class="value" id="heroSensorParam" style="color: #64748b;">ADXL345 (Memeriksa Bus I2C...)</span>
          </div>
          <div class="hero-details-row">
            <span class="label">Status IoT ESP32 &amp; Node :</span>
            <span class="value" id="heroIotNodeStatus" style="color: #64748b;">Memeriksa status node ESP32...</span>
          </div>
        </div>
      </div>

      <!-- KPI SECTION TITLE -->
      <div class="section-title">
        <span>METRIK TELEMETRI GETARAN REAL-TIME PER SUMBU (TRI-AXIAL METRICS)</span>
      </div>

      <!-- 4 KPI CARDS PER SUMBU -->
      <div class="kpi-grid">
        <!-- CARD 1: SUMBU Z (RADIAL) -->
        <div class="kpi-card" style="background: #f0fdfa; box-shadow: 0 0 0 1px #0284c7 inset, 0 8px 20px -2px rgba(2, 132, 199, 0.20), 0 2px 6px -1px rgba(15, 23, 42, 0.05);">
          <div class="kpi-header" style="color: #0369a1;">
            <i data-lucide="activity" style="width: 16px; height: 16px; color: #3b82f6;"></i><span>SUMBU Z (RADIAL)</span>
          </div>
          <div class="kpi-value" id="kpiZVal" style="color: #0369a1;">-- g</div>
          <div class="kpi-footer" id="kpiZFooter" style="color: #0284c7;">RMS: -- | Peak: --</div>
        </div>

        <!-- CARD 2: SUMBU X (AKSIAL) -->
        <div class="kpi-card" style="background: #f0fdfa; box-shadow: 0 0 0 1px #059669 inset, 0 8px 20px -2px rgba(5, 150, 105, 0.20), 0 2px 6px -1px rgba(15, 23, 42, 0.05);">
          <div class="kpi-header" style="color: #065f46;">
            <i data-lucide="activity" style="width: 16px; height: 16px; color: #10b981;"></i><span>SUMBU X (AKSIAL)</span>
          </div>
          <div class="kpi-value" id="kpiXVal" style="color: #065f46;">-- g</div>
          <div class="kpi-footer" id="kpiXFooter" style="color: #059669;">RMS: -- | Peak: --</div>
        </div>

        <!-- CARD 3: SUMBU Y (TANGENSIAL) -->
        <div class="kpi-card" style="background: #f0fdfa; box-shadow: 0 0 0 1px #d97706 inset, 0 8px 20px -2px rgba(217, 119, 6, 0.20), 0 2px 6px -1px rgba(15, 23, 42, 0.05);">
          <div class="kpi-header" style="color: #92400e;">
            <i data-lucide="activity" style="width: 16px; height: 16px; color: #f59e0b;"></i><span>SUMBU Y (TANGENSIAL)</span>
          </div>
          <div class="kpi-value" id="kpiYVal" style="color: #92400e;">-- g</div>
          <div class="kpi-footer" id="kpiYFooter" style="color: #d97706;">RMS: -- | Peak: --</div>
        </div>

        <!-- CARD 4: RESULTAN MAGNITUDE -->
        <div class="kpi-card" style="background: #f0fdfa; box-shadow: 0 0 0 1px #14b8a6 inset, 0 8px 20px -2px rgba(20, 184, 166, 0.25), 0 2px 6px -1px rgba(15, 23, 42, 0.06);">
          <div class="kpi-header">
            <i data-lucide="zap" style="width: 16px; height: 16px; color: #0d8a7c;"></i><span>RESULTAN TOTAL</span>
          </div>
          <div class="kpi-value" id="kpiMagVal">-- g</div>
          <div class="kpi-footer" id="kpiMagFooter">Peak-to-Peak: --</div>
        </div>
      </div>

      <!-- CLASSIFICATION STREAM SECTION TITLE -->
      <div class="section-title">
        <span>ALIRAN HASIL KLASIFIKASI KONDISI RODA GIGI (REAL-TIME CLASSIFICATION STREAM)</span>
      </div>

      <!-- MAIN CLASSIFICATION STREAM CARD -->
      <div class="telemetry-card" style="padding: 24px 28px;">
        <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #ccfbf1; padding-bottom: 16px; margin-bottom: 20px; flex-wrap: wrap; gap: 14px;">
          <div style="display: flex; align-items: center; gap: 14px;">
            <div id="liveClassMainBadge" style="padding: 10px 24px; border-radius: 12px; font-size: 18px; font-weight: 800; background: #f8fafc; color: #64748b; box-shadow: 0 0 0 1px #cbd5e1 inset; display: flex; align-items: center; gap: 10px; transition: all 0.25s ease;">
              <span id="liveClassMainDot" style="width: 12px; height: 12px; border-radius: 50%; background: #94a3b8; box-shadow: none;"></span>
              <span id="liveClassMainText">STATUS: MENUNGGU DATA SENSOR</span>
            </div>
            <div style="font-size: 13.5px; color: #64748b; font-weight: 600;">
              <span>Keyakinan AI: <strong id="liveConfidenceVal" style="color: #0d8a7c;">98.4%</strong></span> &bull; 
              <span>Latensi Inferensi: <strong style="color: #0d8a7c;">&lt; 5 ms (Edge)</strong></span>
            </div>
          </div>

          <div style="display: flex; align-items: center; gap: 12px;">
            <span style="font-size: 13px; font-weight: 700; color: #042f2e;">Node IoT:</span>
            <span style="font-family: 'JetBrains Mono', monospace; font-size: 13px; font-weight: 700; background: #f0fdfa; color: #0d8a7c; padding: 4px 10px; border-radius: 8px; border: 1px solid #ccfbf1;">ESP32-GEARSENSE-01</span>
            <span id="liveHeartbeatBadge" style="font-size: 12px; font-weight: 700; color: #065f46; background: #dcfce7; padding: 4px 10px; border-radius: 8px;">Heartbeat: 0s lalu</span>
          </div>
        </div>

        <!-- LIVE CLASSIFICATION STREAM TABLE -->
        <div style="margin-bottom: 16px;">
          <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
            <span style="font-size: 13.5px; font-weight: 700; color: #0f172a; text-transform: uppercase; letter-spacing: 0.03em;">Riwayat Aliran Hasil Klasifikasi Telemetri (10 Paket Terakhir)</span>
            <button onclick="clearClassTable()" style="background: transparent; border: none; color: #64748b; font-size: 12px; font-weight: 700; cursor: pointer; text-decoration: underline;">Bersihkan Riwayat</button>
          </div>

          <div style="background: #ffffff; border-radius: 12px; border: 1px solid #e2e8f0; overflow: hidden; box-shadow: 0 2px 8px rgba(15, 23, 42, 0.04);">
            <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 13px;">
              <thead>
                <tr style="background: #f8fafc; border-bottom: 1px solid #e2e8f0; color: #475569; font-weight: 700; font-size: 12px; text-transform: uppercase; letter-spacing: 0.03em;">
                  <th style="padding: 12px 16px;">Waktu (WIB)</th>
                  <th style="padding: 12px 16px;">Klasifikasi Kondisi</th>
                  <th style="padding: 12px 16px;">RMS Getaran (Z)</th>
                  <th style="padding: 12px 16px;">Peak-to-Peak (Z)</th>
                  <th style="padding: 12px 16px;">AI Confidence</th>
                  <th style="padding: 12px 16px;">Tindakan Preskriptif (CBM)</th>
                </tr>
              </thead>
              <tbody id="liveClassTableBody">
                <tr style="border-bottom: 1px solid #f1f5f9;">
                  <td style="padding: 12px 16px; font-family: 'JetBrains Mono', monospace; font-weight: 700; color: #0f172a;">Menghubungkan...</td>
                  <td style="padding: 12px 16px;"><span style="background: #ecfdf5; color: #065f46; font-weight: 800; font-size: 11px; padding: 4px 10px; border-radius: 6px; box-shadow: 0 0 0 1px #10b981 inset;">STREAMING AKTIF</span></td>
                  <td style="padding: 12px 16px; font-family: 'JetBrains Mono', monospace;">--</td>
                  <td style="padding: 12px 16px; font-family: 'JetBrains Mono', monospace;">--</td>
                  <td style="padding: 12px 16px; font-weight: 700;">--</td>
                  <td style="padding: 12px 16px; color: #64748b;">Menerima aliran data telemetri dari node ESP32...</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <div class="telemetry-footer-bar" style="border-top: 1px solid #f1f5f9; padding-top: 14px;">
          <span id="scopeSensorBadge">Status Sensor: <span id="scopeSensorDot" style="display:inline-block; width:8px; height:8px; border-radius:50%; background:#10b981; box-shadow:0 0 6px #10b981;"></span> <strong id="scopeSensorText" style="color: #0d8a7c;">ADXL345 (ONLINE - Hardware ESP32 Streaming)</strong></span>
          <span>|</span>
          <span>Sampling Akuisisi: <strong id="liveSamplingRate">50 Hz</strong></span>
          <span>|</span>
          <span>Metode Inferensi: <strong>Standar ISO 10816-3 &amp; FIS Edge</strong></span>
          <span>|</span>
          <span>Buffer: <strong>50 Sampel/Window</strong></span>
        </div>
      </div>

      <!-- CLASSIFICATION CRITERIA FULL WIDTH SECTION -->
      <div class="section-title">
        <span>STANDAR AMBANG BATAS KLASIFIKASI GETARAN (ISO 10816-3 &amp; FUZZY INFERENCE SYSTEM)</span>
      </div>

      <!-- FULL WIDTH CARD: KRITERIA KLASIFIKASI STANDAR ISO 10816-3 & FIS -->
      <div class="info-card" style="width: 100%; margin-bottom: 20px;">
        <div class="info-card-header" style="justify-content: space-between; align-items: center; border-bottom: 1px solid #ccfbf1; padding-bottom: 14px; margin-bottom: 18px;">
          <div style="display: flex; align-items: center; gap: 10px;">
            <i data-lucide="award" style="width: 20px; height: 20px; color: #0d8a7c;"></i>
            <span style="font-size: 15px; font-weight: 700; color: #042f2e;">KRITERIA KLASIFIKASI STANDAR ISO 10816-3 &amp; FIS</span>
          </div>
          <div style="display: flex; align-items: center; gap: 10px;">
            <span style="font-size: 12px; color: #0f766e; font-weight: 700; background: #ccfbf1; padding: 4px 12px; border-radius: 8px;">Evaluasi Getaran Sumbu Z (Radial)</span>
            <span style="font-size: 12px; color: #0369a1; font-weight: 700; background: #e0f2fe; padding: 4px 12px; border-radius: 8px;">Edge Inferensi ESP32</span>
          </div>
        </div>

        <!-- 3 ZONA BERJAJAR SECARA HORIZONTAL KE SAMPING -->
        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 18px; margin-bottom: 18px;">
          
          <!-- ZONA A: NORMAL -->
          <div style="padding: 16px 20px; background: #f0fdf4; border-radius: 14px; border: 1px solid #bbf7d0; box-shadow: 0 2px 6px rgba(22, 101, 52, 0.04); display: flex; flex-direction: column; justify-content: space-between; gap: 14px; transition: transform 0.2s ease;">
            <div>
              <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px;">
                <div style="display: flex; align-items: center; gap: 8px;">
                  <span style="width: 10px; height: 10px; border-radius: 50%; background: #16a34a; box-shadow: 0 0 6px rgba(22, 163, 74, 0.5);"></span>
                  <strong style="color: #166534; font-size: 14px; letter-spacing: 0.3px;">ZONA A (NORMAL)</strong>
                </div>
                <span style="font-family: 'JetBrains Mono', monospace; font-size: 12px; font-weight: 700; color: #166534; background: #dcfce7; padding: 3px 10px; border-radius: 6px; border: 1px solid #86efac;">Z_RMS &lt; 0.06g | P2P &lt; 0.12g</span>
              </div>
              <p style="font-size: 12.5px; color: #15803d; line-height: 1.5; margin: 0;">
                Kondisi mesin baru atau beroperasi sangat optimal. Amplitudo getaran sangat rendah dan transmisi gear beroperasi bebas dari anomali.
              </p>
            </div>
            <div style="font-size: 11.5px; font-weight: 700; color: #166534; padding-top: 10px; border-top: 1px dashed #bbf7d0; display: flex; justify-content: space-between;">
              <span>Status: Aman &amp; Optimal</span>
              <span>Tindakan: Monitoring Rutin</span>
            </div>
          </div>

          <!-- ZONA B/C: SCORING / TERKIKIS -->
          <div style="padding: 16px 20px; background: #fffbeb; border-radius: 14px; border: 1px solid #fde68a; box-shadow: 0 2px 6px rgba(180, 83, 9, 0.04); display: flex; flex-direction: column; justify-content: space-between; gap: 14px; transition: transform 0.2s ease;">
            <div>
              <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px;">
                <div style="display: flex; align-items: center; gap: 8px;">
                  <span style="width: 10px; height: 10px; border-radius: 50%; background: #eab308; box-shadow: 0 0 6px rgba(234, 179, 8, 0.5);"></span>
                  <strong style="color: #92400e; font-size: 14px; letter-spacing: 0.3px;">ZONA B/C (SCORING / TERKIKIS)</strong>
                </div>
                <span style="font-family: 'JetBrains Mono', monospace; font-size: 12px; font-weight: 700; color: #92400e; background: #fef3c7; padding: 3px 10px; border-radius: 6px; border: 1px solid #fcd34d;">0.06g &le; Z_RMS &lt; 0.12g | P2P &ge; 0.12g</span>
              </div>
              <p style="font-size: 12.5px; color: #b45309; line-height: 1.5; margin: 0;">
                Indikasi erosi addendum / keausan permukaan gigi gear sedang. Getaran mengalami peningkatan akibat gesekan antarmuka gigi.
              </p>
            </div>
            <div style="font-size: 11.5px; font-weight: 700; color: #92400e; padding-top: 10px; border-top: 1px dashed #fde68a; display: flex; justify-content: space-between;">
              <span>Status: Peringatan / Waspada</span>
              <span>Tindakan: Pelumasan &amp; Jadwalkan CBM</span>
            </div>
          </div>

          <!-- ZONA D: DAMAGED / RUSAK KRITIS -->
          <div style="padding: 16px 20px; background: #fef2f2; border-radius: 14px; border: 1px solid #fecaca; box-shadow: 0 2px 6px rgba(185, 28, 28, 0.04); display: flex; flex-direction: column; justify-content: space-between; gap: 14px; transition: transform 0.2s ease;">
            <div>
              <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px;">
                <div style="display: flex; align-items: center; gap: 8px;">
                  <span style="width: 10px; height: 10px; border-radius: 50%; background: #dc2626; box-shadow: 0 0 6px rgba(220, 38, 38, 0.5);"></span>
                  <strong style="color: #991b1b; font-size: 14px; letter-spacing: 0.3px;">ZONA D (DAMAGED / RUSAK KRITIS)</strong>
                </div>
                <span style="font-family: 'JetBrains Mono', monospace; font-size: 12px; font-weight: 700; color: #991b1b; background: #fee2e2; padding: 3px 10px; border-radius: 6px; border: 1px solid #fca5a5;">Z_RMS &ge; 0.12g | P2P &ge; 0.30g</span>
              </div>
              <p style="font-size: 12.5px; color: #b91c1c; line-height: 1.5; margin: 0;">
                Retak, chipping, atau patah gigi transmisi. Amplitudo getaran dan beban kejut melonjak tinggi, berisiko fatal pada sistem transmisi.
              </p>
            </div>
            <div style="font-size: 11.5px; font-weight: 700; color: #991b1b; padding-top: 10px; border-top: 1px dashed #fecaca; display: flex; justify-content: space-between;">
              <span>Status: Bahaya / Kritis</span>
              <span>Tindakan: Segera Hentikan &amp; Ganti Komponen</span>
            </div>
          </div>

        </div>

        <!-- FOOTER METADATA -->
        <div class="model-info-footer" style="display: flex; justify-content: space-between; align-items: center; font-size: 12.5px; color: #64748b; border-top: 1px solid #f1f5f9; padding-top: 14px;">
          <span>Standar Referensi: <strong style="color: #0d8a7c;">ISO 10816-3 (Mechanical Vibration Evaluation)</strong> &bull; Metrik: <strong style="color: #0d8a7c;">RMS Percepatan Getaran (g) Sumbu Z</strong></span>
          <span>Klasifikasi Otomatis: <strong style="color: #0d8a7c;">Edge Fuzzy Inference System (FIS) pada Mikrokontroler ESP32</strong></span>
        </div>
      </div>
    </main>
  </div>

        

  <script>
    /**
     * =========================================================================
     * MODUL LIVE MONITORING TELEMETRI - GEARSENSE
     * =========================================================================
     * STATUS INTEGRASI IOT:
     * - Hardware ESP32 & Modul Sensor ADXL345: DINONAKTIFKAN SEMENTARA (STANDBY)
     * - Default stream dimulai dalam status DIJEDA / STANDBY (isStreamPaused = true)
     * - Pengguna dapat mengklik tombol 'Mulai Simulasi Offline' untuk melihat animasi preview
     * - Handler telemetri asli (WebSocket / MQTT) telah disediakan dalam struktur placeholder
     * =========================================================================
     */

    // Inisialisasi ikon Lucide
    // FUNGSI: lucide.createIcons()
    // TUJUAN: Merender ikon visual SVG di seluruh halaman.
    lucide.createIcons();

    /**
     * FUNGSI: Toggle Flip / Collapse Sidebar
     * TUJUAN: Melipat bilah navigasi samping antara lebar 280px dan 84px.
     * CARA KERJA: Mengganti kelas 'collapsed' pada sidebar dan meresize grafik canvas.
     */
    const mainSidebar = document.getElementById('mainSidebar');
    const flipSidebarBtn = document.getElementById('flipSidebarBtn');

    /**
     * FUNGSI: Toggle Dropdown Profil Pengguna
     * TUJUAN: Menampilkan atau menyembunyikan menu akun pengguna.
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
     * TUJUAN: Keluar dari akun dan kembali ke halaman login.
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
     * TUJUAN: Menampilkan waktu sistem terkini (WIB) pada bar atas setiap detik.
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

    // =========================================================================
    // VARIABEL STATE LIVE MONITORING
    // =========================================================================
    let isStreamPaused = false;
    let isFetchingScopeTelemetry = false;
    let previousLogTime = '';

    let latestScopeState = {
      isOnline: false,
      condition: 'NORMAL',
      confidence: 98.4,
      rmsZ: 0.026,
      p2pZ: 0.19,
      samplingRate: 50
    };

    /**
     * FUNGSI: toggleStream()
     * TUJUAN: Menjeda / melanjutkan pembaruan aliran telemetri real-time di tabel
     */
    function toggleStream() {
      isStreamPaused = !isStreamPaused;
      const btn = document.getElementById('pauseResumeBtn');
      const icon = document.getElementById('pauseResumeIcon');
      const txt = document.getElementById('pauseResumeText');

      if (isStreamPaused) {
        if (btn) {
          btn.style.background = '#0284c7';
          btn.style.boxShadow = '0 2px 8px rgba(2, 132, 199, 0.3)';
        }
        if (txt) txt.innerText = 'Lanjutkan Live Stream';
        if (icon) {
          icon.setAttribute('data-lucide', 'play');
          lucide.createIcons();
        }
      } else {
        if (btn) {
          btn.style.background = '#0d8a7c';
          btn.style.boxShadow = '0 2px 8px rgba(13, 138, 124, 0.25)';
        }
        if (txt) txt.innerText = 'Jeda Live Stream';
        if (icon) {
          icon.setAttribute('data-lucide', 'pause');
          lucide.createIcons();
        }
        fetchLiveTelemetryScope();
      }
    }

    async function fetchLiveTelemetryScope() {
      if (isStreamPaused || isFetchingScopeTelemetry || document.hidden) return;
      isFetchingScopeTelemetry = true;
      const badge = document.getElementById('streamStatusBadge');
      const statusText = document.getElementById('streamStatusText');
      const scopeDot = document.getElementById('scopeSensorDot');
      const scopeTxt = document.getElementById('scopeSensorText');
      const mainBadge = document.getElementById('liveClassMainBadge');
      const mainDot = document.getElementById('liveClassMainDot');
      const mainText = document.getElementById('liveClassMainText');
      const confVal = document.getElementById('liveConfidenceVal');
      const hbBadge = document.getElementById('liveHeartbeatBadge');
      const heroIso = document.getElementById('heroIsoStandard');
      const heroSensor = document.getElementById('heroSensorParam');
      const heroIotNode = document.getElementById('heroIotNodeStatus');
      const footZ = document.getElementById('kpiZFooter');
      const footX = document.getElementById('kpiXFooter');
      const footY = document.getElementById('kpiYFooter');
      const footMag = document.getElementById('kpiMagFooter');

      try {
        const response = await fetch('/api/telemetry/latest');
        if (!response.ok) return;
        const json = await response.json();

        const isOnline = (json && json.status === 'ONLINE' && json.is_online !== false);
        latestScopeState.isOnline = isOnline;

        if (isOnline) {
          latestScopeState.rawZ = parseFloat(json.raw_z) || 0.85;
          latestScopeState.rawX = parseFloat(json.raw_x) || 0.01;
          latestScopeState.rawY = parseFloat(json.raw_y) || 0.02;
          latestScopeState.rmsZ = parseFloat(json.rms_z) || 0.026;
          latestScopeState.p2pZ = parseFloat(json.p2p_z) || 0.19;
          latestScopeState.condition = (json.condition || 'NORMAL').toUpperCase();
          latestScopeState.confidence = parseFloat(json.confidence_score) || 98.4;
          latestScopeState.samplingRate = parseInt(json.sampling_rate_hz) || 50;

          const newMag = Math.sqrt(latestScopeState.rawZ*latestScopeState.rawZ + latestScopeState.rawX*latestScopeState.rawX + latestScopeState.rawY*latestScopeState.rawY);

          // Update KPI Cards
          const elZ = document.getElementById('kpiZVal');
          const elX = document.getElementById('kpiXVal');
          const elY = document.getElementById('kpiYVal');
          const elMag = document.getElementById('kpiMagVal');
          const elRate = document.getElementById('liveSamplingRate');

          if (elZ) elZ.innerText = latestScopeState.rawZ.toFixed(4) + ' g';
          if (elX) elX.innerText = latestScopeState.rawX.toFixed(4) + ' g';
          if (elY) elY.innerText = latestScopeState.rawY.toFixed(4) + ' g';
          if (elMag) elMag.innerText = newMag.toFixed(4) + ' g';
          if (elRate) elRate.innerText = (latestScopeState.samplingRate || 50) + ' Hz';
          const heroRate = document.getElementById('heroSamplingRateText');
          if (heroRate) heroRate.innerText = (latestScopeState.samplingRate || 50) + ' Hz';
          const heroBuf = document.getElementById('heroBufferWindowText');
          if (heroBuf) heroBuf.innerText = (latestScopeState.samplingRate || 50) + ' Sampel / 1.0 Detik';
          if (confVal) confVal.innerText = latestScopeState.confidence.toFixed(1) + '%';
          if (hbBadge) hbBadge.innerText = 'Heartbeat: ' + (json.diff_seconds || 0) + 's lalu';

          // Update Status IoT ESP32 & Node secara dinamis
          const nodeName = json.node_code || 'ESP32-GEARSENSE-01';
          const ipAddr = json.ip_address || '192.168.100.8';
          if (heroIotNode) {
            heroIotNode.innerHTML = `Node: <strong>${nodeName}</strong> | IP: <strong>${ipAddr}</strong> | Status: <strong style="color: #059669;">ONLINE (WiFi Connected)</strong>`;
          }

          // Update Standar Evaluasi Getaran ISO 10816-3 secara dinamis berdasarkan RMS Z
          if (heroIso) {
            const rmsVal = latestScopeState.rmsZ;
            let isoText = '';
            let isoColor = '#059669';
            if (rmsVal < 0.05) {
              isoText = 'Zona A (Kondisi Mesin Baru / Beroperasi Sangat Optimal, < 0.05g RMS)';
              isoColor = '#059669';
            } else if (rmsVal < 0.10) {
              isoText = 'Zona B (Kondisi Mesin Masih Dapat Diterima Tanpa Batasan, 0.05g - 0.10g RMS)';
              isoColor = '#0284c7';
            } else if (rmsVal < 0.15) {
              isoText = 'Zona C (Kondisi Mesin Waspada / Tidak Memuaskan, 0.10g - 0.15g RMS)';
              isoColor = '#d97706';
            } else {
              isoText = 'Zona D (Batas Bahaya / Kerusakan Kritis, \u2265 0.15g RMS)';
              isoColor = '#dc2626';
            }
            heroIso.innerText = isoText;
            heroIso.style.color = isoColor;
          }

          // Update Parameter Sensor secara dinamis
          if (heroSensor) {
            heroSensor.innerHTML = `ADXL345 <span style="font-size: 12px; font-weight: 600; color: #047857;">(I2C 0x53 Fast Mode 400kHz | Range \u00B116g | 200 Hz Sampling)</span>`;
          }

          // Update KPI Card Footers secara dinamis
          if (footZ) footZ.innerText = `RMS: ${latestScopeState.rmsZ.toFixed(4)}g | Peak: ${latestScopeState.p2pZ.toFixed(4)}g`;
          if (footX) footX.innerText = `Raw: ${latestScopeState.rawX.toFixed(4)}g | P2P: ${(parseFloat(json.p2p_x) || 0).toFixed(4)}g`;
          if (footY) footY.innerText = `Raw: ${latestScopeState.rawY.toFixed(4)}g | P2P: ${(parseFloat(json.p2p_y) || 0).toFixed(4)}g`;
          if (footMag) footMag.innerText = `Peak-to-Peak (P2P Z): ${latestScopeState.p2pZ.toFixed(4)} g`;

          // Update Top Status Badge
          if (badge && statusText) {
            badge.style.background = '#ecfdf5';
            badge.style.color = '#065f46';
            badge.style.boxShadow = '0 0 0 0.5px #10b981 inset';
            statusText.innerText = 'ONLINE (HARDWARE ESP32 & ADXL345 TERHUBUNG)';
            const dot = badge.querySelector('.indicator-dot');
            if (dot) {
              dot.style.background = '#10b981';
              dot.style.boxShadow = '0 0 8px #10b981';
            }
          }

          if (scopeDot && scopeTxt) {
            scopeDot.style.background = '#10b981';
            scopeDot.style.boxShadow = '0 0 8px #10b981';
            scopeTxt.innerText = 'ADXL345 (ONLINE - Hardware ESP32 Streaming)';
            scopeTxt.style.color = '#0d8a7c';
          }

          // Update Main Classification Hero Badge
          if (mainBadge && mainDot && mainText) {
            if (latestScopeState.condition === 'DAMAGED') {
              mainBadge.style.background = '#fef2f2';
              mainBadge.style.color = '#991b1b';
              mainBadge.style.boxShadow = '0 0 0 2px #ef4444 inset, 0 6px 20px rgba(239, 68, 68, 0.35)';
              mainDot.style.background = '#ef4444';
              mainDot.style.boxShadow = '0 0 12px #ef4444';
              mainText.innerText = 'KLASIFIKASI: DAMAGED (RUSAK KRITIS)';
            } else if (latestScopeState.condition === 'SCORING') {
              mainBadge.style.background = '#fffbeb';
              mainBadge.style.color = '#92400e';
              mainBadge.style.boxShadow = '0 0 0 2px #f59e0b inset, 0 6px 20px rgba(245, 158, 11, 0.35)';
              mainDot.style.background = '#f59e0b';
              mainDot.style.boxShadow = '0 0 12px #f59e0b';
              mainText.innerText = 'KLASIFIKASI: SCORING (TERKIKIS)';
            } else {
              mainBadge.style.background = '#ecfdf5';
              mainBadge.style.color = '#065f46';
              mainBadge.style.boxShadow = '0 0 0 2px #10b981 inset, 0 6px 20px rgba(16, 185, 129, 0.3)';
              mainDot.style.background = '#10b981';
              mainDot.style.boxShadow = '0 0 12px #10b981';
              mainText.innerText = 'KLASIFIKASI: NORMAL (OPTIMAL)';
            }
          }

          // Tambahkan ke tabel riwayat klasifikasi
          const curTime = json.formatted_time || new Date().toLocaleTimeString('id-ID');
          if (curTime !== previousLogTime) {
            appendClassificationTableRow(curTime, latestScopeState.condition, latestScopeState.rmsZ, latestScopeState.p2pZ, latestScopeState.confidence);
            previousLogTime = curTime;
          }

        } else {
          // SENSOR OFFLINE
          if (heroIotNode) {
            const nodeName = (json && json.node_code) ? json.node_code : 'ESP32-GEARSENSE-01';
            heroIotNode.innerHTML = `Node: <strong>${nodeName}</strong> | Status: <strong style="color: #dc2626;">OFFLINE (Terputus / Belum Terhubung)</strong>`;
          }
          if (heroIso) {
            heroIso.innerText = 'STANDBY (Menunggu Sinyal Getaran Sensor ESP32)';
            heroIso.style.color = '#64748b';
          }
          if (heroSensor) {
            heroSensor.innerHTML = `ADXL345 <span style="font-size: 12px; font-weight: 600; color: #dc2626;">(Sensor Offline / Tidak Terdeteksi)</span>`;
          }
          if (footZ) footZ.innerText = 'RMS: -- | Peak: --';
          if (footX) footX.innerText = 'Raw: -- | P2P: --';
          if (footY) footY.innerText = 'Raw: -- | P2P: --';
          if (footMag) footMag.innerText = 'Peak-to-Peak: --';

          if (badge && statusText) {
            badge.style.background = '#fef2f2';
            badge.style.color = '#991b1b';
            badge.style.boxShadow = '0 0 0 0.5px #ef4444 inset';
            statusText.innerText = 'OFFLINE (SENSOR TERPUTUS / KABEL LEPAS)';
            const dot = badge.querySelector('.indicator-dot');
            if (dot) {
              dot.style.background = '#ef4444';
              dot.style.boxShadow = '0 0 8px #ef4444';
            }
          }
          if (scopeDot && scopeTxt) {
            scopeDot.style.background = '#ef4444';
            scopeDot.style.boxShadow = '0 0 8px #ef4444';
            scopeTxt.innerText = 'ADXL345 (OFFLINE - Sensor / ESP32 Terputus)';
            scopeTxt.style.color = '#ef4444';
          }
          if (hbBadge) {
            hbBadge.innerText = 'Heartbeat: Terputus';
            hbBadge.style.color = '#991b1b';
            hbBadge.style.background = '#fee2e2';
          }
          if (mainBadge && mainDot && mainText) {
            mainBadge.style.background = '#f8fafc';
            mainBadge.style.color = '#64748b';
            mainBadge.style.boxShadow = '0 0 0 1px #cbd5e1 inset';
            mainDot.style.background = '#94a3b8';
            mainDot.style.boxShadow = 'none';
            mainText.innerText = 'STATUS: STANDBY (SENSOR TERPUTUS)';
          }
          if (confVal) confVal.innerText = '--';

          // Reset KPI kartu ke standby
          const elZ = document.getElementById('kpiZVal');
          const elX = document.getElementById('kpiXVal');
          const elY = document.getElementById('kpiYVal');
          const elMag = document.getElementById('kpiMagVal');
          if (elZ) elZ.innerText = '-- g';
          if (elX) elX.innerText = '-- g';
          if (elY) elY.innerText = '-- g';
          if (elMag) elMag.innerText = '-- g';
        }
      } catch (e) {
      } finally {
        isFetchingScopeTelemetry = false;
      }
    }

    function appendClassificationTableRow(timeStr, cond, rms, p2p, conf) {
      const tbody = document.getElementById('liveClassTableBody');
      if (!tbody) return;

      const emptyRow = document.getElementById('emptyClassRow');
      if (emptyRow && emptyRow.parentNode) {
        emptyRow.parentNode.removeChild(emptyRow);
      }

      let badgeHtml = '';
      let actionText = '';

      if (cond === 'DAMAGED') {
        badgeHtml = '<span style="background: #fee2e2; color: #991b1b; font-weight: 800; font-size: 11px; padding: 4px 10px; border-radius: 6px; box-shadow: 0 0 0 1px #ef4444 inset;">DAMAGED (RUSAK)</span>';
        actionText = '<strong style="color: #dc2626;">STOP KONVEYOR!</strong> Ganti modul roda gigi segera.';
      } else if (cond === 'SCORING') {
        badgeHtml = '<span style="background: #fef3c7; color: #92400e; font-weight: 800; font-size: 11px; padding: 4px 10px; border-radius: 6px; box-shadow: 0 0 0 1px #f59e0b inset;">SCORING (TERKIKIS)</span>';
        actionText = '<span style="color: #d97706;">Peringatan Keausan:</span> Bersihkan debris & pelumasan NLGI #2.';
      } else {
        badgeHtml = '<span style="background: #dcfce7; color: #166534; font-weight: 800; font-size: 11px; padding: 4px 10px; border-radius: 6px; box-shadow: 0 0 0 1px #10b981 inset;">NORMAL (AMAN)</span>';
        actionText = '<span style="color: #059669;">Operasi Optimal:</span> Tidak diperlukan tindakan korektif.';
      }

      const tr = document.createElement('tr');
      tr.style.borderBottom = '1px solid #f1f5f9';
      tr.style.animation = 'fadeIn 0.3s ease';
      tr.innerHTML = `
        <td style="padding: 11px 16px; font-family: 'JetBrains Mono', monospace; font-weight: 700; color: #0f172a;">${timeStr}</td>
        <td style="padding: 11px 16px;">${badgeHtml}</td>
        <td style="padding: 11px 16px; font-family: 'JetBrains Mono', monospace; font-weight: 600; color: #0f172a;">${rms.toFixed(4)} g</td>
        <td style="padding: 11px 16px; font-family: 'JetBrains Mono', monospace; font-weight: 600; color: #0f172a;">${p2p.toFixed(4)} g</td>
        <td style="padding: 11px 16px; font-weight: 700; color: #0d8a7c;">${conf.toFixed(1)}%</td>
        <td style="padding: 11px 16px; font-size: 12.5px;">${actionText}</td>
      `;

      if (tbody.children.length >= 10) {
        tbody.removeChild(tbody.lastChild);
      }
      tbody.insertBefore(tr, tbody.firstChild);
    }

    function clearClassTable() {
      const tbody = document.getElementById('liveClassTableBody');
      if (tbody) {
        tbody.innerHTML = `
          <tr id="emptyClassRow">
            <td colspan="6" style="padding: 24px; text-align: center; color: #94a3b8; font-size: 13px; font-style: italic;">
              Riwayat aliran klasifikasi telah dibersihkan. Menunggu paket telemetri baru...
            </td>
          </tr>
        `;
      }
      previousLogTime = '';
    }

    // Inisialisasi awal seketika (Instant probe) & polling setiap 450ms
    fetchLiveTelemetryScope();
    setInterval(fetchLiveTelemetryScope, 450);
  </script>
</body>
</html>
