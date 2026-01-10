<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header bg-success text-white text-center py-3 rounded-top-4">
                    <h4 class="mb-0 fw-bold">📝 Daftar Akun Baru</h4>
                </div>
                <div class="card-body p-4">
                    
                    <form action="<?= BASEURL; ?>/auth/store" method="POST">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Username Baru</label>
                            <input type="text" name="username" class="form-control form-control-lg" placeholder="Pilih username unik..." required>
                            <div class="form-text">Gunakan nama yang keren untuk dilihat player lain.</div>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-bold">Password</label>
                            <input type="password" name="password" class="form-control form-control-lg" placeholder="Rahasiakan passwordmu..." required>
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-success btn-lg fw-bold">Buat Akun</button>
                        </div>
                    </form>

                </div>
                <div class="card-footer text-center py-3 bg-light rounded-bottom-4">
                    <small>Sudah punya akun? <a href="<?= BASEURL; ?>/auth/login" class="fw-bold text-decoration-none">Login disini</a></small>
                </div>
            </div>
        </div>
    </div>
</div>