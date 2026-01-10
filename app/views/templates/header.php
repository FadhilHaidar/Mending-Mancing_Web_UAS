<!DOCTYPE html>
<html lang="id" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $data['judul']; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f4f7f6; position: relative; min-height: 100vh; }
        .navbar { background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px); border-bottom: 2px solid #e0e0e0; z-index: 1000; }
        .nav-link { color: #555 !important; font-weight: 600; margin: 0 5px; border-radius: 20px; padding: 8px 16px !important; transition: all 0.3s; }
        .nav-link:hover, .nav-link.active { background-color: #e3f2fd; color: #006994 !important; }
        .navbar-brand { font-weight: 800; color: #006994 !important; }
        .nav-avatar { width: 35px; height: 35px; object-fit: cover; border-radius: 50%; border: 2px solid #006994; }
        
        .border-common { border-color: #6c757d !important; } .bg-common { background-color: #6c757d !important; }
        .border-rare { border-color: #0d6efd !important; } .bg-rare { background-color: #0d6efd !important; }
        .border-epic { border-color: #6f42c1 !important; } .bg-epic { background-color: #6f42c1 !important; }
        .border-legendary { border-color: #ffc107 !important; border-width: 3px !important; } .bg-legendary { background-color: #ffc107 !important; color: black !important; }
        .mutation-big img { transform: scale(1.3); } .mutation-tiny img { transform: scale(0.7); }
        .pulse-button { animation: pulse 2s infinite; }
        @keyframes pulse { 0% { transform: scale(1); box-shadow: 0 0 0 0 rgba(255, 193, 7, 0.7); } 70% { transform: scale(1.05); box-shadow: 0 0 0 10px rgba(255, 193, 7, 0); } 100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(255, 193, 7, 0); } }

        .weather-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; pointer-events: none; z-index: 990; display: none; }
        
        /* UPDATE BAGIAN INI */
        .weather-rain { 
            display: block; 
            /* Ganti URL eksternal dengan BASEURL lokal */
            background: url('<?= BASEURL; ?>/img/weather_rain.png'); 
            animation: rain 0.5s linear infinite; 
            opacity: 0.3; 
        }
        
        .weather-snow { 
            display: block; 
            /* Ganti URL eksternal dengan BASEURL lokal */
            background: url('<?= BASEURL; ?>/img/weather_snow.png'); 
            animation: snow 10s linear infinite; 
            opacity: 0.5; 
        }
        /* ---------------- */

        @keyframes rain { 0% { background-position: 0% 0%; } 100% { background-position: 20% 100%; } }
        @keyframes snow { 0% { background-position: 0 0; } 100% { background-position: 100px 1000px; } }
        .weather-heatwave { display: block; background: radial-gradient(circle, rgba(255,165,0,0.1) 0%, rgba(255,69,0,0.2) 100%); animation: heat 4s infinite alternate; }
        @keyframes heat { 0% { opacity: 0.3; } 100% { opacity: 0.6; } }
        .weather-storm { display: block; background-color: rgba(0,0,0,0.3); animation: thunder 5s infinite; }
        @keyframes thunder { 0%, 95% { background-color: rgba(0,0,0,0.3); } 96% { background-color: rgba(255,255,255,0.4); } 98% { background-color: rgba(0,0,0,0.4); } }
        
        .mutation-fire { box-shadow: 0 0 15px #ff4500; border: 2px solid #ff4500 !important; }
        .mutation-ice { box-shadow: 0 0 15px #00d2ff; border: 2px solid #00d2ff !important; }
        .mutation-electric { box-shadow: 0 0 15px #ffd700; border: 2px solid #ffd700 !important; }
    </style>
</head>

<div class="weather-overlay weather-<?= $data['weather'] ?? 'sunny'; ?>"></div>

<bodyclass="d-flex flex-column min-vh-100">
    <main class="flex-grow-1">
    <script>if (localStorage.getItem('theme') === 'dark') { document.documentElement.setAttribute('data-bs-theme', 'dark'); }</script>

    <?php if(isset($_SESSION['achievements_unlocked'])): ?>
        <div class="position-fixed top-0 start-50 translate-middle-x p-3" style="z-index: 9999">
            <?php foreach($_SESSION['achievements_unlocked'] as $ach): ?>
                <div class="toast show align-items-center text-white bg-success border-0 mb-2 shadow-lg" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="d-flex">
                        <div class="toast-body fs-6">
                            <span class="fs-4 me-2"><?= $ach['icon']; ?></span>
                            <strong>Achievement Unlocked!</strong><br>
                            <?= $ach['name']; ?> (+<?= $ach['reward_gold']; ?> G)
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                </div>
            <?php endforeach; ?>
            <?php unset($_SESSION['achievements_unlocked']); ?>
        </div>
    <?php endif; ?>

<nav class="navbar navbar-expand-lg fixed-top shadow-sm">
  <div class="container">
    <a class="navbar-brand" href="<?= BASEURL; ?>">🎣 Mending Mancing</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="mainNav">
      <ul class="navbar-nav mx-auto">
        <?php if(isset($_SESSION['user_id'])): ?>
            <li class="nav-item"><a class="nav-link <?= (strpos($_GET['url'] ?? '', 'map') !== false) ? 'active' : ''; ?>" href="<?= BASEURL; ?>/map">🗺️ Map</a></li>
            <li class="nav-item"><a class="nav-link <?= (strpos($_GET['url'] ?? '', 'inventory') !== false) ? 'active' : ''; ?>" href="<?= BASEURL; ?>/fishing/inventory">🎒 Tas</a></li>
            <li class="nav-item"><a class="nav-link <?= (strpos($_GET['url'] ?? '', 'shop') !== false) ? 'active' : ''; ?>" href="<?= BASEURL; ?>/shop">🏪 Shop</a></li>
            <li class="nav-item"><a class="nav-link <?= (strpos($_GET['url'] ?? '', 'collection') !== false) ? 'active' : ''; ?>" href="<?= BASEURL; ?>/collection">📖 Pedia</a></li>
            <li class="nav-item"><a class="nav-link <?= (strpos($_GET['url'] ?? '', 'achievement') !== false) ? 'active' : ''; ?>" href="<?= BASEURL; ?>/achievement">🏆 Prestasi</a></li>
        <?php endif; ?>
      </ul>

      <ul class="navbar-nav ms-auto align-items-center">
        <?php if(isset($_SESSION['user_id'])): ?>
            
            <li class="nav-item me-3 d-flex align-items-center">
                <div class="d-flex flex-column align-items-end me-2" style="width: 80px;">
                    <div class="progress w-100" style="height: 8px;">
                        <?php 
                            $energy = $data['user']['energy'] ?? 100;
                            $color = ($energy > 50) ? 'success' : (($energy > 20) ? 'warning' : 'danger');
                        ?>
                        <div class="progress-bar bg-<?= $color; ?>" role="progressbar" style="width: <?= $energy; ?>%"></div>
                    </div>
                </div>
                <span class="fw-bold small text-muted">⚡<?= $energy; ?></span>
            </li>

            <li class="nav-item me-3">
                <div class="d-flex gap-2">
                    <span class="badge bg-warning text-dark fs-6" title="Gold">💰 <?= number_format($data['gold'] ?? 0); ?></span>
                    <span class="badge bg-info text-white fs-6" title="Diamonds">💎 <?= $data['user']['diamonds'] ?? 0; ?></span>
                </div>
            </li>

            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown">
                    <img src="<?= BASEURL; ?>/img/<?= $data['user']['avatar'] ?? 'default_avatar.png'; ?>" class="nav-avatar me-2">
                    <div class="d-flex flex-column" style="line-height: 1;">
                        <span class="fw-bold"><?= $_SESSION['username']; ?></span>
                        <small class="text-muted" style="font-size: 0.7rem;">[<?= $data['user']['selected_title'] ?? 'Pemula'; ?>]</small>
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end border-0 shadow">
                    <li><a class="dropdown-item" href="<?= BASEURL; ?>/profile">👤 Profile Saya</a></li>
                    <li><a class="dropdown-item" href="<?= BASEURL; ?>/settings">⚙️ Settings</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item text-danger" href="<?= BASEURL; ?>/auth/logout">🚪 Logout</a></li>
                </ul>
            </li>
        <?php else: ?>
            <li class="nav-item"><a class="nav-link" href="<?= BASEURL; ?>/auth/login">Login</a></li>
            <li class="nav-item"><a class="btn btn-primary rounded-pill px-4" href="<?= BASEURL; ?>/auth/register">Mulai Main</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>
<div style="margin-top: 80px;"></div>

<script src="<?= BASEURL; ?>/js/AudioManager.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const urlParams = new URLSearchParams(window.location.search);
        const msg = urlParams.get('msg');
        const error = urlParams.get('error');
        const successMsgs = ['berhasil_jual', 'sukses', 'listed', 'energy_restored', 'gear_updated', 'cleansed', 'diamond_received', 'food_bought', 'unlocked', 'weather_changed', 'bought'];
        const errorMsgs = ['gagal', 'nomoney', 'no_gold', 'no_key', 'price_error', 'locked'];

        if (successMsgs.includes(msg)) { if(typeof audioManager !== 'undefined') audioManager.playSFX('ching'); }
        else if (errorMsgs.includes(msg) || error) { if(typeof audioManager !== 'undefined') audioManager.playSFX('error'); }
    });
</script>