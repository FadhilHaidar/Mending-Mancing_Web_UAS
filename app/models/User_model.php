<?php

class User_model {
    private $table = 'users';
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    // --- AUTH ---
    public function registerUser($data) {
        $query = "INSERT INTO users (username, password, role, gold, energy, avatar, banner, bio, equipped_title) 
                  VALUES (:username, :password, 'user', 500, 100, 'default_avatar.png', 'default_banner.jpg', 'Player Baru', 'Pemula')";
        $this->db->query($query);
        $this->db->bind('username', $data['username']);
        $this->db->bind('password', password_hash($data['password'], PASSWORD_DEFAULT));
        $this->db->execute();
        return $this->db->rowCount();
    }

    public function getUserByUsername($username) {
        $this->db->query("SELECT * FROM " . $this->table . " WHERE username = :username");
        $this->db->bind('username', $username);
        return $this->db->single();
    }

    public function getUserById($id) {
        if(isset($_SESSION['user_id']) && $_SESSION['user_id'] == $id) {
            $this->db->query("UPDATE users SET last_activity = NOW() WHERE id = :id");
            $this->db->bind('id', $id);
            $this->db->execute();
        }
        $this->db->query("SELECT * FROM " . $this->table . " WHERE id = :id");
        $this->db->bind('id', $id);
        return $this->db->single();
    }

    // --- CURRENCY & ENERGY ---
    public function updateGold($userId, $amount) {
        $this->db->query("UPDATE users SET gold = gold + :a WHERE id = :id");
        $this->db->bind('a', $amount); $this->db->bind('id', $userId); $this->db->execute();
    }

    public function addDiamond($userId, $amount) {
        $this->db->query("UPDATE users SET diamonds = diamonds + :a WHERE id = :id");
        $this->db->bind('a', $amount); $this->db->bind('id', $userId); $this->db->execute();
    }

    public function reduceDiamond($userId, $amount) {
        $this->db->query("UPDATE users SET diamonds = diamonds - :a WHERE id = :id");
        $this->db->bind('a', $amount); $this->db->bind('id', $userId); $this->db->execute();
    }

    public function refreshEnergy($userId) {
        $this->db->query("SELECT energy, last_energy_update FROM users WHERE id = :id");
        $this->db->bind('id', $userId);
        $user = $this->db->single();

        if ($user) {
            $now = time();
            $lastUpdate = strtotime($user['last_energy_update']);
            $diff = $now - $lastUpdate;
            $energyGained = floor($diff / 300);

            if ($energyGained > 0 && $user['energy'] < 100) {
                $newEnergy = min(100, $user['energy'] + $energyGained);
                $this->db->query("UPDATE users SET energy = :eng, last_energy_update = NOW() WHERE id = :id");
                $this->db->bind('eng', $newEnergy); $this->db->bind('id', $userId); $this->db->execute();
            }
        }
    }

    public function reduceEnergy($userId, $amount) {
        $this->db->query("UPDATE users SET energy = energy - :a WHERE id = :id");
        $this->db->bind('a', $amount); $this->db->bind('id', $userId); $this->db->execute();
    }

