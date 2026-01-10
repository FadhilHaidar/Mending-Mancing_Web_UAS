<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>🛠️ Dashboard Admin</h2>
        <a href="<?= BASEURL; ?>/admin/add" class="btn btn-primary">+ Tambah Spesies Baru</a>
    </div>

    <div class="card shadow border-0">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Preview</th>
                            <th>Nama Ikan</th>
                            <th>Rarity</th>
                            <th>Harga</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($data['fishes'] as $fish): ?>
                        <tr>
                            <td>
                                <img src="<?= BASEURL; ?>/img/<?= $fish['image']; ?>" width="50" height="50" class="rounded-circle object-fit-cover">
                            </td>
                            <td class="fw-bold"><?= $fish['name']; ?></td>
                            <td>
                                <span class="badge bg-<?= ($fish['rarity'] == 'legendary') ? 'danger' : (($fish['rarity'] == 'rare') ? 'warning' : 'secondary'); ?>">
                                    <?= $fish['rarity']; ?>
                                </span>
                            </td>
                            <td><?= $fish['price']; ?> G</td>
                            <td>
                                <a href="<?= BASEURL; ?>/admin/delete/<?= $fish['id']; ?>" 
                                   class="btn btn-sm btn-danger"
                                   onclick="return confirm('Hapus permanen spesies <?= $fish['name']; ?>?');">
                                   Hapus
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>