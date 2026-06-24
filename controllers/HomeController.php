<?php
/**
 * File: HomeController.php
 * Deskripsi: Controller utama untuk halaman landing dan menu
 *
 * Controller ini menangani halaman-halaman umum aplikasi WartegTrack yang tidak
 * memerlukan autentikasi khusus, seperti halaman about dan daftar menu.
 *
 * Method yang tersedia:
 * - index(): Menampilkan halaman tentang/landing page
 * - menu(): Menampilkan halaman pencarian dan daftar menu dari database
 */
class HomeController {

    /**
     * Method untuk menampilkan halaman landing page (About Us)
     * Halaman ini menjelaskan fitur-fitur aplikasi WartegTrack
     *
     * @return void - Method ini hanya menampilkan view, tidak mengembalikan nilai
     */
    public function index() {
        // Set variabel $content untuk menentukan view yang akan dimuat
        // Layout utama (views/layout.php) akan meng-include file ini
        $content = 'views/home.php';

        // Include layout utama yang akan menampilkan navbar, content, dan footer
        include 'views/layout.php';
    }

    /**
     * Method untuk menampilkan halaman menu dengan fitur pencarian
     * Mengambil data menu dari database dan menampilkannya dalam grid layout
     *
     * @return void - Method ini hanya menampilkan view, tidak mengembalikan nilai
     */
    public function menu() {
        // Buat koneksi PDO ke database warteg_track
        // Konfigurasi: host=localhost, db=warteg_track, user=root, password=kosong
        // Dalam production, sebaiknya gunakan environment variables untuk security
        $pdo = new PDO('mysql:host=localhost;dbname=warteg_track','root','');

        // Persiapkan query SQL untuk mengambil semua data menu
        // Diurutkan berdasarkan ID secara ascending (dari kecil ke besar)
        $stmt = $pdo->prepare("SELECT * FROM menu ORDER BY id ASC");

        // Eksekusi query
        $stmt->execute();

        // Ambil semua hasil query sebagai array associative
        // FETCH_ASSOC berarti hasil berupa array dengan key nama kolom database
        $menus = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Set variabel $content untuk view menu
        // Data $menus akan tersedia di view menu.php
        $content = 'views/menu.php';

        // Include layout utama untuk menampilkan halaman lengkap
        include 'views/layout.php';
    }

    public function dashboardUser() {
    session_start();
    if ($_SESSION['role'] !== 'user') {
        header("Location: index.php?page=login");
        exit;
    }
    include 'views/dashboard/user-dashboard.php';

    }

}
