<?php if(empty($list)): ?>
    <div class="text-center py-5 text-muted">Belum ada data.</div>
<?php else: ?>
    <div class="d-flex align-items-end justify-content-center mb-5" style="height: 280px;">
        <?php 
        $top3 = array_slice($list, 0, 3);
        $order = [1, 0, 2]; // Juara 2, 1, 3
        foreach($order as $i): if(isset($top3[$i])): $u = $top3[$i]; $rank = $i+1; 
            $h = ($rank==1)?180:($rank==2?140:110);
            $bg = ($rank==1)?'#FFD700':($rank==2?'#C0C0C0':'#cd7f32');
        ?>
            <div class="text-center mx-2" style="order:<?= ($rank==1)?2:($rank==2?1:3); ?>">
                <?php if($rank==1) echo '<div class="fs-1 mb-1">👑</div>'; ?>
                <a href="<?= BASEURL; ?>/profile/index/<?= $u['id']; ?>">
                    <img src="<?= BASEURL; ?>/img/<?= $u['avatar']; ?>" class="rounded-circle border border-3 border-white shadow" width="<?= ($rank==1)?100:80; ?>" height="<?= ($rank==1)?100:80; ?>" style="object-fit:cover; margin-bottom:-15px; position:relative; z-index:2; background:#fff;">
                </a>
                <div class="shadow rounded-top pt-4 px-3 d-flex flex-column justify-content-start" style="height:<?= $h; ?>px; background:<?= $bg; ?>; min-width:100px; color:white;">
                    <h6 class="fw-bold text-dark mb-0 text-truncate" style="max-width:100px;"><?= $u['username']; ?></h6>
                    <small class="fw-bold text-dark opacity-75"><?= number_format($u['score']); ?></small>
                </div>
            </div>
        <?php endif; endforeach; ?>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white fw-bold">📋 Peringkat Lengkap</div>
        <div class="card-body p-0">
            <table class="table table-hover align-middle mb-0">
                <tbody>
                    <?php foreach($list as $i => $u): ?>
                    <tr onclick="window.location='<?= BASEURL; ?>/profile/index/<?= $u['id']; ?>'" style="cursor: pointer;">
                        <td class="ps-4 fw-bold text-muted" style="width: 50px;">#<?= $i+1; ?></td>
                        <td>
                            <img src="<?= BASEURL; ?>/img/<?= $u['avatar']; ?>" class="rounded-circle me-2 border" width="30" height="30" style="object-fit:cover;">
                            <span class="fw-bold"><?= $u['username']; ?></span>
                            <span class="badge bg-light text-dark border ms-2"><?= $u['selected_title']; ?></span>
                        </td>
                        <td class="text-end pe-4 fw-bold text-dark"><?= number_format($u['score']); ?> <?= isset($metric)?$metric:''; ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php endif; ?>