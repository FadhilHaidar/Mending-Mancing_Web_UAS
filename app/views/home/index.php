<div class="container mt-4 mb-5">
    
    <div class="p-5 mb-4 bg-dark text-white rounded-3 shadow-lg position-relative overflow-hidden">
        <div style="position: absolute; top: -50%; left: -20%; width: 200%; height: 200%; background: radial-gradient(circle, rgba(13,110,253,0.2) 0%, rgba(0,0,0,0) 70%); pointer-events: none;"></div>
        
        <div class="container-fluid py-3 position-relative">
            <h1 class="display-4 fw-bold">Selamat Datang, <?= isset($data['user']) ? $data['user']['username'] : 'Pemancing'; ?>! 🎣</h1>
            <p class="col-md-8 fs-4">Musim ikan <span class="text-warning fw-bold">Legendary</span> telah tiba. Siapkan joran terbaikmu dan taklukkan perairan!</p>
            <?php if(!isset($_SESSION['user_id'])): ?>
                <a class="btn btn-primary btn-lg fw-bold px-4 shadow" href="<?= BASEURL; ?>/auth/login">Mulai Petualangan</a>
            <?php else: ?>
                <a class="btn btn-warning btn-lg fw-bold px-4 shadow" href="<?= BASEURL; ?>/map">Pergi Memancing</a>
            <?php endif; ?>
        </div>
    </div>

    <div class="row g-4">
        
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-gradient bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-calendar-alt me-2"></i> Event Berlangsung</h5>
                    <span class="badge bg-danger animate__animated animate__pulse animate__infinite">LIVE</span>
                </div>
                <div class="card-body p-0">
                    <div id="eventCarousel" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <div class="p-4 d-flex align-items-center bg-light">
                                    <div class="me-4 text-center display-1">⛈️</div>
                                    <div>
                                        <h4 class="fw-bold text-primary">Badai Petir: Luck +30%</h4>
                                        <p class="mb-0 text-muted">Cuaca Badai sedang melanda Kolam Ikan! Kesempatan mendapatkan mutasi meningkat drastis. Hati-hati energi cepat habis.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="carousel-item">
                                <div class="p-4 d-flex align-items-center bg-light">
                                    <div class="me-4 text-center display-1">💎</div>
                                    <div>
                                        <h4 class="fw-bold text-info">Pasar Ikan: Tukar Berlian</h4>
                                        <p class="mb-0 text-muted">Punya ikan Legendary dobel? Tukarkan sekarang di Pasar Ikan untuk mendapatkan Berlian berharga!</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-warning text-dark fw-bold">
                    <i class="fas fa-trophy me-2"></i> Tangkapan Sultan Terbaru
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        <?php if(empty($data['recent_legendaries'])): ?>
                            <div class="p-3 text-center text-muted small">Belum ada tangkapan legendary hari ini. Jadilah yang pertama!</div>
                        <?php else: ?>
                            <?php foreach($data['recent_legendaries'] as $log): ?>
                                <div class="list-group-item d-flex align-items-center px-3 py-2">
                                    <img src="<?= BASEURL; ?>/img/<?= $log['avatar']; ?>" class="rounded-circle border border-2 border-warning me-2" width="35" height="35">
                                    <div class="flex-grow-1" style="line-height: 1.1;">
                                        <small class="fw-bold d-block text-truncate" style="max-width: 120px;"><?= $log['username']; ?></small>
                                        <span class="text-muted" style="font-size: 0.7rem;">Menangkap</span> 
                                        <span class="fw-bold text-warning" style="font-size: 0.75rem;"><?= $log['fish_name']; ?></span>
                                    </div>
                                    <img src="<?= BASEURL; ?>/img/<?= $log['fish_image']; ?>" width="30">
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm bg-primary text-white text-center p-3">
                <h3 class="fw-bold mb-0"><?= number_format($data['global_stats']['total_players']); ?></h3>
                <small class="text-white-50">Pemancing Aktif</small>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm bg-success text-white text-center p-3">
                <h3 class="fw-bold mb-0"><?= number_format($data['global_stats']['total_caught']); ?></h3>
                <small class="text-white-50">Ikan Ditangkap</small>
            </div>
        </div>
        <div class="col-12 col-md-6 mt-3 mt-md-0">
            <div class="card border-0 shadow-sm bg-white p-3 d-flex align-items-center justify-content-center h-100">
                <p class="mb-0 text-muted fst-italic">"Mancing itu bukan soal ikan, tapi soal kesabaran."</p>
            </div>
        </div>
    </div>

</div>