# WartegTrack - Sistem Informasi Manajemen Produk dan Penjualan Berbasis Website

## 🌟 Fitur Utama (Features)

Sistem ini memiliki beberapa modul utama yang disesuaikan dengan kebutuhan operasional Warteg:

1. **Pengelolaan Stok Bahan Baku (Inventory Management)**
   - Mencatat dan memonitor stok bahan baku (Misal: Beras, Ayam, Telur, dll).
   - Menampilkan notifikasi stok kritis untuk mengingatkan admin.

2. **Manajemen Laporan (Reporting)**
   - Laporan Keuangan: Rekapitulasi pengeluaran untuk bahan baku.
   - Laporan Laba/Rugi: Menghitung keuntungan berdasarkan penjualan.

3. **Manajemen Pesanan Katering (Order Management)**
   - Pelanggan dapat melihat menu dan melakukan pemesanan.
   - Admin dapat mengelola pesanan masuk dan memantau statusnya.

## 🚀 Cara Menggunakan (Usage Guide)

1. **Akses Aplikasi**
   - Buka browser dan akses aplikasi melalui URL: `http://localhost/warteg-track2`
2. **Login Admin**
   - **Email/Username**: `admin`
   - **Password**: `password`
     _(Catatan: Segera ganti password admin demi keamanan setelah berhasil login)_

## 📂 Struktur Direktori Utama

```
warteg-track2/
├── controllers/       # Logic aplikasi (PHP)
├── models/            # Model Database (PHP)
├── views/             # Tampilan (HTML, CSS, JS)
│   ├── admin/         # Dashboard Admin
│   ├── dashboard/     # Dashboard Umum
│   └── user/          # Tampilan User
├── css/               # Stylesheets
├── js/                # Javascript
└── uploads/           # Gambar Menu
```

## 🛠️ Teknologi yang Digunakan

- **Bahasa Pemrograman**: PHP
- **Database**: MySQL
- **Frontend**: HTML, CSS, JavaScript (Vanilla JS)
