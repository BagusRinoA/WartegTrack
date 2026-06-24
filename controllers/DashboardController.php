<?php
/**
 * File: DashboardController.php
 * Deskripsi: Controller untuk menangani dashboard admin dan user
 * 
 * Controller ini mengelola tampilan dashboard dengan data statistik
 * dari database seperti total menu, pesanan, dan user.
 */

class DashboardController 
{
    /**
     * Method untuk mendapatkan koneksi database PDO
     * @return PDO Object koneksi database
     */
    private function getPDO() {
        return new PDO('mysql:host=localhost;dbname=warteg_track','root','');
    }

    /**
     * Method untuk menampilkan dashboard admin
     * Menampilkan statistik total menu, pesanan, user, dan daftar pesanan terbaru
     */
    public function admin() {
        // Cek session dan role
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
        if (empty($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
            header('Location: index.php?page=login');
            exit;
        }

        $pdo = $this->getPDO();
        
        // Ambil statistik dari database
        $stats = [];
        
        // Total Menu
        $stmt = $pdo->query("SELECT COUNT(*) as total FROM menu");
        $stats['total_menu'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;
        
        // Total Pesanan (cek apakah tabel orders ada)
        try {
            $stmt = $pdo->query("SELECT COUNT(*) as total FROM orders");
            $stats['total_orders'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;
        } catch (PDOException $e) {
            $stats['total_orders'] = 0;
        }
        
        // Total User
        $stmt = $pdo->query("SELECT COUNT(*) as total FROM users");
        $stats['total_users'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;
        
        // Total Pendapatan (jika ada kolom total_harga di orders)
        try {
            $stmt = $pdo->query("SELECT SUM(total_harga) as total FROM orders WHERE status != 'cancelled'");
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $stats['total_pendapatan'] = $result['total'] ?? 0;
        } catch (PDOException $e) {
            $stats['total_pendapatan'] = 0;
        }
        
        // Omzet (dari orders)
        $stats['omzet'] = $stats['total_pendapatan'];
        
        // Stok Bahan Baku (total stok)
        try {
            $stmt = $pdo->query("SELECT SUM(stok_saat_ini) as total FROM bahan_baku");
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $stats['stok_bahan_baku'] = $result['total'] ?? 0;
        } catch (PDOException $e) {
            $stats['stok_bahan_baku'] = 0;
        }
        
        // Ambil pesanan terbaru (jika tabel orders ada)
        $recent_orders = [];
        try {
            $stmt = $pdo->query("
                SELECT o.*, u.username 
                FROM orders o 
                LEFT JOIN users u ON o.user_id = u.id 
                ORDER BY o.created_at DESC 
                LIMIT 5
            ");
            $recent_orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Jika tabel tidak ada, gunakan data dummy
            $recent_orders = [];
        }
        
        // Pass data ke view
        $content = "dashboard/admin-dashboard.php";
        include "views/layout.php";
    }

    /**
     * Method untuk menampilkan dashboard user
     * Menampilkan menu yang tersedia dan pesanan user
     */
    public function user() {
        // Cek session
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
        if (empty($_SESSION['user'])) {
            header('Location: index.php?page=login');
            exit;
        }

        $pdo = $this->getPDO();
        $user_id = $_SESSION['user_id'] ?? null;
        
        // Ambil menu yang tersedia
        $stmt = $pdo->query("SELECT * FROM menu ORDER BY id ASC LIMIT 12");
        $menus = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Ambil pesanan user (jika tabel orders ada)
        $user_orders = [];
        if ($user_id) {
            try {
                $stmt = $pdo->prepare("
                    SELECT * FROM orders 
                    WHERE user_id = ? 
                    ORDER BY created_at DESC 
                    LIMIT 10
                ");
                $stmt->execute([$user_id]);
                $user_orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                $user_orders = [];
            }
        }
        
        // Pass data ke view
        $content = "dashboard/user-dashboard.php";
        include "views/layout.php";
    }
    
    /**
     * Method untuk menampilkan pesanan user
     */
    public function pesananSaya() {
        // Cek session
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
        if (empty($_SESSION['user'])) {
            header('Location: index.php?page=login');
            exit;
        }

        $pdo = $this->getPDO();
        $user_id = $_SESSION['user_id'] ?? null;
        
        // Ambil pesanan user
        $user_orders = [];
        if ($user_id) {
            try {
                $stmt = $pdo->prepare("
                    SELECT * FROM orders 
                    WHERE user_id = ? 
                    ORDER BY created_at DESC
                ");
                $stmt->execute([$user_id]);
                $user_orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                $user_orders = [];
            }
        }
        
        // Pass data ke view
        $content = "dashboard/pesanan-saya.php";
        include "views/layout.php";
    }

    /**
     * Method untuk menampilkan halaman menu user
     */
    public function userMenu() {
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
        if (empty($_SESSION['user'])) {
            header('Location: index.php?page=login');
            exit;
        }

        $pdo = $this->getPDO();
        $stmt = $pdo->query("SELECT * FROM menu ORDER BY id ASC");
        $menus = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $content = "dashboard/user-menu.php";
        include "views/layout.php";
    }

    /**
     * Method untuk menampilkan halaman hubungi user
     */
    public function userHubungi() {
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
        if (empty($_SESSION['user'])) {
            header('Location: index.php?page=login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nama = $_POST['nama'] ?? '';
            $email = $_POST['email'] ?? '';
            $pesan = $_POST['pesan'] ?? '';
            
            $_SESSION['success'] = "Pesan Anda telah berhasil dikirim. Kami akan segera menghubungi Anda.";
            header('Location: index.php?page=user-hubungi');
            exit;
        }

        $content = "dashboard/user-hubungi.php";
        include "views/layout.php";
    }

    /**
     * Method untuk menampilkan halaman transaksi user
     */
    public function userTransaksi() {
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
        if (empty($_SESSION['user'])) {
            header('Location: index.php?page=login');
            exit;
        }

        $pdo = $this->getPDO();
        $user_id = $_SESSION['user_id'] ?? null;
        
        $transactions = [];
        if ($user_id) {
            try {
                $stmt = $pdo->prepare("
                    SELECT o.*, m.nama as menu 
                    FROM orders o
                    LEFT JOIN menu m ON o.menu_id = m.id
                    WHERE o.user_id = ? 
                    ORDER BY o.created_at DESC
                ");
                $stmt->execute([$user_id]);
                $transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                // Ignore if tables don't match exactly
                $transactions = [];
            }
        }

        $content = "dashboard/user-transaksi.php";
        include "views/layout.php";
    }

    /**
     * Method untuk menangani pemesanan
     */
    public function userPesan() {
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
        if (empty($_SESSION['user'])) {
            header('Location: index.php?page=login');
            exit;
        }

        $content = "dashboard/user-pesan.php";
        include "views/layout.php";
    }
}

