<div class="container mt-5">
    <div class="row">
        
        <div class="col-lg-8 mb-4">
            
            <?php if(isset($data['weather_info'])): ?>
                <div class="card mb-3 border-0 shadow-sm bg-white text-dark overflow-hidden">
                    <div class="card-body py-2 px-3 d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <span class="display-6 me-3"><?= $data['weather_info']['icon']; ?></span>
                            <div style="line-height: 1.2;">
                                <h6 class="fw-bold mb-0 text-uppercase">Cuaca: <?= $data['weather_info']['name']; ?></h6>
                                <small class="text-primary fw-bold"><?= $data['weather_info']['effect']; ?></small>
                            </div>
                        </div>
                        <?php if($data['weather'] == 'storm'): ?><span class="badge bg-danger">⚠️ BAHAYA (+2 Energy)</span>
                        <?php elseif($data['weather'] == 'heatwave' || $data['weather'] == 'snow'): ?><span class="badge bg-info text-dark">✨ MUTATION CHANCE</span>
                        <?php elseif($data['weather'] == 'rain'): ?><span class="badge bg-primary">🍀 LUCK BOOST</span>
                        <?php else: ?><span class="badge bg-success">AMAN</span><?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>

            <?php 
                // Ambil ID Map dari Session untuk menentukan gambar background
                // Format file harus sama dengan di Map menu: bg_map_1.jpg, bg_map_2.jpg, dst.
                $currentMapId = $_SESSION['current_map_id'] ?? 1; 
                $bgImage = 'bg_map_' . $currentMapId . '.jpg';
            ?>
            
            <div class="card shadow-lg border-0 bg-dark text-white overflow-hidden position-relative">
                
                <div style="
                    height: 400px; 
                    background: linear-gradient(180deg, rgba(0,0,0,0.1), rgba(0,0,0,0.8)), 
                                url('<?= BASEURL; ?>/img/<?= $bgImage; ?>') center/cover no-repeat;
                    display: flex; 
                    flex-direction: column;
                    align-items: center; 
                    justify-content: center;
                    position: relative;
                ">
                    <?php if($data['weather'] == 'rain'): ?><div class="weather-overlay weather-rain" style="position:absolute; inset:0;"></div><?php endif; ?>
                    <?php if($data['weather'] == 'storm'): ?><div class="weather-overlay weather-storm" style="position:absolute; inset:0;"></div><?php endif; ?>
                    <?php if($data['weather'] == 'snow'): ?><div class="weather-overlay weather-snow" style="position:absolute; inset:0;"></div><?php endif; ?>

                    <div class="text-center position-relative" style="z-index: 10;">
                        <div class="mb-3">
                            <span class="display-1 bg-white rounded-circle p-2 shadow pulse-button" style="opacity: 0.9;">🎣</span>
                        </div>
                        <h2 class="fw-bold display-5 text-shadow"><?= $data['map_name']; ?></h2>
                        <p class="mb-4 fw-bold text-white-50 text-shadow">Siapkan joranmu, tunggu sambaran!</p>

                        <form action="<?= BASEURL; ?>/fishing/cast" method="POST">
                            <button type="submit" 
                                    onclick="if(typeof audioManager !== 'undefined') audioManager.playSFX('cast')" 
                                    class="btn btn-warning btn-lg px-5 py-3 rounded-pill fw-bold shadow pulse-button border border-2 border-white">
                                LEMPAR KAIL!
                            </button>
                        </form>
                        
                        <div class="mt-3 badge bg-dark bg-opacity-75 fs-6 border border-secondary">
                            Biaya: <?= ($data['weather'] == 'storm') ? '<span class="text-warning">7 Energi (Badai)</span>' : '5 Energi'; ?>
                        </div>
                    </div>
                </div>

                <?php if(isset($_GET['error']) || isset($_GET['msg']) || isset($data['message'])): ?>
                    <div class="card-footer bg-white text-dark text-center py-3">
                        <?php if(isset($_GET['error']) && $_GET['error'] == 'no_bait'): ?><div class="alert alert-danger fw-bold m-0">⚠️ Pasang umpan dulu di menu samping!</div><?php endif; ?>
                        <?php if(isset($_GET['error']) && $_GET['error'] == 'no_energy'): ?><div class="alert alert-danger fw-bold m-0">😫 Energi Habis! Makan dulu.</div><?php endif; ?>
                        <?php if(isset($_GET['msg']) && $_GET['msg'] == 'gear_updated'): ?><div class="alert alert-success fw-bold m-0">✅ Alat berhasil diganti!</div><?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow border-0">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">🎒 Alat Pancing</h5>
                    <a href="<?= BASEURL; ?>/shop" class="btn btn-sm btn-outline-primary fw-bold">Toko</a>
                </div>
                <div class="card-body">
                    
                    <?php 
                        $rod = $data['loadout']['rod'];
                        $bait = $data['loadout']['bait'];
                        $rodLuck = isset($rod['luck_stat']) ? $rod['luck_stat'] : 0;
                        $baitLuck = isset($bait['luck_stat']) ? $bait['luck_stat'] : 0;
                        $totalLuck = $rodLuck + $baitLuck;
                    ?>

                    <div class="alert alert-info d-flex justify-content-between align-items-center mb-4 border-info">
                        <span class="fw-bold"><i class="fas fa-clover"></i> Total Luck</span>
                        <span class="h4 mb-0 fw-bold text-primary">+<?= $totalLuck; ?>%</span>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="text-muted text-uppercase small mb-0 fw-bold">Joran</h6>
                        <button class="btn btn-sm btn-outline-dark" data-bs-toggle="modal" data-bs-target="#modalRod">Ganti</button>
                    </div>
                    <div class="card mb-3 border-<?= $rod['rarity'] ?? 'secondary'; ?> shadow-sm">
                        <div class="card-body d-flex align-items-center p-2">
                            <?php if($rod): ?>
                                <img src="<?= BASEURL; ?>/img/<?= $rod['image']; ?>" width="50" class="me-3">
                                <div>
                                    <h6 class="fw-bold mb-0 small"><?= $rod['name']; ?></h6>
                                    <small class="text-success fw-bold">+<?= $rodLuck; ?> Luck</small>
                                </div>
                            <?php else: ?>
                                <span class="text-muted fst-italic small">Belum ada joran</span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="text-muted text-uppercase small mb-0 fw-bold">Umpan</h6>
                        <button class="btn btn-sm btn-outline-dark" data-bs-toggle="modal" data-bs-target="#modalBait">Ganti</button>
                    </div>
                    <div class="card mb-3 border-<?= $bait['rarity'] ?? 'secondary'; ?> shadow-sm">
                        <div class="card-body d-flex align-items-center p-2">
                            <?php if($bait): ?>
                                <img src="<?= BASEURL; ?>/img/<?= $bait['image']; ?>" width="50" class="me-3">
                                <div>
                                    <h6 class="fw-bold mb-0 small"><?= $bait['name']; ?></h6>
                                    <small class="text-success fw-bold">+<?= $baitLuck; ?> Luck</small>
                                </div>
                            <?php else: ?>
                                <span class="text-muted fst-italic small">Belum ada umpan</span>
                            <?php endif; ?>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'partials/modals_equipment.php'; ?>

