<?php

class Magic_model {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    // Ambil Data Joran User untuk Dropdown (Hanya Rod)
    public function getUserRods($userId) {
        $query = "SELECT ue.id as equip_id, s.name, e.name as enchant_name, c.name as curse_name, e.color_hex 
                  FROM user_equipment ue 
                  JOIN shop_items s ON ue.item_id = s.id 
                  LEFT JOIN enchantments e ON ue.enchantment_id = e.id
                  LEFT JOIN curses c ON ue.curse_id = c.id
                  WHERE ue.user_id = :uid AND s.type = 'rod'";
        $this->db->query($query);
        $this->db->bind('uid', $userId);
        return $this->db->resultSet();
    }

    // Ambil Ikan Legendary User (Tumbal)
    public function getUserLegendaryFish($userId) {
        $query = "SELECT i.id as inventory_id, f.name 
                  FROM inventory i 
                  JOIN fishes f ON i.fish_id = f.id 
                  WHERE i.user_id = :uid AND f.rarity = 'legendary'";
        $this->db->query($query);
        $this->db->bind('uid', $userId);
        return $this->db->resultSet();
    }

    // LAKUKAN RITUAL ENCHANT
    public function performEnchant($equipId, $sacrificeId) {
        // 1. Hapus Tumbal (Sacrifice)
        $this->db->query("DELETE FROM inventory WHERE id = :iid");
        $this->db->bind('iid', $sacrificeId);
        $this->db->execute();

        // 2. Roll Gacha (70% Sukses, 30% Kutukan)
        $roll = rand(1, 100);
        $isSuccess = ($roll <= 70);

        if ($isSuccess) {
            // -- SUKSES: Pilih Random Enchantment --
            // Logic simple: Random pick. (Bisa dikembangkan pakai weight rarity)
            $this->db->query("SELECT id FROM enchantments ORDER BY RAND() LIMIT 1");
            $enchant = $this->db->single();
            
            // Update Joran: Hapus Curse lama, Pasang Enchant baru
            $this->db->query("UPDATE user_equipment SET enchantment_id = :eid, curse_id = NULL WHERE id = :ueid");
            $this->db->bind('eid', $enchant['id']);
            $this->db->bind('ueid', $equipId);
            $this->db->execute();

            return ['status' => 'success', 'enchant_id' => $enchant['id']];
        } else {
            // -- GAGAL: Pilih Random Curse --
            $this->db->query("SELECT id FROM curses ORDER BY RAND() LIMIT 1");
            $curse = $this->db->single();

            // Update Joran: Pasang Curse (Enchantment lama HILANG/TERTIMPA atau tetap? Kita buat tertimpa/hilang biar sakit)
            $this->db->query("UPDATE user_equipment SET enchantment_id = NULL, curse_id = :cid WHERE id = :ueid");
            $this->db->bind('cid', $curse['id']);
            $this->db->bind('ueid', $equipId);
            $this->db->execute();

            return ['status' => 'cursed', 'curse_id' => $curse['id']];
        }
    }

    // CLEANSE (Hapus Kutukan Bayar Gold)
    public function cleanseCurse($userId, $equipId) {
        $cost = 2000;
        
        // Cek Gold
        $this->db->query("SELECT gold FROM users WHERE id = :uid");
        $this->db->bind('uid', $userId);
        $user = $this->db->single();

        if ($user['gold'] < $cost) return false;

        // Potong Gold
        $this->db->query("UPDATE users SET gold = gold - :cost WHERE id = :uid");
        $this->db->bind('cost', $cost);
        $this->db->bind('uid', $userId);
        $this->db->execute();

        // Hapus Curse
        $this->db->query("UPDATE user_equipment SET curse_id = NULL WHERE id = :ueid AND user_id = :uid");
        $this->db->bind('ueid', $equipId);
        $this->db->bind('uid', $userId);
        $this->db->execute();

        return true;
    }

    // Helper: Tambah Treasure ke User
    public function addTreasure($userId) {
        // Random Treasure
        $this->db->query("SELECT * FROM treasures ORDER BY RAND() LIMIT 1");
        $treasure = $this->db->single();

        $this->db->query("INSERT INTO user_treasures (user_id, treasure_id) VALUES (:uid, :tid)");
        $this->db->bind('uid', $userId);
        $this->db->bind('tid', $treasure['id']);
        $this->db->execute();
        
        // Update Achievement flag
        $this->db->query("UPDATE user_collections SET has_found_treasure = 1 WHERE user_id = :uid");
        $this->db->bind('uid', $userId);
        $this->db->execute();

        return $treasure;
    }
}