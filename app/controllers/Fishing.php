<?php
require_once '../app/core/FishingEngine.php';
require_once '../app/core/AchievementHelper.php';
require_once '../app/core/WeatherHelper.php';

class Fishing extends Controller {
    
    public function __construct() {
        if(!isset($_SESSION['user_id'])) { 
            header('Location: ' . BASEURL . '/auth/login'); 
            exit; 
        }
    }

    // --- HALAMAN UTAMA MANCING ---
    public function index() {
        $userId = $_SESSION['user_id'];
        
        // Cek Map
        if(!isset($_SESSION['current_map_id'])) { 
            header('Location: ' . BASEURL . '/map'); 
            exit; 
        }
        
        // Refresh Energy
        $this->model('User_model')->refreshEnergy($userId);

        // Prepare Data
        $data['judul'] = 'Mancing';
        $data['user'] = $this->model('User_model')->getUserById($userId);
        $data['gold'] = $data['user']['gold'];
        $data['map_name'] = $_SESSION['current_map_name'];
        
        // Loadout & Equipment
        $data['loadout'] = $this->model('User_model')->getLoadout($userId);
        $data['my_gears'] = $this->model('User_model')->getAllUserEquipment($userId);
        
        // Weather
        $weather = WeatherHelper::getCurrentWeather();
        $data['weather'] = $weather; 
        $data['weather_info'] = WeatherHelper::getWeatherInfo($weather);

        $this->view('templates/header', $data);
        $this->view('fishing/index', $data);
        $this->view('templates/footer');
    }

    // --- ACTION: LEMPAR KAIL (CAST) ---
    public function cast() {
        $userId = $_SESSION['user_id'];
        if(!isset($_SESSION['current_map_id'])) { header('Location: ' . BASEURL . '/map'); exit; }

        $this->model('User_model')->refreshEnergy($userId);
        $weather = WeatherHelper::getCurrentWeather();
        
        // Loadout & Effects
        $loadout = $this->model('User_model')->getLoadout($userId);
        $rodEffects = $this->model('User_model')->getEquipmentEffects($loadout['rod']['equip_id'] ?? 0);

        // Hitung Cost Energy
        $baseCost = 5;
        if(isset($rodEffects['curse']) && $rodEffects['curse']['name'] == 'Heavy Hook') $baseCost += 2;
        $extraCost = ($weather == 'storm') ? 2 : 0; 
        $totalCost = $baseCost + $extraCost;

        $user = $this->model('User_model')->getUserById($userId);
        
        // Validasi
        if($user['energy'] < $totalCost) { header('Location: ' . BASEURL . '/fishing?error=no_energy'); exit; }
        if(empty($loadout['bait'])) { header('Location: ' . BASEURL . '/fishing?error=no_bait'); exit; }

        // Hitung Luck
        $rodLuck = $loadout['rod']['luck_stat'] ?? 0;
        $baitLuck = $loadout['bait']['luck_stat'] ?? 0;
        $totalLuck = $rodLuck + $baitLuck;

        // Proses Mancing
        $fishModel = $this->model('Fish_model');
        $possibleFishes = $fishModel->getFishesByMap($_SESSION['current_map_id']);

        if(empty($possibleFishes)) {
            $data['message'] = "Belum ada populasi ikan.";
        } else {
            // Kurangi Energi
            $this->model('User_model')->reduceEnergy($userId, $totalCost);
            
            // Gacha Engine
            $result = FishingEngine::gacha($possibleFishes, $totalLuck, $weather, $rodEffects);

            if ($result) {
                // Simpan Hasil
                $fishModel->catchFish($userId, $result['id'], $result['mutation']);
                $fishModel->recordCatch($userId, $result['id'], $result['mutation']);
                
                // --- DROP BERLIAN JIKA LEGENDARY ---
                if ($result['rarity'] == 'legendary') {
                    $this->model('User_model')->addDiamond($userId, 1);
                    $data['message'] = "Strike! " . $result['name'] . " <span class='fw-bold text-info'>+1 💎</span>";
                } else {
                    $mutText = ($result['mutation'] != 'normal') ? " (" . strtoupper($result['mutation']) . ")!" : "!";
                    $data['message'] = "Strike! " . $result['name'] . $mutText;
                }
                $data['got_fish'] = $result;
                
                // Achievement Check (Optional)
                AchievementHelper::check($userId, 'catch_rarity', $result['rarity']);
            } else {
                $data['got_fish'] = null;
                $data['message'] = "Zonk! Ikan lolos...";
            }
        }

        // Render Ulang Halaman Index dengan Hasil
        $data['judul'] = 'Hasil Mancing';
        $data['map_name'] = $_SESSION['current_map_name'];
        $data['user'] = $this->model('User_model')->getUserById($userId);
        $data['gold'] = $data['user']['gold'];
        $data['loadout'] = $this->model('User_model')->getLoadout($userId); 
        $data['my_gears'] = $this->model('User_model')->getAllUserEquipment($userId);
        $data['weather'] = $weather;
        $data['weather_info'] = WeatherHelper::getWeatherInfo($weather);

        $this->view('templates/header', $data);
        $this->view('fishing/index', $data);
        $this->view('templates/footer');
    }

