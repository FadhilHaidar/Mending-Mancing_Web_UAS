<?php

class WeatherHelper {
    
    // Tentukan cuaca berdasarkan Jam Server (Deterministik)
    // Agar semua player mengalami cuaca yang sama di jam yang sama.
    public static function getCurrentWeather() {
        $hour = (int)date('H'); // Ambil jam (0-23)

        // JADWAL CUACA
        // 05:00 - 09:59 : Sunny (Cerah)
        // 10:00 - 13:59 : Heatwave (Gelombang Panas)
        // 14:00 - 17:59 : Rain (Hujan)
        // 18:00 - 20:59 : Storm (Badai)
        // 21:00 - 04:59 : Snow (Salju/Dingin Malam)

        if ($hour >= 5 && $hour < 10) return 'sunny';
        if ($hour >= 10 && $hour < 14) return 'heatwave';
        if ($hour >= 14 && $hour < 18) return 'rain';
        if ($hour >= 18 && $hour < 21) return 'storm';
        return 'snow';
    }

    // Info Buff untuk UI
    public static function getWeatherInfo($weather) {
        switch($weather) {
            case 'sunny': return ['icon' => '☀️', 'name' => 'Cerah', 'effect' => 'Normal'];
            case 'heatwave': return ['icon' => '🔥', 'name' => 'Heatwave', 'effect' => 'Mutation: FIRE Guaranteed!'];
            case 'rain': return ['icon' => '🌧️', 'name' => 'Hujan', 'effect' => 'Luck +15%'];
            case 'storm': return ['icon' => '⛈️', 'name' => 'Badai Petir', 'effect' => 'Luck +30% | Energy Cost +2'];
            case 'snow': return ['icon' => '❄️', 'name' => 'Salju', 'effect' => 'Mutation: ICE Guaranteed!'];
            default: return ['icon' => '☁️', 'name' => 'Berawan', 'effect' => '-'];
        }
    }
}