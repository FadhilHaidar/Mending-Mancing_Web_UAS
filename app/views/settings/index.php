<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <h4 class="mb-0 fw-bold"><i class="fas fa-cog"></i> Pengaturan</h4>
                </div>
                <div class="card-body">
                    
                    <h6 class="text-muted text-uppercase mb-3">Tampilan</h6>
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h5 class="mb-1">🌙 Mode Gelap</h5>
                            <small class="text-muted">Aktifkan tema gelap untuk kenyamanan mata.</small>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="darkModeSwitch" style="transform: scale(1.5);">
                        </div>
                    </div>

                    <hr>

                    <h6 class="text-muted text-uppercase mb-3 mt-4">Audio</h6>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h5 class="mb-1">🔊 Musik Latar</h5>
                            <small class="text-muted">Putar musik santai saat memancing.</small>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" checked>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-1">🔔 Efek Suara</h5>
                            <small class="text-muted">Suara 'Splash' saat mendapatkan ikan.</small>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" checked>
                        </div>
                    </div>

                    <hr>

                    <h6 class="text-muted text-uppercase mb-3 mt-4">Zona Bahaya</h6>
                    <button class="btn btn-outline-danger w-100 text-start" onclick="alert('Fitur ini belum tersedia di Demo!');">
                        <i class="fas fa-trash-alt me-2"></i> Hapus Akun Permanen
                    </button>

                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // 1. Cek LocalStorage saat halaman dimuat
    const toggleSwitch = document.querySelector('#darkModeSwitch');
    const currentTheme = localStorage.getItem('theme');

    if (currentTheme) {
        document.documentElement.setAttribute('data-bs-theme', currentTheme);
        if (currentTheme === 'dark') {
            toggleSwitch.checked = true;
        }
    }

    // 2. Event Listener saat tombol diklik
    toggleSwitch.addEventListener('change', function(e) {
        if (e.target.checked) {
            document.documentElement.setAttribute('data-bs-theme', 'dark');
            localStorage.setItem('theme', 'dark');
        } else {
            document.documentElement.setAttribute('data-bs-theme', 'light');
            localStorage.setItem('theme', 'light');
        }
    });
</script>