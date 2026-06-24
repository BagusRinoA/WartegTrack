<?php
/**
 * File: index.php
 * Deskripsi: Front controller / router utama aplikasi WartegTrack.
 */

// Mulai session
if (session_status() !== PHP_SESSION_ACTIVE) session_start();

// Autoload controllers
require_once 'controllers/HomeController.php';
require_once 'controllers/AuthController.php';
require_once 'controllers/DashboardController.php';
require_once 'controllers/KelolaController.php';
require_once 'controllers/OwnerController.php';
require_once 'controllers/OrderController.php';

// Ambil parameter page
$page = $_GET['page'] ?? 'home';

// Instansiasi controllers
$homeController = new HomeController();
$authController = new AuthController();
$dashboardController = new DashboardController();
$kelolaController = new KelolaController();
$ownerController = new OwnerController();
$orderController = new OrderController();

// Routing sederhana
switch ($page) {
    // Auth Routes
    case 'login':
        $authController->login();
        break;
    case 'logout':
        $authController->logout();
        break;
    case 'signup':
        $authController->signup();
        break;

    // Public Routes
    case 'home':
        $homeController->index();
        break;
    case 'menu':
        $homeController->menu();
        break;
    case 'order':
        $orderController->form();
        break;

    // Dashboard & Admin Routes
    case 'admin-dashboard':
        $dashboardController->admin();
        break;
    case 'user-dashboard':
        $dashboardController->user();
        break;
    case 'pesanan-saya':
        $dashboardController->pesananSaya();
        break;
    case 'user-menu':
        $dashboardController->userMenu();
        break;
    case 'user-hubungi':
        $dashboardController->userHubungi();
        break;
    case 'user-transaksi':
        $dashboardController->userTransaksi();
        break;
    case 'user-pesan':
        $dashboardController->userPesan();
        break;

    // Kelola Routes (Admin/Kelola)
    case 'kelola-menu':
        $kelolaController->menu();
        break;
    case 'kelola-pesanan':
        $kelolaController->pesanan();
        break;
    case 'kelola-user':
        $kelolaController->user();
        break;
    case 'update-order':
        $kelolaController->updateOrder();
        break;
    case 'delete-order':
        $kelolaController->deleteOrder();
        break;
    case 'edit-user':
        $kelolaController->editUser();
        break;
    case 'delete-user':
        $kelolaController->deleteUser();
        break;
    case 'kelola-supplier':
        $kelolaController->supplier();
        break;
    case 'kelola-bahan-baku':
        $kelolaController->bahanBaku();
        break;
    case 'kelola-pembelian':
        $kelolaController->pembelian();
        break;

    // Owner Routes
    case 'owner-pembelian':
        $ownerController->pembelian();
        break;
    case 'owner-supplier':
        $ownerController->supplier();
        break;
    case 'owner-bahan-baku':
        $ownerController->bahanBaku();
        break;
    case 'owner-laporan-keuangan':
        $ownerController->laporanKeuangan();
        break;
    case 'delete-pembelian':
        $ownerController->deletePembelian();
        break;
    case 'edit-pembelian':
        $ownerController->editPembelian();
        break;
    case 'delete-supplier':
        $ownerController->deleteSupplier();
        break;
    case 'edit-supplier':
        $ownerController->editSupplier();
        break;
    case 'delete-bahan-baku':
        $ownerController->deleteBahanBaku();
        break;
    case 'edit-bahan-baku':
        $ownerController->editBahanBaku();
        break;

    default:
        // Jika route tidak ditemukan
        echo "404 - Halaman tidak ditemukan.";
        break;
}
