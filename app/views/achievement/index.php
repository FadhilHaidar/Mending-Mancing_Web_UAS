<div class="container mt-4 mb-5">
    <div class="text-center mb-4">
        <h2 class="fw-bold">🏆 Hall of Fame</h2>
    </div>

    <ul class="nav nav-pills justify-content-center mb-4" id="pills-tab" role="tablist">
        <li class="nav-item"><button class="nav-link active rounded-pill px-4" data-bs-toggle="pill" data-bs-target="#tab-ach">🏅 Prestasi Saya</button></li>
        <li class="nav-item ms-2"><button class="nav-link rounded-pill px-4" data-bs-toggle="pill" data-bs-target="#tab-sultan">💰 Top Sultan</button></li>
        <li class="nav-item ms-2"><button class="nav-link rounded-pill px-4" data-bs-toggle="pill" data-bs-target="#tab-kolektor">🐟 Top Kolektor</button></li>
    </ul>

    <div class="tab-content">
        
        <div class="tab-pane fade show active" id="tab-ach">
            <div class="row">
                <?php foreach($data['achievements'] as $ach): 
                    $unlocked = !is_null($ach['unlocked_at']); 
                    $cls = $unlocked ? 'border-success' : 'bg-light text-muted opacity-75';
                ?>
                <div class="col-6 col-md-3 mb-3">
                    <div class="card h-100 text-center p-3 shadow-sm <?= $cls; ?>">
                        <div class="fs-1 mb-2"><?= $ach['icon']; ?></div>
                        <h6 class="fw-bold"><?= $ach['name']; ?></h6>
                        <p class="small mb-0"><?= $ach['description']; ?></p>
                        <?php if($unlocked): ?><small class="text-success fw-bold">Unlocked</small><?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="tab-pane fade" id="tab-sultan">
            <?php $list = $data['top_sultan']; $metric = 'G'; include 'partials/leaderboard_render.php'; ?>
        </div>
        <div class="tab-pane fade" id="tab-kolektor">
            <?php $list = $data['top_collector']; $metric = 'Ekor'; include 'partials/leaderboard_render.php'; ?>
        </div>

    </div>
</div>