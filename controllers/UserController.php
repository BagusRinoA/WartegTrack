<?php
/**
 * File: UserController.php
 * Deskripsi: Controller untuk fitur-fitur USER (pelanggan)
 */

class UserController {
    
    private function getPDO() {
        return new PDO('mysql:host=localhost;dbname=warteg_track','root','');
    }
    
    /**
     * Halaman menu untuk user
     */
    public function menu() {
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
        if (empty($_SESSION['user'])) {
            header('Location: index.php?page=login');
            exit;
        }
        
        $pdo = $this->getPDO();
        
        // Ambil semua menu
        $stmt = $pdo->query("SELECT * FROM menu ORDER BY id ASC");
        $menus = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $content = "dashboard/user-menu.php";
        include "views/layout.php";
    }
    
    /**
     * Halaman pemesanan untuk user
     */
    public function pesan() {
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
        if (empty($_SESSION['user'])) {
            header('Location: index.php?page=login');
            exit;
        }
        
        $pdo = $this->getPDO();
        
        // Ambil semua menu
        $stmt = $pdo->query("SELECT * FROM menu ORDER BY id ASC");
        $menus = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Handle add to cart
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
            if (!isset($_SESSION['cart'])) {
                $_SESSION['cart'] = [];
            }
            
            $menu_id = (int)$_POST['menu_id'];
            $jumlah = (int)$_POST['jumlah'];
            
            if (isset($_SESSION['cart'][$menu_id])) {
                $_SESSION['cart'][$menu_id]['jumlah'] += $jumlah;
            } else {
                $menu = $pdo->prepare("SELECT * FROM menu WHERE id = ?");
                $menu->execute([$menu_id]);
                $menuData = $menu->fetch(PDO::FETCH_ASSOC);
                
                if ($menuData) {
                    $_SESSION['cart'][$menu_id] = [
                        'id' => $menu_id,
                        'nama_produk' => $menuData['nama_produk'], // Corrected from 'nama'
                        'harga' => $menuData['harga'],
                        'jumlah' => $jumlah
                    ];
                }
            }
            
            header("Location: index.php?page=user-pesan");
            exit;
        }
        
        $content = "dashboard/user-pesan.php";
        include "views/layout.php";
    }
    
    /**
     * Halaman transaksi untuk user
     */
    public function transaksi() {
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
        if (empty($_SESSION['user'])) {
            header('Location: index.php?page=login');
            exit;
        }
        
        $pdo = $this->getPDO();
        $user_id = $_SESSION['user_id'] ?? null;
        
        // Ensure orders table exists
        try {
            $checkTable = $pdo->query("SHOW TABLES LIKE 'orders'");
            if ($checkTable->rowCount() == 0) {
                // Create table orders if not exists
                $pdo->exec("
                    CREATE TABLE IF NOT EXISTS orders (
                        id INT AUTO_INCREMENT PRIMARY KEY,
                        user_id INT NULL,
                        nama VARCHAR(100) NOT NULL,
                        menu VARCHAR(100) NOT NULL,
                        jumlah INT NOT NULL,
                        catatan TEXT,
                        total_harga DECIMAL(10,2) DEFAULT 0,
                        status VARCHAR(20) DEFAULT 'pending',
                        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
                    )
                ");
            }
        } catch (PDOException $e) {
            // Ignore if error checking/creating table
        }
        
        // Handle checkout
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['checkout'])) {
            if (!empty($_SESSION['cart'])) {
                $total = 0;
                foreach ($_SESSION['cart'] as $item) {
                    $total += $item['harga'] * $item['jumlah'];
                }
                
                // Simpan transaksi
                try {
                    $stmt = $pdo->prepare("
                        INSERT INTO orders (user_id, nama, menu, jumlah, total_harga, status, created_at) 
                        VALUES (?, ?, ?, ?, ?, 'pending', NOW())
                    ");
                    
                    foreach ($_SESSION['cart'] as $item) {
                        $menu_detail = ($item['nama_produk'] ?? $item['nama'] ?? 'Produk') . ' x' . $item['jumlah'];
                        $stmt->execute([
                            $user_id,
                            $_SESSION['user'],
                            $menu_detail,
                            $item['jumlah'],
                            $item['harga'] * $item['jumlah']
                        ]);
                    }
                    
                    $_SESSION['cart'] = [];
                    $_SESSION['success'] = "Pesanan berhasil dibuat!";
                } catch (PDOException $e) {
                    $_SESSION['error'] = "Terjadi kesalahan: " . $e->getMessage();
                }
            }
        }
        
        // Ambil riwayat transaksi
        $transactions = [];
        if ($user_id) {
            try {
                $stmt = $pdo->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC");
                $stmt->execute([$user_id]);
                $transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                $transactions = [];
            }
        }
        
        $content = "dashboard/user-transaksi.php";
        include "views/layout.php";
    }
    
    /**
     * Halaman hubungi
     */
    public function hubungi() {
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
        if (empty($_SESSION['user'])) {
            header('Location: index.php?page=login');
            exit;
        }
        
        $content = "dashboard/user-hubungi.php";
        include "views/layout.php";
    }
}


