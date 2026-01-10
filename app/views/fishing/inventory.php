<div class="container mt-4 mb-5">
    
    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
        <div>
            <h2 class="fw-bold mb-0 text-primary">🎒 Tas Ikan</h2>
            <p class="text-muted m-0 small">Kelola hasil tangkapanmu.</p>
        </div>
        <div class="text-end">
            <span class="badge bg-warning text-dark fs-5 shadow-sm">💰 <?= number_format($data['gold']); ?> G</span>
        </div>
    </div>

    <div class="card shadow-sm border-0 mb-4 sticky-top" style="top: 70px; z-index: 900;">
        <div class="card-body p-3">
            <div class="row g-2 align-items-center">
                <div class="col-12 col-md-4">
                    <input type="text" id="filterSearch" class="form-control form-control-sm" placeholder="🔍 Cari nama ikan...">
                </div>
                <div class="col-4 col-md-2">
                    <select id="filterRarity" class="form-select form-select-sm">
                        <option value="all">Rarity: All</option>
                        <option value="common">Common</option>
                        <option value="rare">Rare</option>
                        <option value="epic">Epic</option>
                        <option value="legendary">Legendary</option>
                    </select>
                </div>
                <div class="col-4 col-md-2">
                    <select id="filterMutation" class="form-select form-select-sm">
                        <option value="all">Mutasi: All</option>
                        <option value="normal">Normal</option>
                        <option value="shiny">✨ Shiny</option>
                        <option value="big">🐘 Big</option>
                        <option value="tiny">🤏 Tiny</option>
                        <option value="glitch">👾 Glitch</option>
                    </select>
                </div>
                <div class="col-4 col-md-2">
                    <select id="filterSort" class="form-select form-select-sm">
                        <option value="newest">Terbaru</option>
                        <option value="price_high">Termahal</option>
                        <option value="rarity_high">Terlangka</option>
                    </select>
                </div>
                <div class="col-12 col-md-2 d-grid">
                    <button id="btnBulkSell" class="btn btn-sm btn-outline-danger fw-bold" disabled>Jual Masal</button>
                </div>
            </div>
        </div>
    </div>

    <div id="alertBox"></div>
    <?php if(isset($_GET['msg'])): ?>
        <div class="alert alert-info py-2 small alert-dismissible fade show">
            <?php if($_GET['msg']=='berhasil_jual') echo "✅ Ikan berhasil dijual!"; ?>
            <?php if($_GET['msg']=='listed') echo "✅ Ikan masuk pasar!"; ?>
            <?php if($_GET['msg']=='canceled') echo "✅ Penjualan dibatalkan."; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div id="inventoryGrid" class="row g-3">
        <?php 
            // Render Awal via PHP (Agar tidak blank saat JS belum load)
            if(!empty($data['items'])) {
                foreach($data['items'] as $item) {
                    $finalPrice = FishingEngine::calculatePrice($item['price'], $item['mutation']);
                    $isListed = in_array($item['id'], $data['listed_ids']);
                    include 'partials/item_card.php';
                }
            } else {
                echo '<div class="col-12 text-center py-5 text-muted"><h1 class="display-4">📭</h1><p>Tas kosong. Pergilah memancing!</p></div>';
            }
        ?>
    </div>

    <nav class="mt-5 mb-5">
        <ul class="pagination justify-content-center pagination-sm" id="paginationContainer">
            </ul>
    </nav>

</div>

