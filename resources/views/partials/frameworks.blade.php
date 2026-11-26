<!-- =========================================================================
     GEARSENSE - FRAMEWORK INTEGRATION: BOOTSTRAP 5 ONLY
     ========================================================================= -->

<!-- 1. BOOTSTRAP 5.3.3 (CSS & JS BUNDLE WITH POPPER.JS) -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script src="https://unpkg.com/lucide@latest"></script>

<!-- 2. ISOLATION SHIELD: Menjamin 100% konsistensi kanvas 1920px & tipografi General Sans -->
<style>
  /* Menjaga kanvas dan tautan dari efek reset bawaan Bootstrap reboot */
  html, body {
    width: 1920px !important;
    height: 100vh !important;
    max-height: 100vh !important;
    background-color: #ffffff !important;
    color: #1e293b !important;
    font-family: 'General Sans', -apple-system, BlinkMacSystemFont, sans-serif !important;
    overflow-y: hidden !important; /* KUNCI 1: Cegah window browser scroll ke atas */
    overflow-x: auto !important;   /* KUNCI 2: Izinkan scroll horizontal jika monitor < 1920px */
    margin: 0 !important;
    padding: 0 !important;
  }

  /* Khusus halaman login tanpa kanvas kaku 1920px */
  body:has(.login-container) {
    width: 100vw !important;
    height: 100vh !important;
    overflow: hidden !important;
    background-color: #f3fbfa !important;
  }

  a {
    text-decoration: none !important;
  }

  /* Imunitas warna link sidebar agar tetap putih murni & tidak diubah biru oleh Bootstrap reboot */
  .sidebar a,
  .sidebar a:hover,
  .sidebar a:focus,
  .sidebar a:visited,
  .sidebar a:active,
  .sidebar .nav-item,
  .sidebar .nav-item span,
  .sidebar .nav-item i,
  .sidebar .nav-item svg {
    color: #ffffff !important;
    text-decoration: none !important;
  }

  /* Menghilangkan efek biru pada active state Bootstrap button */
  .btn:focus, .btn:active {
    box-shadow: none !important;
  }
</style>
