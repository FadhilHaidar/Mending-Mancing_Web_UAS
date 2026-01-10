<?php
class Shop_model {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    // --- 1. GET DATA ---
    public function getShopItems($type) {
        $this->db->query("SELECT * FROM shop_items WHERE type = :t ORDER BY price ASC");
        $this->db->bind('t', $type);
        return $this->db->resultSet();
    }
    public function getFoods() { return $this->getShopItems('food'); }
    public function getRods() { return $this->getShopItems('rod'); }
    public function getBaits() { return $this->getShopItems('bait'); }

    // --- 2. BELI ITEM TOKO ---
    public function buyItem($userId, $itemId) {
        $this->db->query("SELECT * FROM shop_items WHERE id = :id");
        $this->db->bind('id', $itemId);
        $item = $this->db->single();
        
        $this->db->query("SELECT gold FROM users WHERE id = :uid");
        $this->db->bind('uid', $userId);
        $user = $this->db->single();

        if(!$item || $user['gold'] < $item['price']) return 'nomoney';

        // Potong Gold
        $this->db->query("UPDATE users SET gold = gold - :p WHERE id = :uid");
        $this->db->bind('p', $item['price']); $this->db->bind('uid', $userId); $this->db->execute();

        // Tambah Item
        $this->db->query("SELECT id FROM user_equipment WHERE user_id = :uid AND item_id = :iid");
        $this->db->bind('uid', $userId); $this->db->bind('iid', $itemId); $exist = $this->db->single();

        if($exist) {
            $this->db->query("UPDATE user_equipment SET quantity = quantity + 1 WHERE id = :eid");
            $this->db->bind('eid', $exist['id']);
        } else {
            $this->db->query("INSERT INTO user_equipment (user_id, item_id, quantity, is_equipped) VALUES (:uid, :iid, 1, 0)");
            $this->db->bind('uid', $userId); $this->db->bind('iid', $itemId);
        }
        $this->db->execute();
        return 'success';
    }

    // --- 3. BELI MAKANAN ---
    public function buyAndEat($userId, $itemId) {
        $this->db->query("SELECT * FROM shop_items WHERE id = :id");
        $this->db->bind('id', $itemId); $item = $this->db->single();
        $this->db->query("SELECT gold, energy FROM users WHERE id = :uid");
        $this->db->bind('uid', $userId); $user = $this->db->single();

        if(!$item) return 'error';
        if($user['gold'] < $item['price']) return 'nomoney';
        if($user['energy'] >= 100) return 'full_energy';

        $newEnergy = min(100, $user['energy'] + $item['energy_restore']);
        $this->db->query("UPDATE users SET gold = gold - :p, energy = :e WHERE id = :uid");
        $this->db->bind('p', $item['price']); $this->db->bind('e', $newEnergy); $this->db->bind('uid', $userId); $this->db->execute();
        return 'success';
    }

    // --- 4. TUKAR BERLIAN ---
    public function exchangeLegendaryForDiamond($userId, $inventoryId) {
        $this->db->query("SELECT i.id, f.rarity FROM inventory i JOIN fishes f ON i.fish_id = f.id WHERE i.id = :iid AND i.user_id = :uid");
        $this->db->bind('iid', $inventoryId); $this->db->bind('uid', $userId); $fish = $this->db->single();

        if (!$fish || $fish['rarity'] != 'legendary') return false;

        $this->db->query("DELETE FROM inventory WHERE id = :iid");
        $this->db->bind('iid', $inventoryId); $this->db->execute();

        $this->db->query("UPDATE users SET diamonds = diamonds + 1 WHERE id = :uid");
        $this->db->bind('uid', $userId); $this->db->execute();
        return true;
    }

    // --- 5. MARKETPLACE P2P ---
    public function getListedInventoryIds($userId) {
        $this->db->query("SELECT inventory_id FROM market_listings WHERE seller_id = :uid");
        $this->db->bind('uid', $userId);
        $results = $this->db->resultSet();
        $ids = []; foreach($results as $r) $ids[] = $r['inventory_id'];
        return $ids;
    }

