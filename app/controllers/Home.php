<?php
class Home extends Controller {
    public function index() {
        $data['judul'] = 'Home';
        
        // Data untuk Dashboard
        $data['recent_legendaries'] = $this->model('User_model')->getRecentLegendaryCatches();
        $data['global_stats'] = $this->model('User_model')->getGlobalStats();
        
        // Cek User Login (untuk Header)
        if(isset($_SESSION['user_id'])) {
            $data['user'] = $this->model('User_model')->getUserById($_SESSION['user_id']);
            $data['gold'] = $data['user']['gold'];
        }

        $this->view('templates/header', $data);
        $this->view('home/index', $data); // Kita akan ubah ini total
        $this->view('templates/footer');
    }
}