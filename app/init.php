<?php

// Memanggil Konfigurasi Database & URL
require_once 'config/config.php';

// Memanggil Core MVC Utama
require_once 'core/App.php';
require_once 'core/Controller.php';
require_once 'core/Database.php';

// Memanggil Helper/Engine Tambahan (Opsional, tapi bagus ditaruh sini)
require_once 'core/FishingEngine.php';