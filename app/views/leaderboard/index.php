<style>
    .podium-container {
        display: flex;
        align-items: flex-end; /* Ratakan bawah */
        justify-content: center;
        height: 320px;
        margin-bottom: 30px;
    }
    
    .podium-item {
        text-align: center;
        padding: 10px;
        position: relative;
        transition: transform 0.3s;
    }
    
    .podium-item:hover { transform: translateY(-10px); }

    /* Avatar Styles */
    .podium-avatar {
        width: 80px; height: 80px;
        border-radius: 50%;
        border: 4px solid white;
        box-shadow: 0 4px 6px rgba(0,0,0,0.2);
        margin-bottom: -15px; /* Overlap dengan box */
        position: relative;
        z-index: 2;
        object-fit: cover;
    }
    
    /* Box Podium */
    .podium-box {
        border-radius: 10px 10px 0 0;
        color: white;
        padding-top: 25px;
        position: relative;
        z-index: 1;
        display: flex;
        flex-direction: column;
        justify-content: flex-start;
    }

    /* Rank 1 (Gold) */
    .rank-1 { order: 2; width: 140px; } /* Di tengah */
    .rank-1 .podium-box { height: 180px; background: linear-gradient(to bottom, #FFD700, #FDB931); }
    .rank-1 .podium-avatar { width: 100px; height: 100px; border-color: #FFD700; }
    .crown-icon { position: absolute; top: -35px; left: 50%; transform: translateX(-50%); font-size: 2rem; animation: float 2s infinite ease-in-out; }

    /* Rank 2 (Silver) */
    .rank-2 { order: 1; width: 120px; } /* Kiri */
    .rank-2 .podium-box { height: 140px; background: linear-gradient(to bottom, #C0C0C0, #A9A9A9); }

    /* Rank 3 (Bronze) */
    .rank-3 { order: 3; width: 120px; } /* Kanan */
    .rank-3 .podium-box { height: 110px; background: linear-gradient(to bottom, #cd7f32, #b87333); }

    /* Highlight User Sendiri */
    .its-me { background-color: #e8f5e9 !important; font-weight: bold; border-left: 5px solid #28a745; }

    @keyframes float { 0% { top: -35px; } 50% { top: -45px; } 100% { top: -35px; } }

    /* Mobile Responsive */
    @media (max-width: 768px) {
        .podium-container {
            flex-direction: column; /* Tumpuk ke bawah */
            height: auto;
            align-items: center;
        }
        .podium-item { width: 100% !important; margin-bottom: 20px; order: initial !important; }
        .podium-box { height: auto !important; padding: 20px; border-radius: 10px; }
        .rank-1 { order: 1 !important; } /* Juara 1 tetap paling atas */
        .rank-2 { order: 2 !important; }
        .rank-3 { order: 3 !important; }
        .crown-icon { top: -30px; }
    }
</style>

<div class="container mt-4 mb-5">
    
    <div class="text-center mb-5">
        <h1 class="fw-bold text-primary">🏆 Hall of Fame</h1>
        <p class="text-muted">Para legenda Mending Mancing berkumpul di sini.</p>
    </div>

    <ul class="nav nav-pills justify-content-center mb-5" id="leaderboardTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active rounded-pill px-4" id="sultan-tab" data-bs-toggle="tab" data-bs-target="#sultan" type="button">
                💰 Top Sultan
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link rounded-pill px-4" id="collector-tab" data-bs-toggle="tab" data-bs-target="#collector" type="button">
                🐟 Top Collector
            </button>
        </li>
    </ul>

    <div class="tab-content" id="leaderboardTabContent">
        
        <div class="tab-pane fade show active" id="sultan" role="tabpanel">
            <?php renderLeaderboard($data['top_sultan'], 'Gold', 'G'); ?>
        </div>

        <div class="tab-pane fade" id="collector" role="tabpanel">
            <?php renderLeaderboard($data['top_collector'], 'Spesies', 'Ekor'); ?>
        </div>

    </div>
</div>

<?php
// FUNGSI HELPER UNTUK RENDER AGAR TIDAK DUPLIKAT KODE
function renderLeaderboard($dataList, $labelScore, $unit) {
    // Pisahkan Top 3 dan Sisanya
    $top3 = array_slice($dataList, 0, 3);
    $others = array_slice($dataList, 3);
    $myUsername = $_SESSION['username'];
    ?>

    <div class="podium-container">
        <?php 
            // Array urutan render: Juara 2 (Index 1), Juara 1 (Index 0), Juara 3 (Index 2)
            $order = [1, 0, 2]; 
            foreach($order as $index): 
                if(isset($top3[$index])): 
                    $user = $top3[$index];
                    $rank = $index + 1;
        ?>
            <div class="podium-item rank-<?= $rank; ?>">
                <?php if($rank == 1): ?><div class="crown-icon">👑</div><?php endif; ?>
                
                <img src="<?= BASEURL; ?>/img/<?= $user['avatar']; ?>" class="podium-avatar">
                
                <div class="podium-box shadow">
                    <h5 class="fw-bold mb-0 text-truncate px-2"><?= $user['username']; ?></h5>
                    <small class="badge bg-light text-dark bg-opacity-25 mb-2"><?= $user['selected_title']; ?></small>
                    <h4 class="fw-bold"><?= number_format($user['score']); ?></h4>
                    <small style="font-size: 0.7rem;"><?= $unit; ?></small>
                </div>
            </div>
        <?php endif; endforeach; ?>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4" width="10%">#Rank</th>
                        <th width="50%">Player</th>
                        <th class="text-end pe-4"><?= $labelScore; ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($others)): ?>
                        <tr><td colspan="3" class="text-center py-4 text-muted">Belum ada penantang lainnya.</td></tr>
                    <?php else: ?>
                        <?php foreach($others as $index => $user): ?>
                            <tr class="<?= ($user['username'] == $myUsername) ? 'its-me' : ''; ?>">
                                <td class="ps-4 fw-bold text-muted"><?= $index + 4; ?></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="<?= BASEURL; ?>/img/<?= $user['avatar']; ?>" class="rounded-circle me-3" width="35" height="35">
                                        <div>
                                            <div class="fw-bold"><?= $user['username']; ?> 
                                                <?php if($user['username'] == $myUsername): ?>
                                                    <span class="badge bg-success ms-1" style="font-size:0.6rem">YOU</span>
                                                <?php endif; ?>
                                            </div>
                                            <small class="text-muted" style="font-size: 0.75rem;">[<?= $user['selected_title']; ?>]</small>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-end pe-4 fw-bold text-primary">
                                    <?= number_format($user['score']); ?> <small class="text-muted fw-normal"><?= $unit; ?></small>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php } ?>