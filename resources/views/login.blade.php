<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=1920, initial-scale=1.0">
  <title>Login - Gearsense Monitoring</title>

          <!-- Font: General Sans (Fontshare CDN) -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://api.fontshare.com/v2/css?f[]=general-sans@400,500,600,700,800&display=swap" rel="stylesheet">
  
  <!-- Lucide Icons -->
  <script src="https://unpkg.com/lucide@latest"></script>

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
    }

    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
      font-family: 'General Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    }

    body {
      background-color: #f3fbfa;
      color: #1e293b;
      overflow-x: hidden;
      width: 100vw;
      height: 100vh;
      display: flex;
      -webkit-font-smoothing: antialiased;
      -moz-osx-font-smoothing: grayscale;
      font-family: 'General Sans', sans-serif;
    }

    .login-title, .brand-tagline {
      font-family: 'General Sans', sans-serif;
      font-weight: 700;
      letter-spacing: -0.015em;
    }

    /* SUB JUDUL LOGIN HARUS BOLD (font-weight: 700) */
    .login-subtitle {
      font-family: 'General Sans', sans-serif !important;
      font-weight: 700 !important;
      font-size: 15px;
      color: #ffffff;
      margin-bottom: 30px;
      letter-spacing: 0.2px;
    }

    .login-container {
      display: flex;
      width: 100%;
      height: 100vh;
      background-color: #f3fbfa;
      position: relative;
    }

    /* ================= LEFT SIDE (BRANDING & MOTTO) ================= */
    .left-section {
      flex: 1.38; /* Sekitar 58% lebar layar persis perbandingan di screenshot */
      height: 100%;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      padding: 40px;
      background-color: #f3fbfa;
    }

    .branding-box {
      display: flex;
      flex-direction: column;
      align-items: center;
      max-width: 540px;
      text-align: center;
    }

    .brand-logo {
      width: 440px;
      max-width: 90%;
      height: auto;
      object-fit: contain;
      margin-bottom: 28px;
    }

    .brand-tagline {
      font-size: 20px;
      font-weight: 700;
      color: #075049;
      line-height: 1.5;
      text-align: center;
      letter-spacing: -0.2px;
    }

    /* ================= RIGHT SIDE (TEAL LOGIN CARD) ================= */
    .right-section {
      flex: 1; /* Sekitar 42% lebar layar */
      height: 100%;
      background-color: #0d9488;
      border-top-left-radius: 32px;
      border-bottom-left-radius: 32px;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 50px 70px;
      box-shadow: -8px 0 24px rgba(13, 148, 136, 0.15);
    }

    .login-form-wrapper {
      width: 100%;
      max-width: 440px;
      display: flex;
      flex-direction: column;
    }

    .login-title {
      font-size: 28px;
      font-weight: 800;
      color: #000000;
      margin-bottom: 6px;
      letter-spacing: -0.4px;
    }

    .login-subtitle {
      font-family: 'General Sans', sans-serif !important;
      font-weight: 700 !important;
      font-size: 15px;
      color: #ffffff;
      margin-bottom: 30px;
      letter-spacing: 0.2px;
    }

    .login-form {
      display: flex;
      flex-direction: column;
    }

    /* INPUT CARD (WHITE / PALE MINT CONTAINER) */
    .input-card {
      background: #f3fbfa;
      border-radius: 16px;
      padding: 12px 18px;
      display: flex;
      align-items: center;
      gap: 16px;
      margin-bottom: 18px;
      box-shadow: 0 0 0 0.5px rgba(0, 0, 0, 0.04) inset;
      transition: all 0.2s ease;
    }

    .input-card:focus-within {
      box-shadow: 0 0 0 1.5px #03796f inset, 0 4px 14px rgba(0, 0, 0, 0.08);
      background: #ffffff;
    }

    .input-icon-box {
      display: flex;
      align-items: center;
      justify-content: center;
      color: #0d9488;
      flex-shrink: 0;
    }

    .input-content {
      display: flex;
      flex-direction: column;
      flex-grow: 1;
      gap: 3px;
      overflow: hidden;
    }

    .input-label {
      font-size: 12.5px;
      font-weight: 700;
      color: #0d9488;
      letter-spacing: 0.2px;
      line-height: 1.2;
    }

    .input-divider {
      height: 1px;
      background: #cbd5e1;
      width: 100%;
      margin: 1px 0;
    }

    .input-field {
      border: none;
      background: transparent;
      outline: none;
      font-size: 14px;
      color: #0f172a;
      font-weight: 600;
      font-family: inherit;
      padding: 2px 0 0 0;
      width: 100%;
    }

    .input-field::placeholder {
      color: #64748b;
      font-weight: 500;
      font-size: 13.5px;
    }

    /* TOGGLE PASSWORD VISIBILITY BUTTON */
    .toggle-password-btn {
      background: none;
      border: none;
      cursor: pointer;
      color: #0d9488;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 4px;
      transition: transform 0.15s ease, color 0.15s ease;
      flex-shrink: 0;
    }

    .toggle-password-btn:hover {
      color: #03796f;
      transform: scale(1.1);
    }

    /* OPTIONS ROW (INGAT SAYA & LUPA PASSWORD) */
    .options-row {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-top: 4px;
      margin-bottom: 24px;
    }

    .remember-me-label {
      display: flex;
      align-items: center;
      gap: 10px;
      cursor: pointer;
      user-select: none;
      font-size: 14px;
      font-weight: 600;
      color: #ffffff;
    }

    /* Custom Checkbox */
    .remember-checkbox-input {
      display: none;
    }

    .custom-checkbox-box {
      width: 18px;
      height: 18px;
      background: #ffffff;
      border-radius: 5px;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: all 0.2s ease;
      flex-shrink: 0;
    }

    .remember-checkbox-input:checked + .custom-checkbox-box {
      background: #03796f;
    }

    .remember-checkbox-input:checked + .custom-checkbox-box::after {
      content: '';
      width: 5px;
      height: 9px;
      border: solid #ffffff;
      border-width: 0 2px 2px 0;
      transform: rotate(45deg);
      margin-bottom: 2px;
    }

    .forgot-password-link {
      color: #ffffff;
      font-size: 14px;
      font-weight: 600;
      text-decoration: none;
      transition: opacity 0.2s ease;
      cursor: pointer;
    }

    .forgot-password-link:hover {
      opacity: 0.85;
      text-decoration: underline;
    }

    /* TOMBOL MASUK */
    .submit-login-btn {
      width: 100%;
      height: 52px;
      background: #03796f;
      color: #ffffff;
      border-radius: 14px;
      font-size: 17px;
      font-weight: 700;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
      border: none;
      cursor: pointer;
      box-shadow: 0 4px 14px rgba(0, 0, 0, 0.16);
      transition: all 0.2s ease;
      margin-bottom: 18px;
    }

    .submit-login-btn:hover {
      background: #02635a;
      transform: translateY(-2px);
      box-shadow: 0 6px 18px rgba(0, 0, 0, 0.22);
    }

    .submit-login-btn:active {
      transform: translateY(0);
    }

    .arrow-icon {
      width: 20px;
      height: 20px;
      transition: transform 0.2s ease;
    }

    .submit-login-btn:hover .arrow-icon {
      transform: translateX(4px);
    }

    /* FOOTER NOTE */
    .admin-note-text {
      text-align: center;
      color: #ffffff;
      font-size: 12.5px;
      font-weight: 500;
      letter-spacing: 0.1px;
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
  </style>
</head>
<body>

  <div class="login-container">
    
    <!-- LEFT SIDE: LOGO & TAGLINE -->
    <section class="left-section">
      <div class="branding-box">
        <!-- Logo Bersih Gearsense Transparan -->
        <img src="{{ asset('assets/logo_teal_clean.png') }}" alt="Gearsense Logo" class="brand-logo" onerror="this.src='{{ asset('assets/logo.png') }}'">
        
        <!-- Tagline 3 Baris Persis Screenshot -->
        <p class="brand-tagline">
          Pantau kondisi mesin secara real-time,<br>
          deteksi potensi kerusakan lebih awal,<br>
          dan tingkatkan efisiensi operasional
        </p>
      </div>
    </section>

    <!-- RIGHT SIDE: TEAL LOGIN CARD -->
    <section class="right-section">
      <div class="login-form-wrapper">
        
        <!-- Title & Subtitle -->
        <h1 class="login-title">Selamat datang di Gearsense</h1>
        <p class="login-subtitle">Masuk ke akun anda</p>

        <!-- Form Login -->
        @if(session('error') || $errors->any())
          <div style="background: rgba(239, 68, 68, 0.2); border: 1px solid #ef4444; color: #fee2e2; padding: 10px 14px; border-radius: 10px; font-size: 13px; font-weight: 600; margin-bottom: 16px; display: flex; align-items: center; gap: 8px;">
            <i data-lucide="alert-circle" style="width: 18px; height: 18px; flex-shrink: 0; color: #fca5a5;"></i>
            <span>{{ session('error') ?: $errors->first() }}</span>
          </div>
        @endif

        <form class="login-form" id="loginForm" method="POST" action="/login">
          @csrf
          
          <!-- INPUT 1: USERNAME -->
          <div class="input-card">
            <div class="input-icon-box">
              <i data-lucide="user" style="width: 24px; height: 24px;"></i>
            </div>
            <div class="input-content">
              <label for="usernameInput" class="input-label">Username</label>
              <div class="input-divider"></div>
              <input 
                type="text" 
                id="usernameInput" name="username" 
                class="input-field" 
                placeholder="Masukan username anda" 
                required 
                autocomplete="username"
              >
            </div>
          </div>

          <!-- INPUT 2: PASSWORD -->
          <div class="input-card">
            <div class="input-icon-box">
              <i data-lucide="lock" style="width: 24px; height: 24px;"></i>
            </div>
            <div class="input-content">
              <label for="passwordInput" class="input-label">Password</label>
              <div class="input-divider"></div>
              <input 
                type="password" 
                id="passwordInput" name="password" 
                class="input-field" 
                placeholder="Masukan password anda" 
                required 
                autocomplete="current-password"
              >
            </div>
            <!-- Toggle Eye Visibility -->
            <button type="button" class="toggle-password-btn" id="togglePasswordBtn" title="Lihat Kata Sandi">
              <i data-lucide="eye" id="eyeIcon" style="width: 20px; height: 20px;"></i>
            </button>
          </div>

          <!-- OPTIONS: INGAT SAYA & LUPA PASSWORD -->
          <div class="options-row">
            <label class="remember-me-label">
              <input type="checkbox" id="rememberMe" class="remember-checkbox-input" checked>
              <span class="custom-checkbox-box"></span>
              <span>Ingat saya</span>
            </label>
            <a href="#" class="forgot-password-link" onclick="handleForgotPassword(event)">Lupa password ?</a>
          </div>

          <!-- SUBMIT BUTTON -->
          <button type="submit" class="submit-login-btn">
            <span>Masuk</span>
            <i data-lucide="arrow-right" class="arrow-icon"></i>
          </button>

          <!-- FOOTER NOTE -->
          <div class="admin-note-text">
            Hubungi Admin jika lupa kata sandi
          </div>

        </form>

      </div>
    </section>

  </div>

  <script>
    // Inisialisasi ikon Lucide
    lucide.createIcons();

    // Toggle Password Visibility
    const togglePasswordBtn = document.getElementById('togglePasswordBtn');
    const passwordInput = document.getElementById('passwordInput');

    togglePasswordBtn.addEventListener('click', () => {
      const isPassword = passwordInput.getAttribute('type') === 'password';
      passwordInput.setAttribute('type', isPassword ? 'text' : 'password');

      // Ganti ikon eye / eye-off
      togglePasswordBtn.innerHTML = isPassword 
        ? '<i data-lucide="eye-off" style="width: 20px; height: 20px;"></i>' 
        : '<i data-lucide="eye" style="width: 20px; height: 20px;"></i>';
      lucide.createIcons();
    });

    // Simpan username ke localStorage saat submit form untuk backup
    document.getElementById('loginForm').addEventListener('submit', function() {
      const usernameVal = document.getElementById('usernameInput').value.trim();
      if (usernameVal) {
        localStorage.setItem('gearsense_user', usernameVal);
      }
    });

    // Handle Lupa Password
    function handleForgotPassword(e) {
      e.preventDefault();
      alert('Silakan hubungi administrator lab atau dosen pembimbing untuk melakukan reset kata sandi akun Gearsense.');
    }
  </script>
</body>
</html>
