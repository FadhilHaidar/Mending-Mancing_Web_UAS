<?php

class Admin extends Controller {

    public function __construct() {
        // Security Guard: Cek apakah login & role == admin
        if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
            header('Location: ' . BASEURL . '/auth/login');
            exit;
        }
    }

    // Halaman Dashboard Admin (Daftar Ikan)
    public function index() {
        $data['judul'] = 'Dashboard Admin';
        $data['fishes'] = $this->model('Fish_model')->getAllFishes(); // Kita pakai method yg sudah ada
        
        $this->view('templates/header', $data);
        $this->view('admin/index', $data);
        $this->view('templates/footer');
    }

    // Halaman Form Tambah Ikan
    public function add() {
        $data['judul'] = 'Tambah Ikan Baru';
        
        $this->view('templates/header', $data);
        $this->view('admin/add', $data); // Kita buat view ini nanti
        $this->view('templates/footer');
    }

    // Proses Simpan Data (Create)
    public function store() {
        // Upload Gambar Logic
        $gambar = 'default.png'; // Default jika user tidak upload
        
        if($_FILES['image']['error'] === 4) {
            // Tidak ada gambar diupload
            $gambar = 'default.png';
        } else {
            // Proses upload
            $namaFile = $_FILES['image']['name'];
            $tmpName = $_FILES['image']['tmp_name'];
            
            // Generate nama baru agar tidak duplikat (unik)
            $ekstensiGambar = explode('.', $namaFile);
            $ekstensiGambar = strtolower(end($ekstensiGambar));
            $namaFileBaru = uniqid() . '.' . $ekstensiGambar;

            // Pindahkan ke folder public/img
            move_uploaded_file($tmpName, '../public/img/' . $namaFileBaru);
            $gambar = $namaFileBaru;
        }

        // Gabungkan nama gambar ke array $_POST
        $_POST['image'] = $gambar;

        // Kirim ke Model
        if($this->model('Fish_model')->addFish($_POST) > 0) {
            header('Location: ' . BASEURL . '/admin');
        } else {
            // Error handling sederhana
            echo "Gagal menambahkan data.";
        }
    }

    // Proses Hapus Data (Delete)
    public function delete($id) {
        if($this->model('Fish_model')->deleteFish($id) > 0) {
            header('Location: ' . BASEURL . '/admin');
        }
    }
}