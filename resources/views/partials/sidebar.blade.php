<style>
  /* =========================================================================
   * AUTHORITATIVE CENTRALIZED SIDEBAR STYLES (GEARSENSE)
   * Mengatur tampilan sidebar, flip floating button, dan transisi collapsed
   * ========================================================================= */
  .sidebar {
    width: 280px !important;
    height: 100vh !important;
    position: sticky !important;
    top: 0 !important;
    background-color: #0d8a7c !important;
    padding: 24px 0 !important;
    display: flex !important;
    flex-direction: column !important;
    align-items: center !important;
    flex-shrink: 0 !important;
    box-shadow: 0 0 0 0.5px rgba(0, 0, 0, 0.1) inset !important;
    z-index: 9999 !important;
    overflow: visible !important; /* WAJIB VISIBLE AGAR TOMBOL FLIP MELAYANG TIDAK TERPOTONG */
    transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1), padding 0.3s ease !important;
  }

  .sidebar.sidebar-no-transition,
  .sidebar.sidebar-no-transition * {
    transition: none !important;
  }

  .sidebar.collapsed {
    width: 84px !important;
    padding: 24px 0 !important;
    overflow: visible !important; /* WAJIB VISIBLE AGAR TOMBOL FLIP TETAP MUNCUL BEBAS DI KANAN */
  }

  /* TOMBOL FLIP MELAYANG BEBAS - TEPAT DI TENGAH VERTICAL (TOP: 50%) */
  .sidebar-floating-toggle {
    position: absolute !important;
    top: 50% !important;
    transform: translateY(-50%) !important;
    left: calc(100% + 14px) !important;
    width: 44px !important;
    height: 48px !important;
    background-color: #0b786c !important;
    border-radius: 12px !important;
    border: none !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    cursor: pointer !important;
    box-shadow: 0 6px 16px rgba(0, 0, 0, 0.16), 0 2px 6px rgba(0, 0, 0, 0.08) !important;
    z-index: 99999 !important;
    visibility: visible !important;
    opacity: 1 !important;
    pointer-events: auto !important;
    transition: left 0.3s cubic-bezier(0.4, 0, 0.2, 1), transform 0.2s ease, background-color 0.2s ease !important;
  }

  .sidebar-floating-toggle:hover {
    background-color: #086358 !important;
    transform: translateY(-50%) scale(1.08) !important;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.22) !important;
  }

  .sidebar.collapsed .sidebar-floating-toggle {
    left: calc(100% + 14px) !important;
    display: flex !important;
    visibility: visible !important;
    opacity: 1 !important;
    z-index: 99999 !important;
    pointer-events: auto !important;
  }

  .flip-btn-icon-svg {
    width: 24px !important;
    height: 24px !important;
    fill: #ffffff !important;
    transition: transform 0.3s ease !important;
  }

  .sidebar.collapsed .flip-btn-icon-svg {
    transform: scaleX(-1) !important;
  }

  /* CEGAH BOCOR TEKS KATEGORI SAAT COLLAPSED (IMAGE 1 FIX) */
  .sidebar.collapsed .menu-category-title {
    display: none !important;
    visibility: hidden !important;
    width: 0 !important;
    height: 0 !important;
    overflow: hidden !important;
    margin: 0 !important;
    padding: 0 !important;
    opacity: 0 !important;
  }

  /* PROFIL ADMIN */
  .sidebar .admin-profile-box {
    width: 244px !important;
    background: #097266 !important;
    box-shadow: 0 0 0 0.5px rgba(255, 255, 255, 0.2) inset !important;
    padding: 12px 16px !important;
    border-radius: 14px !important;
    margin: 0 auto 24px auto !important;
    box-sizing: border-box !important;
    font-family: 'General Sans', sans-serif !important;
    display: flex !important;
    flex-direction: column !important;
    align-items: center !important;
    justify-content: center !important;
    text-align: center !important;
    gap: 3px !important;
  }

  .sidebar .admin-sub-title {
    font-size: 13.5px !important;
    font-weight: 600 !important;
    color: #ccfbf1 !important;
    white-space: nowrap !important;
    overflow: hidden !important;
    text-overflow: ellipsis !important;
    max-width: 100% !important;
    font-family: 'General Sans', sans-serif !important;
  }

  .sidebar .admin-main-name {
    font-size: 15px !important;
    font-weight: 800 !important;
    letter-spacing: 0.5px !important;
    color: #ffffff !important;
    white-space: nowrap !important;
    font-family: 'General Sans', sans-serif !important;
  }

  .sidebar.collapsed .admin-profile-box {
    width: 52px !important;
    height: 52px !important;
    padding: 0 !important;
    border-radius: 12px !important;
    justify-content: center !important;
    align-items: center !important;
  }

  .sidebar.collapsed .admin-sub-title,
  .sidebar.collapsed .admin-main-name {
    display: none !important;
  }

  .sidebar.collapsed .admin-profile-box::after {
    content: '' !important;
    display: block !important;
    width: 22px !important;
    height: 22px !important;
    background: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='white' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z'/%3E%3C/svg%3E") no-repeat center !important;
    background-size: contain !important;
  }

  /* BRAND LOGO CONTAINER */
  .sidebar .brand-logo-container {
    width: 244px;
    height: 70px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 24px;
  }

  .sidebar .logo-full-img {
    display: block !important;
    max-width: 210px !important;
    max-height: 52px !important;
    object-fit: contain !important;
    margin: 0 auto !important;
  }

  .sidebar .logo-icon-only {
    display: none !important;
    width: 44px !important;
    height: 44px !important;
    object-fit: contain !important;
  }

  .sidebar.collapsed .brand-logo-container {
    width: 100% !important;
    height: 60px !important;
    display: flex !important;
    justify-content: center !important;
    align-items: center !important;
    margin: 0 auto 24px auto !important;
    padding: 0 !important;
  }

  .sidebar.collapsed .logo-full-img {
    display: none !important;
  }

  .sidebar.collapsed .logo-icon-only {
    display: block !important;
    width: 44px !important;
    height: 44px !important;
    object-fit: contain !important;
    margin: 0 auto !important;
  }

  /* =========================================================================
   * JUDUL KATEGORI MENU (Menu pemantauan & Menu Administrator)
   * ========================================================================= */
  .sidebar .menu-category-title {
    width: 244px !important;
    margin: 14px auto 8px auto !important;
    font-size: 13px !important;
    font-weight: 700 !important;
    color: rgba(255, 255, 255, 0.95) !important;
    text-transform: capitalize !important;
    letter-spacing: 0.2px !important;
    padding-left: 14px !important;
    text-align: left !important;
    box-sizing: border-box !important;
    font-family: 'General Sans', sans-serif !important;
  }

  .sidebar.collapsed .menu-category-title {
    display: none !important;
    visibility: hidden !important;
    width: 0 !important;
    height: 0 !important;
    overflow: hidden !important;
    margin: 0 !important;
    padding: 0 !important;
    opacity: 0 !important;
  }

  /* =========================================================================
   * 1. NAV LIST NORMAL (UNCOLLAPSED: RATA KIRI, LEBAR 244px SIMETRIS)
   * ========================================================================= */
  .sidebar:not(.collapsed) .nav-list {
    width: 100% !important;
    padding: 0 !important;
    margin: 0 auto !important;
    list-style: none !important;
    display: flex !important;
    flex-direction: column !important;
    align-items: center !important;
    gap: 6px !important;
  }

  .sidebar:not(.collapsed) .nav-list li {
    width: 100% !important;
    padding: 0 !important;
    margin: 0 !important;
    display: flex !important;
    justify-content: center !important;
    align-items: center !important;
    list-style: none !important;
  }

  .sidebar:not(.collapsed) .nav-item {
    display: flex !important;
    align-items: center !important;
    justify-content: flex-start !important;
    gap: 16px !important;
    width: 244px !important;
    margin: 0 auto !important;
    padding: 12px 18px !important;
    border-radius: 14px !important;
    color: #ffffff !important;
    font-size: 14.5px !important;
    font-weight: 600 !important;
    text-decoration: none !important;
    cursor: pointer !important;
    transition: all 0.2s ease !important;
    position: relative !important;
    text-align: left !important;
    background: transparent !important;
    box-shadow: none !important;
  }

  .sidebar:not(.collapsed) .nav-item i,
  .sidebar:not(.collapsed) .nav-item svg,
  .sidebar:not(.collapsed) .nav-item .menu-icon-svg {
    flex-shrink: 0 !important;
    width: 22px !important;
    height: 22px !important;
    color: #ffffff !important;
    margin: 0 !important;
  }

  .sidebar:not(.collapsed) .nav-item span {
    display: inline-block !important;
    visibility: visible !important;
    width: auto !important;
    white-space: nowrap !important;
    overflow: hidden !important;
    text-overflow: ellipsis !important;
    flex-grow: 1 !important;
    text-align: left !important;
    color: #ffffff !important;
    font-family: 'General Sans', sans-serif !important;
    font-size: 14px !important;
    font-weight: 600 !important;
  }

  .sidebar:not(.collapsed) .nav-item:hover {
    background: rgba(255, 255, 255, 0.16) !important;
    color: #ffffff !important;
  }

  .sidebar:not(.collapsed) .nav-item.active {
    background: #097266 !important;
    box-shadow: 0 0 0 0.5px rgba(255, 255, 255, 0.2) inset !important;
    color: #ffffff !important;
  }

  /* =========================================================================
   * 2. NAV LIST COLLAPSED (FLIP: SEMUA IKON PRESISI SEJAJAR DI TENGAH X=42px)
   * ========================================================================= */
  .sidebar.collapsed .nav-list {
    width: 100% !important;
    padding: 0 !important;
    margin: 0 auto !important;
    list-style: none !important;
    display: flex !important;
    flex-direction: column !important;
    align-items: center !important;
    gap: 6px !important;
  }

  .sidebar.collapsed .nav-list li {
    width: 100% !important;
    padding: 0 !important;
    margin: 0 !important;
    display: flex !important;
    justify-content: center !important;
    align-items: center !important;
    list-style: none !important;
  }

  .sidebar.collapsed .nav-item,
  .sidebar.collapsed .nav-item.nav-sub-text {
    width: 52px !important;
    height: 50px !important;
    padding: 0 !important;
    margin: 0 auto !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    gap: 0 !important;
    text-align: center !important;
    border-radius: 14px !important;
    color: #ffffff !important;
    background: transparent !important;
  }

  .sidebar.collapsed .nav-item i,
  .sidebar.collapsed .nav-item svg,
  .sidebar.collapsed .nav-item .menu-icon-svg {
    margin: 0 auto !important;
    display: block !important;
    flex-shrink: 0 !important;
    width: 22px !important;
    height: 22px !important;
    color: #ffffff !important;
  }

  .sidebar.collapsed .nav-item span {
    display: none !important;
    visibility: hidden !important;
    width: 0 !important;
    height: 0 !important;
  }

  .sidebar.collapsed .nav-item:hover {
    background: rgba(255, 255, 255, 0.16) !important;
    color: #ffffff !important;
  }

  .sidebar.collapsed .nav-item.active {
    background: #097266 !important;
    box-shadow: 0 0 0 0.5px rgba(255, 255, 255, 0.2) inset !important;
    color: #ffffff !important;
  }

  /* TOMBOL LOGOUT DENGAN FRAME MERAH KONTRAS & COLLAPSED */
  .sidebar .logout-btn {
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    gap: 10px !important;
    width: 244px !important;
    margin: 0 auto !important; /* <--- KUNCI: Menjadikan tombol presisi simetris di tengah */
    padding: 12px 18px !important;
    border-radius: 12px !important;
    background: rgba(239, 68, 68, 0.16) !important;
    box-shadow: 0 0 0 1px rgba(239, 68, 68, 0.35) inset !important;
    color: #ffffff !important;
    border: none !important;
    cursor: pointer !important;
    font-size: 14px !important;
    font-weight: 700 !important;
    transition: all 0.2s ease !important;
    font-family: 'General Sans', sans-serif !important;
  }
  .sidebar .logout-btn:hover {
    background: rgba(239, 68, 68, 0.85) !important;
    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.35) !important;
    color: #ffffff !important;
  }
  .sidebar.collapsed .logout-btn {
    width: 52px !important;
    padding: 12px 0 !important;
    justify-content: center !important;
    margin: 0 auto !important;
  }
  .sidebar.collapsed .logout-btn span {
    display: none !important;
    visibility: hidden !important;
    width: 0 !important;
  }
  /* CONTAINER SCROLLABLE MENU (KUNCI AGAR TOMBOL LOGOUT SELALU MUNCUL DI BAWAH SAAT UNFLIPPED) */
  .sidebar .sidebar-scrollable-content {
    width: 100% !important;
    flex: 1 1 auto !important;
    min-height: 0 !important; /* WAJIB AGAR FLEX-CHILD BISA SCROLL */
    overflow-y: auto !important;
    overflow-x: hidden !important;
    display: flex !important;
    flex-direction: column !important;
    align-items: center !important;
    padding-bottom: 8px !important;
    /* Sleek slim scrollbar */
    scrollbar-width: thin !important;
    scrollbar-color: rgba(255, 255, 255, 0.25) transparent !important;
  }

  .sidebar .sidebar-scrollable-content::-webkit-scrollbar {
    width: 4px !important;
  }

  .sidebar .sidebar-scrollable-content::-webkit-scrollbar-track {
    background: transparent !important;
  }

  .sidebar .sidebar-scrollable-content::-webkit-scrollbar-thumb {
    background: rgba(255, 255, 255, 0.25) !important;
    border-radius: 4px !important;
  }

  .sidebar.collapsed .sidebar-scrollable-content {
    overflow-x: hidden !important;
    scrollbar-width: none !important;
  }

  .sidebar.collapsed .sidebar-scrollable-content::-webkit-scrollbar {
    display: none !important;
  }

    /* SIDEBAR FOOTER SELALU TERKUNCI MUNCUL DI BAGIAN BAWAH LAYAR */
  .sidebar .sidebar-footer {
    width: 100% !important;
    flex-shrink: 0 !important;
    display: flex !important;
    justify-content: center !important;
    align-items: center !important;
    padding: 12px 0 16px 0 !important;
    margin-top: auto !important;
    background-color: #0d8a7c !important;
    z-index: 50 !important;
    border-top: 1px solid rgba(255, 255, 255, 0.08) !important;
  }

  /* Form pembungkus agar menengahkan button logout */
  .sidebar .sidebar-footer form {
    width: 100% !important;
    display: flex !important;
    justify-content: center !important;
    align-items: center !important;
    margin: 0 !important;
  }

  .sidebar.collapsed .sidebar-footer {
    padding: 12px 0 16px 0 !important;
    border-top: 1px solid rgba(255, 255, 255, 0.08) !important;
  }
