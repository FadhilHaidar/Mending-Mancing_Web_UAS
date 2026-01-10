<div class="modal fade" id="modalRod" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header"><h5 class="modal-title">Pilih Joran</h5><button class="btn-close" data-bs-dismiss="modal"></button></div>
      <div class="modal-body">
          <div class="list-group">
              <?php foreach($data['my_gears'] as $gear): ?>
                  <?php if($gear['type'] == 'rod'): ?>
                      <a href="<?= BASEURL; ?>/fishing/set_equipment/<?= $gear['equip_id']; ?>/rod" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                          <div class="d-flex align-items-center">
                              <img src="<?= BASEURL; ?>/img/<?= $gear['image']; ?>" width="40" class="me-3">
                              <div>
                                  <h6 class="mb-0 fw-bold"><?= $gear['name']; ?></h6>
                                  <small class="text-success">+<?= $gear['luck_stat']; ?> Luck</small>
                              </div>
                          </div>
                          <span class="badge bg-<?= $gear['rarity']; ?>"><?= $gear['rarity']; ?></span>
                      </a>
                  <?php endif; ?>
              <?php endforeach; ?>
          </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modalBait" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header"><h5 class="modal-title">Pilih Umpan</h5><button class="btn-close" data-bs-dismiss="modal"></button></div>
      <div class="modal-body">
          <div class="list-group">
              <?php foreach($data['my_gears'] as $gear): ?>
                  <?php if($gear['type'] == 'bait'): ?>
                      <a href="<?= BASEURL; ?>/fishing/set_equipment/<?= $gear['equip_id']; ?>/bait" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                          <div class="d-flex align-items-center">
                              <img src="<?= BASEURL; ?>/img/<?= $gear['image']; ?>" width="40" class="me-3">
                              <div>
                                  <h6 class="mb-0 fw-bold"><?= $gear['name']; ?></h6>
                                  <small class="text-success">+<?= $gear['luck_stat']; ?> Luck</small>
                              </div>
                          </div>
                          <span class="badge bg-<?= $gear['rarity']; ?>"><?= $gear['rarity']; ?></span>
                      </a>
                  <?php endif; ?>
              <?php endforeach; ?>
          </div>
      </div>
    </div>
  </div>
</div>