<?php 
if( !session_id() ) session_start(); // Start Session
require_once '../app/init.php'; // (Pastikan init memanggil config, controller, db, dll)
$app = new App;