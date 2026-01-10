<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header bg-primary text-white text-center py-3 rounded-top-4">
                    <h4 class="mb-0 fw-bold">🎣 Login Player</h4>
                </div>
                <div class="card-body p-4">
                    
                    <form action="<?= BASEURL; ?>/auth/login" method="POST">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Username</label>
                            <input type="text" name="username" class="form-control form-control-lg" placeholder="Masukkan username..." required autofocus>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-bold">Password</label>
                            <input type="password" name="password" class="form-control form-control-lg" placeholder="Masukkan password..." required>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg fw-bold">Masuk Game</button>
                        </div>
                    </form>

                </div>
                <div class="card-footer text-center py-3 bg-light rounded-bottom-4">
                    <small>Belum punya akun? <a href="<?= BASEURL; ?>/auth/register" class="fw-bold text-decoration-none">Daftar disini</a></small>
                </div>
            </div>
        </div>
    </div>
</div>