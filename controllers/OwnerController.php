<?php
/**
 * File: OwnerController.php
 * Deskripsi: Controller untuk fitur-fitur OWNER (pemilik)
 */

class OwnerController {
    
    private function getPDO() {
        return new PDO('mysql:host=localhost;dbname=warteg_track','root','');
    }
    
    /**
     * Halaman pembelian
     */
    public function pembelian() {
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
        if (empty($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
            header('Location: index.php?page=login');
            exit;
        }
        
        $pdo = $this->getPDO();
        
        // Handle POST request untuk add/edit/delete pembelian
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? '';
            
            if ($action === 'add') {
                $tanggal = $_POST['tanggal'] ?? date('Y-m-d');
                $supplier_id = (int)($_POST['supplier_id'] ?? 0);
                $bahan_baku_id = (int)($_POST['bahan_baku_id'] ?? 0);
                $kuantitas = (float)($_POST['kuantitas'] ?? 0);
                $harga_satuan = (float)($_POST['harga_satuan'] ?? 0);
                $total_biaya = (float)($_POST['total_biaya'] ?? $kuantitas * $harga_satuan);
                $status_pembayaran = $_POST['status_pembayaran'] ?? 'pending';
                
                if ($supplier_id > 0 && $bahan_baku_id > 0 && $kuantitas > 0 && $harga_satuan > 0) {
                    try {
                        $stmt = $pdo->prepare("
                            INSERT INTO pembelian (tanggal, supplier_id, bahan_baku_id, kuantitas, harga_satuan, total_biaya, status_pembayaran)
                            VALUES (?, ?, ?, ?, ?, ?, ?)
                        ");
                        $stmt->execute([$tanggal, $supplier_id, $bahan_baku_id, $kuantitas, $harga_satuan, $total_biaya, $status_pembayaran]);
                        $_SESSION['success'] = "Pembelian berhasil disimpan!";
                    } catch (PDOException $e) {
                        $_SESSION['error'] = "Gagal menyimpan pembelian: " . $e->getMessage();
                    }
                } else {
                    $_SESSION['error'] = "Data tidak lengkap atau tidak valid.";
                }
                header('Location: index.php?page=owner-pembelian');
                exit;
            } elseif ($action === 'edit') {
                $id = (int)($_POST['id'] ?? 0);
                $tanggal = $_POST['tanggal'] ?? date('Y-m-d');
                $supplier_id = (int)($_POST['supplier_id'] ?? 0);
                $bahan_baku_id = (int)($_POST['bahan_baku_id'] ?? 0);
                $kuantitas = (float)($_POST['kuantitas'] ?? 0);
                $harga_satuan = (float)($_POST['harga_satuan'] ?? 0);
                $total_biaya = (float)($_POST['total_biaya'] ?? $kuantitas * $harga_satuan);
                $status_pembayaran = $_POST['status_pembayaran'] ?? 'pending';
                
                if ($id > 0 && $supplier_id > 0 && $bahan_baku_id > 0 && $kuantitas > 0 && $harga_satuan > 0) {
                    try {
                        $stmt = $pdo->prepare("
                            UPDATE pembelian SET tanggal = ?, supplier_id = ?, bahan_baku_id = ?, kuantitas = ?, harga_satuan = ?, total_biaya = ?, status_pembayaran = ?
                            WHERE id = ?
                        ");
                        $stmt->execute([$tanggal, $supplier_id, $bahan_baku_id, $kuantitas, $harga_satuan, $total_biaya, $status_pembayaran, $id]);
                        $_SESSION['success'] = "Pembelian berhasil diperbarui!";
                    } catch (PDOException $e) {
                        $_SESSION['error'] = "Gagal memperbarui pembelian: " . $e->getMessage();
                    }
                } else {
                    $_SESSION['error'] = "Data tidak lengkap atau tidak valid.";
                }
                header('Location: index.php?page=owner-pembelian');
                exit;
            } elseif ($action === 'delete') {
                $id = (int)($_POST['id'] ?? 0);
                if ($id > 0) {
                    try {
                        $stmt = $pdo->prepare("DELETE FROM pembelian WHERE id = ?");
                        $stmt->execute([$id]);
                        $_SESSION['success'] = "Pembelian berhasil dihapus!";
                    } catch (PDOException $e) {
                        $_SESSION['error'] = "Gagal menghapus pembelian: " . $e->getMessage();
                    }
                } else {
                    $_SESSION['error'] = "ID pembelian tidak valid.";
                }
                header('Location: index.php?page=owner-pembelian');
                exit;
            }
        }
        
        // Extract messages from session
        $success = null;
        $error = null;
        if (isset($_SESSION['success'])) {
            $success = $_SESSION['success'];
            unset($_SESSION['success']);
        }
        if (isset($_SESSION['error'])) {
            $error = $_SESSION['error'];
            unset($_SESSION['error']);
        }
        
        // Ambil data pembelian
        try {
            $stmt = $pdo->query("
                SELECT p.*, s.nama as supplier_nama 
                FROM pembelian p 
                LEFT JOIN supplier s ON p.supplier_id = s.id 
                ORDER BY p.tanggal DESC
            ");
            $pembelians = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $pembelians = [];
        }
        
        // Ambil supplier untuk dropdown
        try {
            $stmt = $pdo->query("SELECT * FROM supplier ORDER BY nama ASC");
            $suppliers = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $suppliers = [];
        }
        
        // Ambil bahan baku untuk dropdown
        try {
            $stmt = $pdo->query("SELECT * FROM bahan_baku ORDER BY nama ASC");
            $bahan_bakus = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $bahan_bakus = [];
        }
        
        $content = "dashboard/owner-pembelian.php";
        include "views/layout.php";
    }
    
    /**
     * Halaman supplier
     */
    public function supplier() {
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
        if (empty($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
            header('Location: index.php?page=login');
            exit;
        }
        
        $pdo = $this->getPDO();
        
        // Handle POST request untuk add/edit/delete supplier
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? '';
            
            if ($action === 'add') {
                $nama = $_POST['nama'] ?? '';
                $kontak = $_POST['kontak'] ?? '';
                $alamat = $_POST['alamat'] ?? '';
                $catatan = $_POST['catatan'] ?? '';
                
                if (!empty($nama) && !empty($kontak) && !empty($alamat)) {
                    try {
                        $stmt = $pdo->prepare("
                            INSERT INTO supplier (nama, kontak, alamat, catatan)
                            VALUES (?, ?, ?, ?)
                        ");
                        $stmt->execute([$nama, $kontak, $alamat, $catatan]);
                        $_SESSION['success'] = "Supplier berhasil ditambahkan!";
                    } catch (PDOException $e) {
                        $_SESSION['error'] = "Gagal menambahkan supplier: " . $e->getMessage();
                    }
                } else {
                    $_SESSION['error'] = "Data tidak lengkap. Nama, kontak, dan alamat harus diisi.";
                }
                header('Location: index.php?page=owner-supplier');
                exit;
            } elseif ($action === 'edit') {
                $id = (int)($_POST['id'] ?? 0);
                $nama = $_POST['nama'] ?? '';
                $kontak = $_POST['kontak'] ?? '';
                $alamat = $_POST['alamat'] ?? '';
                $catatan = $_POST['catatan'] ?? '';
                
                if ($id > 0 && !empty($nama) && !empty($kontak) && !empty($alamat)) {
                    try {
                        $stmt = $pdo->prepare("
                            UPDATE supplier SET nama = ?, kontak = ?, alamat = ?, catatan = ?
                            WHERE id = ?
                        ");
                        $stmt->execute([$nama, $kontak, $alamat, $catatan, $id]);
                        $_SESSION['success'] = "Supplier berhasil diperbarui!";
                    } catch (PDOException $e) {
                        $_SESSION['error'] = "Gagal memperbarui supplier: " . $e->getMessage();
                    }
                } else {
                    $_SESSION['error'] = "Data tidak lengkap. Nama, kontak, dan alamat harus diisi.";
                }
                header('Location: index.php?page=owner-supplier');
                exit;
            } elseif ($action === 'delete') {
                $id = (int)($_POST['id'] ?? 0);
                if ($id > 0) {
                    try {
                        $stmt = $pdo->prepare("DELETE FROM supplier WHERE id = ?");
                        $stmt->execute([$id]);
                        $_SESSION['success'] = "Supplier berhasil dihapus!";
                    } catch (PDOException $e) {
                        $_SESSION['error'] = "Gagal menghapus supplier: " . $e->getMessage();
                    }
                } else {
                    $_SESSION['error'] = "ID supplier tidak valid.";
                }
                header('Location: index.php?page=owner-supplier');
                exit;
            }
        }
        
        // Extract messages from session
        $success = null;
        $error = null;
        if (isset($_SESSION['success'])) {
            $success = $_SESSION['success'];
            unset($_SESSION['success']);
        }
        if (isset($_SESSION['error'])) {
            $error = $_SESSION['error'];
            unset($_SESSION['error']);
        }
        
        // Ambil semua supplier
        try {
            $stmt = $pdo->query("SELECT * FROM supplier ORDER BY id DESC");
            $suppliers = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $suppliers = [];
        }
        
        $content = "dashboard/owner-supplier.php";
        include "views/layout.php";
    }
    
    /**
     * Halaman bahan baku
     */
    public function bahanBaku() {
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
        if (empty($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
            header('Location: index.php?page=login');
            exit;
        }
        
        $pdo = $this->getPDO();
        
        // Handle POST request untuk add/edit/delete bahan baku
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? '';
            
            if ($action === 'add') {
                $nama = $_POST['nama'] ?? '';
                $unit_satuan = $_POST['unit_satuan'] ?? 'Kg';
                $stok_awal = (float)($_POST['stok_awal'] ?? 0);
                $stok_minimum = (float)($_POST['stok_minimum'] ?? 0);
                
                if (!empty($nama) && $stok_awal >= 0) {
                    try {
                        $stmt = $pdo->prepare("
                            INSERT INTO bahan_baku (nama, unit_satuan, stok_saat_ini, stok_minimum)
                            VALUES (?, ?, ?, ?)
                        ");
                        $stmt->execute([$nama, $unit_satuan, $stok_awal, $stok_minimum]);
                        $_SESSION['success'] = "Bahan baku berhasil ditambahkan!";
                    } catch (PDOException $e) {
                        $_SESSION['error'] = "Gagal menambahkan bahan baku: " . $e->getMessage();
                    }
                } else {
                    $_SESSION['error'] = "Data tidak lengkap atau tidak valid.";
                }
                header('Location: index.php?page=owner-bahan-baku');
                exit;
            } elseif ($action === 'edit') {
                $id = (int)($_POST['id'] ?? 0);
                $nama = $_POST['nama'] ?? '';
                $unit_satuan = $_POST['unit_satuan'] ?? 'Kg';
                $stok_saat_ini = (float)($_POST['stok_saat_ini'] ?? 0);
                $stok_minimum = (float)($_POST['stok_minimum'] ?? 0);
                
                if ($id > 0 && !empty($nama) && $stok_saat_ini >= 0) {
                    try {
                        $stmt = $pdo->prepare("
                            UPDATE bahan_baku SET nama = ?, unit_satuan = ?, stok_saat_ini = ?, stok_minimum = ?
                            WHERE id = ?
                        ");
                        $stmt->execute([$nama, $unit_satuan, $stok_saat_ini, $stok_minimum, $id]);
                        $_SESSION['success'] = "Bahan baku berhasil diperbarui!";
                    } catch (PDOException $e) {
                        $_SESSION['error'] = "Gagal memperbarui bahan baku: " . $e->getMessage();
                    }
                } else {
                    $_SESSION['error'] = "Data tidak lengkap atau tidak valid.";
                }
                header('Location: index.php?page=owner-bahan-baku');
                exit;
            } elseif ($action === 'delete') {
                $id = (int)($_POST['id'] ?? 0);
                if ($id > 0) {
                    try {
                        $stmt = $pdo->prepare("DELETE FROM bahan_baku WHERE id = ?");
                        $stmt->execute([$id]);
                        $_SESSION['success'] = "Bahan baku berhasil dihapus!";
                    } catch (PDOException $e) {
                        $_SESSION['error'] = "Gagal menghapus bahan baku: " . $e->getMessage();
                    }
                } else {
                    $_SESSION['error'] = "ID bahan baku tidak valid.";
                }
                header('Location: index.php?page=owner-bahan-baku');
                exit;
            }
        }
        
        // Extract messages from session
        $success = null;
        $error = null;
        if (isset($_SESSION['success'])) {
            $success = $_SESSION['success'];
            unset($_SESSION['success']);
        }
        if (isset($_SESSION['error'])) {
            $error = $_SESSION['error'];
            unset($_SESSION['error']);
        }
        
        // Ambil semua bahan baku
        try {
            $stmt = $pdo->query("SELECT * FROM bahan_baku ORDER BY id DESC");
            $bahan_bakus = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $bahan_bakus = [];
        }
        
        $content = "dashboard/owner-bahan-baku.php";
        include "views/layout.php";
    }
    
    /**
     * Halaman laporan keuangan
     */
    public function laporanKeuangan() {
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
        if (empty($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
            header('Location: index.php?page=login');
            exit;
        }
        
        $pdo = $this->getPDO();
        
        // Ambil periode filter
        $periode = $_GET['periode'] ?? 'bulanan';
        $start_date = $_GET['start_date'] ?? date('Y-m-01');
        $end_date = $_GET['end_date'] ?? date('Y-m-t');

        // Update status order (pending -> complete)
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_order_status'])) {
            $orderId = (int)($_POST['order_id'] ?? 0);
            $newStatus = $_POST['new_status'] ?? '';

            if ($orderId > 0 && in_array($newStatus, ['complete'], true)) {
                try {
                    $stmt = $pdo->prepare("UPDATE orders SET status = ? WHERE id = ? AND status = 'pending'");
                    $stmt->execute([$newStatus, $orderId]);
                    $_SESSION['success'] = "Status pesanan #$orderId berhasil diubah menjadi " . ucfirst($newStatus) . ".";
                } catch (PDOException $e) {
                    $_SESSION['error'] = "Gagal mengubah status pesanan.";
                }
            } else {
                $_SESSION['error'] = "Data tidak valid untuk memperbarui status.";
            }

            // redirect dengan filter yang sama agar tidak double submit
            $qs = http_build_query([
                'page' => 'owner-laporan-keuangan',
                'periode' => $periode,
                'start_date' => $start_date,
                'end_date' => $end_date
            ]);
            header("Location: index.php?$qs");
            exit;
        }
        
        // Hitung omzet
        try {
            $stmt = $pdo->prepare("
                SELECT SUM(total_harga) as total 
                FROM orders 
                WHERE created_at BETWEEN ? AND ? AND status = 'complete'
            ");
            $stmt->execute([$start_date, $end_date . ' 23:59:59']);
            $omzet = $stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;
        } catch (PDOException $e) {
            $omzet = 0;
        }
        
        // Hitung pengeluaran
        try {
            $stmt = $pdo->prepare("
                SELECT SUM(total_biaya) as total 
                FROM pembelian 
                WHERE tanggal BETWEEN ? AND ?
            ");
            $stmt->execute([$start_date, $end_date]);
            $pengeluaran = $stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;
        } catch (PDOException $e) {
            $pengeluaran = 0;
        }
        
        $laba_bersih = $omzet - $pengeluaran;
        
        // Ambil transaksi penjualan
        try {
            $stmt = $pdo->prepare("
                SELECT * FROM orders 
                WHERE created_at BETWEEN ? AND ? AND status != 'cancelled'
                ORDER BY created_at DESC
            ");
            $stmt->execute([$start_date, $end_date . ' 23:59:59']);
            $penjualan = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $penjualan = [];
        }
        
        // Ambil data pembelian
        try {
            $stmt = $pdo->prepare("
                SELECT p.*, s.nama as supplier_nama 
                FROM pembelian p 
                LEFT JOIN supplier s ON p.supplier_id = s.id 
                WHERE p.tanggal BETWEEN ? AND ?
                ORDER BY p.tanggal DESC
            ");
            $stmt->execute([$start_date, $end_date]);
            $pembelians = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $pembelians = [];
        }
        
        $content = "dashboard/owner-laporan-keuangan.php";
        include "views/layout.php";
    }

    /**
     * Handle delete pembelian
     */
    public function deletePembelian() {
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
        if (empty($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
            header('Location: index.php?page=login');
            exit;
        }

        $pdo = $this->getPDO();
        $id = (int)($_GET['id'] ?? 0);

        if ($id > 0) {
            try {
                $stmt = $pdo->prepare("DELETE FROM pembelian WHERE id = ?");
                $stmt->execute([$id]);
                $_SESSION['success'] = "Pembelian berhasil dihapus!";
            } catch (PDOException $e) {
                $_SESSION['error'] = "Gagal menghapus pembelian: " . $e->getMessage();
            }
        } else {
            $_SESSION['error'] = "ID pembelian tidak valid.";
        }

        header('Location: index.php?page=owner-pembelian');
        exit;
    }

    /**
     * Handle edit pembelian
     */
    public function editPembelian() {
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
        if (empty($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
            header('Location: index.php?page=login');
            exit;
        }

        $pdo = $this->getPDO();
        $id = (int)($_GET['id'] ?? 0);

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $id > 0) {
            $tanggal = $_POST['tanggal'] ?? date('Y-m-d');
            $supplier_id = (int)($_POST['supplier_id'] ?? 0);
            $bahan_baku_id = (int)($_POST['bahan_baku_id'] ?? 0);
            $kuantitas = (float)($_POST['kuantitas'] ?? 0);
            $harga_satuan = (float)($_POST['harga_satuan'] ?? 0);
            $total_biaya = (float)($_POST['total_biaya'] ?? $kuantitas * $harga_satuan);
            $status_pembayaran = $_POST['status_pembayaran'] ?? 'pending';

            if ($supplier_id > 0 && $bahan_baku_id > 0 && $kuantitas > 0 && $harga_satuan > 0) {
                try {
                    $stmt = $pdo->prepare("
                        UPDATE pembelian 
                        SET tanggal = ?, supplier_id = ?, bahan_baku_id = ?, kuantitas = ?, 
                            harga_satuan = ?, total_biaya = ?, status_pembayaran = ?
                        WHERE id = ?
                    ");
                    $stmt->execute([$tanggal, $supplier_id, $bahan_baku_id, $kuantitas, $harga_satuan, $total_biaya, $status_pembayaran, $id]);
                    $_SESSION['success'] = "Pembelian berhasil diperbarui!";
                } catch (PDOException $e) {
                    $_SESSION['error'] = "Gagal memperbarui pembelian: " . $e->getMessage();
                }
            } else {
                $_SESSION['error'] = "Data tidak lengkap atau tidak valid.";
            }

            header('Location: index.php?page=owner-pembelian');
            exit;
        }

        // Ambil data pembelian untuk di-edit
        try {
            $stmt = $pdo->prepare("
                SELECT p.*, s.nama as supplier_nama 
                FROM pembelian p 
                LEFT JOIN supplier s ON p.supplier_id = s.id 
                WHERE p.id = ?
            ");
            $stmt->execute([$id]);
            $pembelian = $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $pembelian = null;
        }

        // Ambil supplier untuk dropdown
        try {
            $stmt = $pdo->query("SELECT * FROM supplier ORDER BY nama ASC");
            $suppliers = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $suppliers = [];
        }

        // Ambil bahan baku untuk dropdown
        try {
            $stmt = $pdo->query("SELECT * FROM bahan_baku ORDER BY nama ASC");
            $bahan_bakus = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $bahan_bakus = [];
        }

        if (!$pembelian) {
            $_SESSION['error'] = "Data pembelian tidak ditemukan.";
            header('Location: index.php?page=owner-pembelian');
            exit;
        }

        $content = "dashboard/owner-edit-pembelian.php";
        include "views/layout.php";
    }

    /**
     * Handle delete supplier
     */
    public function deleteSupplier() {
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
        if (empty($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
            header('Location: index.php?page=login');
            exit;
        }

        $pdo = $this->getPDO();
        $id = (int)($_GET['id'] ?? 0);

        if ($id > 0) {
            try {
                $stmt = $pdo->prepare("DELETE FROM supplier WHERE id = ?");
                $stmt->execute([$id]);
                $_SESSION['success'] = "Supplier berhasil dihapus!";
            } catch (PDOException $e) {
                $_SESSION['error'] = "Gagal menghapus supplier: " . $e->getMessage();
            }
        } else {
            $_SESSION['error'] = "ID supplier tidak valid.";
        }

        header('Location: index.php?page=owner-supplier');
        exit;
    }

    /**
     * Handle edit supplier
     */
    public function editSupplier() {
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
        if (empty($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
            header('Location: index.php?page=login');
            exit;
        }

        $pdo = $this->getPDO();
        $id = (int)($_GET['id'] ?? 0);

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $id > 0) {
            $nama = $_POST['nama'] ?? '';
            $kontak = $_POST['kontak'] ?? '';
            $alamat = $_POST['alamat'] ?? '';
            $catatan = $_POST['catatan'] ?? '';

            if (!empty($nama) && !empty($kontak) && !empty($alamat)) {
                try {
                    $stmt = $pdo->prepare("
                        UPDATE supplier 
                        SET nama = ?, kontak = ?, alamat = ?, catatan = ?
                        WHERE id = ?
                    ");
                    $stmt->execute([$nama, $kontak, $alamat, $catatan, $id]);
                    $_SESSION['success'] = "Supplier berhasil diperbarui!";
                } catch (PDOException $e) {
                    $_SESSION['error'] = "Gagal memperbarui supplier: " . $e->getMessage();
                }
            } else {
                $_SESSION['error'] = "Data tidak lengkap. Nama, kontak, dan alamat harus diisi.";
            }

            header('Location: index.php?page=owner-supplier');
            exit;
        }

        // Ambil data supplier untuk di-edit
        try {
            $stmt = $pdo->prepare("SELECT * FROM supplier WHERE id = ?");
            $stmt->execute([$id]);
            $supplier = $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $supplier = null;
        }

        if (!$supplier) {
            $_SESSION['error'] = "Data supplier tidak ditemukan.";
            header('Location: index.php?page=owner-supplier');
            exit;
        }

        $content = "dashboard/owner-edit-supplier.php";
        include "views/layout.php";
    }

    /**
     * Handle delete bahan baku
     */
    public function deleteBahanBaku() {
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
        if (empty($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
            header('Location: index.php?page=login');
            exit;
        }

        $pdo = $this->getPDO();
        $id = (int)($_GET['id'] ?? 0);

        if ($id > 0) {
            try {
                $stmt = $pdo->prepare("DELETE FROM bahan_baku WHERE id = ?");
                $stmt->execute([$id]);
                $_SESSION['success'] = "Bahan baku berhasil dihapus!";
            } catch (PDOException $e) {
                $_SESSION['error'] = "Gagal menghapus bahan baku: " . $e->getMessage();
            }
        } else {
            $_SESSION['error'] = "ID bahan baku tidak valid.";
        }

        header('Location: index.php?page=owner-bahan-baku');
        exit;
    }

    /**
     * Handle edit bahan baku
     */
    public function editBahanBaku() {
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
        if (empty($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
            header('Location: index.php?page=login');
            exit;
        }

        $pdo = $this->getPDO();
        $id = (int)($_GET['id'] ?? 0);

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $id > 0) {
            $nama = $_POST['nama'] ?? '';
            $unit_satuan = $_POST['unit_satuan'] ?? 'Kg';
            $stok_saat_ini = (float)($_POST['stok_saat_ini'] ?? 0);
            $stok_minimum = (float)($_POST['stok_minimum'] ?? 0);

            if (!empty($nama) && $stok_saat_ini >= 0) {
                try {
                    $stmt = $pdo->prepare("
                        UPDATE bahan_baku 
                        SET nama = ?, unit_satuan = ?, stok_saat_ini = ?, stok_minimum = ?
                        WHERE id = ?
                    ");
                    $stmt->execute([$nama, $unit_satuan, $stok_saat_ini, $stok_minimum, $id]);
                    $_SESSION['success'] = "Bahan baku berhasil diperbarui!";
                } catch (PDOException $e) {
                    $_SESSION['error'] = "Gagal memperbarui bahan baku: " . $e->getMessage();
                }
            } else {
                $_SESSION['error'] = "Data tidak lengkap atau tidak valid.";
            }

            header('Location: index.php?page=owner-bahan-baku');
            exit;
        }

        // Ambil data bahan baku untuk di-edit
        try {
            $stmt = $pdo->prepare("SELECT * FROM bahan_baku WHERE id = ?");
            $stmt->execute([$id]);
            $bahan_baku = $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $bahan_baku = null;
        }

        if (!$bahan_baku) {
            $_SESSION['error'] = "Data bahan baku tidak ditemukan.";
            header('Location: index.php?page=owner-bahan-baku');
            exit;
        }

        $content = "dashboard/owner-edit-bahan-baku.php";
        include "views/layout.php";
    }
}




