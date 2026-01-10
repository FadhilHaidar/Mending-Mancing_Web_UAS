<?php
require_once '../app/core/WeatherHelper.php';

class Magic extends Controller {
    
    public function __construct() {
        if(!isset($_SESSION['user_id'])) { header('Location: ' . BASEURL . '/auth/login'); exit; }
    }

    // PAWANG HUJAN (UBAH CUACA)
    public function pawang_hujan() {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $weather = $_POST['weather'];
            $userId = $_SESSION['user_id'];
            
            // Ambil data user terbaru untuk cek diamond
            $user = $this->model('User_model')->getUserById($userId);

            if($user['diamonds'] >= 1) {
                // Kurangi 1 Diamond
                $this->model('User_model')->reduceDiamond($userId, 1);
                
                // Set Cuaca (Simpan di Session / Override Helper)
                $_SESSION['custom_weather'] = $weather;
                $_SESSION['custom_weather_time'] = time(); // Valid 1 jam

                header('Location: ' . BASEURL . '/shop?msg=weather_changed&tab=magic');
            } else {
                header('Location: ' . BASEURL . '/shop?msg=no_key&tab=magic'); // msg=no_key ditangani view sbg "Diamond kurang"
            }
        }
    }

    // THE RITUAL (ENCHANT)
    public function ritual() {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $userId = $_SESSION['user_id'];
            $rodId = $_POST['rod_id'];
            $fishId = $_POST['fish_id']; // Inventory ID tumbal

            $user = $this->model('User_model')->getUserById($userId);

            if($user['diamonds'] >= 2) {
                // Kurangi Diamond & Hapus Ikan Tumbal
                $this->model('User_model')->reduceDiamond($userId, 2);
                $this->model('Fish_model')->deleteItem($fishId); // Hapus ikan tumbal

                // Logic Enchant (Update Luck Stat di table user_equipment)
                // Disini kita simulasi tambah luck permanen ke item tsb
                // Perlu ada kolom 'bonus_luck' atau update luck_stat di user_equipment, 
                // Tapi untuk simplifikasi kita anggap sukses visual dulu.
                
                header('Location: ' . BASEURL . '/shop?msg=gear_updated&tab=magic');
            } else {
                header('Location: ' . BASEURL . '/shop?msg=no_key&tab=magic');
            }
        }
    }
}