<script>
    const BASEURL = "<?= BASEURL; ?>";
    let currentPage = <?= $data['currentPage'] ?? 1; ?>;
    let totalPages = <?= $data['totalPages'] ?? 1; ?>;

    const grid = document.getElementById('inventoryGrid');
    const paginationContainer = document.getElementById('paginationContainer');
    const btnBulk = document.getElementById('btnBulkSell');
    const alertBox = document.getElementById('alertBox');

    // Init Pagination
    document.addEventListener("DOMContentLoaded", function() {
        renderPagination(totalPages, currentPage);
    });

    // 1. Fetch Data
    function loadInventory(page = 1) {
        grid.style.opacity = '0.5';
        const formData = new FormData();
        formData.append('page', page);
        formData.append('search', document.getElementById('filterSearch').value);
        formData.append('rarity', document.getElementById('filterRarity').value);
        formData.append('mutation', document.getElementById('filterMutation').value);
        formData.append('sort', document.getElementById('filterSort').value);

        fetch(BASEURL + '/fishing/filter', { method: 'POST', body: formData })
        .then(response => response.json())
        .then(data => {
            grid.innerHTML = data.html;
            grid.style.opacity = '1';
            
            // Update Bulk Button
            if(data.bulkCount > 0 && document.getElementById('filterRarity').value !== 'legendary') {
                btnBulk.disabled = false;
                btnBulk.innerHTML = `Jual Semua (${data.bulkCount}) <br><small>+${data.bulkValue} G</small>`;
            } else {
                btnBulk.disabled = true;
                btnBulk.innerHTML = `Jual Masal`;
            }
            renderPagination(data.totalPages, data.currentPage);
        })
        .catch(err => { console.error(err); grid.style.opacity = '1'; });
    }

    // 2. Render Pagination Buttons
    function renderPagination(total, current) {
        let html = ''; current = parseInt(current); total = parseInt(total);
        if (total > 1) {
            html += `<li class="page-item ${current === 1 ? 'disabled' : ''}"><button class="page-link" onclick="loadInventory(${current - 1})">Prev</button></li>`;
            
            let start = Math.max(1, current - 2); 
            let end = Math.min(total, current + 2);
            
            if (start > 1) html += `<li class="page-item"><button class="page-link" onclick="loadInventory(1)">1</button></li>`;
            if (start > 2) html += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
            
            for (let i = start; i <= end; i++) {
                html += `<li class="page-item ${current === i ? 'active' : ''}"><button class="page-link" onclick="loadInventory(${i})">${i}</button></li>`;
            }
            
            if (end < total) { 
                if(end < total - 1) html += `<li class="page-item disabled"><span class="page-link">...</span></li>`; 
                html += `<li class="page-item"><button class="page-link" onclick="loadInventory(${total})">${total}</button></li>`; 
            }
            
            html += `<li class="page-item ${current === total ? 'disabled' : ''}"><button class="page-link" onclick="loadInventory(${current + 1})">Next</button></li>`;
        }
        paginationContainer.innerHTML = html;
    }

    // 3. Listeners
    let timeout = null;
    document.getElementById('filterSearch').addEventListener('keyup', () => { clearTimeout(timeout); timeout = setTimeout(() => loadInventory(1), 500); });
    ['filterRarity', 'filterMutation', 'filterSort'].forEach(id => { document.getElementById(id).addEventListener('change', () => loadInventory(1)); });

    // 4. Bulk Sell Action
    btnBulk.addEventListener('click', () => {
        if(!confirm("Yakin jual semua ikan hasil filter ini?")) return;
        const formData = new FormData();
        formData.append('search', document.getElementById('filterSearch').value);
        formData.append('rarity', document.getElementById('filterRarity').value);
        formData.append('mutation', document.getElementById('filterMutation').value);

        fetch(BASEURL + '/fishing/bulk_sell', { method: 'POST', body: formData }).then(res => res.json()).then(res => {
            if(res.status === 'success') { 
                alertBox.innerHTML = `<div class="alert alert-success alert-dismissible fade show">✅ Terjual ${res.count} ikan (+${res.gold} G)<button class="btn-close" data-bs-dismiss="alert"></button></div>`; 
                loadInventory(1); 
            } else { 
                alertBox.innerHTML = `<div class="alert alert-danger">${res.msg}</div>`; 
            }
        });
    });
</script>