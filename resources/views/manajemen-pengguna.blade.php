@php
  $currentRole = session('gearsense_role', 'ADMINISTRATOR');
  $isAdmin = ($currentRole === 'ADMINISTRATOR');

  if (!isset($dbUsers)) {
      try {
          $dbUsers = \App\Models\User::orderBy('id', 'asc')->get();
      } catch (\Throwable $e) {
          $dbUsers = collect([]);
      }
  }
  $totalUsers = isset($totalUsers) ? $totalUsers : ($dbUsers ? $dbUsers->count() : 0);
  $adminCount = isset($adminCount) ? $adminCount : ($dbUsers ? $dbUsers->where('role', 'ADMINISTRATOR')->count() : 0);
  $operatorCount = isset($operatorCount) ? $operatorCount : ($dbUsers ? $dbUsers->where('role', 'OPERATOR')->count() : 0);
  $activeCount = isset($activeCount) ? $activeCount : ($dbUsers ? $dbUsers->where('status', 'AKTIF')->count() : 0);
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=1920, initial-scale=1.0">
  <title>Manajemen Pengguna - Gearsense Monitoring</title>
  
  <!-- Font: General Sans (Fontshare CDN) -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://api.fontshare.com/v2/css?f[]=general-sans@400,500,600,700,800&display=swap" rel="stylesheet">
  <script src="https://unpkg.com/lucide@latest"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  <!-- Framework: Bootstrap 5 -->
  @include('partials.frameworks')

  <style>
    /* =========================================================================
     * DEFINISI FONT GENERAL SANS
     * ========================================================================= */
    @font-face {
      font-family: 'General Sans';
      src: url('https://cdn.fontshare.com/wf/MFQT7HFGCR2L5ULQTW6YXYZXXHMPKLJ3/YWQ244D6TACUX5JBKATPOW5I5MGJ3G73/7YY3ZAAE3TRV2LANYOLXNHTPHLXVWTKH.woff2') format('woff2');
      font-weight: 400;
      font-display: swap;
      font-style: normal;
    }
    @font-face {
      font-family: 'General Sans';
      src: url('https://cdn.fontshare.com/wf/3RZHWSNONLLWJK3RLPEKUZOMM56GO4LJ/BPDRY7AHVI3MCDXXVXTQQ76H3UXA63S3/SB2OEB6IKZPRR6JT4GFJ2TFT6HBB6AZN.woff2') format('woff2');
      font-weight: 500;
      font-display: swap;
      font-style: normal;
    }
    @font-face {
      font-family: 'General Sans';
      src: url('https://cdn.fontshare.com/wf/K46YRH762FH3QJ25IQM3VAXAKCHEXXW4/ISLWQPUZHZF33LRIOTBMFOJL57GBGQ4B/3ZLMEXZEQPLTEPMHTQDAUXP5ZZXCZAEN.woff2') format('woff2');
      font-weight: 600;
      font-display: swap;
      font-style: normal;
    }
    @font-face {
      font-family: 'General Sans';
      src: url('https://cdn.fontshare.com/wf/KWXO5X3YW4X7OLUMPO4X24HQJGJU7E2Q/VOWUQZS3YLP66ZHPTXAFSH6YACY4WJHT/NIQ54PVBBIWVK3PFSOIOUJSXIJ5WTNDP.woff2') format('woff2');
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
      overflow-x: hidden;
      transition: padding 0.3s ease;
    }

    /* TOP BAR PILL */
    .topbar-pill-container {
      display: flex;
      justify-content: flex-start;
    }

    .topbar-pill {
      display: inline-flex;
      align-items: center;
      background: #ffffff;
      box-shadow: 0 0 0 1px rgba(226, 232, 240, 0.9), 0 4px 12px -2px rgba(15, 23, 42, 0.08);
      border-radius: 9999px;
      padding: 8px 24px;
      gap: 16px;
      font-size: 13.5px;
      color: #475569;
    }

    .topbar-pill .separator {
      color: #cbd5e1;
      font-weight: 300;
    }

    .topbar-pill .item {
      display: flex;
      align-items: center;
      gap: 8px;
      font-weight: 600;
    }

    .topbar-pill .time-badge {
      color: #0d8a7c;
      font-weight: 700;
      font-family: 'General Sans', sans-serif;
    }

    /* ================= HERO PANEL BANNER (PENGATURAN PENGGUNA: PRESISI SEMPURNA) ================= */
    .hero-card {
      background: linear-gradient(135deg, #0d8a7c 0%, #097266 100%) !important;
      border-radius: 14px !important;
      padding: 14px 22px !important;
      min-height: 52px !important;
      color: #ffffff !important;
      position: relative !important;
      overflow: hidden !important;
      box-shadow: 0 4px 16px rgba(13, 138, 124, 0.22), 0 0 0 1px rgba(255, 255, 255, 0.15) inset !important;
      display: flex !important;
      align-items: center !important;
      justify-content: flex-start !important;
      box-sizing: border-box !important;
      width: 100% !important;
      margin-bottom: 4px !important;
    }

    .hero-header {
      font-size: 15px !important;
      font-weight: 700 !important;
      letter-spacing: 0.5px !important;
      margin: 0 !important;
      padding: 0 !important;
      display: flex !important;
      align-items: center !important;
      gap: 12px !important;
      text-transform: uppercase !important;
      color: #ffffff !important;
      line-height: 1 !important;
      width: 100% !important;
    }

    .hero-header i,
    .hero-header svg {
      width: 22px !important;
      height: 22px !important;
      color: #ffffff !important;
      flex-shrink: 0 !important;
      display: inline-flex !important;
      align-items: center !important;
      justify-content: center !important;
      margin: 0 !important;
    }

    .hero-header span {
      display: inline-flex !important;
      align-items: center !important;
      line-height: 1 !important;
      color: #ffffff !important;
      font-family: 'General Sans', sans-serif !important;
      font-weight: 700 !important;
      margin: 0 !important;
      padding: 0 !important;
    }

    /* ================= 4 KPI CARDS ================= */
    .kpi-grid {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 20px;
    }

    .kpi-card {
      background: #f0fdfa;
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
      padding: 24px 28px;
      box-shadow: 0 0 0 1px rgba(226, 232, 240, 0.9), 0 8px 24px -4px rgba(15, 23, 42, 0.08), 0 2px 6px -1px rgba(15, 23, 42, 0.04);
      transition: transform 0.25s ease, box-shadow 0.25s ease;
    }

    .telemetry-card:hover {
      box-shadow: 0 0 0 1px rgba(203, 213, 225, 1), 0 12px 28px -4px rgba(15, 23, 42, 0.12), 0 4px 10px -2px rgba(15, 23, 42, 0.06) !important;
    }

    .telemetry-header-bar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 16px;
      flex-wrap: wrap;
      gap: 14px;
    }

    .telemetry-title {
      font-size: 15px;
      font-weight: 700 !important;
      color: #042f2e;
      font-family: 'General Sans', sans-serif;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    /* TWO COLUMNS BOTTOM SECTION */
    .bottom-two-cols {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 20px;
    }

    .info-card {
      background: #ffffff;
      border-radius: 18px;
      padding: 22px 26px;
      box-shadow: 0 0 0 1px rgba(226, 232, 240, 0.9), 0 6px 18px -3px rgba(15, 23, 42, 0.06);
    }

    .info-card-header {
      font-size: 14.5px;
      font-weight: 700 !important;
      color: #042f2e;
      margin-bottom: 16px;
      display: flex;
      align-items: center;
      gap: 8px;
      text-transform: uppercase;
      letter-spacing: 0.03em;
    }

    /* USER AVATAR & BADGES */
    .user-avatar-circle {
      width: 38px;
      height: 38px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      color: #ffffff;
      font-weight: 700;
      font-size: 14px;
      flex-shrink: 0;
      box-shadow: 0 2px 6px rgba(0,0,0,0.12);
    }

    /* 2 ROLE BADGES: ADMINISTRATOR & OPERATOR */
    .role-badge-admin {
      background: #e0e7ff;
      color: #3730a3;
      padding: 4px 10px;
      border-radius: 6px;
      font-size: 11.5px;
      font-weight: 700;
      box-shadow: 0 0 0 0.5px #6366f1 inset;
      display: inline-flex;
      align-items: center;
      gap: 4px;
      letter-spacing: 0.3px;
    }

    .role-badge-operator {
      background: #ccfbf1;
      color: #0d8a7c;
      padding: 4px 10px;
      border-radius: 6px;
      font-size: 11.5px;
      font-weight: 700;
      box-shadow: 0 0 0 0.5px #14b8a6 inset;
      display: inline-flex;
      align-items: center;
      gap: 4px;
      letter-spacing: 0.3px;
    }

    /* ACTION BUTTONS */
    .btn-action {
      background: #ffffff;
      border: 1px solid #cbd5e1;
      border-radius: 8px;
      padding: 6px 12px;
      font-size: 12.5px;
      font-weight: 600;
      color: #334155;
      cursor: pointer;
      display: inline-flex;
      align-items: center;
      gap: 5px;
      transition: all 0.15s ease;
    }

    .btn-action:hover {
      background: #f8fafc;
      border-color: #94a3b8;
      color: #0f172a;
    }

    .btn-action-edit:hover {
      background: #e0f2fe;
      border-color: #0284c7;
      color: #0369a1;
    }

    .btn-action-status:hover {
      background: #fee2e2;
      border-color: #ef4444;
      color: #b91c1c;
    }

    .btn-action-delete {
      color: #dc2626;
      border-color: #fecaca;
    }

    .btn-action-delete:hover {
      background: #fee2e2;
      border-color: #ef4444;
      color: #991b1b;
    }

    /* ACTIVITY LOG */
    .activity-log-list {
      list-style: none;
      display: flex;
      flex-direction: column;
      gap: 12px;
    }

    .activity-log-item {
      display: flex;
      align-items: flex-start;
      gap: 12px;
      padding: 10px 14px;
      border-radius: 10px;
      background: #f8fafc;
      font-size: 12.5px;
      line-height: 1.45;
      border-left: 3px solid #0d8a7c;
    }

    /* MODAL STYLES */
    .modal-backdrop,
    #userModalBackdrop,
    #resetModalBackdrop,
    #deleteUserModalBackdrop {
      position: fixed !important;
      top: 0 !important;
      left: 0 !important;
      width: 100vw !important;
      height: 100vh !important;
      background: rgba(15, 23, 42, 0.6) !important;
      backdrop-filter: blur(4px) !important;
      display: none !important;
      align-items: center !important;
      justify-content: center !important;
      z-index: 9999999 !important;
      opacity: 1 !important;
      visibility: visible !important;
    }

    .modal-backdrop.show,
    #userModalBackdrop.show,
    #resetModalBackdrop.show,
    #deleteUserModalBackdrop.show {
      display: flex !important;
      opacity: 1 !important;
      visibility: visible !important;
    }

    .modal-backdrop .modal-box,
    #userModalBackdrop .modal-box,
    #resetModalBackdrop .modal-box,
    #deleteUserModalBackdrop .modal-box {
      opacity: 1 !important;
      position: relative !important;
      z-index: 10000000 !important;
      pointer-events: auto !important;
    }

    .modal-box {
      background: #ffffff;
      border-radius: 20px;
      width: 540px;
      max-width: 95vw;
      box-shadow: 0 20px 40px rgba(0, 0, 0, 0.25);
      overflow: hidden;
      display: flex;
      flex-direction: column;
      animation: modalFadeIn 0.2s ease-out;
    }

    @keyframes modalFadeIn {
      from { transform: scale(0.96); opacity: 0; }
      to { transform: scale(1); opacity: 1; }
    }

    .modal-header {
      padding: 18px 24px;
      background: #f8fafc;
      border-bottom: 1px solid #e2e8f0;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .modal-title {
      font-size: 16px;
      font-weight: 800;
      color: #042f2e;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .modal-close-btn {
      background: transparent;
      border: none;
      color: #64748b;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      border-radius: 8px;
      padding: 4px;
      transition: background 0.15s ease;
    }

    .modal-close-btn:hover {
      background: #e2e8f0;
      color: #0f172a;
    }

    .modal-body {
      padding: 22px 24px;
      display: flex;
      flex-direction: column;
      gap: 16px;
    }

    .modal-footer {
      padding: 16px 24px;
      background: #f8fafc;
      border-top: 1px solid #e2e8f0;
      display: flex;
      justify-content: flex-end;
      gap: 10px;
    }

    .form-group {
      display: flex;
      flex-direction: column;
      gap: 6px;
    }

    .form-label {
      font-size: 13px;
      font-weight: 700;
      color: #0f172a;
    }

    .form-input, .form-select {
      background: #ffffff;
      border: 1px solid #cbd5e1;
      border-radius: 10px;
      padding: 10px 14px;
      font-size: 13.5px;
      font-family: 'General Sans', sans-serif;
      color: #0f172a;
      outline: none;
      transition: border-color 0.15s ease, box-shadow 0.15s ease;
    }

    .form-input:focus, .form-select:focus {
      border-color: #0d8a7c;
      box-shadow: 0 0 0 3px rgba(13, 138, 124, 0.15);
    }

    /* TOAST */
    .toast-container {
      position: fixed;
      bottom: 28px;
      right: 32px;
      z-index: 9999999;
      display: flex;
      flex-direction: column;
      gap: 10px;
    }

    .toast {
      background: #ffffff;
      color: #0f172a;
      box-shadow: 0 10px 25px rgba(0,0,0,0.18), 0 0 0 1px #e2e8f0;
      border-radius: 12px;
      padding: 14px 18px;
      font-size: 13px;
      font-weight: 600;
      display: flex;
      align-items: center;
      gap: 10px;
      min-width: 300px;
      transform: translateY(20px);
      opacity: 0;
      transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .toast.show {
      transform: translateY(0);
      opacity: 1;
    }

    .toast-success { border-left: 4px solid #10b981; }
    .toast-warning { border-left: 4px solid #f59e0b; }
    .toast-info { border-left: 4px solid #0d8a7c; }
  </style>
</head>
<body>
  <div class="app-container">
    
    <!-- SIDEBAR -->
    @include('partials.sidebar')

    <!-- MAIN CONTENT -->
    <main class="main-content">

      <!-- TOP BAR STATUS PILL (TANPA SHIFT) -->
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
        <span>KONTROL PENGGUNA SISTEM &amp; HAK AKSES ROLE-BASED ACCESS CONTROL (RBAC)</span>
      </div>

      <!-- HERO PANEL BANNER: PRESISI SIMETRIS SEMPURNA -->
      <div class="hero-card">
        <div class="hero-header">
          <i data-lucide="users"></i>
          <span>PANEL MANAJEMEN PENGGUNA &amp; KREDENSIAL KEAMANAN SISTEM</span>
        </div>
      </div>

      <!-- KPI SECTION TITLE -->
      <div class="section-title">
        <span>RINGKASAN METRIK DISTRIBUSI PERAN &amp; STATUS AKUN PENGGUNA</span>
      </div>

      <!-- 4 KPI CARDS (DENGAN 2 ROLE) -->
      <div class="kpi-grid">
        <!-- CARD 1: TOTAL PENGGUNA -->
        <div class="kpi-card" style="background: #f0fdfa; box-shadow: 0 0 0 1px #14b8a6 inset, 0 8px 20px -2px rgba(20, 184, 166, 0.25), 0 2px 6px -1px rgba(15, 23, 42, 0.06);">
          <div class="kpi-header" style="color: #042f2e;">
            <i data-lucide="users" style="width: 16px; height: 16px; color: #0d8a7c;"></i>
            <span>TOTAL PENGGUNA</span>
          </div>
          <div class="kpi-value" style="color: #042f2e;" id="kpiTotalUsers">{{ $totalUsers ?: 6 }} Pengguna</div>
          <div class="kpi-footer" style="color: #097266;">2 Kategori Peran (Admin &amp; Operator)</div>
        </div>

        <!-- CARD 2: ADMINISTRATOR -->
        <div class="kpi-card" style="background: #f0fdfa; box-shadow: 0 0 0 1px #6366f1 inset, 0 8px 20px -2px rgba(99, 102, 241, 0.20), 0 2px 6px -1px rgba(15, 23, 42, 0.05);">
          <div class="kpi-header" style="color: #3730a3;">
            <i data-lucide="shield" style="width: 16px; height: 16px; color: #4f46e5;"></i>
            <span>ADMINISTRATOR</span>
          </div>
          <div class="kpi-value" style="color: #3730a3;" id="kpiAdminCount">{{ $adminCount ?: 3 }} Akun</div>
          <div class="kpi-footer" style="color: #4f46e5;">Akses Penuh Konfigurasi &amp; Kontrol</div>
        </div>

        <!-- CARD 3: OPERATOR -->
        <div class="kpi-card" style="background: #f0fdfa; box-shadow: 0 0 0 1px #0d8a7c inset, 0 8px 20px -2px rgba(13, 138, 124, 0.20), 0 2px 6px -1px rgba(15, 23, 42, 0.05);">
          <div class="kpi-header" style="color: #065f46;">
            <i data-lucide="activity" style="width: 16px; height: 16px; color: #0d8a7c;"></i>
            <span>OPERATOR</span>
          </div>
          <div class="kpi-value" style="color: #065f46;" id="kpiStaffCount">{{ $operatorCount ?: 3 }} Akun</div>
          <div class="kpi-footer" style="color: #0d8a7c;">Operasional &amp; Monitoring Sinyal Getaran</div>
        </div>

        <!-- CARD 4: STATUS AKTIF -->
        <div class="kpi-card" style="background: #f0fdfa; box-shadow: 0 0 0 1px #059669 inset, 0 8px 20px -2px rgba(5, 150, 105, 0.20), 0 2px 6px -1px rgba(15, 23, 42, 0.05);">
          <div class="kpi-header" style="color: #065f46;">
            <i data-lucide="check-circle-2" style="width: 16px; height: 16px; color: #059669;"></i>
            <span>STATUS AKTIF</span>
          </div>
          <div class="kpi-value" style="color: #065f46;" id="kpiActivePercent">{{ $totalUsers > 0 ? round(($activeCount / $totalUsers) * 100) : 100 }}% Aktif</div>
          <div class="kpi-footer" style="color: #059669;"><span id="kpiActiveRatio">{{ $activeCount }}/{{ $totalUsers }}</span> Akun Terverifikasi Siap Pakai</div>
        </div>
      </div>

      <!-- TABLE SECTION TITLE -->
      <div class="section-title">
        <span>DAFTAR PENGGUNA TERDAFTAR &amp; HAK AKSES SISTEM (RBAC)</span>
      </div>

      <!-- MAIN DATA TABLE CARD -->
      <div class="telemetry-card">
        <!-- Filter & Search Bar in Table Header -->
        <div class="telemetry-header-bar">
          <div style="display: flex; align-items: center; gap: 12px; flex-wrap: wrap;">
            <!-- Search Input -->
            <div style="position: relative;">
              <input type="text" id="userSearchInput" onkeyup="filterUsersTable()" placeholder="Cari nama atau username..." style="background: #ffffff; border: none; box-shadow: 0 0 0 0.5px #cbd5e1 inset; border-radius: 10px; padding: 9px 14px 9px 38px; font-size: 13px; font-family: 'General Sans', sans-serif; width: 280px; outline: none;">
              <i data-lucide="search" style="position: absolute; left: 11px; top: 50%; transform: translateY(-50%); width: 16px; height: 16px; color: #94a3b8;"></i>
            </div>

            <!-- Role Filter Select (HANYA 2 ROLE) -->
            <select id="roleFilterSelect" onchange="filterUsersTable()" style="background: #ffffff; border: none; box-shadow: 0 0 0 0.5px #cbd5e1 inset; border-radius: 10px; padding: 9px 14px; font-size: 13px; font-family: 'General Sans', sans-serif; color: #042f2e; font-weight: 600; outline: none; cursor: pointer;">
              <option value="ALL">Semua Peran (Role)</option>
              <option value="ADMINISTRATOR">Administrator</option>
              <option value="OPERATOR">Operator</option>
            </select>

            <!-- Status Filter Select -->
            <select id="statusFilterSelect" onchange="filterUsersTable()" style="background: #ffffff; border: none; box-shadow: 0 0 0 0.5px #cbd5e1 inset; border-radius: 10px; padding: 9px 14px; font-size: 13px; font-family: 'General Sans', sans-serif; color: #042f2e; font-weight: 600; outline: none; cursor: pointer;">
              <option value="ALL">Semua Status Akun</option>
              <option value="AKTIF">Aktif</option>
              <option value="NONAKTIF">Nonaktif</option>
            </select>
          </div>

          <!-- Tombol Tambah Pengguna -->
          <div style="display: flex; align-items: center; gap: 14px;">
            <div style="font-size: 13px; color: #64748b; font-weight: 600;">
              Menampilkan <span id="showingUsersCount" style="color: #0d8a7c; font-weight: 700;">{{ $totalUsers }}</span> dari <span id="totalUsersCountText" style="font-weight: 700; color: #0f172a;">{{ $totalUsers }}</span> akun terdaftar
            </div>
            <button type="button" onclick="openAddUserModal()" style="background: #0d8a7c; color: #ffffff; border: none; border-radius: 10px; padding: 9px 18px; font-size: 13px; font-weight: 700; display: inline-flex; align-items: center; gap: 6px; cursor: pointer; transition: background 0.15s ease;">
              <i data-lucide="user-plus" style="width: 16px; height: 16px;"></i> Tambah Pengguna
            </button>
          </div>
        </div>

        <!-- TABLE ITSELF (TANPA KOLOM SHIFT) -->
        <div style="overflow-x: auto;">
          <table id="usersTable" style="width: 100%; border-collapse: collapse; font-size: 13px; text-align: left;">
            <thead>
              <tr style="background: #f1f5f9; color: #042f2e; border-bottom: 2px solid #cbd5e1;">
                <th style="padding: 12px 14px; font-weight: 700; border-top-left-radius: 8px;">ID Pengguna</th>
                <th style="padding: 12px 14px; font-weight: 700;">Nama Lengkap</th>
                <th style="padding: 12px 14px; font-weight: 700;">Username</th>
                <th style="padding: 12px 14px; font-weight: 700; text-align: center;">Peran (Role)</th>
                <th style="padding: 12px 14px; font-weight: 700; text-align: center;">Status</th>
                <th style="padding: 12px 14px; font-weight: 700;">Terakhir Login</th>
                <th style="padding: 12px 14px; font-weight: 700; text-align: center; border-top-right-radius: 8px;">Aksi</th>
              </tr>
            </thead>
            <tbody id="usersTableBody" style="color: #1e293b;">

              @forelse($dbUsers as $user)

                @php

                  $userCode = $user->user_code ?: ('USR-00' . $user->id);

                  $nameParts = explode(' ', trim($user->name));

                  $initials = strtoupper(substr($nameParts[0] ?? 'U', 0, 1) . substr($nameParts[1] ?? '', 0, 1));

                  $avatarColors = ['#0d8a7c', '#097266', '#0f766e', '#0284c7', '#3730a3', '#059669'];

                  $colorBg = $avatarColors[$user->id % count($avatarColors)];

                  $lastLoginFormatted = $user->last_login_at ? \Carbon\Carbon::parse($user->last_login_at)->diffForHumans() : 'Belum login';

                @endphp

                <tr id="row-usr-{{ $user->id }}" style="border-bottom: 1px solid #f1f5f9; background: #ffffff;" data-role="{{ $user->role }}" data-status="{{ $user->status }}">

                  <td style="padding: 14px; font-family: 'JetBrains Mono', monospace; font-weight: 700; color: #0d8a7c;">{{ $userCode }}</td>

                  <td style="padding: 14px;">

                    <div style="display: flex; align-items: center; gap: 12px;">

                      <div class="user-avatar-circle" style="background: {{ $colorBg }};">{{ $initials }}</div>

                      <div>

                        <div class="user-full-name" style="font-weight: 700; color: #0f172a; font-size: 13.5px;">{{ $user->name }}</div>

                      </div>

                    </div>

                  </td>

                  <td style="padding: 14px; font-family: 'JetBrains Mono', monospace; font-size: 12.5px; color: #334155;">@<span>{{ $user->username }}</span></td>

                  <td style="padding: 14px; text-align: center;">

                    @if($user->role === 'ADMINISTRATOR')

                      <span class="role-badge-admin"><i data-lucide="shield" style="width: 12px; height: 12px;"></i> ADMINISTRATOR</span>

                    @else

                      <span class="role-badge-operator"><i data-lucide="activity" style="width: 12px; height: 12px;"></i> OPERATOR</span>

                    @endif

                  </td>

                  <td style="padding: 14px; text-align: center;">

                    @if($user->status === 'AKTIF')

                      <span class="status-badge-cell" style="background: #ccfbf1; color: #0d8a7c; font-weight: 700; font-size: 11px; padding: 4px 10px; border-radius: 6px; box-shadow: 0 0 0 0.5px #14b8a6 inset; display: inline-flex; align-items: center; gap: 5px;">

                        <span style="width: 7px; height: 7px; border-radius: 50%; background: #10b981;"></span> AKTIF

                      </span>

                    @else

                      <span class="status-badge-cell" style="background: #fee2e2; color: #dc2626; font-weight: 700; font-size: 11px; padding: 4px 10px; border-radius: 6px; box-shadow: 0 0 0 0.5px #ef4444 inset; display: inline-flex; align-items: center; gap: 5px;">

                        <span style="width: 7px; height: 7px; border-radius: 50%; background: #ef4444;"></span> NONAKTIF

                      </span>

                    @endif

                  </td>

                  <td style="padding: 14px; font-size: 12.5px; color: #475569;">

                    <div style="font-weight: 600;">{{ $lastLoginFormatted }}</div>

                    <div style="font-size: 11px; color: #94a3b8;">IP: {{ $user->last_login_ip ?: '127.0.0.1' }}</div>

                  </td>

                  <td style="padding: 14px; text-align: center;">

                    <div style="display: flex; align-items: center; justify-content: center; gap: 6px;">

                      <button class="btn-action btn-action-edit" onclick="openEditUserModal('{{ $user->id }}', '{{ addslashes($user->name) }}', '{{ $user->username }}', '{{ $user->role }}', '{{ $user->status }}', '{{ $user->phone }}')" title="Edit Data Pengguna">

                        <i data-lucide="edit-3" style="width: 14px; height: 14px;"></i> Edit

                      </button>

                      <button class="btn-action" onclick="openResetPasswordModal('{{ $user->id }}', '{{ addslashes($user->name) }}', '{{ $user->username }}')" title="Reset Kata Sandi">

                        <i data-lucide="key" style="width: 14px; height: 14px;"></i> Reset

                      </button>

                      <button class="btn-action btn-action-delete" onclick="openDeleteUserModal('{{ $user->id }}', '{{ addslashes($user->name) }}', '{{ $user->username }}')" title="Hapus Akun Pengguna">

                        <i data-lucide="trash-2" style="width: 14px; height: 14px;"></i> Hapus

                      </button>

                    </div>

                  </td>

                </tr>

              @empty

                <tr>

                  <td colspan="7" style="text-align: center; padding: 30px; color: #64748b;">Belum ada data pengguna di database.</td>

                </tr>

              @endforelse

            </tbody>
          </table>
        </div>

        <div style="margin-top: 16px; padding-top: 14px; border-top: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center; font-size: 13px; color: #64748b; font-weight: 600;">
          <div style="display: flex; align-items: center; gap: 8px;">
            <span style="width: 8px; height: 8px; border-radius: 50%; background: #10b981; display: inline-block;"></span>
            <span>Seluruh akun terhubung dengan sistem otentikasi role-based access control (RBAC).</span>
          </div>
          <div>
            Halaman 1 dari 1 (Menampilkan 6 Pengguna)
          </div>
        </div>
      </div>

      <!-- BOTTOM SECTION: RBAC MATRIX (2 ROLES) & AUDIT TRAIL -->
      <div class="bottom-two-cols">
        <!-- CARD 1: MATRIKS HAK AKSES PERAN (2 ROLE: ADMINISTRATOR & OPERATOR) -->
        <div class="info-card">
          <div class="info-card-header">
            <i data-lucide="shield" style="width: 18px; height: 18px; color: #0d8a7c;"></i>
            <span>MATRIKS HAK AKSES SISTEM (RBAC MATRIX - 2 ROLE)</span>
          </div>

          <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; font-size: 13px; text-align: left;">
              <thead>
                <tr style="background: #f8fafc; color: #0f172a; border-bottom: 2px solid #e2e8f0;">
                  <th style="padding: 11px 14px; font-weight: 700;">Modul / Fitur Aplikasi</th>
                  <th style="padding: 11px 14px; font-weight: 700; text-align: center; color: #3730a3;">Administrator</th>
                  <th style="padding: 11px 14px; font-weight: 700; text-align: center; color: #0d8a7c;">Operator</th>
                </tr>
              </thead>
              <tbody style="color: #334155;">
                <tr style="border-bottom: 1px solid #f1f5f9;">
                  <td style="padding: 10px 14px; font-weight: 600;">Overview Dashboard &amp; KPI Real-Time</td>
                  <td style="padding: 10px 14px; text-align: center; color: #059669; font-weight: 800; font-size: 15px;">✓</td>
                  <td style="padding: 10px 14px; text-align: center; color: #059669; font-weight: 800; font-size: 15px;">✓</td>
                </tr>
                <tr style="border-bottom: 1px solid #f1f5f9;">
                  <td style="padding: 10px 14px; font-weight: 600;">Live Waveform &amp; Spektrum FFT (50 Hz)</td>
                  <td style="padding: 10px 14px; text-align: center; color: #059669; font-weight: 800; font-size: 15px;">✓</td>
                  <td style="padding: 10px 14px; text-align: center; color: #059669; font-weight: 800; font-size: 15px;">✓</td>
                </tr>
                <tr style="border-bottom: 1px solid #f1f5f9;">
                  <td style="padding: 10px 14px; font-weight: 600;">Diagnosis AI Kerusakan (Mamdani FIS &amp; ML)</td>
                  <td style="padding: 10px 14px; text-align: center; color: #059669; font-weight: 800; font-size: 15px;">✓</td>
                  <td style="padding: 10px 14px; text-align: center; color: #059669; font-weight: 800; font-size: 15px;">✓</td>
                </tr>
                <tr style="border-bottom: 1px solid #f1f5f9;">
                  <td style="padding: 10px 14px; font-weight: 600;">Ekspor Dataset Telemetri (CSV / PDF)</td>
                  <td style="padding: 10px 14px; text-align: center; color: #059669; font-weight: 800; font-size: 15px;">✓</td>
                  <td style="padding: 10px 14px; text-align: center; color: #059669; font-weight: 800; font-size: 15px;">✓</td>
                </tr>
                <tr style="border-bottom: 1px solid #f1f5f9;">
                  <td style="padding: 10px 14px; font-weight: 600;">Handshake &amp; Status Sensor ESP32</td>
                  <td style="padding: 10px 14px; text-align: center; color: #059669; font-weight: 800; font-size: 15px;">✓</td>
                  <td style="padding: 10px 14px; text-align: center; color: #059669; font-weight: 800; font-size: 15px;">✓</td>
                </tr>
                <tr style="border-bottom: 1px solid #f1f5f9;">
                  <td style="padding: 10px 14px; font-weight: 600;">Manajemen Pengguna &amp; Pembagian Role</td>
                  <td style="padding: 10px 14px; text-align: center; color: #059669; font-weight: 800; font-size: 15px;">✓</td>
                  <td style="padding: 10px 14px; text-align: center; color: #ef4444; font-weight: 800; font-size: 15px;">✗</td>
                </tr>
                <tr style="border-bottom: 1px solid #f1f5f9;">
                  <td style="padding: 10px 14px; font-weight: 600;">Konfigurasi Token Bot Telegram</td>
                  <td style="padding: 10px 14px; text-align: center; color: #059669; font-weight: 800; font-size: 15px;">✓</td>
                  <td style="padding: 10px 14px; text-align: center; color: #ef4444; font-weight: 800; font-size: 15px;">✗</td>
                </tr>
                <tr>
                  <td style="padding: 10px 14px; font-weight: 600;">Kontrol Perangkat &amp; Emergency Stop Motor</td>
                  <td style="padding: 10px 14px; text-align: center; color: #059669; font-weight: 800; font-size: 15px;">✓</td>
                  <td style="padding: 10px 14px; text-align: center; color: #ef4444; font-weight: 800; font-size: 15px;">✗</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <!-- CARD 2: LOG AUDIT KEAMANAN PENGGUNA TERAKHIR -->
        <div class="info-card">
          <div class="info-card-header">
            <i data-lucide="clipboard-list" style="width: 18px; height: 18px; color: #0d8a7c;"></i>
            <span>LOG AUDIT KEAMANAN &amp; AKTIVITAS PENGGUNA (AUDIT TRAIL)</span>
          </div>

          <ul class="activity-log-list" id="activityLogList">
            <li class="activity-log-item">
              <i data-lucide="check-circle-2" style="width: 17px; height: 17px; color: #10b981; flex-shrink: 0; margin-top: 2px;"></i>
              <div style="flex-grow: 1;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                  <span style="font-weight: 700; color: #0f172a;">Login Berhasil: Administrator</span>
                  <span style="font-size: 11.5px; color: #64748b; font-family: 'JetBrains Mono', monospace;">14:15 WIB</span>
                </div>
                <div style="font-size: 12px; color: #475569;">Sesi admin aktif via IP 192.168.1.105 (Autentikasi Session Guard).</div>
              </div>
            </li>

            <li class="activity-log-item">
              <i data-lucide="user-check" style="width: 17px; height: 17px; color: #0d8a7c; flex-shrink: 0; margin-top: 2px;"></i>
              <div style="flex-grow: 1;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                  <span style="font-weight: 700; color: #0f172a;">Verifikasi Identitas Peneliti TA</span>
                  <span style="font-size: 11.5px; color: #64748b; font-family: 'JetBrains Mono', monospace;">13:40 WIB</span>
                </div>
                <div style="font-size: 12px; color: #475569;">Fathan Mubarak mengakses panel telemetri hardware &amp; bus I2C.</div>
              </div>
            </li>

            <li class="activity-log-item">
              <i data-lucide="key" style="width: 17px; height: 17px; color: #f59e0b; flex-shrink: 0; margin-top: 2px;"></i>
              <div style="flex-grow: 1;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                  <span style="font-weight: 700; color: #0f172a;">Reset Kredensial Berhasil</span>
                  <span style="font-size: 11.5px; color: #64748b; font-family: 'JetBrains Mono', monospace;">10:12 WIB</span>
                </div>
                <div style="font-size: 12px; color: #475569;">Kata sandi akun @rahmat.operator berhasil diperbarui oleh Administrator.</div>
              </div>
            </li>

            <li class="activity-log-item">
              <i data-lucide="shield-alert" style="width: 17px; height: 17px; color: #0284c7; flex-shrink: 0; margin-top: 2px;"></i>
              <div style="flex-grow: 1;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                  <span style="font-weight: 700; color: #0f172a;">Pembaruan Matriks RBAC</span>
                  <span style="font-size: 11.5px; color: #64748b; font-family: 'JetBrains Mono', monospace;">08:30 WIB</span>
                </div>
                <div style="font-size: 12px; color: #475569;">Penyelarasan hak akses 2 peran: Administrator &amp; Operator.</div>
              </div>
            </li>
          </ul>
        </div>
      </div>

<!-- ================= MODAL: TAMBAH / EDIT PENGGUNA (2 ROLE) ================= -->
  <div class="modal-backdrop" id="userModalBackdrop" onclick="if(event.target === this) closeUserModal()">
    <div class="modal-box">
      <div class="modal-header">
        <div class="modal-title">
          <i data-lucide="user-plus" id="modalHeaderIcon" style="width: 20px; height: 20px; color: #0d8a7c;"></i>
          <span id="modalHeaderTitle">Tambah Pengguna Baru</span>
        </div>
        <button class="modal-close-btn" onclick="closeUserModal()">
          <i data-lucide="x" style="width: 20px; height: 20px;"></i>
        </button>
      </div>

      <form id="userForm" onsubmit="handleUserFormSubmit(event)">
        <div class="modal-body">
          <input type="hidden" id="formUserId" value="">

          <div class="form-group">
            <label class="form-label">Nama Lengkap</label>
            <input type="text" id="formFullName" class="form-input" placeholder="Contoh: Muhammad Ilham" required>
          </div>

          <div class="form-group">
            <label class="form-label">Username Sistem</label>
            <input type="text" id="formUsername" class="form-input" placeholder="ilham.operator" required>
          </div>

          <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px;">
            <!-- ROLE HANYA 2 PILIHAN -->
            <div class="form-group">
              <label class="form-label">Peran (Role)</label>
              <select id="formRole" class="form-select" required>
                <option value="ADMINISTRATOR">Administrator</option>
                <option value="OPERATOR">Operator</option>
              </select>
            </div>
            <div class="form-group">
              <label class="form-label">Status Akun</label>
              <select id="formStatus" class="form-select">
                <option value="AKTIF">Aktif (Diberikan Akses)</option>
                <option value="NONAKTIF">Nonaktif (Akses Ditangguhkan)</option>
              </select>
            </div>
          </div>

          <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px;">
            <div class="form-group">
              <label class="form-label">Kata Sandi (Password)</label>
              <input type="password" id="formPassword" class="form-input" placeholder="Minimal 8 karakter">
              <span id="passwordHelpText" style="font-size: 11px; color: #64748b; margin-top: 2px;">Kosongkan jika tidak ingin mengubah sandi</span>
            </div>
            <div class="form-group">
              <label class="form-label">Nomor Telegram</label>
              <input type="text" id="formPhone" class="form-input" placeholder="081234567890">
            </div>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn-action" onclick="closeUserModal()" style="padding: 9px 18px; font-size: 13.5px;">Batal</button>
          <button type="submit" style="background: #0d8a7c; color: #ffffff; border: none; border-radius: 10px; padding: 9px 22px; font-size: 13.5px; font-weight: 700; cursor: pointer; transition: background 0.15s ease;">
            <span id="formSubmitBtnText">Simpan Pengguna</span>
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- ================= MODAL: RESET PASSWORD ================= -->
  <div class="modal-backdrop" id="resetModalBackdrop" onclick="if(event.target === this) closeResetPasswordModal()">
    <div class="modal-box" style="width: 480px;">
      <div class="modal-header">
        <div class="modal-title">
          <i data-lucide="key" style="width: 20px; height: 20px; color: #f59e0b;"></i>
          <span>Reset Kata Sandi Pengguna</span>
        </div>
        <button class="modal-close-btn" onclick="closeResetPasswordModal()">
          <i data-lucide="x" style="width: 20px; height: 20px;"></i>
        </button>
      </div>

      <div class="modal-body">
        <input type="hidden" id="resetUserId" value="">
        <div style="background: #fef3c7; border-left: 4px solid #f59e0b; padding: 12px 16px; border-radius: 8px; font-size: 13px; color: #92400e;">
          Anda sedang mengatur ulang kata sandi untuk akun <strong id="resetUserName">...</strong> (<span id="resetUserUsername">@...</span>).
        </div>

        <div class="form-group">
          <label class="form-label">Kata Sandi Baru</label>
          <input type="password" id="newPasswordInput" class="form-input" placeholder="Masukkan kata sandi baru (min 8 karakter)">
        </div>

        <div class="form-group">
          <label class="form-label">Konfirmasi Kata Sandi Baru</label>
          <input type="password" id="confirmPasswordInput" class="form-input" placeholder="Ketik ulang kata sandi baru">
        </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn-action" onclick="closeResetPasswordModal()" style="padding: 9px 18px; font-size: 13.5px;">Batal</button>
        <button type="button" onclick="confirmResetPassword()" style="background: #f59e0b; color: #ffffff; border: none; border-radius: 10px; padding: 9px 22px; font-size: 13.5px; font-weight: 700; cursor: pointer;">
          Setel Ulang Sandi
        </button>
      </div>
    </div>
  </div>

  <!-- ================= MODAL: HAPUS AKUN PENGGUNA ================= -->
  <div class="modal-backdrop" id="deleteUserModalBackdrop" onclick="if(event.target === this) closeDeleteUserModal()">
    <div class="modal-box" style="width: 460px;">
      <div class="modal-header">
        <div class="modal-title" style="color: #dc2626; display: flex; align-items: center; gap: 8px;">
          <i data-lucide="alert-triangle" style="width: 20px; height: 20px; color: #dc2626;"></i>
          <span>Konfirmasi Hapus Akun</span>
        </div>
        <button class="modal-close-btn" onclick="closeDeleteUserModal()">
          <i data-lucide="x" style="width: 20px; height: 20px;"></i>
        </button>
      </div>

      <div class="modal-body">
        <input type="hidden" id="deleteUserId" value="">
        <div style="background: #fee2e2; border-left: 4px solid #ef4444; padding: 12px 16px; border-radius: 8px; font-size: 13px; color: #991b1b; margin-bottom: 14px;">
          Apakah Anda yakin ingin menghapus akun pengguna <strong id="deleteUserName">...</strong> (<span id="deleteUserUsername">@...</span>)?
        </div>
        <p style="font-size: 13px; color: #64748b; line-height: 1.5; margin: 0;">
          Tindakan ini bersifat permanen. Akun ini tidak akan dapat login kembali dan seluruh catatan hak aksesnya akan dicabut dari sistem.
        </p>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn-action" onclick="closeDeleteUserModal()" style="padding: 9px 18px; font-size: 13.5px;">Batal</button>
        <button type="button" id="btnConfirmDeleteUser" onclick="confirmDeleteUser()" style="background: #dc2626; color: #ffffff; border: none; border-radius: 10px; padding: 9px 22px; font-size: 13.5px; font-weight: 700; cursor: pointer; display: inline-flex; align-items: center; gap: 6px;">
          <i data-lucide="trash-2" style="width: 16px; height: 16px;"></i>
          <span>Hapus Akun</span>
        </button>
      </div>
    </div>
  </div>

  <!-- TOAST CONTAINER -->
  <div class="toast-container" id="toastContainer"></div>

    </main>
  </div>

  <!-- JAVASCRIPT LOGIC -->
  <script>
    // Inisialisasi ikon Lucide
    document.addEventListener('DOMContentLoaded', function() {
      lucide.createIcons();
      updateClock();
      setInterval(updateClock, 1000);
      recalculateKPIs();
    });

    // 1. JAM LIVE WIB
    function updateClock() {
      const now = new Date();
      const options = { 
        day: 'numeric', 
        month: 'short', 
        year: 'numeric', 
        hour: '2-digit', 
        minute: '2-digit', 
        second: '2-digit',
        hour12: false,
        timeZone: 'Asia/Jakarta'
      };
      const formatted = new Intl.DateTimeFormat('id-ID', options).format(now).replace(/\./g, ':');
      const el = document.getElementById('liveTimestamp');
      if (el) el.textContent = formatted + ' WIB';
    }

    // 2. TOAST NOTIFICATION
    function showToast(message, type = 'info') {
      const container = document.getElementById('toastContainer');
      if (!container) return;

      const toast = document.createElement('div');
      toast.className = `toast toast-${type}`;
      
      let iconName = 'info';
      if (type === 'success') iconName = 'check-circle';
      if (type === 'warning') iconName = 'alert-triangle';

      toast.innerHTML = `
        <i data-lucide="${iconName}" style="width: 18px; height: 18px; flex-shrink: 0; color: ${type === 'success' ? '#10b981' : type === 'warning' ? '#f59e0b' : '#0d8a7c'};"></i>
        <div style="flex-grow: 1;">${message}</div>
      `;

      container.appendChild(toast);
      lucide.createIcons();

      setTimeout(() => toast.classList.add('show'), 50);

      setTimeout(() => {
        toast.classList.remove('show');
        setTimeout(() => toast.remove(), 300);
      }, 4000);
    }

    // 3. FILTER & PENCARIAN TABEL PENGGUNA (HANYA BERDASARKAN SEARCH, ROLE & STATUS)
    function filterUsersTable() {
      const searchVal = document.getElementById('userSearchInput').value.toLowerCase().trim();
      const roleVal = document.getElementById('roleFilterSelect').value.toUpperCase();
      const statusVal = document.getElementById('statusFilterSelect').value.toUpperCase();

      const tableBody = document.getElementById('usersTableBody');
      const rows = tableBody.getElementsByTagName('tr');
      let visibleCount = 0;

      for (let i = 0; i < rows.length; i++) {
        const row = rows[i];
        const rowText = row.innerText.toLowerCase();
        const rowRole = (row.getAttribute('data-role') || '').toUpperCase();
        const rowStatus = (row.getAttribute('data-status') || '').toUpperCase();

        const matchSearch = (searchVal === '' || rowText.includes(searchVal));
        const matchRole = (roleVal === 'ALL' || rowRole === roleVal);
        const matchStatus = (statusVal === 'ALL' || rowStatus === statusVal);

        if (matchSearch && matchRole && matchStatus) {
          row.style.display = '';
          visibleCount++;
        } else {
          row.style.display = 'none';
        }
      }

      const counter = document.getElementById('showingUsersCount');
      if (counter) counter.textContent = visibleCount;
    }

    // 4. HITUNG ULANG KPI PENGGUNA (ADMINISTRATOR & OPERATOR)
    function recalculateKPIs() {
      const rows = document.querySelectorAll('#usersTableBody tr');
      let total = rows.length;
      let admins = 0;
      let operators = 0;
      let active = 0;

      rows.forEach(r => {
        const role = (r.getAttribute('data-role') || '').toUpperCase();
        const status = (r.getAttribute('data-status') || '').toUpperCase();

        if (role === 'ADMINISTRATOR') admins++;
        else operators++;

        if (status === 'AKTIF') active++;
      });

      const kpiTotal = document.getElementById('kpiTotalUsers');
      if (kpiTotal) kpiTotal.textContent = `${total} Pengguna`;

      const kpiAdmin = document.getElementById('kpiAdminCount');
      if (kpiAdmin) kpiAdmin.textContent = `${admins} Akun`;

      const kpiStaff = document.getElementById('kpiStaffCount');
      if (kpiStaff) kpiStaff.textContent = `${operators} Akun`;
      
      const pct = total > 0 ? Math.round((active / total) * 100) : 0;
      const kpiActive = document.getElementById('kpiActivePercent');
      if (kpiActive) kpiActive.textContent = `${pct}% Aktif`;

      const kpiRatio = document.getElementById('kpiActiveRatio');
      if (kpiRatio) kpiRatio.textContent = `${active}/${total}`;

      const totalCountText = document.getElementById('totalUsersCountText');
      if (totalCountText) totalCountText.textContent = total;
    }

    // 5. TOGGLE STATUS AKTIF / NONAKTIF (TERHUBUNG KE MYSQL BACKEND)
    function toggleUserStatus(rowId) {
      const row = document.getElementById(rowId);
      if (!row) return;

      const userDbId = rowId.replace('row-usr-', '');
      const currentStatus = row.getAttribute('data-status');
      const targetStatus = (currentStatus === 'AKTIF') ? 'NONAKTIF' : 'AKTIF';

      fetch(`/users/${encodeURIComponent(userDbId)}/toggle-status`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': '{{ csrf_token() }}',
          'Accept': 'application/json'
        }
      })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          const newStatus = data.new_status;
          row.setAttribute('data-status', newStatus);

          const statusCell = row.querySelector('.status-badge-cell');
          if (statusCell) {
            if (newStatus === 'AKTIF') {
              statusCell.style.background = '#ccfbf1';
              statusCell.style.color = '#0d8a7c';
              statusCell.style.boxShadow = '0 0 0 0.5px #14b8a6 inset';
              statusCell.innerHTML = '<span style="width: 7px; height: 7px; border-radius: 50%; background: #10b981;"></span> AKTIF';
            } else {
              statusCell.style.background = '#fee2e2';
              statusCell.style.color = '#dc2626';
              statusCell.style.boxShadow = '0 0 0 0.5px #f87171 inset';
              statusCell.innerHTML = '<span style="width: 7px; height: 7px; border-radius: 50%; background: #ef4444;"></span> NONAKTIF';
            }
          }
          recalculateKPIs();
          showToast(data.message, newStatus === 'AKTIF' ? 'success' : 'warning');
        } else {
          showToast(data.message || 'Gagal mengubah status pengguna.', 'danger');
        }
      })
      .catch(err => showToast('Gagal menghubungi server: ' + err.message, 'danger'));
    }

    // 6. MODAL TAMBAH PENGGUNA
    function openAddUserModal() {
      const modal = document.getElementById('userModalBackdrop');
      if (!modal) return;
      const form = document.getElementById('userForm');
      if (form) form.reset();
      document.getElementById('formUserId').value = '';
      document.getElementById('modalHeaderTitle').textContent = 'Tambah Pengguna Baru';
      document.getElementById('formSubmitBtnText').textContent = 'Simpan Pengguna';
      document.getElementById('passwordHelpText').textContent = 'Wajib diisi untuk akun baru (min. 6 karakter)';
      document.getElementById('formPassword').required = true;
      modal.classList.add('show');
      modal.style.display = 'flex';
      modal.style.zIndex = '9999999';
    }

    // 7. MODAL EDIT PENGGUNA (TANPA PARAMETER SHIFT)
    function openEditUserModal(id, name, username, role, status, phone) {
      const modal = document.getElementById('userModalBackdrop');
      if (!modal) return;
      document.getElementById('formUserId').value = id;
      document.getElementById('modalHeaderTitle').textContent = `Edit Pengguna: ${name}`;
      document.getElementById('formSubmitBtnText').textContent = 'Perbarui Data';
      document.getElementById('passwordHelpText').textContent = 'Kosongkan jika tidak ingin mengubah sandi';
      document.getElementById('formPassword').required = false;

      document.getElementById('formFullName').value = name;
      document.getElementById('formUsername').value = username;
      document.getElementById('formRole').value = role;
      document.getElementById('formStatus').value = status;
      document.getElementById('formPhone').value = phone || '';

      modal.classList.add('show');
      modal.style.display = 'flex';
      modal.style.zIndex = '9999999';
    }

    function closeUserModal() {
      const modal = document.getElementById('userModalBackdrop');
      if (modal) {
        modal.classList.remove('show');
        modal.style.display = 'none';
      }
    }

    // 8. SUBMIT FORM TAMBAH / EDIT (TERHUBUNG KE MYSQL BACKEND)
    function handleUserFormSubmit(e) {
      e.preventDefault();
      const userId = document.getElementById('formUserId').value;
      const fullName = document.getElementById('formFullName').value.trim();
      const username = document.getElementById('formUsername').value.trim().replace(/^@/, '');
      const role = document.getElementById('formRole').value;
      const status = document.getElementById('formStatus').value;
      const phone = document.getElementById('formPhone').value.trim();
      const password = document.getElementById('formPassword').value;

      const submitBtn = document.querySelector('#userForm button[type="submit"]');
      if (submitBtn) submitBtn.disabled = true;

      const url = userId ? `/users/${encodeURIComponent(userId)}` : '/users';
      const payload = {
        name: fullName,
        username: username,
        role: role,
        status: status,
        phone: phone,
      };
      if (password) payload.password = password;

      fetch(url, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': '{{ csrf_token() }}',
          'Accept': 'application/json'
        },
        body: JSON.stringify(payload)
      })
      .then(res => res.json().then(data => ({ status: res.status, ok: res.ok, body: data })))
      .then(res => {
        if (submitBtn) submitBtn.disabled = false;
        if (res.ok && res.body.success) {
          showToast(res.body.message, 'success');
          closeUserModal();
          setTimeout(() => location.reload(), 600);
        } else {
          const errMsg = res.body.message || (res.body.errors ? Object.values(res.body.errors).flat().join('<br>') : 'Gagal menyimpan data.');
          showToast(errMsg, 'danger');
        }
      })
      .catch(err => {
        if (submitBtn) submitBtn.disabled = false;
        showToast('Kesalahan server: ' + err.message, 'danger');
      });
    }

    // 9. MODAL RESET PASSWORD
    function openResetPasswordModal(id, name, username) {
      const modal = document.getElementById('resetModalBackdrop');
      if (!modal) return;
      document.getElementById('resetUserId').value = id;
      document.getElementById('resetUserName').textContent = name;
      document.getElementById('resetUserUsername').textContent = `@${username.replace(/^@/, '')}`;
      document.getElementById('newPasswordInput').value = '';
      document.getElementById('confirmPasswordInput').value = '';
      modal.classList.add('show');
      modal.style.display = 'flex';
      modal.style.zIndex = '9999999';
    }

    function closeResetPasswordModal() {
      const modal = document.getElementById('resetModalBackdrop');
      if (modal) {
        modal.classList.remove('show');
        modal.style.display = 'none';
      }
    }

    function confirmResetPassword() {
      const p1 = document.getElementById('newPasswordInput').value;
      const p2 = document.getElementById('confirmPasswordInput').value;
      const name = document.getElementById('resetUserName').textContent;
      const userId = document.getElementById('resetUserId').value;

      if (!p1 || p1.length < 6) {
        showToast('Kata sandi baru minimal harus 6 karakter.', 'warning');
        return;
      }

      if (p1 !== p2) {
        showToast('Konfirmasi kata sandi tidak cocok. Silakan ulangi.', 'warning');
        return;
      }

      fetch(`/users/${encodeURIComponent(userId)}/reset-password`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': '{{ csrf_token() }}',
          'Accept': 'application/json'
        },
        body: JSON.stringify({
          password: p1,
          password_confirmation: p2
        })
      })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          closeResetPasswordModal();
          showToast(`Kata sandi untuk ${name} berhasil diatur ulang di database.`, 'success');
          addAuditLog(`Reset kata sandi pengguna ${name} oleh Administrator.`);
        } else {
          showToast(data.message || 'Gagal mengatur ulang kata sandi.', 'danger');
        }
      })
      .catch(err => {
        showToast('Kesalahan server: ' + err.message, 'danger');
      });
    }

    // 10. HELPER AUDIT LOG DINAMIS
    function addAuditLog(actionText) {
      const list = document.getElementById('activityLogList');
      if (!list) return;

      const now = new Date();
      const timeStr = `${String(now.getHours()).padStart(2, '0')}:${String(now.getMinutes()).padStart(2, '0')} WIB`;

      const li = document.createElement('li');
      li.className = 'activity-log-item';
      li.innerHTML = `
        <i data-lucide="shield-check" style="width: 17px; height: 17px; color: #0d8a7c; flex-shrink: 0; margin-top: 2px;"></i>
        <div style="flex-grow: 1;">
          <div style="display: flex; justify-content: space-between; align-items: center;">
            <span style="font-weight: 700; color: #0f172a;">Aktivitas Keamanan RBAC</span>
            <span style="font-size: 11.5px; color: #64748b; font-family: 'JetBrains Mono', monospace;">${timeStr}</span>
          </div>
          <div style="font-size: 12px; color: #475569;">${actionText}</div>
        </div>
      `;

      list.insertBefore(li, list.firstChild);
      lucide.createIcons();
    }

    // 11. MODAL & PROSES HAPUS PENGGUNA (DELETE AKUN)
    function openDeleteUserModal(id, name, username) {
      const modal = document.getElementById('deleteUserModalBackdrop');
      if (!modal) return;
      document.getElementById('deleteUserId').value = id;
      document.getElementById('deleteUserName').textContent = name;
      document.getElementById('deleteUserUsername').textContent = '@' + username;
      modal.classList.add('show');
      modal.style.display = 'flex';
      modal.style.zIndex = '9999999';
      if (window.lucide) lucide.createIcons();
    }

    function closeDeleteUserModal() {
      const modal = document.getElementById('deleteUserModalBackdrop');
      if (modal) {
        modal.classList.remove('show');
        modal.style.display = 'none';
      }
    }

    function confirmDeleteUser() {
      const id = document.getElementById('deleteUserId').value;
      const name = document.getElementById('deleteUserName').textContent;
      const btn = document.getElementById('btnConfirmDeleteUser');
      if (btn) btn.disabled = true;

      fetch(`/users/${encodeURIComponent(id)}`, {
        method: 'DELETE',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': '{{ csrf_token() }}',
          'Accept': 'application/json'
        }
      })
      .then(res => res.json().then(data => ({ status: res.status, ok: res.ok, body: data })))
      .then(res => {
        if (btn) btn.disabled = false;
        closeDeleteUserModal();

        if (res.ok && res.body.success) {
          showToast(res.body.message || `Akun ${name} berhasil dihapus dari sistem.`, 'success');
          addAuditLog(`Penghapusan akun pengguna ${name} oleh Administrator.`);
          
          const row = document.getElementById(`row-usr-${id}`);
          if (row) {
            row.style.transition = 'all 0.3s ease';
            row.style.opacity = '0';
            setTimeout(() => {
              row.remove();
              recalculateKPIs();
              filterUsersTable();
            }, 300);
          } else {
            setTimeout(() => location.reload(), 600);
          }
        } else {
          showToast(res.body.message || 'Gagal menghapus akun pengguna.', 'danger');
        }
      })
      .catch(err => {
        if (btn) btn.disabled = false;
        closeDeleteUserModal();
        showToast('Kesalahan server: ' + err.message, 'danger');
      });
    }
  </script>
</body>
</html>
