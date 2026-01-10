-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 10 Jan 2026 pada 23.17
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_mending_mancing`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `achievements`
--

CREATE TABLE `achievements` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `rarity` enum('common','rare','epic','legendary') DEFAULT 'common',
  `reward_gold` int(11) DEFAULT 0,
  `reward_title` varchar(50) DEFAULT NULL,
  `trigger_type` enum('total_catch','catch_rarity','hold_gold') NOT NULL,
  `trigger_value` varchar(50) NOT NULL,
  `icon` varchar(50) DEFAULT '?'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `achievements`
--

INSERT INTO `achievements` (`id`, `name`, `description`, `rarity`, `reward_gold`, `reward_title`, `trigger_type`, `trigger_value`, `icon`) VALUES
(1, 'Mancing Mania', 'Tangkap ikan pertamamu.', 'common', 50, 'Nelayan', 'total_catch', '1', '🎣'),
(2, 'Jam Terbang', 'Tangkap total 10 ikan.', 'common', 100, 'Hobbyist', 'total_catch', '10', '🐟'),
(3, 'Kolektor', 'Tangkap total 50 ikan.', 'rare', 500, 'Pengepul', 'total_catch', '50', '📦'),
(4, 'Penguasa Laut', 'Tangkap total 100 ikan.', 'epic', 1000, 'Kapten', 'total_catch', '100', '⚓'),
(5, 'Hoki Pemula', 'Dapatkan ikan Rare.', 'common', 100, 'Beruntung', 'catch_rarity', 'rare', '✨'),
(6, 'Jackpot!', 'Dapatkan ikan Epic.', 'rare', 500, 'Master', 'catch_rarity', 'epic', '🌟'),
(7, 'Mitos Menjadi Nyata', 'Dapatkan ikan Legendary.', 'legendary', 5000, 'Legend', 'catch_rarity', 'legendary', '🐲'),
(8, 'Glitch Hunter', 'Dapatkan ikan mutasi Glitch.', 'legendary', 10000, 'The Hacker', 'catch_rarity', 'glitch_mutation', '👾'),
(9, 'Tabungan Awal', 'Miliki saldo 1.000 Gold.', 'common', 100, 'Juragan', 'hold_gold', '1000', '💰'),
(10, 'Orang Kaya Baru', 'Miliki saldo 5.000 Gold.', 'rare', 500, 'Sultan', 'hold_gold', '5000', '🏦'),
(11, 'Investor', 'Miliki saldo 20.000 Gold.', 'epic', 2000, 'Tycoon', 'hold_gold', '20000', '💎'),
(12, 'Ekonomi Naga', 'Miliki saldo 100.000 Gold.', 'legendary', 10000, 'Godfather', 'hold_gold', '100000', '👑');

-- --------------------------------------------------------

--
-- Struktur dari tabel `crates`
--

CREATE TABLE `crates` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `type` enum('rod_crate','bait_crate') NOT NULL,
  `cost_keys` int(11) DEFAULT 1,
  `image` varchar(100) DEFAULT 'crate_default.png'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `crates`
--

INSERT INTO `crates` (`id`, `name`, `type`, `cost_keys`, `image`) VALUES
(1, 'Peti Joran', 'rod_crate', 1, 'crate_rod.png'),
(2, 'Peti Umpan', 'bait_crate', 1, 'crate_bait.png');

-- --------------------------------------------------------

--
-- Struktur dari tabel `crate_contents`
--

CREATE TABLE `crate_contents` (
  `id` int(11) NOT NULL,
  `crate_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `probability_weight` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `crate_contents`
--

INSERT INTO `crate_contents` (`id`, `crate_id`, `item_id`, `probability_weight`) VALUES
(1, 1, 1, 50),
(2, 1, 2, 30),
(3, 1, 3, 15),
(4, 1, 4, 5),
(5, 2, 5, 50),
(6, 2, 6, 30),
(7, 2, 7, 15),
(8, 2, 8, 5);

-- --------------------------------------------------------

--
-- Struktur dari tabel `curses`
--

CREATE TABLE `curses` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `effect_type` varchar(50) NOT NULL,
  `effect_value` int(11) DEFAULT 0,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `curses`
--

