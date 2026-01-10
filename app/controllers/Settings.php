<?php

class Settings extends Controller {
    public function index() {
        if(!isset($_SESSION['user_id'])) { header('Location: '.BASEURL.'/auth'); exit; }

        $data['judul'] = 'Pengaturan Game';
        $user = $this->model('User_model')->getUserByUsername($_SESSION['username']);
        $data['gold'] = $user['gold'];

        $this->view('templates/header', $data);
        $this->view('settings/index', $data);
        $this->view('templates/footer');
    }
}