</style>
@php
  $currentRoute = Route::currentRouteName() ?? '';
  $activePage = $active ?? $currentRoute;

  // Deteksi role: Administrator vs Operator/User (Murni berdasarkan sesi login yang sah)
  $currentRole = session('gearsense_role', 'ADMINISTRATOR');
  $isAdmin = ($currentRole === 'ADMINISTRATOR');
  $currentUser = session('gearsense_user', ($isAdmin ? 'Administrator Sistem' : 'Operator'));
@endphp

<aside class="sidebar sidebar-no-transition" id="mainSidebar">
  <script>
    // EKSEKUSI INSTAN: Terapkan class collapsed seketika sebelum layar digambar browser
    (function() {
      try {
        if (localStorage.getItem('gearsense_sidebar_collapsed') === '1') {
          document.getElementById('mainSidebar').classList.add('collapsed');
        }
      } catch (e) {}
    })();
  </script>
  <!-- TOMBOL FLIP MELAYANG BEBAS (TERPISAH / TIDAK MENEMPEL DINDING SIDEBAR) -->
  <button class="sidebar-floating-toggle" id="flipSidebarBtn" type="button" onclick="toggleSidebar()" title="Lipat / Buka Sidebar">
    <svg class="flip-btn-icon-svg" viewBox="0 0 24 24">
      <path d="M4 4h2v16H4V4zm6 3h10v3H10V7zm0 7h7v3h-7v-3z" />
    </svg>
  </button>

  <!-- LOGO BESAR (PRESISI DI TENGAH DENGAN MARGIN SIMETRIS) -->
  <div class="brand-logo-container">
    <img src="{{ asset('assets/logo_teal_clean.png') }}" alt="Gearsense Logo" class="logo-full-img" onerror="this.src='{{ asset('assets/logo.png') }}'">
    <img src="{{ asset('assets/logo_icon.png') }}" alt="Gearsense Icon" class="logo-icon-only">
  </div>

  <!-- CONTAINER SCROLLABLE MENU (KUNCI AGAR TOMBOL LOGOUT SELALU MUNCUL DI BAWAH) -->
  <div class="sidebar-scrollable-content">

  @if($isAdmin)
  <!-- ================= PROFIL ADMINISTRATOR ================= -->
  <div class="admin-profile-box" id="adminProfileBox">
    <div class="admin-sub-title">{{ session('gearsense_username', session('gearsense_user', 'admin')) }}</div>
    <div class="admin-main-name">ADMINISTRATOR</div>
  </div>

  <!-- 1. MENU PEMANTAUAN (ADMIN) -->
  <div class="menu-category-title">Menu pemantauan</div>
  <ul class="nav-list">
    <li>
      <a href="{{ route('dashboard') }}" class="nav-item {{ $activePage === 'dashboard' ? 'active' : '' }}" data-tooltip="Overview">
        <svg class="menu-icon-svg" viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <line x1="18" y1="20" x2="18" y2="10"></line>
          <line x1="12" y1="20" x2="12" y2="4"></line>
          <line x1="6" y1="20" x2="6" y2="14"></line>
        </svg>
        <span>Overview</span>
      </a>
    </li>
    <li>
      <a href="{{ route('live-monitoring') }}" class="nav-item {{ $activePage === 'live-monitoring' ? 'active' : '' }}" data-tooltip="Live Monitoring">
        <svg class="menu-icon-svg" viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline>
        </svg>
        <span>Live Monitoring</span>
      </a>
    </li>
    <li>
      <a href="{{ route('riwayat-pengukuran') }}" class="nav-item {{ $activePage === 'riwayat-pengukuran' ? 'active' : '' }}" data-tooltip="Riwayat Pengukuran">
        <svg class="menu-icon-svg" viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
          <polyline points="14 2 14 8 20 8"></polyline>
          <line x1="16" y1="13" x2="8" y2="13"></line>
          <line x1="16" y1="17" x2="8" y2="17"></line>
          <polyline points="10 9 9 9 8 9"></polyline>
        </svg>
        <span>Riwayat pengukuran</span>
      </a>
    </li>
  </ul>

  <!-- 2. MENU ADMINISTRATOR (HANYA MUNCUL DI DASHBOARD ADMIN) -->
  <div class="menu-category-title" style="margin-top: 14px;">Menu Administrator</div>
  <ul class="nav-list">
    <li>
      <a href="{{ route('manajemen-pengguna') }}" class="nav-item {{ $activePage === 'manajemen-pengguna' ? 'active' : '' }}" data-tooltip="Manajemen Pengguna">
        <svg class="menu-icon-svg" viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
          <circle cx="9" cy="7" r="4"></circle>
          <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
          <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
        </svg>
        <span>Manajemen Pengguna</span>
      </a>
    </li>
    <li>
      <a href="{{ route('konfigurasi-telegram') }}" class="nav-item {{ $activePage === 'konfigurasi-telegram' ? 'active' : '' }}" data-tooltip="Konfigurasi Telegram">
        <svg class="menu-icon-svg" viewBox="0 0 24 24" width="22" height="22" fill="currentColor">
          <path d="M19 2H5a3 3 0 0 0-3 3v10a3 3 0 0 0 3 3h3v3.5a.5.5 0 0 0 .8.4L13 18h6a3 3 0 0 0 3-3V5a3 3 0 0 0-3-3zm-11 9a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm4 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm4 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3z"/>
          <circle cx="19.5" cy="4.5" r="2.5" fill="currentColor"/>
        </svg>
        <span>Konfigurasi Telegram</span>
      </a>
    </li>
  </ul>

  @else
  <!-- ================= PROFIL USER / OPERATOR (PERSIS ROLE ADMIN) ================= -->
  <div class="admin-profile-box" id="userProfileBox">
    <div class="admin-sub-title">{{ session('gearsense_username', session('gearsense_user', 'operator')) }}</div>
    <div class="admin-main-name">OPERATOR</div>
  </div>

  <!-- MENU PEMANTAUAN (USER) -->
  <div class="menu-category-title">Menu pemantauan</div>
  <ul class="nav-list">
    <li>
      <a href="{{ route('dashboard') }}" class="nav-item {{ $activePage === 'dashboard' ? 'active' : '' }}" data-tooltip="Overview">
        <svg class="menu-icon-svg" viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <line x1="18" y1="20" x2="18" y2="10"></line>
          <line x1="12" y1="20" x2="12" y2="4"></line>
          <line x1="6" y1="20" x2="6" y2="14"></line>
        </svg>
        <span>Overview</span>
      </a>
    </li>
    <li>
      <a href="{{ route('live-monitoring') }}" class="nav-item {{ $activePage === 'live-monitoring' ? 'active' : '' }}" data-tooltip="Live Monitoring">
        <svg class="menu-icon-svg" viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline>
        </svg>
        <span>Live Monitoring</span>
      </a>
    </li>
    <li>
      <a href="{{ route('riwayat-pengukuran') }}" class="nav-item {{ $activePage === 'riwayat-pengukuran' ? 'active' : '' }}" data-tooltip="Riwayat Pengukuran">
        <svg class="menu-icon-svg" viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
          <polyline points="14 2 14 8 20 8"></polyline>
          <line x1="16" y1="13" x2="8" y2="13"></line>
          <line x1="16" y1="17" x2="8" y2="17"></line>
          <polyline points="10 9 9 9 8 9"></polyline>
        </svg>
        <span>Riwayat pengukuran</span>
      </a>
    </li>
  </ul>
  @endif

  
  </div>

  <!-- SIDEBAR FOOTER (LOGOUT) -->
    <!-- SIDEBAR FOOTER SELALU MUNCUL DI BAWAH (DETACHED FROM SCROLL) -->
  <div class="sidebar-footer">
    <form id="sidebarLogoutForm" action="{{ route('logout') }}" method="POST" style="width: 100%; display: flex; justify-content: center; align-items: center; margin: 0;">
      @csrf
      <button type="submit" class="logout-btn" title="Logout">
        <svg class="menu-icon-svg" viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
          <polyline points="16 17 21 12 16 7"></polyline>
          <line x1="21" y1="12" x2="9" y2="12"></line>
        </svg>
        <span>Logout</span>
      </button>
    </form>
  </div>
