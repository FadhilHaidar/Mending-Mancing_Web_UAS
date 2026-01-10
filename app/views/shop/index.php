<div class="container mt-4 mb-5">
    <div class="text-center mb-4">
        <h2 class="fw-bold text-success display-5">🏪 Pusat Perbelanjaan</h2>
        <p class="text-muted">Lengkapi kebutuhan memancingmu di sini.</p>
    </div>

    <?php if(isset($_GET['msg'])): ?>
        <div class="alert alert-info text-center">
            <?php 
                $m = $_GET['msg'];
                if($m=='no_key') echo "❌ Berlian tidak cukup!";
                if($m=='weather_changed') echo "🌤️ Cuaca berhasil diubah!";
                if($m=='energy_restored') echo "⚡ Energi dipulihkan!";
                if($m=='diamond_received') echo "💎 Kamu mendapatkan 1 Berlian!";
                if($m=='bought') echo "✅ Item berhasil dibeli dari pasar!";
                if($m=='sold_out') echo "❌ Item sudah terjual!";
                if($m=='self_buy') echo "❌ Tidak bisa membeli item sendiri!";
            ?>
        </div>
    <?php endif; ?>

    <ul class="nav nav-tabs nav-fill mb-4 fw-bold" id="shopTabs" role="tablist">
        <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-pancing">🎣 Toko Pancing</button></li>
        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-warteg">🍛 Warteg</button></li>
        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-magic" style="color: purple;">🧙‍♀️ Toko Sihir</button></li>
        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-market" style="color: green;">🐟 Pasar Ikan</button></li>
    </ul>

    <div class="tab-content">
        <div class="tab-pane fade show active" id="tab-pancing">
            <h5 class="fw-bold mb-3 text-primary border-bottom pb-2">Joran Pancing</h5>
            <div class="row g-3 mb-4">
                <?php foreach($data['rods'] as $item): ?>
                    <div class="col-6 col-md-3">
                        <div class="card h-100 shadow-sm border-<?= $item['rarity']; ?>">
                            <div class="card-body text-center p-3">
                                <img src="<?= BASEURL; ?>/img/<?= $item['image']; ?>" class="img-fluid mb-2" style="height:70px; object-fit:contain;">
                                <h6 class="fw-bold mb-1 small"><?= $item['name']; ?></h6>
                                <span class="badge bg-<?= $item['rarity']; ?> mb-2" style="font-size:0.6rem"><?= $item['rarity']; ?></span>
                                <div class="text-success fw-bold small mb-2">+<?= $item['luck_stat']; ?> Luck</div>
                                <a href="<?= BASEURL; ?>/shop/buy/<?= $item['id']; ?>" class="btn btn-outline-success btn-sm w-100 fw-bold">💰 <?= number_format($item['price']); ?></a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <h5 class="fw-bold mb-3 text-secondary border-bottom pb-2">Umpan</h5>
            <div class="row g-3">
                <?php foreach($data['baits'] as $item): ?>
                    <div class="col-6 col-md-3">
                        <div class="card h-100 shadow-sm border-<?= $item['rarity']; ?>">
                            <div class="card-body text-center p-3">
                                <img src="<?= BASEURL; ?>/img/<?= $item['image']; ?>" class="img-fluid mb-2" style="height:50px; object-fit:contain;">
                                <h6 class="fw-bold mb-1 small"><?= $item['name']; ?></h6>
                                <span class="badge bg-<?= $item['rarity']; ?> mb-2" style="font-size:0.6rem"><?= $item['rarity']; ?></span>
                                <div class="text-success fw-bold small mb-2">+<?= $item['luck_stat']; ?> Luck</div>
                                <a href="<?= BASEURL; ?>/shop/buy/<?= $item['id']; ?>" class="btn btn-outline-success btn-sm w-100 fw-bold">💰 <?= number_format($item['price']); ?></a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="tab-pane fade" id="tab-warteg">
            <div class="row g-3">
                <?php foreach($data['foods'] as $food): ?>
                    <div class="col-6 mb-3">
                        <div class="card h-100 shadow-sm border-0">
                            <div class="card-body text-center d-flex flex-column justify-content-center align-items-center">
                                <img src="<?= BASEURL; ?>/img/<?= $food['image']; ?>" width="80" class="mb-2">
                                <h5 class="fw-bold"><?= $food['name']; ?></h5>
                                <span class="badge bg-success mb-2">+<?= $food['energy_restore']; ?> Energi</span>
                                <a href="<?= BASEURL; ?>/shop/buy_food/<?= $food['id']; ?>" class="btn btn-primary w-100 btn-sm fw-bold">Makan (<?= number_format($food['price']); ?> G)</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="tab-pane fade" id="tab-magic">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="card bg-purple text-white h-100 shadow" style="background:#4b0082;">
                        <div class="card-body text-center p-4">
                            <div class="fs-1 mb-2">🌧️</div>
                            <h4 class="fw-bold">Pawang Hujan</h4>
                            <p class="small">Ubah cuaca (Biaya: 1 💎)</p>
                            <form action="<?= BASEURL; ?>/magic/pawang_hujan" method="POST">
                                <select name="weather" class="form-select mb-3"><option value="sunny">☀️ Cerah</option><option value="rain">🌧️ Hujan</option><option value="storm">⛈️ Badai</option></select>
                                <button class="btn btn-warning w-100 fw-bold">Ubah</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="card bg-dark text-white h-100 shadow border-secondary">
                        <div class="card-body text-center p-4">
                            <div class="fs-1 mb-2">🔮</div>
                            <h4 class="fw-bold">The Ritual</h4>
                            <p class="small">Enchant Joran (Biaya: 2 💎)</p>
                            <form action="<?= BASEURL; ?>/magic/ritual" method="POST">
                                <select name="rod_id" class="form-select bg-secondary text-white mb-2" required>
                                    <option value="" disabled selected>Pilih Joran...</option>
                                    <?php foreach($data['my_rods'] as $rod): if($rod['type']=='rod'): ?><option value="<?= $rod['equip_id']; ?>"><?= $rod['name']; ?></option><?php endif; endforeach; ?>
                                </select>
                                <select name="fish_id" class="form-select bg-secondary text-white mb-3" required>
                                    <option value="" disabled selected>Pilih Tumbal...</option>
                                    <?php foreach($data['legendaries'] as $fish): ?><option value="<?= $fish['inventory_id']; ?>"><?= $fish['name']; ?></option><?php endforeach; ?>
                                </select>
                                <button class="btn btn-danger w-100 fw-bold">Mulai Ritual</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="tab-pane fade" id="tab-market">
            <div class="card mb-4 bg-info bg-opacity-10 border-info">
                <div class="card-body d-flex justify-content-between align-items-center flex-wrap">
                    <div><h5 class="fw-bold text-info mb-0"><i class="fas fa-gem"></i> Tukar Berlian</h5><small class="text-muted">Legendary -> 1 Berlian!</small></div>
                    <form action="<?= BASEURL; ?>/shop/exchange_diamond" method="POST" class="d-flex mt-2 mt-md-0">
                        <select name="fish_id" class="form-select form-select-sm me-2" style="width: 200px;" required>
                            <option value="" selected disabled>Pilih Ikan...</option>
                            <?php foreach($data['legendaries'] as $f): ?><option value="<?= $f['inventory_id']; ?>"><?= $f['name']; ?></option><?php endforeach; ?>
                        </select>
                        <button type="submit" class="btn btn-info text-white fw-bold btn-sm">Tukar</button>
                    </form>
                </div>
            </div>

            <h5 class="fw-bold text-success mb-3">🏷️ Pasar Pemain</h5>
            <div class="row g-3">
                <?php if(empty($data['listings'])): ?>
                    <div class="col-12 text-center py-5 text-muted">Belum ada ikan yang dijual pemain.</div>
                <?php else: ?>
                    <?php foreach($data['listings'] as $item): 
                        $isOwn = $item['seller_id'] == $_SESSION['user_id'];
                    ?>
                        <div class="col-6 col-md-3">
                            <div class="card h-100 shadow-sm border-<?= $item['rarity']; ?>">
                                <div class="card-body text-center p-2 d-flex flex-column justify-content-between">
                                    <div>
                                        <div class="mb-2 d-flex align-items-center justify-content-center" style="height:60px;">
                                            <img src="<?= BASEURL; ?>/img/<?= $item['image']; ?>" class="img-fluid" style="max-height:100%;">
                                        </div>
                                        <h6 class="fw-bold text-truncate small mb-1"><?= $item['fish_name']; ?></h6>
                                        <span class="badge bg-<?= $item['rarity']; ?>" style="font-size:0.6rem"><?= $item['rarity']; ?></span>
                                        <?php if($item['mutation'] != 'normal'): ?>
                                            <span class="badge bg-dark" style="font-size:0.6rem"><?= $item['mutation']; ?></span>
                                        <?php endif; ?>
                                        <div class="mt-2 fw-bold text-success"><?= number_format($item['price']); ?> G</div>
                                    </div>
                                    
                                    <div class="mt-2">
                                        <div class="d-flex align-items-center justify-content-center mb-2 small text-muted">
                                            <img src="<?= BASEURL; ?>/img/<?= $item['seller_avatar']; ?>" width="20" class="rounded-circle me-1">
                                            <?= $item['seller_name']; ?>
                                        </div>
                                        
                                        <?php if($isOwn): ?>
                                            <button class="btn btn-secondary btn-sm w-100 py-0 disabled" style="font-size:0.8rem">Milikmu</button>
                                        <?php else: ?>
                                            <a href="<?= BASEURL; ?>/shop/buy_market/<?= $item['listing_id']; ?>" class="btn btn-success btn-sm w-100 py-0 fw-bold" style="font-size:0.8rem" onclick="return confirm('Beli ikan ini?')">Beli</a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
    const urlParams = new URLSearchParams(window.location.search);
    if(urlParams.get('tab') === 'market') {
        new bootstrap.Tab(document.querySelector('#shopTabs button[data-bs-target="#tab-market"]')).show();
    } else if(urlParams.get('tab') === 'warteg') {
        new bootstrap.Tab(document.querySelector('#shopTabs button[data-bs-target="#tab-warteg"]')).show();
    } else if(urlParams.get('tab') === 'magic') {
        new bootstrap.Tab(document.querySelector('#shopTabs button[data-bs-target="#tab-magic"]')).show();
    }
</script>