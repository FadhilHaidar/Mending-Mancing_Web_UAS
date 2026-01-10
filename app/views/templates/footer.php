</main> <footer class="bg-dark text-white pt-5 pb-3 mt-auto border-top border-secondary">
    <div class="container">
        <div class="row">
            <div class="col-md-4 mb-4">
                <h5 class="fw-bold text-primary mb-3">🎣 Mending Mancing</h5>
                <p class="text-white-50 small">
                    Simulator memancing web-based terbaik. 
                    Lupakan bug kodemu, mari tangkap Ikan Legendaris!
                </p>
                <div class="d-flex gap-3">
                    <a href="#" class="text-white-50 hover-white"><i class="fab fa-github fa-lg"></i></a>
                    <a href="#" class="text-white-50 hover-white"><i class="fab fa-discord fa-lg"></i></a>
                    <a href="#" class="text-white-50 hover-white"><i class="fab fa-instagram fa-lg"></i></a>
                </div>
            </div>

            <div class="col-6 col-md-4 mb-4">
                <h6 class="fw-bold text-uppercase mb-3">Jelajahi</h6>
                <ul class="list-unstyled small text-white-50">
                    <li class="mb-2"><a href="<?= BASEURL; ?>/map" class="text-decoration-none text-reset">Peta Dunia</a></li>
                    <li class="mb-2"><a href="<?= BASEURL; ?>/shop" class="text-decoration-none text-reset">Pusat Perbelanjaan</a></li>
                    <li class="mb-2"><a href="<?= BASEURL; ?>/collection" class="text-decoration-none text-reset">Fishpedia</a></li>
                    <li class="mb-2"><a href="<?= BASEURL; ?>/leaderboard" class="text-decoration-none text-reset">Peringkat Sultan</a></li>
                </ul>
            </div>

            <div class="col-6 col-md-4 mb-4">
                <h6 class="fw-bold text-uppercase mb-3">Pengembang</h6>
                <ul class="list-unstyled small text-white-50">
                    <li class="mb-2"><i class="fas fa-code me-2"></i>Berotak Hiu</li>
                    <li class="mb-2"><i class="fas fa-university me-2"></i>UAS Pemrograman Web</li>
                    <li class="mb-2"><i class="fas fa-heart text-danger me-2"></i>Made with Passion</li>
                </ul>
            </div>
        </div>

        <hr class="border-secondary my-4">

        <div class="row align-items-center">
            <div class="col-md-6 text-center text-md-start mb-2 mb-md-0">
                <small class="text-white-50">&copy; <?= date('Y'); ?> Mending Mancing. All rights reserved.</small>
            </div>
            <div class="col-md-6 text-center text-md-end">
                <small class="text-white-50">v2.0.0 (Legendary Update)</small>
            </div>
        </div>
    </div>
</footer>

<style>
    .hover-white:hover { color: #fff !important; transition: 0.3s; }
    footer a:hover { color: #0d6efd !important; transition: 0.3s; }
</style>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>