INSERT INTO `curses` (`id`, `name`, `effect_type`, `effect_value`, `description`) VALUES
(1, 'Heavy Hook', 'energy_cost', 2, 'Joran terasa sangat berat. Energy Cost +2.'),
(2, 'Jinxed', 'luck_reduction', 20, 'Nasib buruk menghantuimu. Luck -20.'),
(3, 'Repellent', 'rare_reduction', 50, 'Ikan langka menjauhimu. Chance Rare/Epic -50%.');

-- --------------------------------------------------------

--
-- Struktur dari tabel `enchantments`
--

CREATE TABLE `enchantments` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `rarity` enum('common','rare','epic','legendary') DEFAULT 'common',
  `effect_type` varchar(50) NOT NULL,
  `effect_value` int(11) DEFAULT 0,
  `color_hex` varchar(7) DEFAULT '#ffffff'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `enchantments`
--

INSERT INTO `enchantments` (`id`, `name`, `rarity`, `effect_type`, `effect_value`, `color_hex`) VALUES
(1, 'Reeler', 'common', 'luck_boost', 5, '#00ff00'),
(2, 'Magnet', 'rare', 'magnet_treasure', 10, '#c0c0c0'),
(3, 'Stormhunter', 'epic', 'storm_boost', 20, '#0000ff'),
(4, 'Leprechaun', 'legendary', 'luck_boost', 50, '#ffd700');

-- --------------------------------------------------------

--
-- Struktur dari tabel `fishes`
--

CREATE TABLE `fishes` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `rarity` enum('common','rare','epic','legendary') NOT NULL,
  `price` int(11) NOT NULL,
  `image` varchar(100) DEFAULT 'default_fish.png',
  `map_id` int(11) DEFAULT 1,
  `lore` text DEFAULT 'Ikan misterius yang hidup di perairan ini.',
  `preferred_weather` enum('sunny','rain','storm','heatwave','snow','any') DEFAULT 'any'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `fishes`
--

INSERT INTO `fishes` (`id`, `name`, `rarity`, `price`, `image`, `map_id`, `lore`, `preferred_weather`) VALUES
(1, 'Ikan Mas', 'common', 50, 'ikan_mas.png', 1, 'Ikan misterius yang hidup di perairan ini.', 'any'),
(2, 'Mujair', 'common', 40, 'mujair.png', 1, 'Ikan misterius yang hidup di perairan ini.', 'any'),
(3, 'Lele', 'common', 60, 'lele.png', 1, 'Ikan misterius yang hidup di perairan ini.', 'any'),
(4, 'Koi', 'rare', 300, 'koi.png', 1, 'Ikan misterius yang hidup di perairan ini.', 'rain'),
(5, 'Buntal Tawar', 'rare', 350, 'buntal.png', 1, 'Ikan misterius yang hidup di perairan ini.', 'rain'),
(6, 'Gabus', 'rare', 400, 'gabus.png', 1, 'Ikan misterius yang hidup di perairan ini.', 'rain'),
(7, 'Kura-kura Brazil', 'epic', 1000, 'kura_brazil.png', 1, 'Ikan misterius yang hidup di perairan ini.', 'any'),
(8, 'Arwana Silver', 'epic', 1500, 'arwana_silver.png', 1, 'Ikan misterius yang hidup di perairan ini.', 'any'),
(9, 'King Arwana Super Red', 'legendary', 5000, 'arwana_red.png', 1, 'Ikan misterius yang hidup di perairan ini.', 'any'),
(10, 'Sepat', 'common', 40, 'sepat.png', 2, 'Ikan misterius yang hidup di perairan ini.', 'any'),
(11, 'Wader', 'common', 30, 'wader.png', 2, 'Ikan misterius yang hidup di perairan ini.', 'any'),
(12, 'Udang Sungai', 'common', 80, 'udang.png', 2, 'Ikan misterius yang hidup di perairan ini.', 'any'),
(13, 'Bawal', 'rare', 400, 'bawal.png', 2, 'Ikan misterius yang hidup di perairan ini.', 'rain'),
(14, 'Belut', 'rare', 450, 'belut.png', 2, 'Ikan misterius yang hidup di perairan ini.', 'rain'),
(15, 'Piranha', 'rare', 500, 'piranha.png', 2, 'Ikan misterius yang hidup di perairan ini.', 'rain'),
(16, 'Salmon', 'epic', 1200, 'salmon.png', 2, 'Ikan misterius yang hidup di perairan ini.', 'any'),
(17, 'Sidat Listrik', 'epic', 1400, 'sidat_listrik.png', 2, 'Ikan misterius yang hidup di perairan ini.', 'any'),
(18, 'Buaya Putih', 'legendary', 7000, 'buaya_putih.png', 2, 'Ikan misterius yang hidup di perairan ini.', 'any'),
(19, 'Teri', 'common', 20, 'teri.png', 3, 'Ikan misterius yang hidup di perairan ini.', 'any'),
(20, 'Kembung', 'common', 50, 'kembung.png', 3, 'Ikan misterius yang hidup di perairan ini.', 'any'),
(21, 'Kerang Ajaib', 'common', 100, 'kerang.png', 3, 'Ikan misterius yang hidup di perairan ini.', 'any'),
(22, 'Cumi-cumi', 'rare', 500, 'cumi.png', 3, 'Ikan misterius yang hidup di perairan ini.', 'rain'),
(23, 'Kakap Merah', 'rare', 600, 'kakap.png', 3, 'Ikan misterius yang hidup di perairan ini.', 'rain'),
(24, 'Lobster', 'rare', 750, 'lobster.png', 3, 'Ikan misterius yang hidup di perairan ini.', 'rain'),
(25, 'Hiu Martil', 'epic', 2000, 'hiu_martil.png', 3, 'Ikan misterius yang hidup di perairan ini.', 'any'),
(26, 'Pari Manta', 'epic', 2500, 'pari_manta.png', 3, 'Ikan misterius yang hidup di perairan ini.', 'any'),
(27, 'The Leviathan', 'legendary', 15000, 'leviathan.png', 3, 'Ikan misterius yang hidup di perairan ini.', 'any');

