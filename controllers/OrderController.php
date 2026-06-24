<?php
/**
 * Controller untuk proses Pemesanan pelanggan (Pre-Order)
 */
class OrderController {
    /**
     * Menangani form pesanan pre-order.
     * Form bisa dilihat tanpa login, tapi submit memerlukan login.
     */
    public function form() {
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
        
        $success = null;
        $error = null;
        $isLoggedIn = !empty($_SESSION['user']);
        
        // Jika submit form (POST), proses pesanan
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Wajib login untuk submit
            if (!$isLoggedIn) {
                $_SESSION['error'] = "Silakan login untuk mengirim pesanan.";
                header("Location: index.php?page=login");
                exit;
            }

            // Ambil data dari form
            $nama = htmlspecialchars($_POST['nama']);
            $menu = htmlspecialchars($_POST['menu']);
            $jumlah = (int)$_POST['jumlah'];
            $tanggal_pengiriman = htmlspecialchars($_POST['tanggal_pengiriman'] ?? '');
            $alamat_pengiriman = htmlspecialchars($_POST['alamat_pengiriman'] ?? '');
            $catatan = htmlspecialchars($_POST['catatan'] ?? '');
            
            // Validasi input
            if (empty($nama) || empty($menu) || $jumlah < 1 || empty($tanggal_pengiriman) || empty($alamat_pengiriman)) {
                $error = "Mohon lengkapi semua field yang wajib diisi.";
            } else {
                // Simpan ke database jika tabel orders ada
                try {
                    $pdo = new PDO('mysql:host=localhost;dbname=warteg_track','root','');
                    
                    // Cek apakah tabel orders ada, jika tidak buat
                    $checkTable = $pdo->query("SHOW TABLES LIKE 'orders'");
                    if ($checkTable->rowCount() == 0) {
                        // Buat tabel orders jika belum ada
                        $pdo->exec("
                            CREATE TABLE IF NOT EXISTS orders (
                                id INT AUTO_INCREMENT PRIMARY KEY,
                                user_id INT NULL,
                                nama VARCHAR(100) NOT NULL,
                                menu VARCHAR(100) NOT NULL,
                                jumlah INT NOT NULL,
                                tanggal_pengiriman DATE NULL,
                                alamat_pengiriman TEXT NULL,
                                catatan TEXT,
                                total_harga DECIMAL(10,2) DEFAULT 0,
                                status VARCHAR(20) DEFAULT 'pending',
                                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
                            )
                        ");
                    } else {
                        // Cek kolom tanggal_pengiriman
                        $checkCol = $pdo->query("SHOW COLUMNS FROM orders LIKE 'tanggal_pengiriman'");
                        if ($checkCol->rowCount() == 0) {
                            $pdo->exec("ALTER TABLE orders ADD COLUMN tanggal_pengiriman DATE NULL AFTER jumlah");
                            $pdo->exec("ALTER TABLE orders ADD COLUMN alamat_pengiriman TEXT NULL AFTER tanggal_pengiriman");
                        }
                    }
                    
                    // Simpan ke database
                    $stmt = $pdo->prepare("
                        INSERT INTO orders (user_id, nama, menu, jumlah, tanggal_pengiriman, alamat_pengiriman, catatan, total_harga, status, created_at) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, 0, 'pending', NOW())
                    ");
                    $stmt->execute([
                        $_SESSION['user_id'] ?? null,
                        $nama,
                        $menu,
                        $jumlah,
                        $tanggal_pengiriman,
                        $alamat_pengiriman,
                        $catatan
                    ]);
                    
                    // Redirect: jika admin ke admin dashboard, jika user ke user dashboard, jika guest ke home
                    if (!empty($_SESSION['role']) && $_SESSION['role'] === 'admin') {
                        header("Location: index.php?page=admin-dashboard");
                    } elseif (!empty($_SESSION['user'])) {
                        header("Location: index.php?page=user-dashboard");
                    } else {
                        header("Location: index.php?page=home");
                    }
                    exit;
                    
                } catch (PDOException $e) {
                    $error = "Terjadi kesalahan saat menyimpan pesanan: " . $e->getMessage();
                }
            }
        }
        
        // Ambil nama dari session jika sudah login (untuk auto-fill)
        $defaultNama = $isLoggedIn ? ($_SESSION['user'] ?? '') : '';
        
        // Tampilkan halaman form order
        $content = 'views/order.php';
        include 'views/layout.php';
    }
} 
