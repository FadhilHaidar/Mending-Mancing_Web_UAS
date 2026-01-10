<?php
class Shop extends Controller {
    public function __construct() {
        if(!isset($_SESSION['user_id'])) { header('Location: ' . BASEURL . '/auth/login'); exit; }
    }

    public function index() {
        $userId = $_SESSION['user_id'];
        $data['judul'] = 'Pusat Perbelanjaan';
        $data['user'] = $this->model('User_model')->getUserById($userId);
        $data['gold'] = $data['user']['gold'];

        $data['rods'] = $this->model('Shop_model')->getRods();
        $data['baits'] = $this->model('Shop_model')->getBaits();
        $data['foods'] = $this->model('Shop_model')->getFoods();
        
        $data['my_rods'] = $this->model('User_model')->getAllUserEquipment($userId); 
        $allFish = $this->model('User_model')->getAllUserFish($userId);
        $data['legendaries'] = array_filter($allFish, function($f){ return $f['rarity'] == 'legendary'; });

        // AMBIL LISTING PASAR AKTIF
        $data['listings'] = $this->model('Shop_model')->getAllListings('newest', 'all');

        $this->view('templates/header', $data);
        $this->view('shop/index', $data);
        $this->view('templates/footer');
    }

    public function buy($itemId) {
        $res = $this->model('Shop_model')->buyItem($_SESSION['user_id'], $itemId);
        header('Location: ' . BASEURL . '/shop?msg=' . ($res == 'success' ? 'bought' : 'nomoney'));
    }

    public function buy_food($itemId) {
        $res = $this->model('Shop_model')->buyAndEat($_SESSION['user_id'], $itemId);
        if($res == 'success') header('Location: ' . BASEURL . '/shop?msg=energy_restored&tab=warteg');
        elseif($res == 'full_energy') header('Location: ' . BASEURL . '/shop?msg=full_energy&tab=warteg');
        else header('Location: ' . BASEURL . '/shop?msg=nomoney&tab=warteg');
    }

    public function exchange_diamond() {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            if($this->model('Shop_model')->exchangeLegendaryForDiamond($_SESSION['user_id'], $_POST['fish_id'])) {
                header('Location: ' . BASEURL . '/shop?msg=diamond_received&tab=market');
            } else {
                header('Location: ' . BASEURL . '/shop?msg=error');
            }
        }
    }

    // --- MARKETPLACE ACTIONS ---

    public function list_item() {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $inventoryId = $_POST['inventory_id'];
            $price = $_POST['price'];
            
            if($this->model('Shop_model')->addListing($_SESSION['user_id'], $inventoryId, $price)) {
                header('Location: ' . BASEURL . '/fishing/inventory?msg=listed');
            } else {
                header('Location: ' . BASEURL . '/fishing/inventory?msg=error');
            }
        }
    }

    public function cancel_listing() {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $inventoryId = $_POST['inventory_id'];
            if($this->model('Shop_model')->cancelListing($inventoryId, $_SESSION['user_id'])) {
                header('Location: ' . BASEURL . '/fishing/inventory?msg=canceled');
            } else {
                header('Location: ' . BASEURL . '/fishing/inventory?msg=error');
            }
        }
    }

    public function buy_market($listingId) {
        $res = $this->model('Shop_model')->buyMarketItem($listingId, $_SESSION['user_id']);
        if($res == 'success') header('Location: ' . BASEURL . '/shop?msg=bought&tab=market');
        elseif($res == 'no_gold') header('Location: ' . BASEURL . '/shop?msg=nomoney&tab=market');
        elseif($res == 'self_buy') header('Location: ' . BASEURL . '/shop?msg=error&tab=market');
        else header('Location: ' . BASEURL . '/shop?msg=sold_out&tab=market');
    }
}