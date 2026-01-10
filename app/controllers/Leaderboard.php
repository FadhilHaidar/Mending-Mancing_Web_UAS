<?php

class Leaderboard extends Controller {
    
    public function index() {
        // Cek Login (Opsional, tapi sebaiknya user login dulu biar seru)
        if(!isset($_SESSION['user_id'])) {
            header('Location: ' . BASEURL . '/auth/login');
            exit;
        }

        $data['judul'] = 'Hall of Fame';
        
        // Data User Login (Untuk Header)
        $data['user'] = $this->model('User_model')->getUserByUsername($_SESSION['username']);
        $data['gold'] = $data['user']['gold'];

        // Data Leaderboard
        $data['top_sultan'] = $this->model('User_model')->getTopSultan();
        $data['top_collector'] = $this->model('User_model')->getTopCollector();

        $this->view('templates/header', $data);
        $this->view('leaderboard/index', $data);
        $this->view('templates/footer');
    }
}