    // --- HALAMAN INVENTORY (PERBAIKAN TAMPILAN TAS HILANG) ---
    public function inventory() {
        $userId = $_SESSION['user_id'];
        $data['user'] = $this->model('User_model')->getUserById($userId);
        $data['gold'] = $data['user']['gold'];
        $data['judul'] = 'Tas Saya';

        // 1. Config Pagination
        $limit = 12; 
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $offset = ($page - 1) * $limit;

        // 2. Default Filter
        $filters = [
            'search' => '',
            'rarity' => 'all',
            'mutation' => 'all',
            'sort' => 'newest'
        ];

        // 3. Ambil Data Inventory Awal (PENTING AGAR TIDAK KOSONG)
        $data['items'] = $this->model('Fish_model')->getFilteredInventory($userId, $filters, $limit, $offset);
        $totalData = $this->model('Fish_model')->countFilteredInventory($userId, $filters)['total'];
        
        // 4. Data Pendukung View
        $data['totalPages'] = ceil($totalData / $limit);
        $data['currentPage'] = $page;
        // Ambil daftar ID yang sedang dijual di pasar (agar tombol jual disable)
        $data['listed_ids'] = $this->model('Shop_model')->getListedInventoryIds($userId);

        $this->view('templates/header', $data);
        $this->view('fishing/inventory', $data);
        $this->view('templates/footer');
    }

    // --- AJAX: FILTER & PAGINATION ---
    public function filter() {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $userId = $_SESSION['user_id'];
            $filters = [
                'search' => $_POST['search'] ?? '',
                'rarity' => $_POST['rarity'] ?? 'all',
                'mutation' => $_POST['mutation'] ?? 'all',
                'sort' => $_POST['sort'] ?? 'newest'
            ];
            $limit = 12;
            $page = isset($_POST['page']) ? (int)$_POST['page'] : 1;
            $offset = ($page - 1) * $limit;

            // Fetch Data
            $items = $this->model('Fish_model')->getFilteredInventory($userId, $filters, $limit, $offset);
            $countData = $this->model('Fish_model')->countFilteredInventory($userId, $filters)['total'];
            $listedIds = $this->model('Shop_model')->getListedInventoryIds($userId);
            $bulkInfo = $this->model('Fish_model')->calculateFilterValue($userId, $filters);

            // Render Partial HTML
            ob_start();
            if(empty($items)) {
                echo '<div class="col-12 text-center py-5 text-muted"><h1 class="display-4">📭</h1><p>Tidak ada ikan yang cocok.</p></div>';
            } else {
                foreach($items as $item) {
                    $finalPrice = FishingEngine::calculatePrice($item['price'], $item['mutation']);
                    $isListed = in_array($item['id'], $listedIds);
                    // Pastikan path ini benar
                    include '../app/views/fishing/partials/item_card.php';
                }
            }
            $html = ob_get_clean();

            // Return JSON
            echo json_encode([
                'html' => $html,
                'totalPages' => ceil($countData / $limit),
                'currentPage' => $page,
                'totalItems' => $countData,
                'bulkCount' => $bulkInfo['item_count'],
                'bulkValue' => number_format($bulkInfo['total_gold'])
            ]);
        }
    }

    // --- ACTION: JUAL MASAL ---
    public function bulk_sell() {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $userId = $_SESSION['user_id'];
            $filters = [
                'search' => $_POST['search'] ?? '',
                'rarity' => $_POST['rarity'] ?? 'all',
                'mutation' => $_POST['mutation'] ?? 'all'
            ];

            // Safety Check
            if($filters['rarity'] == 'legendary' || $filters['mutation'] == 'glitch' || $filters['mutation'] == 'shiny') {
                echo json_encode(['status' => 'error', 'msg' => 'Item langka tidak bisa dijual massal! Jual manual satu per satu.']);
                exit;
            }

            $result = $this->model('Fish_model')->executeBulkSell($userId, $filters);
            echo json_encode($result);
        }
    }

    // --- ACTION: JUAL SATUAN ---
    public function sell($inventoryId) {
        $userId = $_SESSION['user_id'];
        $fishModel = $this->model('Fish_model');
        $item = $fishModel->getInventoryItem($inventoryId);

        if($item && $item['user_id'] == $userId) {
            // Cek apakah sedang dilisting di pasar
            $shopModel = $this->model('Shop_model');
            $listedIds = $shopModel->getListedInventoryIds($userId);
            
            if(in_array($inventoryId, $listedIds)) {
                header('Location: ' . BASEURL . '/fishing/inventory?msg=gagal'); // Item sedang dijual
                exit;
            }

            $finalPrice = FishingEngine::calculatePrice($item['price'], $item['mutation']);
            
            if($fishModel->deleteItem($inventoryId) > 0) {
                $this->model('User_model')->updateGold($userId, $finalPrice);
                header('Location: ' . BASEURL . '/fishing/inventory?msg=berhasil_jual');
            }
        } else {
            header('Location: ' . BASEURL . '/fishing/inventory?msg=gagal');
        }
    }

    // --- ACTION: GANTI EQUIPMENT ---
    public function set_equipment($equipId, $type) {
        if($this->model('User_model')->equipItem($_SESSION['user_id'], $equipId, $type) > 0) {
            header('Location: ' . BASEURL . '/fishing?msg=gear_updated');
        } else {
            header('Location: ' . BASEURL . '/fishing');
        }
    }
}