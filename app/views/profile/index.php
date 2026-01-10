<?php
    // Logic Status Online
    $lastActive = strtotime($data['user']['last_activity']);
    $isOnline = (time() - $lastActive) < 300; // 5 menit toleransi
    $statusColor = $isOnline ? '#2ecc71' : '#e74c3c'; 
    $statusText = $isOnline ? 'Online' : 'Offline ' . date('H:i', $lastActive);
?>

<style>
    .profile-card { border-radius: 15px; overflow: hidden; border: none; }
    .profile-banner { height: 280px; background-size: cover; background-position: center; position: relative; }
    .avatar-container { position: relative; margin-top: -75px; display: inline-block; }
    .profile-avatar { width: 150px; height: 150px; border: 5px solid white; border-radius: 50%; object-fit: cover; background-color: #fff; box-shadow: 0 5px 15px rgba(0,0,0,0.2); }
    .status-indicator { position: absolute; bottom: 15px; right: 15px; width: 25px; height: 25px; border-radius: 50%; border: 3px solid white; background-color: <?= $statusColor; ?>; }
    .badge-title { font-size: 0.8rem; padding: 5px 12px; border-radius: 20px; margin: 0 2px; }
    .stat-box { background: #f8f9fa; border-radius: 10px; padding: 15px; text-align: center; height: 100%; transition: 0.3s; }
    .stat-box:hover { background: #e9ecef; transform: translateY(-3px); }
</style>

<div class="container mb-5">
    
    <?php if(isset($_GET['msg'])): ?>
        <div class="alert alert-info text-center">
            <?= ($_GET['msg'] == 'updated') ? '✅ Profil berhasil diperbarui!' : '❌ Terjadi kesalahan saat update profil.'; ?>
        </div>
    <?php endif; ?>

    <div class="card profile-card shadow mb-4">
        <div class="profile-banner" style="background-image: url('<?= BASEURL; ?>/img/<?= $data['user']['banner'] ?? 'default_banner.jpg'; ?>');">
            <?php if($data['is_own_profile']): ?>
                <button class="btn btn-light btn-sm position-absolute top-0 end-0 m-3 shadow fw-bold" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                    <i class="fas fa-camera"></i> Edit Tampilan
                </button>
            <?php endif; ?>
        </div>
        
        <div class="card-body text-center pt-0">
            <div class="avatar-container">
                <img src="<?= BASEURL; ?>/img/<?= $data['user']['avatar'] ?? 'default_avatar.png'; ?>" class="profile-avatar">
                <div class="status-indicator" title="<?= $statusText; ?>"></div>
            </div>

            <h2 class="fw-bold mt-2 mb-1"><?= $data['user']['username']; ?></h2>
            
            <div class="mb-3">
                <span class="badge bg-warning text-dark badge-title border border-dark"><?= $data['user']['selected_title']; ?></span>
                <?php if(!empty($data['user']['equipped_title_2'])): ?>
                    <span class="badge bg-secondary badge-title"><?= $data['user']['equipped_title_2']; ?></span>
                <?php endif; ?>
                <?php if(!empty($data['user']['equipped_title_3'])): ?>
                    <span class="badge bg-secondary badge-title"><?= $data['user']['equipped_title_3']; ?></span>
                <?php endif; ?>
            </div>
            
            <p class="text-muted fst-italic mb-4">"<?= $data['user']['bio'] ?? '...'; ?>"</p>

            <div class="row px-3 g-2 justify-content-center">
                <div class="col-4 col-md-2">
                    <div class="stat-box">
                        <small class="text-muted d-block text-uppercase" style="font-size:0.7rem">Total Gold</small>
                        <span class="fw-bold text-warning">💰 <?= number_format($data['user']['gold']); ?></span>
                    </div>
                </div>
                <div class="col-4 col-md-2">
                    <div class="stat-box">
                        <small class="text-muted d-block text-uppercase" style="font-size:0.7rem">Diamonds</small>
                        <span class="fw-bold text-info">💎 <?= number_format($data['user']['diamonds']); ?></span>
                    </div>
                </div>
                <div class="col-4 col-md-2">
                    <div class="stat-box">
                        <small class="text-muted d-block text-uppercase" style="font-size:0.7rem">Pedia Index</small>
                        <span class="fw-bold text-info">📖 <?= $data['stats']['pedia_percent']; ?>%</span>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-box">
                        <small class="text-muted d-block text-uppercase" style="font-size:0.7rem">Total Tangkapan</small>
                        <span class="fw-bold text-success">🐟 <?= number_format($data['stats']['total_catch']); ?> Ekor</span>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-box">
                        <small class="text-muted d-block text-uppercase" style="font-size:0.7rem">Bergabung Sejak</small>
                        <span class="fw-bold text-secondary">📅 <?= date('d M Y', strtotime($data['user']['created_at'] ?? 'now')); ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        
        <div class="col-lg-12 mb-4">
            <h5 class="fw-bold mb-3"><i class="fas fa-crown text-warning"></i> Koleksi Ikan Terbaik</h5>
            <div class="row g-2">
                <?php for($i=0; $i<6; $i++): ?>
                    <?php if(isset($data['showcase_fish'][$i])): $fish = $data['showcase_fish'][$i]; ?>
                        <div class="col-4 col-md-2">
                            <div class="card h-100 border-<?= $fish['rarity']; ?> shadow-sm text-center bg-white">
                                <div class="card-body p-2 d-flex flex-column align-items-center justify-content-center">
                                    <div style="height: 50px; display: flex; align-items: center;">
                                        <img src="<?= BASEURL; ?>/img/<?= $fish['image']; ?>" class="img-fluid" style="max-height: 100%;">
                                    </div>
                                    <h6 class="small fw-bold text-truncate w-100 mb-0 mt-2"><?= $fish['name']; ?></h6>
                                    <span class="badge bg-<?= $fish['rarity']; ?>" style="font-size: 0.5rem;"><?= $fish['rarity']; ?></span>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="col-4 col-md-2">
                            <div class="card h-100 border-light shadow-sm text-center bg-light">
                                <div class="card-body p-2 d-flex flex-column align-items-center justify-content-center text-muted">
                                    <i class="fas fa-plus-circle fs-3 mb-2 opacity-25"></i>
                                    <small class="small">Kosong</small>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endfor; ?>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <h5 class="fw-bold mb-3"><i class="fas fa-tools text-secondary"></i> Alat Pancing Favorit</h5>
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="row w-100 text-center m-0">
                        <div class="col-6 border-end">
                            <img src="<?= BASEURL; ?>/img/<?= $data['showcase_gears']['rod']['image'] ?? 'rod_default.png'; ?>" width="60" class="mb-2">
                            <h6 class="fw-bold mb-0 text-primary">Rod</h6>
                            <small class="text-muted"><?= $data['showcase_gears']['rod']['name'] ?? 'Bamboo'; ?></small>
                        </div>
                        <div class="col-6">
                            <img src="<?= BASEURL; ?>/img/<?= $data['showcase_gears']['bait']['image'] ?? 'bait_default.png'; ?>" width="60" class="mb-2">
                            <h6 class="fw-bold mb-0 text-success">Bait</h6>
                            <small class="text-muted"><?= $data['showcase_gears']['bait']['name'] ?? 'Worm'; ?></small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <h5 class="fw-bold mb-3"><i class="fas fa-trophy text-warning"></i> Top Prestasi</h5>
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body d-flex justify-content-around align-items-center text-center">
                    
                    <?php 
                    // Pastikan variable ada
                    $achievements = !empty($data['showcase_ach']) ? $data['showcase_ach'] : [];
                    
                    // Loop 3 kali (Slot 1, 2, 3)
                    for($i=0; $i<3; $i++): 
                        if(isset($achievements[$i])): 
                            $ach = $achievements[$i];
                    ?>
                        <div class="flex-fill p-2">
                            <div class="fs-1 mb-1" title="<?= $ach['description']; ?>"><?= $ach['icon']; ?></div>
                            <small class="d-block fw-bold lh-1 text-muted" style="font-size: 0.65rem;"><?= $ach['name']; ?></small>
                        </div>
                    <?php else: ?>
                        <div class="flex-fill p-2 text-muted opacity-25">
                            <div class="fs-1"><i class="fas fa-circle"></i></div>
                            <small class="small">Kosong</small>
                        </div>
                    <?php endif; endfor; ?>

                </div>
            </div>
        </div>
    </div>
</div>

<?php if($data['is_own_profile']): ?>
<div class="modal fade" id="editProfileModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title fw-bold"><i class="fas fa-edit"></i> Edit Profile</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      
      <form action="<?= BASEURL; ?>/profile/update" method="POST" enctype="multipart/form-data">
          <div class="modal-body">
              
              <h6 class="text-primary border-bottom pb-2 mb-3">📸 Visual</h6>
              <div class="row mb-3">
                  <div class="col-6">
                      <label class="form-label fw-bold small">Banner (Landscape)</label>
                      <input type="file" name="banner" class="form-control form-control-sm" accept="image/*">
                  </div>
                  <div class="col-6">
                      <label class="form-label fw-bold small">Avatar (Square)</label>
                      <input type="file" name="avatar" class="form-control form-control-sm" accept="image/*">
                  </div>
              </div>
              <div class="mb-3">
                  <label class="form-label fw-bold small">Bio</label>
                  <input type="text" name="bio" class="form-control" value="<?= $data['user']['bio']; ?>" maxlength="100">
              </div>

              <h6 class="text-primary border-bottom pb-2 mb-3 mt-4">🏷️ Titles (Max 3)</h6>
              <div class="row g-2 mb-3">
                  <div class="col-4">
                      <select name="title_1" class="form-select form-select-sm">
                          <option value="Pemula">Utama</option>
                          <?php foreach($data['available_titles'] as $t) echo "<option value='$t' ".($data['user']['selected_title']==$t?'selected':'').">$t</option>"; ?>
                      </select>
                  </div>
                  <div class="col-4">
                      <select name="title_2" class="form-select form-select-sm">
                          <option value="">(Kosong)</option>
                          <?php foreach($data['available_titles'] as $t) echo "<option value='$t' ".($data['user']['equipped_title_2']==$t?'selected':'').">$t</option>"; ?>
                      </select>
                  </div>
                  <div class="col-4">
                      <select name="title_3" class="form-select form-select-sm">
                          <option value="">(Kosong)</option>
                          <?php foreach($data['available_titles'] as $t) echo "<option value='$t' ".($data['user']['equipped_title_3']==$t?'selected':'').">$t</option>"; ?>
                      </select>
                  </div>
              </div>

              <h6 class="text-primary border-bottom pb-2 mb-3 mt-4">🐟 Showcase Ikan (6 Slot)</h6>
              <div class="row g-2 mb-3">
                  <?php for($i=1; $i<=6; $i++): ?>
                  <div class="col-4">
                      <label class="small text-muted">Slot <?= $i; ?></label>
                      <select name="fish_<?= $i; ?>" class="form-select form-select-sm">
                          <option value="">(Kosong)</option>
                          <?php foreach($data['all_fish'] as $f): ?>
                              <option value="<?= $f['inventory_id']; ?>" <?= ($data['user']['showcase_fish_'.$i]==$f['inventory_id']?'selected':''); ?>>
                                  <?= $f['name']; ?> (<?= $f['rarity']; ?>)
                              </option>
                          <?php endforeach; ?>
                      </select>
                  </div>
                  <?php endfor; ?>
              </div>

              <h6 class="text-primary border-bottom pb-2 mb-3 mt-4">🏆 Top Prestasi (3 Slot)</h6>
              <div class="row g-2">
                  <?php for($i=1; $i<=3; $i++): ?>
                  <div class="col-4">
                      <select name="ach_<?= $i; ?>" class="form-select form-select-sm">
                          <option value="">(Kosong)</option>
                          <?php foreach($data['all_achievements'] as $ach): 
                              // Hanya tampilkan jika sudah unlocked
                              if($ach['unlocked_at']): 
                          ?>
                              <option value="<?= $ach['id']; ?>" <?= ($data['user']['showcase_ach_'.$i] == $ach['id'] ? 'selected' : ''); ?>>
                                  <?= $ach['name']; ?>
                              </option>
                          <?php endif; endforeach; ?>
                      </select>
                  </div>
                  <?php endfor; ?>
              </div>

          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
              <button type="submit" class="btn btn-primary fw-bold">Simpan Perubahan</button>
          </div>
      </form>
    </div>
  </div>
</div>
<?php endif; ?>