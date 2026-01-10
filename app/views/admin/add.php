<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow border-0">
                <div class="card-header bg-primary text-white">
                    <h4>Tambah Spesies Ikan Baru</h4>
                </div>
                <div class="card-body">
                    <form action="<?= BASEURL; ?>/admin/store" method="POST" enctype="multipart/form-data">
                        
                        <div class="mb-3">
                            <label class="form-label">Nama Ikan</label>
                            <input type="text" name="name" class="form-control" required placeholder="Contoh: Ikan Cupang Purba">
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tingkat Kelangkaan (Rarity)</label>
                                <select name="rarity" class="form-select">
                                    <option value="common">Common (Biasa)</option>
                                    <option value="rare">Rare (Langka)</option>
                                    <option value="legendary">Legendary (Legendaris)</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Harga Jual (Gold)</label>
                                <input type="number" name="price" class="form-control" required min="1">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Gambar Ikan</label>
                            <input type="file" name="image" class="form-control" accept="image/*">
                            <div class="form-text">Format: JPG, PNG, GIF. Kosongkan untuk gambar default.</div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="<?= BASEURL; ?>/admin" class="btn btn-secondary">Batal</a>
                            <button type="submit" class="btn btn-success">Simpan Data</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>