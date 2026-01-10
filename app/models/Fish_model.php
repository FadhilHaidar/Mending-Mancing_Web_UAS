<?php

class Fish_model {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    // --- CORE LOGIC (Catch, Inventory, etc) ---
    // Pastikan method ini ada (jika hilang, copy dari backup sebelumnya)
    public function catchFish($userId, $fishId, $mutation) {
        $this->db->query("INSERT INTO inventory (user_id, fish_id, mutation, obtained_at) VALUES (:uid, :fid, :mut, NOW())");
        $this->db->bind('uid', $userId); $this->db->bind('fid', $fishId); $this->db->bind('mut', $mutation);
        $this->db->execute();
    }

    public function getFishesByMap($mapId) {
        $this->db->query("SELECT * FROM fishes WHERE map_id = :map_id");
        $this->db->bind('map_id', $mapId);
        return $this->db->resultSet();
    }

    // --- FISHPEDIA LOGIC (INI YANG DIPERBAIKI) ---
    public function recordCatch($userId, $fishId, $mutation) {
        $columnMap = [
            'normal' => 'has_normal', 'big' => 'has_big', 'tiny' => 'has_tiny',
            'shiny' => 'has_shiny', 'glitch' => 'has_glitch',
            'fire' => 'has_fire', 'ice' => 'has_ice', 'electric' => 'has_electric'
        ];
        $colToUpdate = isset($columnMap[$mutation]) ? $columnMap[$mutation] : 'has_normal';

        $query = "INSERT INTO user_collections (user_id, fish_id, total_caught, $colToUpdate) 
                  VALUES (:uid, :fid, 1, 1)
                  ON DUPLICATE KEY UPDATE total_caught = total_caught + 1, $colToUpdate = 1";
        
        $this->db->query($query);
        $this->db->bind('uid', $userId); $this->db->bind('fid', $fishId);
        $this->db->execute();
    }

    public function getFishpediaData($userId) {
        $this->db->query("SELECT * FROM maps ORDER BY id ASC");
        $maps = $this->db->resultSet();

        if (empty($maps)) return []; 

        $pokedex = [];
        foreach($maps as $map) {
            // FIX: Tambahkan semua kolom 'has_...' di SELECT
            $query = "SELECT f.*, 
                             uc.total_caught, 
                             uc.has_normal, uc.has_big, uc.has_tiny, uc.has_shiny, uc.has_glitch, 
                             uc.has_fire, uc.has_ice, uc.has_electric, 
                             (uc.fish_id IS NOT NULL) as is_caught
                      FROM fishes f
                      LEFT JOIN user_collections uc ON f.id = uc.fish_id AND uc.user_id = :uid
                      WHERE f.map_id = :mid
                      ORDER BY f.rarity ASC, f.price ASC";
            
            $this->db->query($query);
            $this->db->bind('uid', $userId);
            $this->db->bind('mid', $map['id']);
            $fishes = $this->db->resultSet();

            $totalFish = count($fishes);
            $caughtCount = 0;
            foreach($fishes as $f) { if($f['is_caught']) $caughtCount++; }

            $pokedex[] = [
                'map_info' => $map,
                'fishes' => $fishes,
                'progress' => ($totalFish > 0) ? round(($caughtCount / $totalFish) * 100) : 0
            ];
        }
        return $pokedex;
    }

    // --- INVENTORY FILTER LOGIC ---
    private function buildFilterQuery($userId, $filters, $isCount = false) {
        $sql = " FROM inventory i 
                 JOIN fishes f ON i.fish_id = f.id 
                 LEFT JOIN market_listings ml ON i.id = ml.inventory_id
                 WHERE i.user_id = :uid AND ml.id IS NULL"; 

        if (!empty($filters['search'])) $sql .= " AND f.name LIKE :search";
        if (!empty($filters['rarity']) && $filters['rarity'] != 'all') $sql .= " AND f.rarity = :rarity";
        if (!empty($filters['mutation']) && $filters['mutation'] != 'all') $sql .= " AND i.mutation = :mutation";
        return $sql;
    }