-- --------------------------------------------------------

--
-- Struktur dari tabel `inventory`
--

CREATE TABLE `inventory` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `fish_id` int(11) NOT NULL,
  `quantity` int(11) DEFAULT 1,
  `obtained_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `mutation` enum('normal','big','tiny','shiny','glitch','fire','ice','electric') DEFAULT 'normal'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `inventory`
--

INSERT INTO `inventory` (`id`, `user_id`, `fish_id`, `quantity`, `obtained_at`, `mutation`) VALUES
(10, 1, 12, 1, '2026-01-05 15:40:52', 'shiny'),
(29, 1, 2, 1, '2026-01-07 16:43:37', 'shiny'),
(40, 1, 27, 1, '2026-01-07 16:54:53', 'normal'),
(41, 1, 27, 1, '2026-01-07 17:37:23', 'normal'),
(42, 2, 20, 1, '2026-01-07 18:11:22', 'normal'),
(44, 2, 21, 1, '2026-01-07 18:11:29', 'normal'),
(45, 2, 27, 1, '2026-01-07 19:22:54', 'electric'),
(46, 2, 20, 1, '2026-01-07 19:22:59', 'electric'),
(47, 2, 22, 1, '2026-01-07 19:23:01', 'normal'),
(48, 2, 27, 1, '2026-01-07 19:23:02', 'normal'),
(60, 1, 5, 1, '2026-01-08 06:46:41', 'shiny'),
(67, 2, 6, 1, '2026-01-08 12:58:51', 'fire'),
(73, 2, 5, 1, '2026-01-08 14:54:49', 'normal'),
(74, 2, 4, 1, '2026-01-08 14:54:52', 'big'),
(75, 2, 1, 1, '2026-01-08 14:57:58', 'normal'),
(151, 1, 9, 1, '2026-01-09 20:44:00', 'ice'),
(155, 1, 9, 1, '2026-01-09 20:44:05', 'normal'),
(156, 1, 9, 1, '2026-01-09 20:44:09', 'ice'),
(157, 1, 27, 1, '2026-01-09 20:49:59', 'normal'),
(159, 1, 27, 1, '2026-01-09 20:50:02', 'ice'),
(160, 1, 9, 1, '2026-01-10 17:12:11', 'electric'),
(162, 1, 18, 1, '2026-01-10 17:12:23', 'normal'),
(163, 1, 27, 1, '2026-01-10 17:12:28', 'electric'),
(166, 1, 18, 1, '2026-01-10 18:26:02', 'electric'),
(167, 1, 8, 1, '2026-01-10 21:28:56', 'ice'),
(168, 1, 1, 1, '2026-01-10 21:28:58', 'ice'),
(169, 1, 1, 1, '2026-01-10 21:28:59', 'ice'),
(170, 1, 3, 1, '2026-01-10 21:29:01', 'normal'),
(171, 1, 5, 1, '2026-01-10 21:29:02', 'ice');

-- --------------------------------------------------------

--
-- Struktur dari tabel `maps`
--

CREATE TABLE `maps` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `difficulty_level` int(11) DEFAULT 1,
  `image` varchar(100) DEFAULT 'map_default.jpg'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `maps`
--

INSERT INTO `maps` (`id`, `name`, `difficulty_level`, `image`) VALUES
(1, 'Kolam Ikan', 1, 'map_default.jpg'),
(2, 'Sungai Deras', 2, 'map_default.jpg'),
(3, 'Laut Lepas', 3, 'map_default.jpg');

-- --------------------------------------------------------

--
-- Struktur dari tabel `market_listings`
--

CREATE TABLE `market_listings` (
  `id` int(11) NOT NULL,
  `seller_id` int(11) NOT NULL,
  `inventory_id` int(11) NOT NULL,
  `price` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `market_listings`
--

INSERT INTO `market_listings` (`id`, `seller_id`, `inventory_id`, `price`, `created_at`) VALUES
(1, 1, 41, 30000, '2026-01-07 18:03:46'),
(4, 1, 166, 42000, '2026-01-10 19:30:18'),
(5, 1, 163, 90000, '2026-01-10 19:42:44'),
(6, 1, 162, 10500, '2026-01-10 19:43:16');

-- --------------------------------------------------------

--
-- Struktur dari tabel `shop_items`
--

CREATE TABLE `shop_items` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `type` enum('rod','bait','food') DEFAULT NULL,
  `rarity` enum('common','rare','epic','legendary') DEFAULT 'common',
  `price` int(11) NOT NULL,
  `luck_stat` int(11) NOT NULL,
  `image` varchar(100) DEFAULT 'default_item.png',
  `description` text DEFAULT NULL,
  `energy_restore` int(11) DEFAULT 0,
  `special_effect` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `shop_items`
--

INSERT INTO `shop_items` (`id`, `name`, `type`, `rarity`, `price`, `luck_stat`, `image`, `description`, `energy_restore`, `special_effect`) VALUES
(1, 'Old Bamboo Rod', 'rod', 'common', 0, 0, 'rod_bamboo.png', 'Joran peninggalan kakek. Sering patah.', 0, NULL),
(2, 'Fiberglass Rod', 'rod', 'rare', 10000, 10, 'rod_fiber.png', 'Ringan dan kuat. Standar pemancing pro.', 0, NULL),
(3, 'Deep Sea Hunter', 'rod', 'epic', 25000, 25, 'rod_deepsea.png', 'Dibuat dari alloy titanium. Tahan karat.', 0, NULL),
(4, 'Trisula Poseidon', 'rod', 'legendary', 50000, 50, 'rod_poseidon.png', 'Senjata dewa laut. Ikan takut padanya.', 0, NULL),
(5, 'Remah Roti', 'bait', 'common', 0, 0, 'bait_bread.png', 'Murah meriah. Ikan kecil menyukainya.', 0, NULL),
(6, 'Udang Kupas', 'bait', 'rare', 5000, 5, 'bait_shrimp.png', 'Baunya amis menyengat. Mengundang selera.', 0, NULL),
(7, 'Neon Lure', 'bait', 'epic', 15000, 15, 'bait_neon.png', 'Menyala dalam gelap. Menarik ikan aneh.', 0, NULL),
(8, 'Golden Grub', 'bait', 'legendary', 30000, 30, 'bait_gold.png', 'Cacing emas. Ikan rela mati demi ini.', 0, NULL),
(9, 'Gorengan', 'food', 'common', 500, 0, 'food_gorengan.png', 'Berminyak dan sudah agak keras. Lumayan buat ganjal perut.', 10, NULL),
(10, 'Indomie Telur', 'food', 'rare', 1500, 0, 'food_indomie.png', 'Penyelamat anak kos di akhir bulan. Mengembalikan semangat.', 30, NULL),
(11, 'Nasi Padang', 'food', 'epic', 3000, 0, 'food_padang.png', 'Nasi, ayam bakar, sayur nangka, dan sambal ijo. Gizi seimbang!', 60, NULL),
(12, 'Pizza 3 Meter', 'food', 'legendary', 6000, 0, 'food_pizza.png', 'Pizza raksasa diameter 3 meter dengan topping melimpah. Bikin kenyang seminggu!', 100, NULL),
(13, 'Magic Powder', '', 'rare', 2000, 0, 'magic_powder.png', 'Bubuk ajaib untuk Enchant Joran (+5 Luck). Peluang berhasil 80%.', 0, NULL),
(14, 'Totem Hujan', '', 'epic', 5000, 0, 'totem_rain.png', 'Memanggil hujan selama 10 menit.', 0, NULL),
(15, 'Totem Panas', '', 'epic', 5000, 0, 'totem_sun.png', 'Memanggil gelombang panas selama 10 menit.', 0, NULL),
(16, 'Wooden Chest', '', 'common', 500, 0, 'chest_wood.png', 'Berisi Joran Common (80%), Rare (19%), Epic (1%).', 0, NULL),
(17, 'Sultan Chest', '', 'legendary', 5000, 0, 'chest_gold.png', 'Berisi Joran Rare (50%), Epic (40%), Legendary (10%).', 0, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `treasures`
--

CREATE TABLE `treasures` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `rarity` enum('junk','common','legendary') DEFAULT 'common',
  `sell_price` int(11) NOT NULL,
  `image` varchar(100) DEFAULT 'treasure_chest.png'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `treasures`
--

INSERT INTO `treasures` (`id`, `name`, `rarity`, `sell_price`, `image`) VALUES
(1, 'Old Boot', 'junk', 1, 'tr_boot.png'),
(2, 'Empty Can', 'junk', 1, 'tr_can.png'),
(3, 'Pearl', 'common', 100, 'tr_pearl.png'),
(4, 'Message in Bottle', 'common', 100, 'tr_bottle.png'),
(5, 'Ancient Coin', 'legendary', 5000, 'tr_coin.png'),
(6, 'Pirate Crown', 'legendary', 5000, 'tr_crown.png');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') DEFAULT 'user',
  `gold` int(11) DEFAULT 500,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `avatar` varchar(100) DEFAULT 'default_avatar.png',
  `banner` varchar(100) DEFAULT 'default_banner.jpg',
  `bio` text DEFAULT NULL,
  `equipped_title` varchar(100) DEFAULT 'Pemula',
  `energy` int(11) DEFAULT 100,
  `last_energy_update` timestamp NOT NULL DEFAULT current_timestamp(),
  `selected_title` varchar(50) DEFAULT 'Pemula',
  `level` int(11) DEFAULT 1,
  `xp` int(11) DEFAULT 0,
  `max_energy` int(11) DEFAULT 100,
  `diamonds` int(11) DEFAULT 0,
  `pity_counter_rod` int(11) DEFAULT 0,
  `pity_counter_bait` int(11) DEFAULT 0,
  `equipped_title_2` varchar(50) DEFAULT NULL,
  `equipped_title_3` varchar(50) DEFAULT NULL,
  `showcase_ach_1` int(11) DEFAULT NULL,
  `showcase_ach_2` int(11) DEFAULT NULL,
  `showcase_ach_3` int(11) DEFAULT NULL,
  `showcase_fish_1` int(11) DEFAULT NULL,
  `showcase_fish_2` int(11) DEFAULT NULL,
  `showcase_fish_3` int(11) DEFAULT NULL,
  `showcase_fish_4` int(11) DEFAULT NULL,
  `showcase_fish_5` int(11) DEFAULT NULL,
  `showcase_fish_6` int(11) DEFAULT NULL,
  `last_activity` timestamp NOT NULL DEFAULT current_timestamp(),
  `map_access_level` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `gold`, `created_at`, `avatar`, `banner`, `bio`, `equipped_title`, `energy`, `last_energy_update`, `selected_title`, `level`, `xp`, `max_energy`, `diamonds`, `pity_counter_rod`, `pity_counter_bait`, `equipped_title_2`, `equipped_title_3`, `showcase_ach_1`, `showcase_ach_2`, `showcase_ach_3`, `showcase_fish_1`, `showcase_fish_2`, `showcase_fish_3`, `showcase_fish_4`, `showcase_fish_5`, `showcase_fish_6`, `last_activity`, `map_access_level`) VALUES
(1, 'SuciptoBasoIkan', '$2y$10$crnT9QopjLthPnH58otI6.fK3auXdWuicdo5mZYwk3YTH3kN0wWD6', 'user', 11261, '2026-01-05 14:04:11', '695e9125c42fe.gif', '6962c120503a6_480046832_2169587283456689_6373543226631147197_n.jpg', 'Hawlo', 'Pemula', 631, '2026-01-09 19:39:05', 'Legend', 1, 0, 100, 2, 0, 0, 'Nelayan', 'Hobbyist', 1, 2, 7, 159, 166, 160, 60, 29, 10, '2026-01-10 22:07:25', 3),
(2, 'Sharkchipto', '$2y$10$/etP1OlP3RNkQ08RSIpiCOlrzFZwLDT9jgbGulcgnzecofgDo7yTW', 'user', 4090, '2026-01-07 18:06:53', '695ea0dcbfb41.gif', 'default_banner.jpg', 'Player Baru', 'Pemula', 85, '2026-01-08 13:30:23', 'Nelayan', 1, 0, 100, 0, 0, 0, NULL, NULL, 1, 5, 7, 48, 48, 67, NULL, NULL, NULL, '2026-01-08 14:57:58', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `user_achievements`
--

CREATE TABLE `user_achievements` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `achievement_id` int(11) NOT NULL,
  `unlocked_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `user_achievements`