</aside>

<script>
  /**
   * =========================================================================
   * GEARSENSE SEAMLESS NAVIGATION & SIDEBAR PERSISTENCE ENGINE (SPA MODE)
   * Menghilangkan refresh halaman penuh, menjaga sidebar tetap tenang & fixed,
   * dan mengingat status lipat (collapsed/expanded) secara permanen.
   * =========================================================================
   */

  // 1. NONAKTIFKAN KELAS PEMBUNGKAM TRANSISI SETELAH INITIAL RENDER SELESAI
  setTimeout(function() {
    const sb = document.getElementById('mainSidebar');
    if (sb) sb.classList.remove('sidebar-no-transition');
  }, 60);

  // 2. FUNGSI: toggleSidebar()
  // TUJUAN: Melipat (collapse) atau membuka (expand) sidebar & simpan state ke localStorage
  function toggleSidebar() {
    const sb = document.getElementById('mainSidebar');
    if (sb) {
      sb.classList.remove('sidebar-no-transition');
      sb.classList.toggle('collapsed');
      const isCol = sb.classList.contains('collapsed');
      try {
        localStorage.setItem('gearsense_sidebar_collapsed', isCol ? '1' : '0');
      } catch (e) {}

      setTimeout(function() {
        window.dispatchEvent(new Event('resize'));
        document.querySelectorAll('canvas').forEach(c => {
          const ch = typeof Chart !== 'undefined' ? Chart.getChart(c) : null;
          if (ch && typeof ch.resize === 'function') ch.resize();
        });
      }, 310);
    }
  }

  // 4. PELACAKAN & CLEANUP TIMER OTOMATIS (Mencegah Ghost Interval / Kebocoran Memori)
  if (!window._gearsenseIntervalTracked) {
    window._gearsenseIntervalTracked = true;
    window._gearsenseActiveIntervals = [];
    const origSetInterval = window.setInterval;
    window.setInterval = function(fn, delay, ...args) {
      const id = origSetInterval(fn, delay, ...args);
      window._gearsenseActiveIntervals.push(id);
      return id;
    };
  }

  function cleanupGearsenseResources() {
    // Bersihkan semua timer interval dari halaman sebelumnya
    if (window._gearsenseActiveIntervals && Array.isArray(window._gearsenseActiveIntervals)) {
      window._gearsenseActiveIntervals.forEach(id => clearInterval(id));
      window._gearsenseActiveIntervals = [];
    }
    // Hancurkan instance Chart.js aktif agar kanvas baru dapat digambar bersih
    document.querySelectorAll('canvas').forEach(canvas => {
      try {
        const chart = typeof Chart !== 'undefined' ? Chart.getChart(canvas) : null;
        if (chart && typeof chart.destroy === 'function') {
          chart.destroy();
        }
      } catch (e) {}
    });
  }

  // 5. INDIKATOR PROGRES ATAS PADA NAVIGASI MENU
  function showProgressBar() {
    let bar = document.getElementById('gearsenseTopProgress');
    if (!bar) {
      bar = document.createElement('div');
      bar.id = 'gearsenseTopProgress';
      bar.style.cssText = 'position:fixed;top:0;left:0;height:3px;width:0%;background:linear-gradient(90deg,#0d8a7c,#14b8a6,#5eead4);z-index:99999999;transition:width 0.22s ease, opacity 0.2s ease;box-shadow:0 0 10px rgba(20,184,166,0.8);';
      document.body.appendChild(bar);
    }
    bar.style.opacity = '1';
    bar.style.width = '45%';
    setTimeout(() => { if (bar.style.width === '45%') bar.style.width = '85%'; }, 80);
  }

  // Tampilkan visual feedback seketika saat tautan menu diklik, biarkan browser memuat halaman secara penuh & bersih
  document.addEventListener('click', function(e) {
    const link = e.target.closest('.sidebar .nav-list a, .role-pill-btn, a[href*="?role="]');
    if (!link) return;

    const href = link.getAttribute('href');
    if (!href || href.startsWith('#') || href.startsWith('javascript:')) return;
    if (link.target === '_blank') return;

    // Tampilkan loading bar seketika tanpa mencegah perpindahan halaman native
    showProgressBar();
  });

  // 6. INISIALISASI OTOMATIS IKON LUCIDE (FAIL-SAFE UNTUK SEMUA HALAMAN)
  function initLucideIcons() {
    if (typeof lucide !== 'undefined' && typeof lucide.createIcons === 'function') {
      lucide.createIcons();
    }
  }
  initLucideIcons();
  document.addEventListener('DOMContentLoaded', initLucideIcons);
</script>