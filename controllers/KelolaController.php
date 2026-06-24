<?php
/**
 * File: KelolaController.php
 * Deskripsi: Controller untuk halaman kelola admin (Menu, Pesanan, User)
 */

class KelolaController {
    
    private function getPDO() {
        return new PDO('mysql:host=localhost;dbname=warteg_track','root','');
    }
    
    /**
     * Halaman kelola menu
     */
    public function menu() {
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
        if (empty($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
            header('Location: index.php?page=login');
            exit;
        }
        
        $pdo = $this->getPDO();
        $success = null;
        $error = null;
        
        // Handle POST request untuk tambah, edit, atau delete menu
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Tambah menu baru
            if (isset($_POST['action']) && $_POST['action'] === 'add') {
                $nama = htmlspecialchars($_POST['nama_produk'] ?? '');
                $harga = (int)($_POST['harga'] ?? 0);
                $stok = (int)($_POST['stok'] ?? 0);
                $kategori = htmlspecialchars($_POST['kategori'] ?? '');
                $deskripsi = htmlspecialchars($_POST['deskripsi'] ?? '');
                
                if (empty($nama) || $harga <= 0 || $stok < 0 || empty($kategori)) {
                    $_SESSION['error'] = "Mohon lengkapi semua field yang wajib diisi.";
                } else {
                    try {
                        $stmt = $pdo->prepare("
                            INSERT INTO menu (nama_produk, harga, stok, kategori, deskripsi) 
                            VALUES (?, ?, ?, ?, ?)
                        ");
                        $stmt->execute([$nama, $harga, $stok, $kategori, $deskripsi]);
                        $_SESSION['success'] = "Menu berhasil ditambahkan!";
                    } catch (PDOException $e) {
                        $_SESSION['error'] = "Gagal menambahkan menu: " . $e->getMessage();
                    }
                }
                header('Location: index.php?page=kelola-menu');
                exit;
            }
            
            // Edit menu
            if (isset($_POST['action']) && $_POST['action'] === 'edit') {
                $id = (int)($_POST['id'] ?? 0);
                $nama = htmlspecialchars($_POST['nama_produk'] ?? '');
                $harga = (int)($_POST['harga'] ?? 0);
                $stok = (int)($_POST['stok'] ?? 0);
                $kategori = htmlspecialchars($_POST['kategori'] ?? '');
                $deskripsi = htmlspecialchars($_POST['deskripsi'] ?? '');
                
                if ($id <= 0 || empty($nama) || $harga <= 0 || $stok < 0 || empty($kategori)) {
                    $_SESSION['error'] = "Mohon lengkapi semua field yang wajib diisi.";
                } else {
                    try {
                        $stmt = $pdo->prepare("
                            UPDATE menu 
                            SET nama_produk = ?, harga = ?, stok = ?, kategori = ?, deskripsi = ? 
                            WHERE id = ?
                        ");
                        $stmt->execute([$nama, $harga, $stok, $kategori, $deskripsi, $id]);
                        $_SESSION['success'] = "Menu berhasil diupdate!";
                    } catch (PDOException $e) {
                        $_SESSION['error'] = "Gagal mengupdate menu: " . $e->getMessage();
                    }
                }
                header('Location: index.php?page=kelola-menu');
                exit;
            }
            
            // Delete menu
            if (isset($_POST['action']) && $_POST['action'] === 'delete') {
                $id = (int)($_POST['id'] ?? 0);
                
                if ($id <= 0) {
                    $_SESSION['error'] = "ID menu tidak valid.";
                } else {
                    try {
                        $stmt = $pdo->prepare("DELETE FROM menu WHERE id = ?");
                        $stmt->execute([$id]);
                        $_SESSION['success'] = "Menu berhasil dihapus!";
                    } catch (PDOException $e) {
                        $_SESSION['error'] = "Gagal menghapus menu: " . $e->getMessage();
                    }
                }
                header('Location: index.php?page=kelola-menu');
                exit;
            }
        }
        
        // Ambil pesan dari session jika ada
        if (isset($_SESSION['success'])) {
            $success = $_SESSION['success'];
            unset($_SESSION['success']);
        }
        if (isset($_SESSION['error'])) {
            $error = $_SESSION['error'];
            unset($_SESSION['error']);
        }
        
        // Ambil semua menu
        try {
            $stmt = $pdo->query("SELECT * FROM menu ORDER BY id DESC");
            $menus = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $menus = [];
        }
        
        $content = "dashboard/kelola-menu.php";
        include "views/layout.php";
    }
    
    /**
     * Halaman kelola pesanan
     */
    public function pesanan() {
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
        if (empty($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
            header('Location: index.php?page=login');
            exit;
        }
        
        $pdo = $this->getPDO();
        
        // Ambil semua pesanan
        try {
            $stmt = $pdo->query("
                SELECT o.*, u.username 
                FROM orders o 
                LEFT JOIN users u ON o.user_id = u.id 
                ORDER BY o.created_at DESC
            ");
            $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $orders = [];
        }
        
        $content = "dashboard/kelola-pesanan.php";
        include "views/layout.php";
    }
    
    /**
     * Halaman kelola user
     */
    public function user() {
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
        if (empty($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
            header('Location: index.php?page=login');
            exit;
        }
        
        $pdo = $this->getPDO();
        
        // Ambil semua user
        $stmt = $pdo->query("SELECT * FROM users ORDER BY created_at DESC");
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $content = "dashboard/kelola-user.php";
        include "views/layout.php";
    }

    /**
     * Update order (via POST)
     */
    public function updateOrder() {
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
        if (empty($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
            header('Location: index.php?page=login');
            exit;
        }

        $pdo = $this->getPDO();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)($_POST['id'] ?? 0);
            $status = $_POST['status'] ?? '';
            if ($id > 0 && in_array($status, ['pending','complete','cancelled'], true)) {
                try {
                    $stmt = $pdo->prepare("UPDATE orders SET status = ? WHERE id = ?");
                    $stmt->execute([$status, $id]);
                    $_SESSION['success'] = "Status pesanan berhasil diperbarui.";
                } catch (PDOException $e) {
                    $_SESSION['error'] = "Gagal memperbarui pesanan: " . $e->getMessage();
                }
            } else {
                $_SESSION['error'] = "Data pesanan tidak valid.";
            }
        }
        header('Location: index.php?page=kelola-pesanan');
        exit;
    }

    /**
     * Delete order (via GET or POST)
     */
    public function deleteOrder() {
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
        if (empty($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
            header('Location: index.php?page=login');
            exit;
        }

        $pdo = $this->getPDO();
        $id = (int)($_GET['id'] ?? $_POST['id'] ?? 0);
        if ($id > 0) {
            try {
                $stmt = $pdo->prepare("DELETE FROM orders WHERE id = ?");
                $stmt->execute([$id]);
                $_SESSION['success'] = "Pesanan berhasil dihapus.";
            } catch (PDOException $e) {
                $_SESSION['error'] = "Gagal menghapus pesanan: " . $e->getMessage();
            }
        } else {
            $_SESSION['error'] = "ID pesanan tidak valid.";
        }
        header('Location: index.php?page=kelola-pesanan');
        exit;
    }

    /**
     * Edit user (via POST)
     */
    public function editUser() {
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
        if (empty($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
            header('Location: index.php?page=login');
            exit;
        }

        $pdo = $this->getPDO();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)($_POST['id'] ?? 0);
            $username = htmlspecialchars($_POST['username'] ?? '');
            $email = htmlspecialchars($_POST['email'] ?? '');
            $role = htmlspecialchars($_POST['role'] ?? 'user');

            if ($id > 0 && !empty($username) && !empty($email)) {
                try {
                    $stmt = $pdo->prepare("UPDATE users SET username = ?, email = ?, role = ? WHERE id = ?");
                    $stmt->execute([$username, $email, $role, $id]);
                    $_SESSION['success'] = "Data user berhasil diperbarui.";
                } catch (PDOException $e) {
                    $_SESSION['error'] = "Gagal memperbarui user: " . $e->getMessage();
                }
            } else {
                $_SESSION['error'] = "Data user tidak valid.";
            }
        }
        header('Location: index.php?page=kelola-user');
        exit;
    }

    /**
     * Delete user (via GET or POST)
     */
    public function deleteUser() {
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
        if (empty($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
            header('Location: index.php?page=login');
            exit;
        }

        $pdo = $this->getPDO();
        $id = (int)($_GET['id'] ?? $_POST['id'] ?? 0);
        if ($id > 0) {
            try {
                $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
                $stmt->execute([$id]);
                $_SESSION['success'] = "User berhasil dihapus.";
            } catch (PDOException $e) {
                $_SESSION['error'] = "Gagal menghapus user: " . $e->getMessage();
            }
        } else {
            $_SESSION['error'] = "ID user tidak valid.";
        }
        header('Location: index.php?page=kelola-user');
        exit;
    }

    /**
     * Halaman kelola supplier (Admin)
     */
    public function supplier() {
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
        if (empty($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
            header('Location: index.php?page=login');
            exit;
        }

        $pdo = $this->getPDO();
        $success = null;
        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? '';
            // Tambah supplier
            if ($action === 'add') {
                $nama = htmlspecialchars($_POST['nama'] ?? '');
                $kontak = htmlspecialchars($_POST['kontak'] ?? '');
                $alamat = htmlspecialchars($_POST['alamat'] ?? '');
                $catatan = htmlspecialchars($_POST['catatan'] ?? '');

                if (empty($nama)) {
                    $_SESSION['error'] = "Nama supplier wajib diisi.";
                } else {
                    try {
                        $stmt = $pdo->prepare("INSERT INTO supplier (nama, kontak, alamat, catatan) VALUES (?, ?, ?, ?)");
                        $stmt->execute([$nama, $kontak, $alamat, $catatan]);
                        $_SESSION['success'] = "Supplier berhasil ditambahkan.";
                    } catch (PDOException $e) {
                        $_SESSION['error'] = "Gagal menambahkan supplier: " . $e->getMessage();
                    }
                }
                header('Location: index.php?page=kelola-supplier');
                exit;
            }

            // Edit supplier
            if ($action === 'edit') {
                $id = (int)($_POST['id'] ?? 0);
                $nama = htmlspecialchars($_POST['nama'] ?? '');
                $kontak = htmlspecialchars($_POST['kontak'] ?? '');
                $alamat = htmlspecialchars($_POST['alamat'] ?? '');
                $catatan = htmlspecialchars($_POST['catatan'] ?? '');

                if ($id <= 0 || empty($nama)) {
                    $_SESSION['error'] = "Data supplier tidak valid.";
                } else {
                    try {
                        $stmt = $pdo->prepare("UPDATE supplier SET nama = ?, kontak = ?, alamat = ?, catatan = ? WHERE id = ?");
                        $stmt->execute([$nama, $kontak, $alamat, $catatan, $id]);
                        $_SESSION['success'] = "Supplier berhasil diperbarui.";
                    } catch (PDOException $e) {
                        $_SESSION['error'] = "Gagal memperbarui supplier: " . $e->getMessage();
                    }
                }
                header('Location: index.php?page=kelola-supplier');
                exit;
            }

            // Delete supplier
            if ($action === 'delete') {
                $id = (int)($_POST['id'] ?? 0);
                if ($id <= 0) {
                    $_SESSION['error'] = "ID supplier tidak valid.";
                } else {
                    try {
                        $stmt = $pdo->prepare("DELETE FROM supplier WHERE id = ?");
                        $stmt->execute([$id]);
                        $_SESSION['success'] = "Supplier berhasil dihapus.";
                    } catch (PDOException $e) {
                        $_SESSION['error'] = "Gagal menghapus supplier: " . $e->getMessage();
                    }
                }
                header('Location: index.php?page=kelola-supplier');
                exit;
            }
        }

        if (isset($_SESSION['success'])) { $success = $_SESSION['success']; unset($_SESSION['success']); }
        if (isset($_SESSION['error'])) { $error = $_SESSION['error']; unset($_SESSION['error']); }

        try {
            $stmt = $pdo->query("SELECT * FROM supplier ORDER BY id DESC");
            $suppliers = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $suppliers = [];
        }

        $content = "dashboard/kelola-supplier.php";
        include "views/layout.php";
    }

    /**
     * Halaman kelola bahan baku (Admin)
     */
    public function bahanBaku() {
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
        if (empty($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
            header('Location: index.php?page=login');
            exit;
        }

        $pdo = $this->getPDO();
        $success = null;
        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? '';

            // Tambah bahan
            if ($action === 'add') {
                $nama = htmlspecialchars($_POST['nama'] ?? '');
                $unit = htmlspecialchars($_POST['unit_satuan'] ?? 'Kg');
                $stok_awal = floatval($_POST['stok_awal'] ?? 0);
                $stok_min = floatval($_POST['stok_minimum'] ?? 0);

                if (empty($nama)) {
                    $_SESSION['error'] = "Nama bahan wajib diisi.";
                } else {
                    try {
                        $stmt = $pdo->prepare("INSERT INTO bahan_baku (nama, unit_satuan, stok_saat_ini, stok_minimum) VALUES (?, ?, ?, ?)");
                        $stmt->execute([$nama, $unit, $stok_awal, $stok_min]);
                        $_SESSION['success'] = "Bahan baku berhasil ditambahkan.";
                    } catch (PDOException $e) {
                        $_SESSION['error'] = "Gagal menambahkan bahan baku: " . $e->getMessage();
                    }
                }
                header('Location: index.php?page=kelola-bahan-baku');
                exit;
            }

            // Edit bahan
            if ($action === 'edit') {
                $id = (int)($_POST['id'] ?? 0);
                $nama = htmlspecialchars($_POST['nama'] ?? '');
                $unit = htmlspecialchars($_POST['unit_satuan'] ?? 'Kg');
                $stok = floatval($_POST['stok_saat_ini'] ?? 0);
                $stok_min = floatval($_POST['stok_minimum'] ?? 0);

                if ($id <= 0 || empty($nama)) {
                    $_SESSION['error'] = "Data bahan tidak valid.";
                } else {
                    try {
                        $stmt = $pdo->prepare("UPDATE bahan_baku SET nama = ?, unit_satuan = ?, stok_saat_ini = ?, stok_minimum = ? WHERE id = ?");
                        $stmt->execute([$nama, $unit, $stok, $stok_min, $id]);
                        $_SESSION['success'] = "Bahan baku berhasil diperbarui.";
                    } catch (PDOException $e) {
                        $_SESSION['error'] = "Gagal memperbarui bahan: " . $e->getMessage();
                    }
                }
                header('Location: index.php?page=kelola-bahan-baku');
                exit;
            }

            // Delete bahan
            if ($action === 'delete') {
                $id = (int)($_POST['id'] ?? 0);
                if ($id <= 0) {
                    $_SESSION['error'] = "ID bahan tidak valid.";
                } else {
                    try {
                        $stmt = $pdo->prepare("DELETE FROM bahan_baku WHERE id = ?");
                        $stmt->execute([$id]);
                        $_SESSION['success'] = "Bahan baku berhasil dihapus.";
                    } catch (PDOException $e) {
                        $_SESSION['error'] = "Gagal menghapus bahan: " . $e->getMessage();
                    }
                }
                header('Location: index.php?page=kelola-bahan-baku');
                exit;
            }
        }

        if (isset($_SESSION['success'])) { $success = $_SESSION['success']; unset($_SESSION['success']); }
        if (isset($_SESSION['error'])) { $error = $_SESSION['error']; unset($_SESSION['error']); }

        try {
            $stmt = $pdo->query("SELECT * FROM bahan_baku ORDER BY id DESC");
            $bahan_bakus = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $bahan_bakus = [];
        }

        $content = "dashboard/kelola-bahan-baku.php";
        include "views/layout.php";
    }

    /**
     * Halaman kelola pembelian (Admin)
     */
    public function pembelian() {
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
        if (empty($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
            header('Location: index.php?page=login');
            exit;
        }

        $pdo = $this->getPDO();
        $success = null;
        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? '';

            if ($action === 'add') {
                $tanggal = $_POST['tanggal'] ?? date('Y-m-d');
                $supplier_id = (int)($_POST['supplier_id'] ?? 0);
                $bahan_id = (int)($_POST['bahan_baku_id'] ?? 0);
                $kuantitas = floatval($_POST['kuantitas'] ?? 0);
                $harga_satuan = floatval($_POST['harga_satuan'] ?? 0);
                $total = floatval($_POST['total_biaya'] ?? ($kuantitas * $harga_satuan));
                $status = $_POST['status_pembayaran'] ?? 'Belum';

                if ($supplier_id <=0 || $bahan_id <=0 || $kuantitas <=0) {
                    $_SESSION['error'] = "Data pembelian tidak valid.";
                } else {
                    try {
                        $stmt = $pdo->prepare("INSERT INTO pembelian (tanggal, supplier_id, bahan_baku_id, kuantitas, harga_satuan, total_biaya, status_pembayaran) VALUES (?, ?, ?, ?, ?, ?, ?)");
                        $stmt->execute([$tanggal, $supplier_id, $bahan_id, $kuantitas, $harga_satuan, $total, $status]);
                        $_SESSION['success'] = "Pembelian berhasil dicatat.";
                    } catch (PDOException $e) {
                        $_SESSION['error'] = "Gagal mencatat pembelian: " . $e->getMessage();
                    }
                }
                header('Location: index.php?page=kelola-pembelian');
                exit;
            }

            if ($action === 'edit') {
                $id = (int)($_POST['id'] ?? 0);
                $tanggal = $_POST['tanggal'] ?? date('Y-m-d');
                $supplier_id = (int)($_POST['supplier_id'] ?? 0);
                $bahan_id = (int)($_POST['bahan_baku_id'] ?? 0);
                $kuantitas = floatval($_POST['kuantitas'] ?? 0);
                $harga_satuan = floatval($_POST['harga_satuan'] ?? 0);
                $total = floatval($_POST['total_biaya'] ?? ($kuantitas * $harga_satuan));
                $status = $_POST['status_pembayaran'] ?? 'Belum';

                if ($id <=0 || $supplier_id <=0 || $bahan_id <=0 || $kuantitas <=0) {
                    $_SESSION['error'] = "Data pembelian tidak valid.";
                } else {
                    try {
                        $stmt = $pdo->prepare("UPDATE pembelian SET tanggal = ?, supplier_id = ?, bahan_baku_id = ?, kuantitas = ?, harga_satuan = ?, total_biaya = ?, status_pembayaran = ? WHERE id = ?");
                        $stmt->execute([$tanggal, $supplier_id, $bahan_id, $kuantitas, $harga_satuan, $total, $status, $id]);
                        $_SESSION['success'] = "Pembelian berhasil diperbarui.";
                    } catch (PDOException $e) {
                        $_SESSION['error'] = "Gagal memperbarui pembelian: " . $e->getMessage();
                    }
                }
                header('Location: index.php?page=kelola-pembelian');
                exit;
            }

            if ($action === 'delete') {
                $id = (int)($_POST['id'] ?? 0);
                if ($id <= 0) {
                    $_SESSION['error'] = "ID pembelian tidak valid.";
                } else {
                    try {
                        $stmt = $pdo->prepare("DELETE FROM pembelian WHERE id = ?");
                        $stmt->execute([$id]);
                        $_SESSION['success'] = "Pembelian berhasil dihapus.";
                    } catch (PDOException $e) {
                        $_SESSION['error'] = "Gagal menghapus pembelian: " . $e->getMessage();
                    }
                }
                header('Location: index.php?page=kelola-pembelian');
                exit;
            }
        }

        if (isset($_SESSION['success'])) { $success = $_SESSION['success']; unset($_SESSION['success']); }
        if (isset($_SESSION['error'])) { $error = $_SESSION['error']; unset($_SESSION['error']); }

        try {
            $stmt = $pdo->query("SELECT * FROM supplier ORDER BY id DESC");
            $suppliers = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $suppliers = [];
        }

        try {
            $stmt = $pdo->query("SELECT * FROM bahan_baku ORDER BY id DESC");
            $bahan_bakus = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $bahan_bakus = [];
        }

        try {
            $stmt = $pdo->query("SELECT p.*, s.nama as supplier_nama, b.nama as bahan_nama FROM pembelian p LEFT JOIN supplier s ON p.supplier_id = s.id LEFT JOIN bahan_baku b ON p.bahan_baku_id = b.id ORDER BY p.id DESC");
            $pembelians = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $pembelians = [];
        }

        $content = "dashboard/kelola-pembelian.php";
        include "views/layout.php";
    }
}