--

INSERT INTO `user_achievements` (`id`, `user_id`, `achievement_id`, `unlocked_at`) VALUES
(1, 1, 7, '2026-01-07 17:37:23'),
(2, 1, 1, '2026-01-07 17:37:23'),
(3, 1, 2, '2026-01-07 17:37:23'),
(4, 2, 1, '2026-01-07 18:11:22'),
(5, 2, 7, '2026-01-07 19:22:54'),
(6, 2, 5, '2026-01-07 19:23:01'),
(7, 1, 5, '2026-01-08 02:46:56'),
(8, 1, 3, '2026-01-08 06:46:46'),
(9, 1, 6, '2026-01-09 20:42:02');

-- --------------------------------------------------------

--
-- Struktur dari tabel `user_collections`
--

CREATE TABLE `user_collections` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `fish_id` int(11) NOT NULL,
  `total_caught` int(11) DEFAULT 1,
  `has_normal` tinyint(1) DEFAULT 0,
  `has_big` tinyint(1) DEFAULT 0,
  `has_tiny` tinyint(1) DEFAULT 0,
  `has_shiny` tinyint(1) DEFAULT 0,
  `has_glitch` tinyint(1) DEFAULT 0,
  `has_fire` tinyint(1) DEFAULT 0,
  `has_ice` tinyint(1) DEFAULT 0,
  `has_electric` tinyint(1) DEFAULT 0,
  `first_caught_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `has_found_treasure` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `user_collections`
--

INSERT INTO `user_collections` (`id`, `user_id`, `fish_id`, `total_caught`, `has_normal`, `has_big`, `has_tiny`, `has_shiny`, `has_glitch`, `has_fire`, `has_ice`, `has_electric`, `first_caught_at`, `has_found_treasure`) VALUES
(1, 1, 23, 1, 1, 0, 0, 0, 0, 0, 0, 0, '2026-01-08 02:46:56', 0),
(2, 1, 22, 1, 0, 0, 0, 0, 0, 0, 1, 0, '2026-01-08 02:47:03', 0),
(3, 1, 21, 1, 1, 0, 0, 0, 0, 0, 0, 0, '2026-01-08 02:47:06', 0),
(4, 1, 3, 18, 1, 1, 0, 0, 0, 0, 1, 0, '2026-01-08 06:46:21', 0),
(5, 1, 5, 12, 1, 1, 0, 1, 0, 0, 1, 0, '2026-01-08 06:46:25', 0),
(6, 1, 6, 9, 1, 0, 0, 0, 0, 1, 1, 0, '2026-01-08 06:46:27', 0),
(7, 1, 4, 14, 1, 1, 0, 0, 0, 0, 1, 0, '2026-01-08 06:46:28', 0),
(15, 1, 2, 18, 1, 0, 0, 0, 0, 0, 1, 0, '2026-01-08 06:46:43', 0),
(16, 1, 1, 18, 1, 0, 0, 0, 0, 0, 1, 0, '2026-01-08 06:46:45', 0),
(23, 1, 11, 2, 1, 0, 0, 0, 0, 0, 0, 0, '2026-01-08 12:59:12', 0),
(24, 1, 12, 1, 1, 0, 0, 0, 0, 0, 0, 0, '2026-01-08 12:59:15', 0),
(25, 2, 5, 1, 1, 0, 0, 0, 0, 0, 0, 0, '2026-01-08 14:54:49', 0),
(26, 2, 4, 1, 0, 1, 0, 0, 0, 0, 0, 0, '2026-01-08 14:54:52', 0),
(27, 2, 1, 1, 1, 0, 0, 0, 0, 0, 0, 0, '2026-01-08 14:57:58', 0),
(59, 1, 8, 7, 1, 0, 0, 0, 0, 0, 1, 0, '2026-01-09 20:42:02', 0),
(69, 1, 7, 6, 1, 0, 0, 0, 0, 0, 1, 0, '2026-01-09 20:42:14', 0),
(103, 1, 9, 5, 1, 0, 0, 0, 0, 0, 1, 1, '2026-01-09 20:44:00', 0),
(109, 1, 27, 3, 1, 0, 0, 0, 0, 0, 1, 1, '2026-01-09 20:49:59', 0),
(110, 1, 26, 1, 1, 0, 0, 0, 0, 0, 0, 0, '2026-01-09 20:50:01', 0),
(114, 1, 18, 2, 1, 0, 0, 0, 0, 0, 0, 1, '2026-01-10 17:12:23', 0),
(116, 1, 10, 1, 1, 0, 0, 0, 0, 0, 0, 0, '2026-01-10 18:25:58', 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `user_equipment`
--

CREATE TABLE `user_equipment` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `quantity` int(11) DEFAULT 1,
  `is_equipped` tinyint(1) DEFAULT 0,
  `enchantment_id` int(11) DEFAULT NULL,
  `curse_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `user_equipment`
