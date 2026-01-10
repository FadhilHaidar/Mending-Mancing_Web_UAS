<?php

class Achievement_model {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    // Ambil SEMUA achievement (dicocokkan dengan status user)
    public function getAchievementsByUser($userId) {
        // Query ini agak kompleks: Menggabungkan Master Data dengan Data User (LEFT JOIN)
        // Jika user punya achievement, kolom 'unlocked_at' tidak akan NULL.
        $query = "SELECT a.*, ua.unlocked_at 
                  FROM achievements a
                  LEFT JOIN user_achievements ua ON a.id = ua.achievement_id AND ua.user_id = :uid";
        
        $this->db->query($query);
        $this->db->bind('uid', $userId);
        return $this->db->resultSet();
    }
}