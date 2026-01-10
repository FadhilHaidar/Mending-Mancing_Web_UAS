<div class="container mt-5 mb-5">
    
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
        <div>
            <h2 class="fw-bold text-success"><i class="fas fa-store"></i> Marketplace Global</h2>
            <p class="text-muted">Temukan ikan langka dari pemain lain!</p>
        </div>
        
        <form action="<?= BASEURL; ?>/shop/market" method="GET" class="d-flex gap-2">
            <select name="rarity" class="form-select" onchange="this.form.submit()">
                <option value="all">Semua Rarity</option>
                <option value="common" <?= ($data['rarity']=='common')?'selected':''; ?>>Common</option>
                <option value="rare" <?= ($data['rarity']=='rare')?'selected':''; ?>>Rare</option>
                <option value="epic" <?= ($data['rarity']=='epic')?'selected':''; ?>>Epic</option>
                <option value="legendary" <?= ($data['rarity']=='legendary')?'selected':''; ?>>Legendary</option>
            </select>
            <select name="sort" class="form-select" onchange="this.form.submit()">
                <option value="newest" <?= ($data['sort']=='newest')?'selected':''; ?>>Terbaru</option>
                <option value="price_asc" <?= ($data['sort']=='price_asc')?'selected':''; ?>>Termurah</option>
                <option value="price_desc" <?= ($data['sort']=='price_desc')?'selected':''; ?>>Termahal</option>
            </select>
        </form>
    </div>

    <?php if(isset($_GET['msg'])): ?>
        <div class="alert alert-info text-center">
            <?php 
                if($_GET['msg'] == 'success') echo "✅ Transaksi Berhasil! Ikan masuk ke tasmu.";
                if($_GET['msg'] == 'sold_out') echo "❌ Yah, Barang sudah laku duluan!";
                if($_GET['msg'] == 'self_buy') echo "⚠️ Tidak bisa membeli barang sendiri.";
                if($_GET['msg'] == 'no_gold') echo "💸 Gold tidak cukup!";
            ?>
        </div>
    <?php endif; ?>

    <div class="row">
        <?php if(empty($data['listings'])): ?>
            <div class="col-12 text-center py-5">
                <h1 class="display-1 text-muted">🕸️</h1>
                <p class="lead">Pasar sedang sepi. Jadilah yang pertama menjual!</p>
            </div>
        <?php else: ?>
            <?php foreach($data['listings'] as $item): ?>
                
                <?php 
                    $borderClass = 'border-' . $item['rarity']; 
                    $bgClass = 'bg-' . $item['rarity'];
                    $mutationClass = 'mutation-' . $item['mutation'];
                ?>

                <div class="col-6 col-md-3 mb-4">
                    <div class="card h-100 shadow-sm <?= $borderClass; ?> <?= $mutationClass; ?>">
                        
                        <div class="card-header bg-white border-0 d-flex align-items-center p-2">
                            <img src="<?= BASEURL; ?>/img/<?= $item['seller_avatar']; ?>" class="rounded-circle me-2" width="25" height="25">
                            <small class="text-truncate fw-bold text-muted"><?= $item['seller_name']; ?></small>
                        </div>

                        <div class="card-body text-center p-2">
                            <div class="mb-2" style="height: 80px; display: flex; align-items: center; justify-content: center;">
                                <img src="<?= BASEURL; ?>/img/<?= $item['image']; ?>" class="img-fluid" style="max-height: 100%;">
                            </div>
                            
                            <h6 class="fw-bold mb-1 text-truncate"><?= $item['fish_name']; ?></h6>
                            
                            <div class="mb-2">
                                <span class="badge <?= $bgClass; ?>"><?= strtoupper($item['rarity']); ?></span>
                                <?php if($item['mutation'] != 'normal'): ?>
                                    <span class="badge bg-dark pulse-button small"><?= strtoupper($item['mutation']); ?></span>
                                <?php endif; ?>
                            </div>

                            <h4 class="text-success fw-bold">💰 <?= number_format($item['price']); ?></h4>
                        </div>

                        <div class="card-footer bg-transparent border-0 p-2">
                            <?php if($item['seller_name'] == $_SESSION['username']): ?>
                                <button class="btn btn-outline-secondary w-100 btn-sm disabled">Punya Anda</button>
                            <?php else: ?>
                                <a href="<?= BASEURL; ?>/shop/buy_market/<?= $item['listing_id']; ?>" 
                                   class="btn btn-success w-100 btn-sm fw-bold"
                                   onclick="return confirm('Beli <?= $item['fish_name']; ?> seharga <?= number_format($item['price']); ?> G?')">
                                   Beli Sekarang
                                </a>
                            <?php endif; ?>
                        </div>

                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>