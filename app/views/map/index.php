<div class="container mt-4 mb-5 pb-5"> <div class="text-center mb-5">
        <h2 class="fw-bold display-5 text-primary" style="text-shadow: 1px 1px 2px rgba(0,0,0,0.1);">
            🗺️ Peta Dunia
        </h2>
        <p class="text-muted lead">Pilih lokasi memancingmu dan temukan spesies unik di setiap perairan.</p>
    </div>

    <?php if(isset($_GET['msg'])): ?>
        <div class="alert alert-info text-center shadow-sm mx-auto mb-4" style="max-width: 500px;">
            <?php
                $m = $_GET['msg'];
                if($m=='unlocked') echo '✅ Lokasi baru berhasil dibuka! Selamat memancing.';
                elseif($m=='no_key') echo '💎 Berlian tidak cukup untuk membuka lokasi ini.'; // Fallback msg
                elseif($m=='no_diamond') echo '💎 Berlian tidak cukup untuk membuka lokasi ini.';
                elseif($m=='locked') echo '🔒 Lokasi ini masih terkunci. Buka dulu!';
            ?>
        </div>
    <?php endif; ?>

    <div class="row justify-content-center g-4">
        
        <?php foreach($data['maps'] as $map):
            $isLocked = $data['user']['map_access_level'] < $map['id'];
            $cost = ($map['id'] == 2) ? 1 : 3;
            $bgImageFile = 'bg_map_' . $map['id'] . '.jpg';
            $overlayOpacity = $isLocked ? '0.8' : '0.4';
        ?>
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 shadow-lg border-0 overflow-hidden text-white position-relative hover-scale"
                 style="background: url('<?= BASEURL; ?>/img/<?= $bgImageFile; ?>') center/cover no-repeat; min-height: 300px;">

                <div class="card-body d-flex flex-column justify-content-center align-items-center p-5 w-100 h-100"
                     style="background-color: rgba(0,0,0, <?= $overlayOpacity; ?>); transition: all 0.3s ease;">

                    <h3 class="fw-bold mb-3 display-6 text-center" style="text-shadow: 2px 2px 4px rgba(0,0,0,0.8);">
                        <?= $map['name']; ?>
                    </h3>

                    <?php if($isLocked): ?>
                        <div class="text-center animate__animated animate__fadeIn">
                            <div class="display-2 mb-3">🔒</div>
                            <a href="<?= BASEURL; ?>/map/unlock/<?= $map['id']; ?>"
                               class="btn btn-info btn-lg text-white fw-bold px-4 rounded-pill shadow-sm pulse-button"
                               onclick="return confirm('Buka wilayah <?= $map['name']; ?> dengan <?= $cost; ?> Berlian?')">
                                <i class="fas fa-key me-2"></i> Buka (<?= $cost; ?> 💎)
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="text-center mt-2 animate__animated animate__fadeInUp">
                            <span class="badge bg-success mb-3">Siap diarungi!</span>
                            <a href="<?= BASEURL; ?>/map/visit/<?= $map['id']; ?>"
                               class="btn btn-primary btn-lg px-4 fw-bold shadow rounded-pill w-100">
                                🎣 Pergi Memancing
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endforeach; ?>

        <div class="col-md-6 col-lg-4">
            <div class="card h-100 shadow border-0 overflow-hidden text-white position-relative coming-soon-card">
                <div class="bg-placeholder" style="background: url('<?= BASEURL; ?>/img/bg_map_1.jpg') center/cover;"></div>
                <div class="card-body d-flex flex-column justify-content-center align-items-center p-5 w-100 h-100 position-relative z-1">
                    <h3 class="fw-bold mb-2 text-secondary">Rawa Hitam</h3>
                    <div class="display-4 mb-3 text-secondary">🚧</div>
                    <span class="badge bg-secondary px-3 py-2">COMING SOON</span>
                    <p class="mt-3 text-white-50 small text-center">Update v2.1</p>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-4">
            <div class="card h-100 shadow border-0 overflow-hidden text-white position-relative coming-soon-card">
                <div class="bg-placeholder" style="background: url('<?= BASEURL; ?>/img/bg_map_3.jpg') center/cover;"></div>
                <div class="card-body d-flex flex-column justify-content-center align-items-center p-5 w-100 h-100 position-relative z-1">
                    <h3 class="fw-bold mb-2 text-secondary">Palung Mariana</h3>
                    <div class="display-4 mb-3 text-secondary">🐙</div>
                    <span class="badge bg-secondary px-3 py-2">COMING SOON</span>
                    <p class="mt-3 text-white-50 small text-center">Update v2.2</p>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-4">
            <div class="card h-100 shadow border-0 overflow-hidden text-white position-relative coming-soon-card">
                <div class="bg-placeholder" style="background: url('<?= BASEURL; ?>/img/bg_map_2.jpg') center/cover;"></div>
                <div class="card-body d-flex flex-column justify-content-center align-items-center p-5 w-100 h-100 position-relative z-1">
                    <h3 class="fw-bold mb-2 text-secondary">Pulau Terkutuk</h3>
                    <div class="display-4 mb-3 text-secondary">☠️</div>
                    <span class="badge bg-secondary px-3 py-2">COMING SOON</span>
                    <p class="mt-3 text-white-50 small text-center">Special Event</p>
                </div>
            </div>
        </div>

    </div>
</div>

<style>
/* Animasi Tombol Buka */
.pulse-button {
    animation: pulse-animation 2s infinite;
}
@keyframes pulse-animation {
    0% { box-shadow: 0 0 0 0 rgba(13, 202, 240, 0.7); }
    70% { box-shadow: 0 0 0 15px rgba(13, 202, 240, 0); }
    100% { box-shadow: 0 0 0 0 rgba(13, 202, 240, 0); }
}

/* Efek Hover pada Card Aktif */
.hover-scale { transition: transform 0.3s ease; }
.hover-scale:hover { transform: translateY(-5px); }
.hover-scale:hover .card-body { background-color: rgba(0,0,0, 0.3) !important; }

/* Style untuk Dummy Maps (Coming Soon) */
.coming-soon-card {
    background-color: #1a1a1a;
    border: 2px dashed #444 !important;
    cursor: not-allowed;
    min-height: 300px;
}
.coming-soon-card .bg-placeholder {
    position: absolute;
    inset: 0;
    opacity: 0.1; /* Gambar sangat redup */
    filter: grayscale(100%) blur(4px); /* Hitam putih dan blur */
}
.coming-soon-card h3 {
    text-shadow: none;
    letter-spacing: 1px;
}
</style>