    // --- INVENTORY & LOADOUT ---
    public function getLoadout($userId) {
        $this->db->query("SELECT s.name, s.image, s.type, s.luck_stat, s.rarity, ue.id as equip_id 
                          FROM user_equipment ue 
                          JOIN shop_items s ON ue.item_id = s.id 
                          WHERE ue.user_id=:uid AND ue.is_equipped=1");
        $this->db->bind('uid', $userId);
        $res = $this->db->resultSet();
        $loadout = ['rod'=>null, 'bait'=>null];
        foreach($res as $r) { $loadout[$r['type']] = $r; }
        return $loadout;
    }

    public function getAllUserEquipment($userId) {
        $this->db->query("SELECT ue.id as equip_id, s.name, s.type, s.luck_stat, s.rarity, s.image 
                          FROM user_equipment ue JOIN shop_items s ON ue.item_id=s.id WHERE ue.user_id=:uid");
        $this->db->bind('uid', $userId); return $this->db->resultSet();
    }

    public function equipItem($userId, $equipId, $type) {
        $this->db->query("UPDATE user_equipment ue JOIN shop_items s ON ue.item_id = s.id SET ue.is_equipped = 0 WHERE ue.user_id = :uid AND s.type = :type");
        $this->db->bind('uid', $userId); $this->db->bind('type', $type); $this->db->execute();
        $this->db->query("UPDATE user_equipment SET is_equipped = 1 WHERE id = :eid AND user_id = :uid");
        $this->db->bind('eid', $equipId); $this->db->bind('uid', $userId); $this->db->execute();
        return $this->db->rowCount();
    }

    // --- LEADERBOARD ---
    public function getTopSultan() {
        $this->db->query("SELECT id, username, avatar, selected_title, gold as score FROM users ORDER BY gold DESC LIMIT 10");
        return $this->db->resultSet();
    }
    public function getTopCollector() {
        $query = "SELECT u.id, u.username, u.avatar, u.selected_title, COALESCE(SUM(uc.total_caught), 0) as score 
                  FROM users u LEFT JOIN user_collections uc ON u.id = uc.user_id GROUP BY u.id ORDER BY score DESC LIMIT 10";
        $this->db->query($query); return $this->db->resultSet();
    }

    // --- MAP & SHOWCASE ---
    public function unlockMap($userId, $targetLevel) {
        $this->db->query("UPDATE users SET map_access_level = :lvl WHERE id = :uid");
        $this->db->bind('lvl', $targetLevel); $this->db->bind('uid', $userId); $this->db->execute();
    }

    public function getShowcaseFish($userId) {
        $this->db->query("SELECT showcase_fish_1, showcase_fish_2, showcase_fish_3, showcase_fish_4, showcase_fish_5, showcase_fish_6 FROM users WHERE id = :uid");
        $this->db->bind('uid', $userId);
        $slots = $this->db->single();
        $showcase = [];
        for($i=1; $i<=6; $i++) {
            if(!empty($slots['showcase_fish_'.$i])) {
                $this->db->query("SELECT f.name, f.image, f.rarity FROM inventory i JOIN fishes f ON i.fish_id = f.id WHERE i.id = :id");
                $this->db->bind('id', $slots['showcase_fish_'.$i]);
                $fish = $this->db->single();
                if($fish) $showcase[] = $fish;
            }
        }
        return $showcase;
    }

    public function getShowcaseAchievements($userId) {
        $this->db->query("SELECT showcase_ach_1, showcase_ach_2, showcase_ach_3 FROM users WHERE id = :uid");
        $this->db->bind('uid', $userId);
        $ids = $this->db->single();
        $achievements = [];
        
        for($i=1; $i<=3; $i++) {
            $id = $ids['showcase_ach_'.$i];
            // Validasi ID tidak kosong
            if(!empty($id)) {
                $this->db->query("SELECT * FROM achievements WHERE id = :id");
                $this->db->bind('id', $id);
                $ach = $this->db->single();
                if($ach) $achievements[] = $ach;
            }
        }
        return $achievements;
    }

    // --- FIX UTAMA PRESTASI DI SINI ---
public function updateProfileComplete($userId, $data, $bannerFile, $avatarFile) {
        // Handle Avatar Upload
        if($avatarFile['error'] !== 4) {
            $n = uniqid().'_'.$avatarFile['name']; 
            move_uploaded_file($avatarFile['tmp_name'], 'img/'.$n);
            $this->db->query("UPDATE users SET avatar = :v WHERE id = :id"); 
            $this->db->bind('v',$n); 
            $this->db->bind('id',$userId); 
            $this->db->execute();
        }
        
        // Handle Banner Upload
        if($bannerFile['error'] !== 4) {
            $n = uniqid().'_'.$bannerFile['name']; 
            move_uploaded_file($bannerFile['tmp_name'], 'img/'.$n);
            $this->db->query("UPDATE users SET banner = :v WHERE id = :id"); 
            $this->db->bind('v',$n); 
            $this->db->bind('id',$userId); 
            $this->db->execute();
        }

        $sql = "UPDATE users SET bio=:bio, selected_title=:t1, equipped_title_2=:t2, equipped_title_3=:t3, 
                showcase_ach_1=:a1, showcase_ach_2=:a2, showcase_ach_3=:a3,
                showcase_fish_1=:f1, showcase_fish_2=:f2, showcase_fish_3=:f3, showcase_fish_4=:f4, showcase_fish_5=:f5, showcase_fish_6=:f6 
                WHERE id=:uid";
        
        $this->db->query($sql);
        $this->db->bind('uid', $userId);
        $this->db->bind('bio', $data['bio']);
        $this->db->bind('t1', $data['title_1']);
        
        // Handle Nullable Titles
        $this->db->bind('t2', !empty($data['title_2']) ? $data['title_2'] : null);
        $this->db->bind('t3', !empty($data['title_3']) ? $data['title_3'] : null);
        
        // FIX: Konversi string kosong "" menjadi NULL untuk Achievements
        // Pastikan input name di form adalah ach_1, ach_2, ach_3
        $this->db->bind('a1', (isset($data['ach_1']) && $data['ach_1'] !== "") ? $data['ach_1'] : null);
        $this->db->bind('a2', (isset($data['ach_2']) && $data['ach_2'] !== "") ? $data['ach_2'] : null);
        $this->db->bind('a3', (isset($data['ach_3']) && $data['ach_3'] !== "") ? $data['ach_3'] : null);

        // Handle Nullable Fish Showcase
        for($i=1; $i<=6; $i++) {
            $this->db->bind('f'.$i, (isset($data['fish_'.$i]) && $data['fish_'.$i] !== "") ? $data['fish_'.$i] : null);
        }
        
        try {
            $this->db->execute();
            return true;
        } catch (PDOException $e) {
            // Log error jika perlu
            return false;
        }
    }
    public function getUserStatsDetailed($userId) {
        $stats = [];
        $this->db->query("SELECT COALESCE(SUM(total_caught),0) as t FROM user_collections WHERE user_id=:uid");
        $this->db->bind('uid',$userId); $stats['total_catch'] = $this->db->single()['t'];
        $this->db->query("SELECT COUNT(*) as t FROM fishes"); $tot = $this->db->single()['t'];
        $this->db->query("SELECT COUNT(*) as c FROM user_collections WHERE user_id=:uid"); 
        $this->db->bind('uid',$userId); $got = $this->db->single()['c'];
        $stats['pedia_percent'] = ($tot>0)?round(($got/$tot)*100):0;
        return $stats;
    }
    public function getAllUserFish($userId) {
        $this->db->query("SELECT i.id as inventory_id, f.name, f.rarity FROM inventory i JOIN fishes f ON i.fish_id=f.id WHERE i.user_id=:uid ORDER BY f.rarity DESC");
        $this->db->bind('uid',$userId); return $this->db->resultSet();
    }
    public function getUserAchievements($userId) {
        $this->db->query("SELECT a.*, ua.unlocked_at FROM achievements a LEFT JOIN user_achievements ua ON a.id = ua.achievement_id AND ua.user_id = :uid ORDER BY a.reward_gold ASC"); 
        $this->db->bind('uid', $userId); return $this->db->resultSet();
    }
    // --- DASHBOARD DATA ---
    public function getRecentLegendaryCatches() {
        // Mengambil 5 ikan legendary terakhir yang masuk inventory (ID terbesar = terbaru)
        $query = "SELECT u.username, u.avatar, f.name as fish_name, f.image as fish_image 
                  FROM inventory i 
                  JOIN users u ON i.user_id = u.id 
                  JOIN fishes f ON i.fish_id = f.id 
                  WHERE f.rarity = 'legendary' 
                  ORDER BY i.id DESC LIMIT 5";
        $this->db->query($query);
        return $this->db->resultSet();
    }

    public function getGlobalStats() {
        $stats = [];
        $this->db->query("SELECT COUNT(*) as t FROM users"); 
        $stats['total_players'] = $this->db->single()['t'];
        
        // Asumsi inventory id terus bertambah sebagai log tangkapan
        $this->db->query("SELECT MAX(id) as t FROM inventory"); 
        $stats['total_caught'] = $this->db->single()['t'] ?? 0;
        
        return $stats;
    }
    public function getEquipmentEffects($equipId) { return []; } 
    public function consumeBait($userId, $equipId) { }
}