    public function getAllListings($sort = 'newest', $rarity = 'all') {
        $query = "SELECT m.id as listing_id, m.price, m.created_at, m.seller_id, 
                         i.fish_id, i.mutation, f.name as fish_name, f.rarity, f.image, 
                         u.username as seller_name, u.avatar as seller_avatar 
                  FROM market_listings m 
                  JOIN inventory i ON m.inventory_id = i.id 
                  JOIN fishes f ON i.fish_id = f.id 
                  JOIN users u ON m.seller_id = u.id 
                  WHERE 1"; // WHERE 1 allows appending AND cleanly
        
        if($rarity != 'all') $query .= " AND f.rarity = '$rarity'";
        
        switch($sort) {
            case 'price_asc': $query .= " ORDER BY m.price ASC"; break;
            case 'price_desc': $query .= " ORDER BY m.price DESC"; break;
            default: $query .= " ORDER BY m.created_at DESC";
        }

        $this->db->query($query);
        return $this->db->resultSet();
    }

    public function addListing($sellerId, $inventoryId, $price) {
        // Cek milik sendiri
        $this->db->query("SELECT id FROM inventory WHERE id = :iid AND user_id = :uid");
        $this->db->bind('iid', $inventoryId); $this->db->bind('uid', $sellerId);
        if(!$this->db->single()) return false;

        // Cek duplikat
        $this->db->query("SELECT id FROM market_listings WHERE inventory_id = :iid");
        $this->db->bind('iid', $inventoryId);
        if($this->db->single()) return false;

        $this->db->query("INSERT INTO market_listings (seller_id, inventory_id, price) VALUES (:uid, :iid, :price)");
        $this->db->bind('uid', $sellerId); $this->db->bind('iid', $inventoryId); $this->db->bind('price', $price);
        $this->db->execute();
        return $this->db->rowCount();
    }

    public function cancelListing($inventoryId, $userId) {
        $this->db->query("DELETE FROM market_listings WHERE inventory_id = :lid AND seller_id = :uid");
        $this->db->bind('lid', $inventoryId); $this->db->bind('uid', $userId);
        $this->db->execute();
        return $this->db->rowCount();
    }

    // --- FIX UTAMA: HAPUS TRANSACTION AGAR TIDAK ERROR ---
    public function buyMarketItem($listingId, $buyerId) {
        // 1. Ambil Info Listing
        $this->db->query("SELECT * FROM market_listings WHERE id = :lid");
        $this->db->bind('lid', $listingId);
        $listing = $this->db->single();

        if(!$listing) return "sold_out";
        if($listing['seller_id'] == $buyerId) return "self_buy";

        // 2. Cek Uang Buyer
        $this->db->query("SELECT gold FROM users WHERE id = :uid");
        $this->db->bind('uid', $buyerId);
        $buyer = $this->db->single();

        if($buyer['gold'] < $listing['price']) return "no_gold";

        // 3. Eksekusi Berurutan (Tanpa beginTransaction)
        // Kurangi Gold Buyer
        $this->db->query("UPDATE users SET gold = gold - :p WHERE id = :uid");
        $this->db->bind('p', $listing['price']); $this->db->bind('uid', $buyerId); $this->db->execute();

        // Tambah Gold Seller
        $this->db->query("UPDATE users SET gold = gold + :p WHERE id = :uid");
        $this->db->bind('p', $listing['price']); $this->db->bind('uid', $listing['seller_id']); $this->db->execute();

        // Pindahkan Item (Update Owner)
        $this->db->query("UPDATE inventory SET user_id = :new_uid WHERE id = :iid");
        $this->db->bind('new_uid', $buyerId); $this->db->bind('iid', $listing['inventory_id']); $this->db->execute();

        // Hapus Listing
        $this->db->query("DELETE FROM market_listings WHERE id = :lid");
        $this->db->bind('lid', $listingId); $this->db->execute();

        return "success";
    }
}