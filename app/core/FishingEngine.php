<?php

class FishingEngine {
    
    // 1. Logika Gacha Rarity (Updated: Weather + Magic Effects)
    public static function rollRarity($totalLuck = 0, $weather = 'sunny', $effects = []) {
        $baseRoll = rand(1, 100);
        
        // A. Weather Modifier
        $weatherBonus = 0;
        if ($weather == 'rain') $weatherBonus = 15;   // Hujan: Luck +15
        if ($weather == 'storm') $weatherBonus = 30;  // Badai: Luck +30
        
        // B. Magic Effects (Enchant/Curse)
        $magicBonus = 0;
        
        // Enchant: Luck Boost (Reeler / Leprechaun)
        if (isset($effects['enchant']) && $effects['enchant']['effect_type'] == 'luck_boost') {
            $magicBonus += $effects['enchant']['effect_value'];
        }
        
        // Enchant: Stormhunter (Bonus Besar saat Badai)
        if (isset($effects['enchant']) && $effects['enchant']['name'] == 'Stormhunter' && $weather == 'storm') {
            $magicBonus += 50; 
        }
        
        // Curse: Jinxed (Pengurangan Luck)
        if (isset($effects['curse']) && $effects['curse']['name'] == 'Jinxed') {
            $magicBonus -= 20;
        }

        // Rumus Final
        $finalScore = $baseRoll + $totalLuck + $weatherBonus + $magicBonus;

        // Curse: Repellent (Mencegah Rarity Tinggi)
        if (isset($effects['curse']) && $effects['curse']['name'] == 'Repellent') {
            // Cap score di 80 (Mentok di Rare, susah dapat Epic/Legendary)
            $finalScore = min($finalScore, 80);
        }

        // Threshold Rarity
        if ($finalScore >= 120) return 'legendary'; 
        if ($finalScore > 95) return 'epic';
        if ($finalScore > 70) return 'rare';
        return 'common';
    }

    // 2. Logika Mutasi (Updated: Elemental Force)
    public static function rollMutation($weather = 'sunny') {
        // Cek Elemental Force
        if ($weather == 'heatwave') return (rand(1, 100) <= 30) ? 'fire' : 'normal'; 
        if ($weather == 'snow') return (rand(1, 100) <= 30) ? 'ice' : 'normal';
        if ($weather == 'storm') return (rand(1, 100) <= 30) ? 'electric' : 'normal';

        // Mutasi Standar
        $chance = rand(1, 100);
        if ($chance <= 75) return 'normal';
        if ($chance <= 90) return 'big';
        if ($chance <= 95) return 'tiny';
        if ($chance <= 99) return 'shiny';
        return 'glitch';
    }

    // 3. Kalkulasi Harga
    public static function calculatePrice($basePrice, $mutation) {
        switch($mutation) {
            case 'big': return $basePrice * 1.5;
            case 'tiny': return $basePrice * 1.2;
            case 'shiny': return $basePrice * 5;
            case 'glitch': return $basePrice * 10;
            case 'fire': return $basePrice * 3;
            case 'ice': return $basePrice * 3;
            case 'electric': return $basePrice * 4;
            default: return $basePrice;
        }
    }

    // 4. Engine Utama
    public static function gacha($fishList, $totalLuck = 0, $weather = 'sunny', $effects = []) {
        // A. Roll Rarity dengan Efek Lengkap
        $rarity = self::rollRarity($totalLuck, $weather, $effects);

        // B. Filter Ikan
        $potentialCatch = array_filter($fishList, function($fish) use ($rarity) {
            return $fish['rarity'] === $rarity;
        });

        // Fallback Logic
        if (empty($potentialCatch) && $rarity == 'legendary') {
            $rarity = 'epic';
            $potentialCatch = array_filter($fishList, function($fish) { return $fish['rarity'] === 'epic'; });
        }
        if (empty($potentialCatch) && $rarity == 'epic') {
            $rarity = 'rare';
            $potentialCatch = array_filter($fishList, function($fish) { return $fish['rarity'] === 'rare'; });
        }
        if (empty($potentialCatch)) {
            $potentialCatch = array_filter($fishList, function($fish) { return $fish['rarity'] === 'common'; });
        }

        // C. Ambil Satu Ikan
        if (!empty($potentialCatch)) {
            $fish = $potentialCatch[array_rand($potentialCatch)];
            $fish['mutation'] = self::rollMutation($weather);
            return $fish;
        }
        
        return null;
    }

    // 5. Cek Treasure (Fitur Enchant Magnet)
    public static function checkTreasure($effects) {
        if (isset($effects['enchant']) && $effects['enchant']['name'] == 'Magnet') {
            // 15% Chance dapat Treasure tambahan
            if (rand(1, 100) <= 15) return true;
        }
        return false;
    }
}