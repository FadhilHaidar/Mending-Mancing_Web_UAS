<?php

class AchievementHelper {
    
    // Method statis agar bisa dipanggil di mana saja tanpa instansiasi model yang rumit
    public static function check($userId, $triggerType, $currentValue) {
        $db = new Database; // Instansiasi DB langsung

        // 1. Ambil semua achievement tipe ini yang BELUM dimiliki user
        $query = "SELECT * FROM achievements 
                  WHERE trigger_type = :type 
                  AND id NOT IN (SELECT achievement_id FROM user_achievements WHERE user_id = :uid)";
        
        $db->query($query);
        $db->bind('type', $triggerType);
        $db->bind('uid', $userId);
        $candidates = $db->resultSet();

        foreach($candidates as $ach) {
            $passed = false;

            // 2. Logika Pengecekan Berdasarkan Tipe
            if ($triggerType == 'total_catch' || $triggerType == 'hold_gold') {
                // Untuk angka: Cek apakah nilai sekarang >= target
                if (intval($currentValue) >= intval($ach['trigger_value'])) {
                    $passed = true;
                }
            } 
            elseif ($triggerType == 'catch_rarity') {
                // Untuk Rarity: Cek kesamaan string (misal: dapat 'legendary')
                // Logika: Trigger value database ('legendary') == Value yang didapat user ('legendary')
                // Note: Kita bisa buat logika hierarki (dapat legendary otomatis unlock rare), tapi ini simpelnya dulu.
                if (strtolower($currentValue) == strtolower($ach['trigger_value'])) {
                    $passed = true;
                }
                // Khusus Mutasi Glitch (jika logic rarity di controller mengirim string 'glitch_mutation')
                if ($ach['trigger_value'] == 'glitch_mutation' && $currentValue == 'glitch') {
                    $passed = true;
                }
            }

            // 3. JIKA LOLOS SYARAT -> UNLOCK!
            if ($passed) {
                // A. Insert ke user_achievements
                $db->query("INSERT INTO user_achievements (user_id, achievement_id) VALUES (:uid, :aid)");
                $db->bind('uid', $userId);
                $db->bind('aid', $ach['id']);
                $db->execute();

                // B. Beri Hadiah Gold
                if ($ach['reward_gold'] > 0) {
                    $db->query("UPDATE users SET gold = gold + :g WHERE id = :uid");
                    $db->bind('g', $ach['reward_gold']);
                    $db->bind('uid', $userId);
                    $db->execute();
                }

                // C. Set Flash Message (Agar muncul notifikasi di View)
                // Kita gunakan Session array agar bisa menampung multiple unlock sekaligus
                if (!isset($_SESSION['achievements_unlocked'])) {
                    $_SESSION['achievements_unlocked'] = [];
                }
                $_SESSION['achievements_unlocked'][] = $ach;
            }
        }
    }
}