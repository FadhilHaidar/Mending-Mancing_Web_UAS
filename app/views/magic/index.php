<style>
    /* MAGIC SHOP THEME */
    body.magic-theme {
        background-color: #12001b !important; /* Ungu Gelap Pekat */
        color: #e0d0e0;
        background-image: radial-gradient(circle at 50% 50%, #2a003b 0%, #12001b 100%);
    }
    .card-magic {
        background: rgba(40, 0, 60, 0.8);
        border: 1px solid #9932cc;
        box-shadow: 0 0 15px rgba(153, 50, 204, 0.3);
        color: white;
    }
    .text-neon { color: #00ff00; text-shadow: 0 0 5px #00ff00; }
    .text-curse { color: #ff0055; text-shadow: 0 0 5px #ff0055; }
    .btn-ritual {
        background: linear-gradient(45deg, #4b0082, #800080);
        border: none;
        color: white;
        transition: 0.3s;
    }
    .btn-ritual:hover {
        background: linear-gradient(45deg, #800080, #ff00ff);
        box-shadow: 0 0 20px #ff00ff;
        transform: scale(1.05);
    }

    /* Witch Dialog Box */
    .witch-box {
        position: relative;
        background: #fff;
        color: #000;
        border-radius: 20px;
        padding: 15px;
        margin-bottom: 20px;
        border: 3px solid #800080;
    }
    .witch-box:after {
        content: ''; position: absolute; bottom: 0; left: 50%; width: 0; height: 0;
        border: 20px solid transparent; border-top-color: #800080;
        border-bottom: 0; border-left: 0; margin-left: -10px; margin-bottom: -20px;
    }

    /* Animation */
    @keyframes shake { 0% { transform: translate(1px, 1px) rotate(0deg); } 10% { transform: translate(-1px, -2px) rotate(-1deg); } 20% { transform: translate(-3px, 0px) rotate(1deg); } 30% { transform: translate(3px, 2px) rotate(0deg); } 40% { transform: translate(1px, -1px) rotate(1deg); } 50% { transform: translate(-1px, 2px) rotate(-1deg); } 60% { transform: translate(-3px, 1px) rotate(0deg); } 70% { transform: translate(3px, 1px) rotate(-1deg); } 80% { transform: translate(-1px, -1px) rotate(1deg); } 90% { transform: translate(1px, 2px) rotate(0deg); } 100% { transform: translate(1px, -2px) rotate(-1deg); } }
    .shake-hard { animation: shake 0.5s; animation-iteration-count: infinite; }
</style>

<script>document.body.classList.add('magic-theme');</script>

<div class="container mt-5 mb-5">
    
    <div class="row justify-content-center mb-5">
        <div class="col-md-8 text-center">
            <img src="https://cdn-icons-png.flaticon.com/512/3306/3306606.png" width="120" class="mb-3">
            
            <div class="witch-box shadow" id="witchDialog">
                <h5 class="fw-bold text-uppercase mb-1" style="color: #4b0082;">Penyihir Hutan</h5>
                <p class="mb-0 fst-italic fs-5" id="witchText">"Hehe... Membawa tumbal apa hari ini, Anak Muda?"</p>
            </div>
        </div>
    </div>

    <div class="row">
        
        <div class="col-md-6 mb-4">
            <div class="card card-magic h-100">
                <div class="card-header bg-transparent border-bottom border-secondary text-center">
                    <h3 class="fw-bold text-neon mb-0">🔮 The Ritual</h3>
                    <small>Enchant Joranmu (70%) atau Terkutuk (30%)</small>
                </div>
                <div class="card-body">
                    <form action="<?= BASEURL; ?>/magic/ritual" method="POST" id="ritualForm">
                        
                        <div class="mb-4">
                            <label class="form-label text-light fw-bold">1. Pilih Joran (Target)</label>
                            <select name="rod_id" class="form-select bg-dark text-white border-secondary" required>
                                <option value="" selected disabled>-- Pilih Joran --</option>
                                <?php foreach($data['rods'] as $rod): ?>
                                    <option value="<?= $rod['equip_id']; ?>">
                                        <?= $rod['name']; ?> 
                                        <?= ($rod['enchant_name']) ? '[✨ '.$rod['enchant_name'].']' : ''; ?>
                                        <?= ($rod['curse_name']) ? '[💀 '.$rod['curse_name'].']' : ''; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="form-label text-light fw-bold">2. Pilih Tumbal (Legendary Only)</label>
                            <select name="fish_id" class="form-select bg-dark text-white border-secondary" required>
                                <option value="" selected disabled>-- Pilih Ikan Legendary --</option>
                                <?php foreach($data['sacrifices'] as $fish): ?>
                                    <option value="<?= $fish['inventory_id']; ?>">
                                        <?= $fish['name']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="form-text text-white-50">*Ikan akan hilang selamanya.</div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-ritual btn-lg fw-bold py-3" onclick="return confirm('Mulai Ritual? Ikan akan dihapus!')">
                                MULAI RITUAL
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            
            <div class="card card-magic mb-4">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="fw-bold text-light mb-1">🕯️ Purification</h5>
                        <p class="small text-white-50 mb-0">Hapus kutukan. Biaya: 2.000 G</p>
                    </div>
                    <div class="dropdown">
                        <button class="btn btn-outline-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            Pilih Joran
                        </button>
                        <ul class="dropdown-menu dropdown-menu-dark">
                            <?php foreach($data['rods'] as $rod): ?>
                                <?php if($rod['curse_name']): ?>
                                    <li>
                                        <a class="dropdown-item d-flex justify-content-between" href="<?= BASEURL; ?>/magic/cleanse/<?= $rod['equip_id']; ?>" onclick="return confirm('Bayar 2000G untuk hapus kutukan?')">
                                            <span><?= $rod['name']; ?></span>
                                            <span class="text-danger small">💀 <?= $rod['curse_name']; ?></span>
                                        </a>
                                    </li>
                                <?php endif; ?>
                            <?php endforeach; ?>
                            <?php if(empty(array_filter($data['rods'], fn($r) => $r['curse_name']))): ?>
                                <li><span class="dropdown-item disabled">Tidak ada joran terkutuk</span></li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="card card-magic">
                <div class="card-header bg-transparent border-bottom border-secondary">
                    <h5 class="fw-bold text-white mb-0">Grimoire (Panduan)</h5>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush bg-transparent">
                        <li class="list-group-item bg-transparent text-white border-secondary">
                            <span class="text-neon fw-bold">Reeler:</span> Luck +5 (Common)
                        </li>
                        <li class="list-group-item bg-transparent text-white border-secondary">
                            <span class="text-neon fw-bold">Magnet:</span> Peluang dapat Treasure (Rare)
                        </li>
                        <li class="list-group-item bg-transparent text-white border-secondary">
                            <span class="text-neon fw-bold">Stormhunter:</span> Bonus Luck Besar saat Badai (Epic)
                        </li>
                        <li class="list-group-item bg-transparent text-white border-secondary">
                            <span class="text-curse fw-bold">Heavy Hook:</span> Energy Cost bertambah (Curse)
                        </li>
                        <li class="list-group-item bg-transparent text-white border-secondary">
                            <span class="text-curse fw-bold">Jinxed:</span> Luck berkurang drastis (Curse)
                        </li>
                    </ul>
                </div>
            </div>

        </div>
    </div>
</div>

<?php if(isset($_GET['result'])): ?>
<div class="modal fade show" id="resultModal" tabindex="-1" style="display: block; background: rgba(0,0,0,0.8);">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content bg-dark text-white border-secondary text-center p-4" style="box-shadow: 0 0 50px <?= ($_GET['result']=='success')?'#00ff00':'#ff0000'; ?>;">
      
      <div class="mb-3 display-1">
          <?= ($_GET['result']=='success') ? '✨' : '💀'; ?>
      </div>
      
      <h2 class="fw-bold <?= ($_GET['result']=='success') ? 'text-neon' : 'text-curse'; ?>">
          <?= ($_GET['result']=='success') ? 'ENCHANTMENT SUCCESS!' : 'CURSED!'; ?>
      </h2>
      
      <p class="fs-5">
          <?= ($_GET['result']=='success') 
            ? 'Kekuatan magis merasuki joranmu...' 
            : 'Ritual gagal! Roh jahat menempel pada alatmu...'; ?>
      </p>

      <a href="<?= BASEURL; ?>/magic" class="btn btn-outline-light mt-3">Terima Nasib</a>
    </div>
  </div>
</div>
<?php endif; ?>

<script>
    // Random Dialog
    const dialogs = [
        "Hehe... Ikan Legendary-mu terlihat lezat...",
        "Berani menanggung risiko terkutuk?",
        "Cuaca hari ini... buruk, bukan?",
        "Kekuatan besar butuh pengorbanan besar..."
    ];
    document.getElementById('witchText').innerText = '"' + dialogs[Math.floor(Math.random() * dialogs.length)] + '"';

    // Remove Theme on Exit (Optional, biar halaman lain gak jadi ungu)
    window.onbeforeunload = function() {
        document.body.classList.remove('magic-theme');
    };
</script>