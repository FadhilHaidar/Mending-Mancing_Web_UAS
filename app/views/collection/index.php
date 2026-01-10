<style>
    /* Siluet Ikan Belum Ditangkap */
    .fish-locked {
        filter: brightness(0) opacity(0.15); /* Hitam transparan soft */
        pointer-events: none;
    }
    
    /* Card Style */
    .fish-card { transition: transform 0.2s, box-shadow 0.2s; border: none; }
    .fish-card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important; cursor: pointer; }
    
    /* Badge Style Custom */
    .badge-mini { font-size: 0.6rem; padding: 3px 6px; border-radius: 4px; margin-right: 2px; }
    
    /* Warna Mutasi Badge */
    .bg-fire { background-color: #ff4500; color: white; }
    .bg-ice { background-color: #00d2ff; color: white; }
    .bg-electric { background-color: #ffd700; color: black; }
    .bg-shiny { background-color: #ff00ff; color: white; }
    .bg-big { background-color: #28a745; color: white; }
    .bg-glitch { background-color: #333; color: #0f0; font-family: monospace; }
</style>

<div class="container mt-4 mb-5">
    
    <div class="text-center mb-5">
        <h2 class="fw-bold text-info"><i class="fas fa-book-open"></i> Fishpedia</h2>
        <p class="text-muted">Koleksi seluruh spesies ikan di dunia Mending Mancing.</p>
    </div>

    <ul class="nav nav-pills justify-content-center mb-4" id="pokedexTab" role="tablist">
        <?php foreach($data['pokedex'] as $index => $mapData): ?>
            <li class="nav-item mx-1" role="presentation">
                <button class="nav-link rounded-pill px-4 <?= ($index === 0) ? 'active' : ''; ?>" 
                        id="tab-<?= $mapData['map_info']['id']; ?>" 
                        data-bs-toggle="tab" 
                        data-bs-target="#content-<?= $mapData['map_info']['id']; ?>" 
                        type="button">
                    <?= $mapData['map_info']['name']; ?>
                    <?php if($mapData['progress'] >= 100): ?>
                        <span class="badge bg-warning text-dark ms-1">👑 100%</span>
                    <?php else: ?>
                        <span class="badge bg-secondary ms-1"><?= $mapData['progress']; ?>%</span>
                    <?php endif; ?>
                </button>
            </li>
        <?php endforeach; ?>
    </ul>

    <div class="tab-content" id="pokedexContent">
        <?php foreach($data['pokedex'] as $index => $mapData): ?>
            
            <div class="tab-pane fade <?= ($index === 0) ? 'show active' : ''; ?>" 
                 id="content-<?= $mapData['map_info']['id']; ?>" role="tabpanel">
                
                <div class="row g-4 justify-content-center"> 
                    
                    <?php if(empty($mapData['fishes'])): ?>
                        <div class="col-12 text-center text-muted py-5">Belum ada data ikan di area ini.</div>
                    <?php else: ?>
                        
                        <?php foreach($mapData['fishes'] as $fish): ?>
                            <div class="col-6 col-md-4"> 
                                
                                <div class="card h-100 shadow-sm fish-card text-center" 
                                     <?= ($fish['is_caught']) ? 'data-bs-toggle="modal" data-bs-target="#modalFish'.$fish['id'].'"' : ''; ?>>
                                    
                                    <div class="card-body p-3 d-flex flex-column align-items-center justify-content-between">
                                        <div class="mb-3 position-relative d-flex align-items-center justify-content-center" style="height: 100px; width: 100%;">
                                            <img src="<?= BASEURL; ?>/img/<?= $fish['image']; ?>" 
                                                 class="img-fluid <?= (!$fish['is_caught']) ? 'fish-locked' : ''; ?>" 
                                                 style="max-height: 100%; max-width: 100%; object-fit: contain;">
                                            
                                            <?php if(!$fish['is_caught']): ?>
                                                <div class="position-absolute fw-bold text-muted fs-1 opacity-50">?</div>
                                            <?php endif; ?>
                                        </div>

                                        <div class="w-100">
                                            <?php if($fish['is_caught']): ?>
                                                <h6 class="fw-bold mb-2 text-truncate"><?= $fish['name']; ?></h6>
                                                
                                                <span class="badge bg-<?= $fish['rarity']; ?> mb-2" style="font-size: 0.7rem;"><?= strtoupper($fish['rarity']); ?></span>
                                                
                                                <div class="mt-1 d-flex flex-wrap justify-content-center gap-1">
                                                    <?php if($fish['has_shiny']): ?><span class="badge badge-mini bg-shiny" title="Shiny">✨ Shiny</span><?php endif; ?>
                                                    <?php if($fish['has_big']): ?><span class="badge badge-mini bg-big" title="Big">🐘 Big</span><?php endif; ?>
                                                    <?php if($fish['has_glitch']): ?><span class="badge badge-mini bg-glitch" title="Glitch">👾 Glitch</span><?php endif; ?>
                                                    <?php if($fish['has_fire']): ?><span class="badge badge-mini bg-fire" title="Fire">🔥 Fire</span><?php endif; ?>
                                                    <?php if($fish['has_ice']): ?><span class="badge badge-mini bg-ice" title="Ice">❄️ Ice</span><?php endif; ?>
                                                    <?php if($fish['has_electric']): ?><span class="badge badge-mini bg-electric" title="Electric">⚡ Elec</span><?php endif; ?>
                                                </div>

                                            <?php else: ?>
                                                <h6 class="fw-bold mb-1 text-muted">???</h6>
                                                <span class="badge bg-secondary mb-2" style="font-size: 0.7rem;">UNKNOWN</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <?php if($fish['is_caught']): ?>
                            <div class="modal fade" id="modalFish<?= $fish['id']; ?>" tabindex="-1">
                              <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                  <div class="modal-header bg-<?= $fish['rarity']; ?> text-white">
                                    <h5 class="modal-title fw-bold"><?= $fish['name']; ?></h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                  </div>
                                  <div class="modal-body text-center">
                                      <img src="<?= BASEURL; ?>/img/<?= $fish['image']; ?>" class="img-fluid mb-3 w-50">
                                      <p class="fst-italic text-muted">"<?= $fish['lore'] ?? 'Ikan misterius...'; ?>"</p>
                                      <hr>
                                      <div class="row text-start small">
                                          <div class="col-6 mb-2">
                                              <span class="text-uppercase text-muted fw-bold d-block">Habitat</span>
                                              <?= $mapData['map_info']['name']; ?>
                                          </div>
                                          <div class="col-6 mb-2">
                                              <span class="text-uppercase text-muted fw-bold d-block">Cuaca Favorit</span>
                                              <?php 
                                                $wIcon = ['any'=>'☁️ Bebas', 'rain'=>'🌧️ Hujan', 'storm'=>'⛈️ Badai', 'heatwave'=>'🔥 Panas', 'snow'=>'❄️ Salju'];
                                                echo $wIcon[$fish['preferred_weather'] ?? 'any'] ?? 'Bebas';
                                              ?>
                                          </div>
                                          <div class="col-6">
                                              <span class="text-uppercase text-muted fw-bold d-block">Total Ditangkap</span>
                                              <span class="fs-5 fw-bold"><?= $fish['total_caught']; ?></span> Ekor
                                          </div>
                                          <div class="col-6">
                                              <span class="text-uppercase text-muted fw-bold d-block">Rarity</span>
                                              <span class="badge bg-<?= $fish['rarity']; ?>"><?= strtoupper($fish['rarity']); ?></span>
                                          </div>
                                      </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <?php endif; ?>

                        <?php endforeach; ?>
                    <?php endif; ?>
                </div> </div>
        <?php endforeach; ?>
    </div>

</div>