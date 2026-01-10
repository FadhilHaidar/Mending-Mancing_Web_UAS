<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h1 class="fw-bold text-primary">⚒️ The Armory</h1>
            <p class="text-muted">Upgrade Joranmu untuk menangkap Leviathan!</p>
        </div>
        <div>
            <a href="<?= BASEURL; ?>/shop" class="btn btn-outline-secondary me-2">Pasar Ikan</a>
            <a href="<?= BASEURL; ?>/profile" class="btn btn-outline-primary">Cek Inventory Saya</a>
        </div>
    </div>

    <?php if(isset($_GET['msg'])): ?>
        <div class="alert alert-info text-center mb-4">
            <?php 
                if($_GET['msg']=='sukses') echo "✅ Pembelian Berhasil!";
                if($_GET['msg']=='nomoney') echo "❌ Gold tidak cukup!";
                if($_GET['msg']=='owned') echo "⚠️ Kamu sudah punya Joran ini (Max 1).";
            ?>
        </div>
    <?php endif; ?>

    <div class="row">
        <?php foreach($data['items'] as $item): ?>
            <div class="col-md-3 col-6 mb-4">
                <div class="card h-100 shadow-sm border-<?= $item['rarity']; ?>">
                    <div class="card-header bg-transparent text-center border-0 pt-4">
                        <img src="<?= BASEURL; ?>/img/<?= $item['image']; ?>" class="img-fluid" style="height: 100px; object-fit: contain;">
                    </div>
                    <div class="card-body text-center">
                        <h5 class="fw-bold"><?= $item['name']; ?></h5>
                        
                        <div class="mb-2">
                            <span class="badge bg-secondary"><?= strtoupper($item['type']); ?></span>
                            <span class="badge bg-<?= $item['rarity']; ?>"><?= strtoupper($item['rarity']); ?></span>
                        </div>

                        <p class="small text-muted mb-2"><?= $item['description']; ?></p>
                        
                        <h4 class="text-success fw-bold">+<?= $item['luck_stat']; ?> Luck</h4>
                    </div>
                    <div class="card-footer bg-light border-0 p-3">
                        <div class="d-grid">
                            <a href="<?= BASEURL; ?>/shop/buy_equipment/<?= $item['id']; ?>" 
                               class="btn btn-primary fw-bold"
                               onclick="return confirm('Beli <?= $item['name']; ?> seharga <?= number_format($item['price']); ?> G?');">
                               💰 <?= number_format($item['price']); ?> G
                               <?php if($item['type'] == 'bait') echo '<span class="badge bg-white text-dark ms-1">x10</span>'; ?>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>