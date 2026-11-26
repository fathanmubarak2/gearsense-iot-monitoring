@php
  if (!isset($config) || !isset($recipients)) {
      try {
          $config = \App\Models\TelegramConfig::first();
          $recipients = \App\Models\TelegramRecipient::orderBy('id', 'asc')->get();
      } catch (\Throwable $e) {
          $config = null;
          $recipients = collect([]);
      }
  }
  $currentRole = session('gearsense_role', 'ADMINISTRATOR');
  $isAdmin = ($currentRole === 'ADMINISTRATOR');
  $currentUser = session('gearsense_user', ($isAdmin ? 'Administrator Sistem' : 'Operator'));
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=1920, initial-scale=1.0">
  <title>Gearsense - Konfigurasi Notifikasi Telegram</title>
  
  <!-- Font: General Sans (Fontshare CDN) -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://api.fontshare.com/v2/css?f[]=general-sans@400,500,600,700,800&display=swap" rel="stylesheet">
  <script src="https://unpkg.com/lucide@latest"></script>

  <!-- Framework: Bootstrap 5 -->
  @include('partials.frameworks')

  <style>
    @font-face {
      font-family: 'General Sans';
      src: url('https://cdn.fontshare.com/wf/MFQT7HFGCR2L5ULQTW6YXYZXXHMPKLJ3/YWQ244D6TACUX5JBKATPOW5I5MGJ3G73/7YY3ZAAE3TRV2LANYOLXNHTPHLXVWTKH.woff2') format('woff2');
      font-weight: 400; font-display: swap; font-style: normal;
    }
    @font-face {
      font-family: 'General Sans';
      src: url('https://cdn.fontshare.com/wf/3RZHWSNONLLWJK3RLPEKUZOMM56GO4LJ/BPDRY7AHVI3MCDXXVXTQQ76H3UXA63S3/SB2OEB6IKZPRR6JT4GFJ2TFT6HBB6AZN.woff2') format('woff2');
      font-weight: 500; font-display: swap; font-style: normal;
    }
    @font-face {
      font-family: 'General Sans';
      src: url('https://cdn.fontshare.com/wf/K46YRH762FH3QJ25IQM3VAXAKCHEXXW4/ISLWQPUZHZF33LRIOTBMFOJL57GBGQ4B/3ZLMEXZEQPLTEPMHTQDAUXP5ZZXCZAEN.woff2') format('woff2');
      font-weight: 600; font-display: swap; font-style: normal;
    }
    @font-face {
      font-family: 'General Sans';
      src: url('https://cdn.fontshare.com/wf/KWXO5X3YW4X7OLUMPO4X24HQJGJU7E2Q/VOWUQZS3YLP66ZHPTXAFSH6YACY4WJHT/NIQ54PVBBIWVK3PFSOIOUJSXIJ5WTNDP.woff2') format('woff2');
      font-weight: 700; font-display: swap; font-style: normal;
    }

    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
      font-family: 'General Sans', sans-serif;
    }

    body {
      background-color: #ffffff;
      color: #0f172a;
      line-height: 1.5;
      overflow-x: auto;
      overflow-y: hidden;
      width: 1920px;
      height: 100vh;
      font-family: 'General Sans', sans-serif;
    }

    .app-container {
      display: flex;
      width: 1920px;
      height: 100vh;
      overflow: hidden;
    }

    .main-content {
      flex: 1;
      padding: 24px 36px 36px 36px;
      overflow-y: auto;
      background: #ffffff;
      height: 100vh;
      scrollbar-width: thin;
      scrollbar-color: #cbd5e1 transparent;
    }

    .main-content::-webkit-scrollbar { width: 6px; }
    .main-content::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }

    /* TOP BAR PILL */
    .topbar-pill-container {
      display: flex;
      justify-content: flex-end;
      margin-bottom: 20px;
    }
    .topbar-pill {
      background: #ffffff;
      border: 1px solid #e2e8f0;
      box-shadow: 0 2px 6px rgba(15, 23, 42, 0.04);
      border-radius: 9999px;
      padding: 6px 18px;
      display: inline-flex;
      align-items: center;
      gap: 12px;
      font-size: 13.5px;
      color: #334155;
    }
    .topbar-pill .separator { color: #cbd5e1; }
    .topbar-pill .time-badge {
      display: flex;
      align-items: center;
      gap: 6px;
      color: #0d8a7c;
      font-weight: 700;
    }

    /* SECTION TITLE */
    .section-title {
      font-size: 15px;
      font-weight: 700 !important;
      color: #0f172a;
      text-transform: uppercase;
      letter-spacing: 0.4px;
      margin-bottom: 14px;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    /* CARDS */
    .clean-card {
      background: #ffffff;
      border: 1px solid #e2e8f0;
      border-radius: 18px;
      padding: 24px 28px;
      box-shadow: 0 4px 16px -2px rgba(15, 23, 42, 0.05);
      transition: box-shadow 0.2s ease;
    }
    .clean-card:hover {
      box-shadow: 0 8px 24px -4px rgba(15, 23, 42, 0.08);
    }

    .clean-card-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 18px;
      padding-bottom: 14px;
      border-bottom: 1px solid #f1f5f9;
    }
    .clean-card-title {
      font-size: 16px;
      font-weight: 700;
      color: #0f172a;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    /* STATUS BANNER */
    .status-banner {
      display: flex;
      align-items: center;
      justify-content: space-between;
      background: linear-gradient(135deg, #f0fdfa 0%, #e6fffa 100%);
      border: 1px solid #99f6e4;
      border-radius: 16px;
      padding: 18px 24px;
      margin-bottom: 22px;
    }
    .status-banner-left {
      display: flex;
      align-items: center;
      gap: 14px;
    }
    .status-banner-icon {
      width: 44px;
      height: 44px;
      border-radius: 12px;
      background: #0d8a7c;
      color: #ffffff;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
    }
    .status-banner-text h4 {
      font-size: 15px;
      font-weight: 700;
      color: #042f2e;
      margin-bottom: 2px;
    }
    .status-banner-text p {
      font-size: 13px;
      color: #0f766e;
      margin: 0;
    }

    /* SWITCH TOGGLE */
    .switch-container {
      display: flex;
      align-items: center;
      gap: 12px;
    }
    .switch {
      position: relative;
      display: inline-block;
      width: 48px;
      height: 26px;
    }
    .switch input { opacity: 0; width: 0; height: 0; }
    .slider {
      position: absolute;
      cursor: pointer;
      top: 0; left: 0; right: 0; bottom: 0;
      background-color: #cbd5e1;
      transition: .3s;
      border-radius: 26px;
    }
    .slider:before {
      position: absolute;
      content: "";
      height: 20px;
      width: 20px;
      left: 3px;
      bottom: 3px;
      background-color: white;
      transition: .3s;
      border-radius: 50%;
      box-shadow: 0 1px 3px rgba(0,0,0,0.2);
    }
    input:checked + .slider { background-color: #0d8a7c; }
    input:checked + .slider:before { transform: translateX(22px); }

    /* 2 COLUMNS GRID */
    .grid-two-cols {
      display: grid;
      grid-template-columns: 1fr 1.15fr;
      gap: 22px;
      margin-bottom: 22px;
    }

    /* FORMS */
    .form-group {
      margin-bottom: 16px;
    }
    .form-label {
      display: block;
      font-size: 13.5px;
      font-weight: 700;
      color: #334155;
      margin-bottom: 6px;
    }
    .form-input {
      width: 100%;
      height: 44px;
      padding: 0 14px;
      border: 1px solid #cbd5e1;
      border-radius: 10px;
      font-size: 14px;
      color: #0f172a;
      background: #ffffff;
      transition: all 0.2s ease;
      outline: none;
    }
    .form-input:focus {
      border-color: #0d8a7c;
      box-shadow: 0 0 0 3px rgba(13, 138, 124, 0.15);
    }
    .form-help {
      display: block;
      font-size: 12px;
      color: #64748b;
      margin-top: 5px;
    }

    /* CHECKBOX GROUP */
    .checkbox-pill-group {
      display: flex;
      flex-direction: column;
      gap: 10px;
      margin-top: 8px;
    }
    .checkbox-pill {
      display: flex;
      align-items: center;
      gap: 10px;
      background: #f8fafc;
      border: 1px solid #e2e8f0;
      border-radius: 10px;
      padding: 10px 14px;
      cursor: pointer;
      transition: all 0.15s ease;
    }
    .checkbox-pill:hover {
      background: #f1f5f9;
      border-color: #cbd5e1;
    }
    .checkbox-pill input[type="checkbox"] {
      width: 18px;
      height: 18px;
      accent-color: #0d8a7c;
      cursor: pointer;
    }
    .checkbox-pill span {
      font-size: 13.5px;
      font-weight: 600;
      color: #1e293b;
    }

    /* BUTTONS */
    .btn-primary-gs {
      background: #0d8a7c;
      color: #ffffff;
      border: none;
      border-radius: 10px;
      padding: 11px 22px;
      font-size: 13.5px;
      font-weight: 700;
      display: inline-flex;
      align-items: center;
      gap: 8px;
      cursor: pointer;
      transition: all 0.2s ease;
    }
    .btn-primary-gs:hover {
      background: #0b786c;
      transform: translateY(-1px);
      box-shadow: 0 4px 12px rgba(13, 138, 124, 0.25);
    }

    .btn-outline-gs {
      background: #ffffff;
      color: #0d8a7c;
      border: 1px solid #0d8a7c;
      border-radius: 10px;
      padding: 10px 20px;
      font-size: 13.5px;
      font-weight: 700;
      display: inline-flex;
      align-items: center;
      gap: 8px;
      cursor: pointer;
      transition: all 0.2s ease;
    }
    .btn-outline-gs:hover {
      background: #f0fdfa;
      transform: translateY(-1px);
    }

    .btn-sm-danger {
      background: #fef2f2;
      color: #ef4444;
      border: 1px solid #fecaca;
      border-radius: 8px;
      padding: 5px 10px;
      font-size: 12px;
      font-weight: 700;
      display: inline-flex;
      align-items: center;
      gap: 4px;
      cursor: pointer;
      transition: all 0.15s ease;
    }
    .btn-sm-danger:hover {
      background: #ef4444;
      color: #ffffff;
    }

    .btn-sm-ping {
      background: #f0fdfa;
      color: #0d8a7c;
      border: 1px solid #99f6e4;
      border-radius: 8px;
      padding: 5px 10px;
      font-size: 12px;
      font-weight: 700;
      display: inline-flex;
      align-items: center;
      gap: 4px;
      cursor: pointer;
      transition: all 0.15s ease;
    }
    .btn-sm-ping:hover {
      background: #0d8a7c;
      color: #ffffff;
    }

    /* RECIPIENT TABLE */
    .recipients-table {
      width: 100%;
      border-collapse: collapse;
      font-size: 13px;
    }
    .recipients-table th {
      background: #f8fafc;
      color: #475569;
      font-weight: 700;
      padding: 10px 14px;
      text-align: left;
      border-bottom: 2px solid #e2e8f0;
    }
    .recipients-table td {
      padding: 12px 14px;
      border-bottom: 1px solid #f1f5f9;
      vertical-align: middle;
    }
    .recipients-table tr:hover td {
      background: #fafafa;
    }

    /* GUIDE SECTION (3 SIMPLE STEPS) */
    .guide-grid {
      display: grid;
      grid-template-columns: 1fr 1fr 1fr;
      gap: 16px;
    }
    .guide-box {
      background: #f8fafc;
      border: 1px solid #e2e8f0;
      border-radius: 14px;
      padding: 18px 20px;
      display: flex;
      gap: 14px;
      align-items: flex-start;
    }
    .guide-step-num {
      width: 32px;
      height: 32px;
      border-radius: 50%;
      background: #0d8a7c;
      color: #ffffff;
      font-weight: 700;
      font-size: 14px;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
    }
    .guide-box h5 {
      font-size: 14px;
      font-weight: 700;
      color: #0f172a;
      margin-bottom: 4px;
    }
    .guide-box p {
      font-size: 12.5px;
      color: #64748b;
      margin: 0;
      line-height: 1.45;
    }

    /* MODAL */
    .custom-modal-backdrop {
      position: fixed;
      top: 0; left: 0; width: 100vw; height: 100vh;
      background: rgba(15, 23, 42, 0.45);
      backdrop-filter: blur(4px);
      z-index: 99999;
      display: none;
      align-items: center;
      justify-content: center;
    }
    .custom-modal-box {
      background: #ffffff;
      border-radius: 18px;
      width: 480px;
      max-width: 90vw;
      box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
      border: 1px solid #e2e8f0;
      overflow: hidden;
      animation: modalSlide 0.2s ease-out;
    }
    @keyframes modalSlide {
      from { transform: translateY(16px); opacity: 0; }
      to { transform: translateY(0); opacity: 1; }
    }
    .custom-modal-header {
      background: #f0fdfa;
      padding: 18px 22px;
      border-bottom: 1px solid #ccfbf1;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    .custom-modal-header h5 {
      font-size: 15px;
      font-weight: 700;
      color: #042f2e;
      margin: 0;
      display: flex;
      align-items: center;
      gap: 8px;
    }
    .custom-modal-body {
      padding: 22px;
    }
    .custom-modal-footer {
      background: #f8fafc;
      padding: 14px 22px;
      border-top: 1px solid #e2e8f0;
      display: flex;
      justify-content: flex-end;
      gap: 10px;
    }

    /* TOAST */
    .toast-container-gs {
      position: fixed;
      bottom: 28px;
      right: 28px;
      z-index: 100000;
      display: flex;
      flex-direction: column;
      gap: 8px;
    }
    .toast-gs {
      background: #ffffff;
      border: 1px solid #e2e8f0;
      border-radius: 12px;
      padding: 12px 18px;
      box-shadow: 0 10px 25px rgba(0,0,0,0.1);
      display: flex;
      align-items: center;
      gap: 10px;
      font-size: 13.5px;
      font-weight: 600;
      color: #0f172a;
      min-width: 300px;
      border-left: 4px solid #0d8a7c;
      transform: translateY(12px);
      opacity: 0;
      transition: all 0.25s ease;
    }
    .toast-gs.show { transform: translateY(0); opacity: 1; }
    .toast-gs.toast-success { border-left-color: #10b981; }
    .toast-gs.toast-error { border-left-color: #ef4444; }
  </style>
</head>
<body>

  <div class="app-container">
    
    <!-- SIDEBAR -->
    @include('partials.sidebar', ['active' => 'konfigurasi-telegram'])

    <!-- MAIN CONTENT -->
    <main class="main-content">

      <!-- TOP BAR STATUS PILL -->
      <div class="topbar-pill-container">
        <div class="topbar-pill">
          <div class="item">
            <span>Operator: <strong>{{ $currentUser }}</strong></span>
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

      <!-- SECTION TITLE -->
      <div class="section-title">
        <i data-lucide="send" style="width: 18px; height: 18px; color: #0d8a7c;"></i>
        <span>PENGATURAN NOTIFIKASI TELEGRAM</span>
      </div>

      <!-- STATUS BANNER -->
      <div class="status-banner">
        <div class="status-banner-left">
          <div class="status-banner-icon">
            <i data-lucide="bell" style="width: 22px; height: 22px;"></i>
          </div>
          <div class="status-banner-text">
            <h4>Status Sistem Peringatan Dini Telegram</h4>
            <p>Saat aktif, sistem otomatis mengirim notifikasi ke ponsel Anda jika sensor mendeteksi roda gigi abnormal/rusak.</p>
          </div>
        </div>

        <div class="switch-container">
          <span id="monitoringStatusLabel" style="font-size: 13.5px; font-weight: 700; color: {{ ($config && $config->status_monitoring) ? '#0d8a7c' : '#64748b' }};">
            {{ ($config && $config->status_monitoring) ? 'Notifikasi Aktif' : 'Notifikasi Nonaktif' }}
          </span>
          <label class="switch">
            <input type="checkbox" id="checkStatusMonitoring" {{ ($config && $config->status_monitoring) ? 'checked' : '' }} onchange="toggleMonitoringSwitch()">
            <span class="slider"></span>
          </label>
        </div>
      </div>

      <!-- 2 COLUMNS: CONFIG FORM & RECIPIENTS TABLE -->
      <div class="grid-two-cols">

        <!-- KARTU KIRI: PENGATURAN BOT TELEGRAM -->
        <div class="clean-card">
          <div class="clean-card-header">
            <div class="clean-card-title">
              <i data-lucide="bot" style="width: 20px; height: 20px; color: #0d8a7c;"></i>
              <span>Akun Bot Telegram</span>
            </div>
          </div>

          <form id="formTelegramConfig" onsubmit="event.preventDefault(); saveConfig();">
            <div class="form-group">
              <label class="form-label" for="inputBotToken">
                Bot Token API
                <span style="font-weight: normal; color: #64748b;">(Wajib)</span>
              </label>
              <div style="position: relative;">
                <input type="password" id="inputBotToken" class="form-input" style="padding-right: 42px; font-family: monospace;" 
                       value="{{ $config ? $config->bot_token : '' }}" placeholder="Contoh: 7129845120:AAHq_3uXk-...">
                <button type="button" onclick="toggleTokenView()" style="position: absolute; right: 10px; top: 10px; background: none; border: none; cursor: pointer; color: #64748b;">
                  <i data-lucide="eye" id="tokenViewIcon" style="width: 18px; height: 18px;"></i>
                </button>
              </div>
              <span class="form-help">Token resmi yang Anda dapatkan saat membuat bot di Telegram (@BotFather).</span>
            </div>

            <div class="form-group">
              <label class="form-label" for="inputBotUsername">
                Username Bot
                <span style="font-weight: normal; color: #64748b;">(Opsional)</span>
              </label>
              <input type="text" id="inputBotUsername" class="form-input" 
                     value="{{ $config ? $config->bot_username : '' }}" placeholder="@GearsenseAlert_bot">
              <span class="form-help">Nama akun bot Telegram Anda agar mudah dikenali.</span>
            </div>

            <div class="form-group" style="margin-top: 18px;">
              <label class="form-label">Kirim Pesan Ketika Terjadi:</label>
              <div class="checkbox-pill-group">
                <label class="checkbox-pill">
                  <input type="checkbox" id="checkNotifyScoring" {{ ($config && $config->notify_on_scoring) ? 'checked' : '' }}>
                  <span>⚠️ Roda Gigi Terkikis (Kondisi Scoring - Peringatan Awal)</span>
                </label>
                <label class="checkbox-pill">
                  <input type="checkbox" id="checkNotifyDamaged" {{ ($config && $config->notify_on_damaged) ? 'checked' : '' }}>
                  <span>🚨 Roda Gigi Rusak (Kondisi Damaged - Bahaya Kritis)</span>
                </label>
              </div>
            </div>

            <div style="display: flex; gap: 10px; margin-top: 24px;">
              <button type="submit" class="btn-primary-gs">
                <i data-lucide="check" style="width: 17px; height: 17px;"></i>
                <span>Simpan Pengaturan</span>
              </button>
              <button type="button" class="btn-outline-gs" onclick="testDispatchMsg()">
                <i data-lucide="send" style="width: 16px; height: 16px;"></i>
                <span>Uji Kirim Pesan</span>
              </button>
            </div>
          </form>
        </div>

        <!-- KARTU KANAN: DAFTAR PENERIMA NOTIFIKASI -->
        <div class="clean-card">
          <div class="clean-card-header">
            <div class="clean-card-title">
              <i data-lucide="users" style="width: 20px; height: 20px; color: #0d8a7c;"></i>
              <span>Daftar Penerima Notifikasi</span>
            </div>
            <button type="button" class="btn-primary-gs" onclick="openModalAdd()" style="padding: 8px 16px; font-size: 13px;">
              <i data-lucide="user-plus" style="width: 15px; height: 15px;"></i>
              <span>Tambah Kontak</span>
            </button>
          </div>

          <div style="overflow-x: auto; max-height: 380px;">
            <table class="recipients-table">
              <thead>
                <tr>
                  <th>Nama Penerima</th>
                  <th>Chat ID</th>
                  <th style="text-align: center;">Status</th>
                  <th style="text-align: center;">Aksi</th>
                </tr>
              </thead>
              <tbody id="recipientsBody">
                @forelse($recipients as $rec)
                  <tr id="row-rec-{{ $rec->id }}">
                    <td>
                      <div style="font-weight: 700; color: #0f172a;">{{ $rec->recipient_name }}</div>
                      <div style="font-size: 11px; color: #64748b;">{{ $rec->type === 'GROUP' ? 'Grup Telegram' : 'Akun Pribadi' }}</div>
                    </td>
                    <td style="font-family: monospace; font-weight: 700; color: #0d8a7c;">
                      {{ $rec->chat_id }}
                    </td>
                    <td style="text-align: center;">
                      <span id="badge-mute-{{ $rec->id }}" style="font-size: 11px; font-weight: 700; padding: 3px 8px; border-radius: 6px; {{ $rec->is_active ? 'background: #ccfbf1; color: #0f766e;' : 'background: #fee2e2; color: #b91c1c;' }}">
                        {{ $rec->is_active ? 'Aktif' : 'Dibisukan' }}
                      </span>
                    </td>
                    <td style="text-align: center;">
                      <div style="display: flex; gap: 6px; justify-content: center;">
                        <button type="button" class="btn-sm-ping" onclick="pingTarget('{{ $rec->chat_id }}', '{{ addslashes($rec->recipient_name) }}')" title="Kirim Pesan Tes">
                          <i data-lucide="send" style="width: 12px; height: 12px;"></i> Tes
                        </button>
                        <button type="button" class="btn-sm-ping" onclick="toggleMute({{ $rec->id }})" title="Mute/Aktifkan">
                          <i data-lucide="{{ $rec->is_active ? 'bell' : 'bell-off' }}" id="icon-mute-{{ $rec->id }}" style="width: 12px; height: 12px;"></i>
                        </button>
                        <button type="button" class="btn-sm-danger" onclick="deleteRec({{ $rec->id }}, '{{ addslashes($rec->recipient_name) }}')" title="Hapus">
                          <i data-lucide="trash-2" style="width: 12px; height: 12px;"></i>
                        </button>
                      </div>
                    </td>
                  </tr>
                @empty
                  <tr id="emptyRow">
                    <td colspan="4" style="text-align: center; color: #94a3b8; padding: 28px 14px;">
                      Belum ada kontak penerima. Klik tombol <strong>+ Tambah Kontak</strong> di atas untuk mendaftarkan akun atau grup Telegram Anda.
                    </td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>

      </div>

      <!-- KARTU BAWAH: PANDUAN CEPAT 3 LANGKAH -->
      <div class="section-title">
        <i data-lucide="help-circle" style="width: 18px; height: 18px; color: #0d8a7c;"></i>
        <span>PANDUAN MUDAH MENGHUBUNGKAN TELEGRAM (3 LANGKAH)</span>
      </div>

      <div class="clean-card" style="margin-bottom: 24px;">
        <div class="guide-grid">
          
          <div class="guide-box">
            <div class="guide-step-num">1</div>
            <div>
              <h5>Buat Bot Telegram</h5>
              <p>Buka Telegram, cari akun <strong>@BotFather</strong>, ketik perintah <code>/newbot</code>, lalu salin kode <strong>Bot Token API</strong> yang diberikan ke form di atas.</p>
            </div>
          </div>

          <div class="guide-box">
            <div class="guide-step-num">2</div>
            <div>
              <h5>Dapatkan Chat ID Anda</h5>
              <p>Buka Telegram, cari akun <strong>@userinfobot</strong> lalu tekan <strong>Start</strong>. Salin angka <strong>Id</strong> Anda (contoh: <code>684920184</code>) ke daftar kontak.</p>
            </div>
          </div>

          <div class="guide-box">
            <div class="guide-step-num">3</div>
            <div>
              <h5>Uji &amp; Selesai</h5>
              <p>Klik tombol <strong>Uji Kirim Pesan</strong>. Jika pesan notifikasi berhasil masuk ke ponsel Anda, sistem siap memantau getaran roda gigi secara otomatis.</p>
            </div>
          </div>

        </div>
      </div>

    </main>
  </div>

  <!-- MODAL TAMBAH KONTAK PENERIMA -->
  <div class="custom-modal-backdrop" id="modalAddRecipient">
    <div class="custom-modal-box">
      <div class="custom-modal-header">
        <h5>
          <i data-lucide="user-plus" style="width: 18px; height: 18px; color: #0d8a7c;"></i>
          <span>Tambah Penerima Notifikasi</span>
        </h5>
        <button type="button" onclick="closeModalAdd()" style="background: none; border: none; cursor: pointer; color: #64748b;">
          <i data-lucide="x" style="width: 20px; height: 20px;"></i>
        </button>
      </div>

      <form id="formAddRecipient" onsubmit="event.preventDefault(); submitAddRecipient();">
        <div class="custom-modal-body">
          <div class="form-group">
            <label class="form-label" for="addRecName">Nama Penerima / Nama Tim</label>
            <input type="text" id="addRecName" class="form-input" placeholder="Contoh: Fathan (Teknisi Lab) / Tim Monitoring" required>
          </div>

          <div class="form-group">
            <label class="form-label" for="addRecChatId">Chat ID Telegram</label>
            <input type="text" id="addRecChatId" class="form-input" style="font-family: monospace;" placeholder="Contoh: 684920184 (atau -100xxx untuk grup)" required>
            <span class="form-help">Bisa berupa ID akun pribadi atau ID grup tempat bot dimasukkan.</span>
          </div>

          <div class="form-group" style="margin-bottom: 0;">
            <label class="form-label" for="addRecType">Tipe Penerima</label>
            <select id="addRecType" class="form-input" style="cursor: pointer;">
              <option value="PERSONAL">Akun Pribadi</option>
              <option value="GROUP">Grup Telegram</option>
            </select>
          </div>
        </div>

        <div class="custom-modal-footer">
          <button type="button" class="btn-outline-gs" onclick="closeModalAdd()" style="padding: 8px 16px;">Batal</button>
          <button type="submit" class="btn-primary-gs" style="padding: 8px 18px;">
            <i data-lucide="plus" style="width: 15px; height: 15px;"></i>
            <span>Simpan Kontak</span>
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- TOAST CONTAINER -->
  <div class="toast-container-gs" id="toastBox"></div>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      lucide.createIcons();
      updateClock();
      setInterval(updateClock, 1000);
    });

    function updateClock() {
      const now = new Date();
      const options = { 
        day: 'numeric', month: 'short', year: 'numeric', 
        hour: '2-digit', minute: '2-digit', second: '2-digit',
        hour12: false
      };
      const formatted = new Intl.DateTimeFormat('id-ID', options).format(now).replace(/\./g, ':');
      const el = document.getElementById('liveTimestamp');
      if (el) el.textContent = formatted + ' WIB';
    }

    function showToast(msg, type = 'info') {
      const box = document.getElementById('toastBox');
      if (!box) return;
      const t = document.createElement('div');
      t.className = `toast-gs ${type === 'success' ? 'toast-success' : type === 'error' ? 'toast-error' : ''}`;
      t.innerHTML = `
        <i data-lucide="${type === 'success' ? 'check-circle' : type === 'error' ? 'alert-circle' : 'info'}" 
           style="width: 18px; height: 18px; color: ${type === 'success' ? '#10b981' : type === 'error' ? '#ef4444' : '#0d8a7c'}; flex-shrink: 0;"></i>
        <span>${msg}</span>
      `;
      box.appendChild(t);
      lucide.createIcons();
      setTimeout(() => t.classList.add('show'), 30);
      setTimeout(() => {
        t.classList.remove('show');
        setTimeout(() => t.remove(), 250);
      }, 3500);
    }

    function toggleTokenView() {
      const input = document.getElementById('inputBotToken');
      const icon = document.getElementById('tokenViewIcon');
      if (!input) return;
      if (input.type === 'password') {
        input.type = 'text';
        icon.setAttribute('data-lucide', 'eye-off');
      } else {
        input.type = 'password';
        icon.setAttribute('data-lucide', 'eye');
      }
      lucide.createIcons();
    }

    function toggleMonitoringSwitch() {
      const isChecked = document.getElementById('checkStatusMonitoring').checked;
      const label = document.getElementById('monitoringStatusLabel');
      if (label) {
        label.textContent = isChecked ? 'Notifikasi Aktif' : 'Notifikasi Nonaktif';
        label.style.color = isChecked ? '#0d8a7c' : '#64748b';
      }
      saveConfig(false);
    }

    function saveConfig(showFeedback = true) {
      const token = document.getElementById('inputBotToken').value.trim();
      const username = document.getElementById('inputBotUsername').value.trim();
      const statusMonitoring = document.getElementById('checkStatusMonitoring').checked;
      const notifyScoring = document.getElementById('checkNotifyScoring').checked;
      const notifyDamaged = document.getElementById('checkNotifyDamaged').checked;

      fetch('{{ route("telegram.save-config") }}', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': '{{ csrf_token() }}',
          'Accept': 'application/json'
        },
        body: JSON.stringify({
          bot_token: token,
          bot_username: username,
          status_monitoring: statusMonitoring,
          notify_on_scoring: notifyScoring,
          notify_on_damaged: notifyDamaged
        })
      })
      .then(res => res.json())
      .then(data => {
        if (showFeedback) {
          if (data.success) {
            showToast('Pengaturan bot Telegram berhasil disimpan!', 'success');
          } else {
            showToast(data.message || 'Gagal menyimpan pengaturan.', 'error');
          }
        }
      })
      .catch(err => {
        if (showFeedback) showToast('Gagal menghubungi server.', 'error');
      });
    }

    function testDispatchMsg(targetChatId = null) {
      const token = document.getElementById('inputBotToken').value.trim();
      if (!token) {
        showToast('Isi Bot Token terlebih dahulu sebelum menguji pesan.', 'error');
        return;
      }
      showToast('Mengirim pesan uji coba ke Telegram...', 'info');

      fetch('{{ route("telegram.test-dispatch") }}', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': '{{ csrf_token() }}',
          'Accept': 'application/json'
        },
        body: JSON.stringify({
          bot_token: token,
          chat_id: targetChatId
        })
      })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          showToast(data.message || 'Pesan tes berhasil dikirim ke Telegram!', 'success');
        } else {
          showToast(data.message || 'Gagal mengirim pesan ke Telegram.', 'error');
        }
      })
      .catch(err => {
        showToast('Gagal menghubungi Telegram API: ' + err.message, 'error');
      });
    }

    function pingTarget(chatId, name) {
      showToast(`Mengirim pesan tes ke ${name}...`, 'info');
      testDispatchMsg(chatId);
    }

    function toggleMute(id) {
      fetch(`/telegram/recipients/${id}/toggle-mute`, {
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
          const badge = document.getElementById(`badge-mute-${id}`);
          const icon = document.getElementById(`icon-mute-${id}`);
          if (badge) {
            badge.textContent = data.is_active ? 'Aktif' : 'Dibisukan';
            badge.style.background = data.is_active ? '#ccfbf1' : '#fee2e2';
            badge.style.color = data.is_active ? '#0f766e' : '#b91c1c';
          }
          if (icon) {
            icon.setAttribute('data-lucide', data.is_active ? 'bell' : 'bell-off');
            lucide.createIcons();
          }
          showToast(data.message, 'success');
        }
      })
      .catch(() => showToast('Gagal mengubah status kontak.', 'error'));
    }

    function deleteRec(id, name) {
      if (!confirm(`Apakah Anda yakin ingin menghapus kontak "${name}"?`)) return;

      fetch(`/telegram/recipients/${id}`, {
        method: 'DELETE',
        headers: {
          'X-CSRF-TOKEN': '{{ csrf_token() }}',
          'Accept': 'application/json'
        }
      })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          const row = document.getElementById(`row-rec-${id}`);
          if (row) row.remove();
          showToast(`Kontak "${name}" berhasil dihapus.`, 'success');
        } else {
          showToast('Gagal menghapus kontak.', 'error');
        }
      })
      .catch(() => showToast('Gagal menghapus kontak.', 'error'));
    }

    function openModalAdd() {
      const modal = document.getElementById('modalAddRecipient');
      if (modal) {
        document.getElementById('formAddRecipient').reset();
        modal.style.display = 'flex';
      }
    }

    function closeModalAdd() {
      const modal = document.getElementById('modalAddRecipient');
      if (modal) modal.style.display = 'none';
    }

    function submitAddRecipient() {
      const name = document.getElementById('addRecName').value.trim();
      const chatId = document.getElementById('addRecChatId').value.trim();
      const type = document.getElementById('addRecType').value;

      if (!name || !chatId) {
        showToast('Nama dan Chat ID wajib diisi.', 'error');
        return;
      }

      fetch('{{ route("telegram.add-recipient") }}', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': '{{ csrf_token() }}',
          'Accept': 'application/json'
        },
        body: JSON.stringify({
          recipient_name: name,
          chat_id: chatId,
          type: type
        })
      })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          showToast('Kontak berhasil didaftarkan!', 'success');
          closeModalAdd();
          setTimeout(() => location.reload(), 400);
        } else {
          showToast(data.message || 'Gagal mendaftarkan kontak.', 'error');
        }
      })
      .catch(() => showToast('Gagal mendaftarkan kontak ke server.', 'error'));
    }

    window.addEventListener('keydown', function(e) {
      if (e.key === 'Escape') closeModalAdd();
    });
  </script>

</body>
</html>