--

INSERT INTO `user_equipment` (`id`, `user_id`, `item_id`, `quantity`, `is_equipped`, `enchantment_id`, `curse_id`) VALUES
(1, 1, 1, 1, 0, NULL, NULL),
(2, 1, 5, 10, 0, NULL, NULL),
(3, 1, 6, 0, 1, NULL, NULL),
(5, 1, 2, 1, 1, 2, NULL),
(6, 2, 1, 1, 1, NULL, NULL),
(7, 2, 6, 3, 1, NULL, NULL),
(8, 2, 5, 10, 0, NULL, NULL),
(9, 1, 9, 1, 0, NULL, NULL),
(10, 1, 10, 1, 0, NULL, NULL),
(11, 1, 4, 1, 0, 1, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `user_treasures`
--

CREATE TABLE `user_treasures` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `treasure_id` int(11) NOT NULL,
  `quantity` int(11) DEFAULT 1,
  `obtained_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `achievements`
--
ALTER TABLE `achievements`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `crates`
--
ALTER TABLE `crates`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `crate_contents`
--
ALTER TABLE `crate_contents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `crate_id` (`crate_id`),
  ADD KEY `item_id` (`item_id`);

--
-- Indeks untuk tabel `curses`
--
ALTER TABLE `curses`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `enchantments`
--
ALTER TABLE `enchantments`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `fishes`
--
ALTER TABLE `fishes`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `inventory`
--
ALTER TABLE `inventory`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `fish_id` (`fish_id`);

--
-- Indeks untuk tabel `maps`
--
ALTER TABLE `maps`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `market_listings`
--
ALTER TABLE `market_listings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `seller_id` (`seller_id`),
  ADD KEY `inventory_id` (`inventory_id`);

--
-- Indeks untuk tabel `shop_items`
--
ALTER TABLE `shop_items`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `treasures`
--
ALTER TABLE `treasures`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indeks untuk tabel `user_achievements`
--
ALTER TABLE `user_achievements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `achievement_id` (`achievement_id`);

--
-- Indeks untuk tabel `user_collections`
--
ALTER TABLE `user_collections`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_collection` (`user_id`,`fish_id`),
  ADD KEY `fish_id` (`fish_id`);

--
-- Indeks untuk tabel `user_equipment`
--
ALTER TABLE `user_equipment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `item_id` (`item_id`),
  ADD KEY `fk_enchant` (`enchantment_id`),
  ADD KEY `fk_curse` (`curse_id`);

--
-- Indeks untuk tabel `user_treasures`
--
ALTER TABLE `user_treasures`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `treasure_id` (`treasure_id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `achievements`
--
ALTER TABLE `achievements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT untuk tabel `crates`
--
ALTER TABLE `crates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `crate_contents`
--
ALTER TABLE `crate_contents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `curses`
--
ALTER TABLE `curses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `enchantments`
--
ALTER TABLE `enchantments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `fishes`
--
ALTER TABLE `fishes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT untuk tabel `inventory`
--
ALTER TABLE `inventory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=172;

--
-- AUTO_INCREMENT untuk tabel `maps`
--
ALTER TABLE `maps`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `market_listings`
--
ALTER TABLE `market_listings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `shop_items`
--
ALTER TABLE `shop_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT untuk tabel `treasures`
--
ALTER TABLE `treasures`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `user_achievements`
--
ALTER TABLE `user_achievements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT untuk tabel `user_collections`
--
ALTER TABLE `user_collections`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=124;

--
-- AUTO_INCREMENT untuk tabel `user_equipment`
--
ALTER TABLE `user_equipment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT untuk tabel `user_treasures`
--
ALTER TABLE `user_treasures`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `crate_contents`
--
ALTER TABLE `crate_contents`
  ADD CONSTRAINT `crate_contents_ibfk_1` FOREIGN KEY (`crate_id`) REFERENCES `crates` (`id`),
  ADD CONSTRAINT `crate_contents_ibfk_2` FOREIGN KEY (`item_id`) REFERENCES `shop_items` (`id`);

--
-- Ketidakleluasaan untuk tabel `inventory`
--
ALTER TABLE `inventory`
  ADD CONSTRAINT `inventory_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `inventory_ibfk_2` FOREIGN KEY (`fish_id`) REFERENCES `fishes` (`id`);

--
-- Ketidakleluasaan untuk tabel `market_listings`
--
ALTER TABLE `market_listings`
  ADD CONSTRAINT `market_listings_ibfk_1` FOREIGN KEY (`seller_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `market_listings_ibfk_2` FOREIGN KEY (`inventory_id`) REFERENCES `inventory` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `user_achievements`
--
ALTER TABLE `user_achievements`
  ADD CONSTRAINT `user_achievements_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_achievements_ibfk_2` FOREIGN KEY (`achievement_id`) REFERENCES `achievements` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `user_collections`
--
ALTER TABLE `user_collections`
  ADD CONSTRAINT `user_collections_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_collections_ibfk_2` FOREIGN KEY (`fish_id`) REFERENCES `fishes` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `user_equipment`
--
ALTER TABLE `user_equipment`
  ADD CONSTRAINT `fk_curse` FOREIGN KEY (`curse_id`) REFERENCES `curses` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_enchant` FOREIGN KEY (`enchantment_id`) REFERENCES `enchantments` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `user_equipment_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `user_equipment_ibfk_2` FOREIGN KEY (`item_id`) REFERENCES `shop_items` (`id`);

--
-- Ketidakleluasaan untuk tabel `user_treasures`
--
ALTER TABLE `user_treasures`
  ADD CONSTRAINT `user_treasures_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `user_treasures_ibfk_2` FOREIGN KEY (`treasure_id`) REFERENCES `treasures` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
