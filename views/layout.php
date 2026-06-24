<?php 
// Jika login/signup → TAMPILKAN tanpa layout (sudah ditangani di AuthController)
// Tapi untuk jaga-jaga, tetap cek di sini
if (isset($content)) {
    $contentFile = basename($content);
    if (in_array($contentFile, ['login.php', 'signup.php'])) {
        include __DIR__ . '/' . $contentFile;
        return;
    }
}

if (session_status() !== PHP_SESSION_ACTIVE) session_start();

// Ambil parameter page
$page = $_GET['page'] ?? 'home';

// Halaman yang navbar-nya disembunyikan (admin area dan auth pages)
$hide_navbar = in_array($page, [
    'admin-dashboard',
    'user-dashboard',
    'kelola-menu',
    'kelola-pesanan',
    'kelola-user',
    'owner-pembelian',
    'owner-supplier',
    'owner-bahan-baku',
    'owner-laporan-keuangan',
    'user-menu',
    'user-pesan',
    'user-transaksi',
    'user-hubungi',
    'pesanan-saya',
    'login',
    'signup'
]);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>WartegTrack</title>

    <link rel="stylesheet" href="css/style.css">
</head>

<body>
<div class="wtg-wrapper">

    <!-- ========== NAVBAR (hilang jika admin) ========== -->
    <?php if (!$hide_navbar): ?>
    <header class="navbar">
        <img src="logo/logo-WT-no-bg.png" class="navbar-logo" alt="WartegTrack">

        <div class="nav-left">
            <a href="index.php?page=home" class="nav-link<?= ($page=='home'?' active':'') ?>">About Us</a>
            <span class="divider">|</span>

            <a href="index.php?page=menu" class="nav-link<?= ($page=='menu'?' active':'') ?>">Menu</a>
            <span class="divider">|</span>

            <a href="index.php?page=order" class="nav-link<?= ($page=='order'?' active':'') ?>">Pesanan Katering</a>
        </div>

        <div class="nav-right">
        <?php if (empty($_SESSION['user'])): ?>
            <a href="index.php?page=login" class="nav-login"><span class="icon">👤</span> Login</a>
        <?php else: ?>
            <div class="dropdown">
                <button class="dropbtn">
                    <span class="icon">👤</span> <?= htmlspecialchars($_SESSION['user']) ?> ▼
                </button>

                <div class="dropdown-content">
                    <?php if ($_SESSION['role'] === 'admin'): ?>
                        <a href="index.php?page=admin-dashboard">Dashboard</a>
                    <?php else: ?>
                        <a href="index.php?page=user-dashboard">Dashboard</a>
                    <?php endif; ?>

                    <a href="index.php?page=logout">Sign Out</a>
                </div>
            </div>
        <?php endif; ?>
        </div>
    </header>
    <?php endif; ?>
    <!-- ========== END NAVBAR ========== -->


    <!-- ========== KONTEN HALAMAN ========== -->
    <main class="main-content">
        <?php
        // pastikan hanya nama file
        if (isset($content)) {

            $content = basename($content);

            // cek di folder views utama
            $filePath = __DIR__ . '/' . $content;

            // cek di folder views/dashboard
            if (!file_exists($filePath)) {
                $filePath = __DIR__ . '/dashboard/' . $content;
            }

            // tampilkan file view
            if (file_exists($filePath)) {
                include $filePath;
            } else {
                echo "<div style='color:red; padding:20px;'>
                        Error: view tidak ditemukan → <b>$filePath</b>
                      </div>";
            }
        } else {
            echo "<div style='padding:20px; color:red;'>Error: variabel \$content tidak dikirim dari controller.</div>";
        }
        ?>
    </main>
    <!-- ========== END CONTENT ========== -->

</div>
</body>
</html>
