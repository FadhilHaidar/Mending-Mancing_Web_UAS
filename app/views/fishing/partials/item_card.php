<?php
// Pastikan variable tersedia
$rarityBorder = 'border-' . $item['rarity']; 
$rarityBadge = 'bg-' . $item['rarity'];
$mutationClass = 'mutation-' . $item['mutation'];
?>
<div class="col-6 col-md-4 col-lg-3">
    <div class="card h-100 shadow-sm <?= $rarityBorder; ?> <?= $mutationClass; ?>">
        <div class="card-body text-center p-2 d-flex flex-column justify-content-between">
            
            <div class="mb-2 d-flex justify-content-center align-items-center" style="height: 100px; overflow: hidden;">
                <img src="<?= BASEURL; ?>/img/<?= $item['image']; ?>" 
                    class="img-fluid" 
                    style="max-height: 90px; object-fit: contain;" 
                    loading="lazy"
                    onerror="this.onerror=null; this.src='<?= BASEURL; ?>/img/default_fish.png';"> 
            </div>
            
            <div>
                <h6 class="card-title fw-bold text-truncate mb-1" style="font-size: 0.9rem;" title="<?= $item['name']; ?>">
                    <?= $item['name']; ?>
                </h6>
                
                <div class="mb-2">
                    <span class="badge <?= $rarityBadge; ?>" style="font-size: 0.6rem;"><?= strtoupper($item['rarity']); ?></span>
                    <?php if($item['mutation'] != 'normal'): ?>
                        <span class="badge bg-dark border border-white pulse-button" style="font-size: 0.6rem;"><?= strtoupper($item['mutation']); ?></span>
                    <?php endif; ?>
                </div>
                
                <p class="small text-muted mb-2 lh-1 fw-bold text-success">
                    <?= number_format($finalPrice); ?> G
                </p>
            </div>

            <div class="d-grid gap-1 mt-auto">
                <?php if ($isListed): ?>
                    <div class="badge bg-warning text-dark mb-1">Sedang Dijual</div>
                    <form action="<?= BASEURL; ?>/shop/cancel_listing" method="POST">
                        <input type="hidden" name="inventory_id" value="<?= $item['id']; ?>">
                        <button type="submit" class="btn btn-sm btn-outline-danger w-100 py-0" style="font-size: 0.7rem;">Batal</button>
                    </form>
                <?php else: ?>
                    <a href="<?= BASEURL; ?>/fishing/sell/<?= $item['id']; ?>" class="btn btn-sm btn-secondary w-100 py-0 mb-1" style="font-size: 0.7rem;" onclick="return confirm('Jual cepat?')">Jual</a>
                    <button type="button" class="btn btn-sm btn-success w-100 py-0" style="font-size: 0.7rem;" data-bs-toggle="modal" data-bs-target="#sellMarketModal<?= $item['id']; ?>">Pasar</button>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php if (!$isListed): ?>
<div class="modal fade" id="sellMarketModal<?= $item['id']; ?>" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-header py-2"><h6 class="modal-title">Jual di Pasar</h6><button class="btn-close" data-bs-dismiss="modal"></button></div>
            <form action="<?= BASEURL; ?>/shop/list_item" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="inventory_id" value="<?= $item['id']; ?>">
                    <div class="text-center"><img src="<?= BASEURL; ?>/img/<?= $item['image']; ?>" width="50"><p class="small mb-0"><?= $item['name']; ?></p></div>
                    <label class="small mt-2">Harga (G)</label>
                    <input type="number" name="price" class="form-control form-control-sm" min="<?= $finalPrice; ?>" value="<?= $finalPrice * 1.5; ?>" required>
                </div>
                <div class="modal-footer py-1"><button type="submit" class="btn btn-sm btn-success w-100">Jual</button></div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>