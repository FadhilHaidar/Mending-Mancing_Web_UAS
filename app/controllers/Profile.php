<?php
class Profile extends Controller {
    public function __construct() {
        if(!isset($_SESSION['user_id'])) { header('Location: ' . BASEURL . '/auth/login'); exit; }
    }

    public function index($userId = null) {
        // Jika userId kosong, tampilkan profil sendiri
        $targetId = $userId ?? $_SESSION['user_id'];
        
        $data['user'] = $this->model('User_model')->getUserById($targetId);
        if(!$data['user']) { header('Location: ' . BASEURL); exit; }

        // --- PERBAIKAN: Definisikan Gold agar Navbar tidak error/hilang ---
        $data['gold'] = $data['user']['gold']; 
        // ------------------------------------------------------------------

        $data['judul'] = 'Profil ' . $data['user']['username'];
        $data['is_own_profile'] = ($targetId == $_SESSION['user_id']);
        
        // Data Showcase
        $data['showcase_fish'] = $this->model('User_model')->getShowcaseFish($targetId);
        $data['showcase_gears'] = $this->model('User_model')->getLoadout($targetId); 
        $data['showcase_ach'] = $this->model('User_model')->getShowcaseAchievements($targetId);
        $data['stats'] = $this->model('User_model')->getUserStatsDetailed($targetId);

        // Data untuk Modal Edit (Hanya jika profil sendiri)
        if($data['is_own_profile']) {
            $data['all_fish'] = $this->model('User_model')->getAllUserFish($targetId);
            $data['all_achievements'] = $this->model('User_model')->getUserAchievements($targetId);
            $data['available_titles'] = ['Pemula', 'Nelayan', 'Hobbyist', 'Mancing Mania', 'Sultan', 'Kolektor', 'Legend'];
        }

        $this->view('templates/header', $data);
        $this->view('profile/index', $data);
        $this->view('templates/footer');
    }

    public function update() {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $userId = $_SESSION['user_id'];
            // Kirim $_POST dan $_FILES ke model
            // Pastikan model User_model->updateProfileComplete sudah yang versi TERBARU (yang menangani NULL)
            if($this->model('User_model')->updateProfileComplete($userId, $_POST, $_FILES['banner'], $_FILES['avatar'])) {
                header('Location: ' . BASEURL . '/profile?msg=updated');
            } else {
                header('Location: ' . BASEURL . '/profile?msg=error');
            }
        }
    }
}