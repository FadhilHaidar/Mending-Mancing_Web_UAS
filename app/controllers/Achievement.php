<?php

class Achievement extends Controller {
    
    public function index() {
        if(!isset($_SESSION['user_id'])) header('Location: ' . BASEURL . '/auth/login');

        $data['judul'] = 'Prestasi & Peringkat';
        $userId = $_SESSION['user_id'];
        $data['user'] = $this->model('User_model')->getUserById($userId);
        $data['gold'] = $data['user']['gold'];

        // Tab 1: Achievement User
        $data['achievements'] = $this->model('User_model')->getUserAchievements($userId);

        // Tab 2 & 3: Leaderboard
        $data['top_sultan'] = $this->model('User_model')->getTopSultan();
        $data['top_collector'] = $this->model('User_model')->getTopCollector(); // FIX: Panggil method ini

        $this->view('templates/header', $data);
        $this->view('achievement/index', $data);
        $this->view('templates/footer');
    }
}