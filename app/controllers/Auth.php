<?php

class Auth extends Controller {

    public function index() {
        // Jika akses /auth, langsung arahkan ke login
        $this->login();
    }

    public function login() {
        // 1. Cek apakah user sudah login? Kalau iya, lempar ke Map
        if(isset($_SESSION['user_id'])) {
            header('Location: ' . BASEURL . '/map');
            exit;
        }

        // 2. LOGIKA SAAT TOMBOL LOGIN DITEKAN (POST REQUEST)
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = $_POST['username'];
            $password = $_POST['password'];

            // Ambil data user dari DB
            $user = $this->model('User_model')->getUserByUsername($username);
            
            // Cek Username & Password
            if($user && password_verify($password, $user['password'])) {
                // Set Session Data
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role']; // admin/user
                
                // Redirect Sukses
                if($user['role'] == 'admin') {
                    header('Location: ' . BASEURL . '/admin');
                } else {
                    header('Location: ' . BASEURL . '/map');
                }
                exit;
            } else {
                // Jika Gagal Login (Username/Password salah)
                // Kita bisa tambahkan alert error nanti, sementara biarkan reload form
            }
        }

        // 3. TAMPILKAN HALAMAN (GET REQUEST)
        $data['judul'] = 'Login - Mending Mancing';
        
        // Cek dulu apakah View Header ada (untuk debugging blank page)
        if(!file_exists('../app/views/templates/header.php')) {
            die("Error: File views/templates/header.php tidak ditemukan!");
        }

        $this->view('templates/header', $data);
        $this->view('auth/login', $data); // Pastikan file ini ada di views/auth/login.php
        $this->view('templates/footer');
    }

    public function register() {
        // Cek jika sudah login
        if(isset($_SESSION['user_id'])) {
            header('Location: ' . BASEURL . '/map');
            exit;
        }

        $data['judul'] = 'Daftar Akun Baru';
        $this->view('templates/header', $data);
        $this->view('auth/register');
        $this->view('templates/footer');
    }

    public function store() {
        // Logika Menyimpan User Baru
        if($this->model('User_model')->registerUser($_POST) > 0) {
            // Sukses daftar, arahkan ke login
            header('Location: ' . BASEURL . '/auth/login');
            exit;
        } else {
            // Gagal daftar, kembali ke register
            header('Location: ' . BASEURL . '/auth/register');
            exit;
        }
    }

    public function logout() {
        // Hapus semua session
        session_unset();
        session_destroy();
        
        // Redirect ke login
        header('Location: ' . BASEURL . '/auth/login');
        exit;
    }
}