<?php if (isset($data['got_fish']) && $data['got_fish']): ?>
<div class="modal fade show" id="resultModal" tabindex="-1" style="display: block; background: rgba(0,0,0,0.85);">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content bg-transparent border-0">
      <div class="modal-body text-center">
          <div class="card shadow-lg border-0 animate__animated animate__zoomIn" style="border-radius: 20px; overflow: hidden;">
              <div class="card-header py-3 text-white fw-bold bg-<?= $data['got_fish']['rarity']; ?>" style="letter-spacing: 2px;">
                  <?= strtoupper($data['got_fish']['rarity']); ?> CATCH!
              </div>
              
              <div class="card-body bg-white p-5">
                  <div class="mb-4 position-relative d-inline-block">
                      <img src="<?= BASEURL; ?>/img/<?= $data['got_fish']['image']; ?>" class="img-fluid" style="max-height: 180px; filter: drop-shadow(0 10px 10px rgba(0,0,0,0.3));">
                      
                      <?php if($data['got_fish']['mutation'] != 'normal'): ?>
                          <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-dark border border-warning pulse-button">
                              <?= strtoupper($data['got_fish']['mutation']); ?>
                          </span>
                      <?php endif; ?>
                  </div>

                  <h2 class="fw-bold mb-2"><?= $data['got_fish']['name']; ?></h2>
                  <p class="text-muted fst-italic mb-4">"<?= $data['got_fish']['lore'] ?? 'Ikan yang sangat menarik!'; ?>"</p>
                  
                  <?php if($data['got_fish']['rarity'] == 'legendary'): ?>
                      <div class="alert alert-info fw-bold py-2"><i class="fas fa-gem"></i> Bonus: +1 Diamond!</div>
                  <?php endif; ?>

                  <a href="<?= BASEURL; ?>/fishing" class="btn btn-primary w-100 py-3 rounded-pill fw-bold shadow">
                      Simpan & Mancing Lagi
                  </a>
              </div>
          </div>
      </div>
    </div>
  </div>
</div>
<?php endif; ?>

<style>
    .text-shadow { text-shadow: 2px 2px 4px rgba(0,0,0,0.8); }
</style>