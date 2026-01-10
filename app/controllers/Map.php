<?php
class Map extends Controller {
    public function index() {
        if(!isset($_SESSION['user_id'])) header('Location: ' . BASEURL . '/auth/login');
        
        $data['judul'] = 'Peta Dunia';
        $data['user'] = $this->model('User_model')->getUserById($_SESSION['user_id']);
        $data['gold'] = $data['user']['gold'];
        $data['maps'] = [
            ['id'=>1, 'name'=>'Kolam Ikan'],
            ['id'=>2, 'name'=>'Sungai Deras'],
            ['id'=>3, 'name'=>'Laut Lepas']
        ];

        $this->view('templates/header', $data);
        $this->view('map/index', $data);
        $this->view('templates/footer');
    }

    public function unlock($level) {
        $userId = $_SESSION['user_id'];
        $user = $this->model('User_model')->getUserById($userId);
        
        // Biaya: Sungai (1 Diamond), Laut (3 Diamond)
        $cost = ($level == 2) ? 1 : 3;

        if($user['diamonds'] >= $cost) {
            $this->model('User_model')->reduceDiamond($userId, $cost);
            $this->model('User_model')->unlockMap($userId, $level);
            header('Location: ' . BASEURL . '/map?msg=unlocked');
        } else {
            header('Location: ' . BASEURL . '/map?msg=no_key');
        }
    }

    public function visit($id) {
        $user = $this->model('User_model')->getUserById($_SESSION['user_id']);
        if ($user['map_access_level'] >= $id) {
            $_SESSION['current_map_id'] = $id;
            $names = [1=>'Kolam Ikan', 2=>'Sungai Deras', 3=>'Laut Lepas'];
            $_SESSION['current_map_name'] = $names[$id];
            header('Location: ' . BASEURL . '/fishing');
        } else {
            header('Location: ' . BASEURL . '/map?msg=locked');
        }
    }
}