    public function getFilteredInventory($userId, $filters, $limit = 12, $offset = 0) {
        $sql = "SELECT i.id, i.fish_id, i.mutation, i.obtained_at, f.name, f.image, f.rarity, f.price " . $this->buildFilterQuery($userId, $filters);
        
        $sort = isset($filters['sort']) ? $filters['sort'] : 'newest';
        switch ($sort) {
            case 'oldest': $sql .= " ORDER BY i.obtained_at ASC"; break;
            case 'price_high': $sql .= " ORDER BY f.price DESC, i.mutation DESC"; break;
            case 'price_low': $sql .= " ORDER BY f.price ASC"; break;
            case 'rarity_high': $sql .= " ORDER BY FIELD(f.rarity, 'legendary', 'epic', 'rare', 'common')"; break;
            default: $sql .= " ORDER BY i.obtained_at DESC";
        }
        $sql .= " LIMIT :limit OFFSET :offset";

        $this->db->query($sql);
        $this->db->bind('uid', $userId); $this->db->bind('limit', $limit); $this->db->bind('offset', $offset);
        if (!empty($filters['search'])) $this->db->bind('search', "%".$filters['search']."%");
        if (!empty($filters['rarity']) && $filters['rarity'] != 'all') $this->db->bind('rarity', $filters['rarity']);
        if (!empty($filters['mutation']) && $filters['mutation'] != 'all') $this->db->bind('mutation', $filters['mutation']);
        
        return $this->db->resultSet();
    }

    public function countFilteredInventory($userId, $filters) {
        $sql = "SELECT COUNT(*) as total " . $this->buildFilterQuery($userId, $filters, true);
        $this->db->query($sql);
        $this->db->bind('uid', $userId);
        if (!empty($filters['search'])) $this->db->bind('search', "%".$filters['search']."%");
        if (!empty($filters['rarity']) && $filters['rarity'] != 'all') $this->db->bind('rarity', $filters['rarity']);
        if (!empty($filters['mutation']) && $filters['mutation'] != 'all') $this->db->bind('mutation', $filters['mutation']);
        return $this->db->single();
    }

    // --- BULK SELL LOGIC ---
    public function calculateFilterValue($userId, $filters) {
        $sql = "SELECT i.mutation, f.price " . $this->buildFilterQuery($userId, $filters) . " AND f.rarity != 'legendary' AND i.mutation NOT IN ('shiny', 'glitch')";
        $this->db->query($sql);
        $this->db->bind('uid', $userId);
        if (!empty($filters['search'])) $this->db->bind('search', "%".$filters['search']."%");
        if (!empty($filters['rarity']) && $filters['rarity'] != 'all') $this->db->bind('rarity', $filters['rarity']);
        if (!empty($filters['mutation']) && $filters['mutation'] != 'all') $this->db->bind('mutation', $filters['mutation']);
        
        $items = $this->db->resultSet();
        $total = 0; $count = 0;
        
        // Pastikan FishingEngine diload di Controller, atau include manual disini jika error
        // require_once '../app/core/FishingEngine.php'; 
        
        foreach($items as $item) {
            $total += FishingEngine::calculatePrice($item['price'], $item['mutation']);
            $count++;
        }
        return ['total_gold' => $total, 'item_count' => $count];
    }

    public function executeBulkSell($userId, $filters) {
        $data = $this->calculateFilterValue($userId, $filters);
        if($data['item_count'] == 0) return ['status' => 'empty'];

        $sql = "DELETE i FROM inventory i JOIN fishes f ON i.fish_id = f.id LEFT JOIN market_listings ml ON i.id = ml.inventory_id WHERE i.user_id = :uid AND ml.id IS NULL AND f.rarity != 'legendary' AND i.mutation NOT IN ('shiny', 'glitch')";
        if (!empty($filters['search'])) $sql .= " AND f.name LIKE :search";
        if (!empty($filters['rarity']) && $filters['rarity'] != 'all') $sql .= " AND f.rarity = :rarity";
        if (!empty($filters['mutation']) && $filters['mutation'] != 'all') $sql .= " AND i.mutation = :mutation";

        $this->db->query($sql);
        $this->db->bind('uid', $userId);
        if (!empty($filters['search'])) $this->db->bind('search', "%".$filters['search']."%");
        if (!empty($filters['rarity']) && $filters['rarity'] != 'all') $this->db->bind('rarity', $filters['rarity']);
        if (!empty($filters['mutation']) && $filters['mutation'] != 'all') $this->db->bind('mutation', $filters['mutation']);
        $this->db->execute();
        
        if($this->db->rowCount() > 0) {
            $this->db->query("UPDATE users SET gold = gold + :g WHERE id = :uid");
            $this->db->bind('g', $data['total_gold']); $this->db->bind('uid', $userId); $this->db->execute();
        }
        return ['status' => 'success', 'count' => $this->db->rowCount(), 'gold' => $data['total_gold']];
    }

    // --- OTHER HELPERS ---
    public function getInventoryItem($id) {
        $this->db->query("SELECT i.*, f.name, f.price, f.rarity FROM inventory i JOIN fishes f ON i.fish_id = f.id WHERE i.id = :id");
        $this->db->bind('id', $id);
        return $this->db->single();
    }
    public function deleteItem($id) {
        $this->db->query("DELETE FROM inventory WHERE id = :id");
        $this->db->bind('id', $id);
        $this->db->execute();
        return $this->db->rowCount();
    }
}