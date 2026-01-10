<?php

class Collection extends Controller {
    
    public function index() {
        if(!isset($_SESSION['user_id'])) {
            header('Location: ' . BASEURL . '/auth/login');
            exit;
        }

        $data['judul'] = 'Fishpedia';
        $data['user'] = $this->model('User_model')->getUserByUsername($_SESSION['username']);
        $data['gold'] = $data['user']['gold'];
        
        // Ambil Data Pokedex
        $data['pokedex'] = $this->model('Fish_model')->getFishpediaData($_SESSION['user_id']);

        $this->view('templates/header', $data);
        $this->view('collection/index', $data);
        $this->view('templates/footer');
    }
}