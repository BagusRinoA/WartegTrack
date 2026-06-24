<?php
/**
 * File: User.php
 * Deskripsi: Model untuk entitas User dalam aplikasi WartegTrack
 *
 * Model ini merepresentasikan tabel users dalam database dan menyediakan
 * method-method untuk operasi CRUD (Create, Read, Update, Delete) serta
 * autentikasi password. Menggunakan OOP (Object-Oriented Programming)
 * untuk mengelola data user dengan aman.
 */

class User {
    // Properties private untuk enkapsulasi data
    private $id;           // ID unik user (auto increment dari database)
    private $username;     // Username unik untuk login
    private $email;        // Email user (harus unik)
    private $password;     // Password yang sudah di-hash
    private $role;         // Role user: 'admin' atau 'user'
    private $created_at;   // Timestamp kapan user dibuat

    /**
     * Constructor: Dipanggil otomatis saat objek User dibuat
     * Mengatur nilai default untuk role dan created_at
     */
    public function __construct() {
        $this->role = "user";  // Default role untuk user baru
        $this->created_at = date("Y-m-d H:i:s");  // Timestamp saat ini
    }

    public function getId(){ return $this->id; }
    public function getUsername(){ return $this->username; }
    public function setUsername($username){ $this->username = $username; }

    public function getEmail(){ return $this->email; }
    public function setEmail($email){ $this->email = $email; }

    public function getPassword(){ return $this->password; }

    public function setPassword($plainPassword){
        if (strlen($plainPassword) < 8) {
            throw new Exception("Password minimal 8 karakter.");
        }
        $this->password = password_hash($plainPassword, PASSWORD_DEFAULT);
    }

    public function setPasswordHash($hash){
        $this->password = $hash;
    }

    public function getRole(){ return $this->role; }
    public function setRole($role){ $this->role = $role; }

    public function getCreatedAt(){ return $this->created_at; }

    /**
     * Method static untuk mencari user berdasarkan username
     * Mengembalikan objek User jika ditemukan, null jika tidak ada
     *
     * @param PDO $pdo Koneksi database
     * @param string $username Username yang dicari
     * @return User|null Objek User atau null jika tidak ditemukan
     */
    public static function loadByUsername(PDO $pdo, $username){
        // Persiapkan query SQL dengan prepared statement untuk keamanan
        $st = $pdo->prepare("
            SELECT id, username, email, password, role, created_at
            FROM users
            WHERE username = ?
            LIMIT 1
        ");

        // Eksekusi query dengan parameter username
        $st->execute([$username]);

        // Ambil hasil query sebagai array associative
        $row = $st->fetch(PDO::FETCH_ASSOC);

        // Jika tidak ada hasil, kembalikan null
        if (!$row) return null;

        // Buat objek User baru dan isi dengan data dari database
        $user = new self();
        $user->id = $row['id'];
        $user->username = $row['username'];
        $user->email = $row['email'];
        $user->password = $row['password'];
        $user->role = $row['role'];
        $user->created_at = $row['created_at'];

        // Kembalikan objek User yang sudah terisi data
        return $user;
    }

    /**
     * Method untuk menyimpan user baru ke database
     * Melakukan INSERT data user ke tabel users
     *
     * @param PDO $pdo Koneksi database
     * @return bool True jika berhasil, false jika gagal
     */
    public function create(PDO $pdo){
        // Persiapkan query INSERT dengan prepared statement
        $st = $pdo->prepare("
            INSERT INTO users (username, email, password, role, created_at)
            VALUES (?, ?, ?, ?, ?)
        ");

        // Eksekusi query dengan data dari properties objek
        return $st->execute([
            $this->username,
            $this->email,
            $this->password,
            $this->role,
            $this->created_at
        ]);
    }

    /**
     * Method untuk verifikasi password user
     * Mendukung verifikasi hybrid: password hash modern dan plaintext lama
     * Digunakan untuk backward compatibility dengan data user lama
     *
     * @param string $plainPassword Password dalam bentuk plain text
     * @return bool True jika password cocok, false jika tidak
     */
    public function verifyPassword($plainPassword){
        // Cek apakah password di database sudah di-hash (dimulai dengan '$')
        // Jika tidak, berarti password masih plaintext (untuk user lama)
        if (strpos($this->password, '$') !== 0) {
            // Verifikasi plaintext (untuk backward compatibility)
            return $plainPassword === $this->password;
        }

        // Verifikasi password hash menggunakan password_verify()
        return password_verify($plainPassword, $this->password);
    }
}