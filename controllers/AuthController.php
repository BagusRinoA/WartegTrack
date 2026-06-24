<?php
/**
 * File: AuthController.php
 * Deskripsi: Controller untuk menangani autentikasi pengguna (login, logout, signup)
 *
 * Controller ini mengelola semua aspek autentikasi pengguna dalam aplikasi WartegTrack,
 * termasuk validasi input, hashing password, dan manajemen session PHP.
 */

class AuthController {

    /**
     * Method privat untuk mendapatkan koneksi database PDO
     * Menggunakan konfigurasi database lokal (localhost) dengan database warteg_track
     *
     * @return PDO Object koneksi database
     */
    private function getPDO() {
        return new PDO('mysql:host=localhost;dbname=warteg_track','root','');
    }

    /**
     * Method untuk menangani proses login pengguna
     * Menampilkan form login dan memproses autentikasi saat POST request
     */
    public function login() {
        // Mulai session jika belum aktif (penting untuk manajemen login state)
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();

        // Variabel untuk menyimpan pesan error jika login gagal
        $error = null;

        // Cek apakah request adalah POST (form dikirim)
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Ambil dan bersihkan input dari form
            $username = trim($_POST['username']);
            $password = trim($_POST['password']);

            // Load model User untuk operasi database
            require_once 'models/User.php';
            $pdo = $this->getPDO();

            // Cari user berdasarkan username di database
            $db_user = User::loadByUsername($pdo, $username);

            // Verifikasi password jika user ditemukan
            if ($db_user && $db_user->verifyPassword($password)) {
                // Set session data untuk user yang berhasil login
                $_SESSION['user_id'] = $db_user->getId();
                $_SESSION['username'] = $db_user->getUsername();
                $_SESSION['user'] = $db_user->getUsername(); // Untuk kompatibilitas dengan kode lain
                $_SESSION['role'] = $db_user->getRole();

                // Redirect berdasarkan role user
                if ($db_user->getRole() === 'admin') {
                    header("Location: index.php?page=admin-dashboard");
                } else {
                    header("Location: index.php?page=user-dashboard");
                }
                exit;

            }

            // Set pesan error jika login gagal
            $error = "Username atau password salah!";
        }

        // Tampilkan halaman login langsung tanpa layout (tidak ada navbar)
        include 'views/login.php';
    }

    /**
     * Method untuk menangani proses logout pengguna
     * Menghapus semua data session dan redirect ke halaman utama
     */
    public function logout() {
        // Pastikan session aktif sebelum menghapusnya
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();

        // Hapus semua data session (logout)
        session_destroy();

        // Redirect ke halaman utama setelah logout
        header('Location: index.php');
        exit;
    }

    /**
     * Method untuk menangani proses pendaftaran pengguna baru
     * Melakukan validasi input dan menyimpan user ke database jika valid
     */
    public function signup() {
        // Array untuk menyimpan pesan error validasi
        $errors  = [];
        // Variabel untuk pesan sukses setelah registrasi berhasil
        $success = null;
        // Array untuk menyimpan nilai input form (untuk mengisi ulang form jika ada error)
        $old = [
            'email' => '',
            'username' => '',
            'password' => ''
        ];

        // Proses hanya jika request adalah POST (form disubmit)
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Load model User untuk operasi database
            require_once 'models/User.php';
            $pdo = $this->getPDO();

            // Bersihkan dan simpan input dari form
            $old['email']    = strtolower(trim($_POST['email']));
            $old['username'] = trim($_POST['username']);
            $old['password'] = trim($_POST['password']);

            // Validasi 1: Pastikan semua field diisi
            if ($old['email'] === '' || $old['username'] === '' || $old['password'] === '') {
                $errors[] = "Semua field harus diisi.";
            }

            // Validasi 2: Cek format email valid
            if (!filter_var($old['email'], FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Format email tidak valid.";
            }

            // Validasi 3: Cek apakah username sudah digunakan
            if (User::loadByUsername($pdo, $old['username'])) {
                $errors[] = "Username sudah digunakan.";
            }

            // Validasi 4: Cek apakah email sudah terdaftar (case insensitive)
            $cekEmail = $pdo->prepare("SELECT 1 FROM users WHERE LOWER(TRIM(email)) = ? LIMIT 1");
            $cekEmail->execute([$old['email']]);
            if ($cekEmail->fetch()) {
                $errors[] = "Email sudah terdaftar.";
            }

            // Validasi 5: Cek panjang password minimal 8 karakter
            if (strlen($old['password']) < 8) {
                $errors[] = "Password minimal 8 karakter.";
            }

            // Jika ada error validasi, tampilkan form lagi dengan error
            if (!empty($errors)) {
                $content = 'views/signup.php';
                include 'views/layout.php';
                return; // Stop eksekusi method
            }

            // Jika validasi lolos, buat objek User baru
            $user = new User();
            $user->setUsername($old['username']);
            $user->setEmail($old['email']);
            $user->setRole('user'); // Default role untuk user baru
            $user->setPassword($old['password']); // Password akan di-hash otomatis

            // Simpan user ke database
            if ($user->create($pdo)) {
                // Registrasi berhasil
                $success = "Berhasil daftar. Silakan login!";
                // Reset form data
                $old = ['email' => '', 'username' => '', 'password' => ''];
            } else {
                // Registrasi gagal (biasanya karena error database)
                $errors[] = "Terjadi kesalahan saat menyimpan data.";
            }
        }

        // Tampilkan halaman signup langsung tanpa layout (tidak ada navbar)
        include 'views/signup.php';
    }
}