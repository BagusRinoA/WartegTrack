<div class="dashboard-wrapper">

    <div class="sidebar">
        <div class="owner-section">
            <div class="owner-box">
                <span class="owner-icon">👤</span>
                <span class="owner-text">USER</span>
            </div>
        </div>
        <nav class="sidebar-nav">
            <a href="index.php?page=user-dashboard">
                <span class="nav-icon">🏠</span> Dashboard
            </a>
            <a href="index.php?page=user-menu">
                <span class="nav-icon">🍽️</span> Menu
            </a>
            <a href="index.php?page=user-hubungi" class="active">
                <span class="nav-icon">📞</span> Hubungi
            </a>
            <a href="index.php?page=user-transaksi">
                <span class="nav-icon">💰</span> Transaksi
            </a>
            <a href="index.php?page=home">
                <span class="nav-icon">⬅️</span> Kembali ke Home
            </a>
        </nav>
        <div class="logout-section">
            <a href="index.php?page=logout" class="logout-link">
                <span class="logout-icon">🚪</span> Log Out
            </a>
        </div>
    </div>

    <div class="content">
        <div class="dashboard-header">
            <div class="header-left">
                <div class="title-section">
                    <span class="title-line">|</span>
                    <div>
                        <h1 class="main-title">HUBUNGI</h1>
                        <p class="sub-title">User</p>
                    </div>
                </div>
            </div>
            <div class="header-center">
                <div class="search-box-header">
                    <span class="search-icon">🔍</span>
                    <input type="text" placeholder="Search..." class="search-input">
                </div>
            </div>
            <div class="header-right">
                <!-- Logo dihilangkan -->
            </div>
        </div>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert-success">
                <span class="success-icon">✔️</span>
                <?= htmlspecialchars($_SESSION['success']) ?>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <div class="table-section-menu" style="max-width: 600px; margin: 0 auto;">
            <h2 style="color: #1e7ba8;">Informasi Kontak</h2>
            <div style="margin-top: 20px; font-size: 1.1rem; line-height: 1.8; color: #2C3E50;">
                <p><strong>📞 Telepon:</strong> 08**********</p>
                <p><strong>📧 Email:</strong> info@wartegtrack.com</p>
                <p><strong>📍 Alamat:</strong> Jl. Warteg No. 123, Warteg</p>
                <p><strong>🕒 Jam Operasional:</strong> Senin - Minggu, 07:00 - 22:00 WIB</p>
            </div>
            
            <h3 style="margin-top: 30px; color: #1e7ba8; margin-bottom: 15px;">Kirim Pesan</h3>
            <form method="post" class="order-form" style="width: 100%; padding: 0; box-shadow: none; background: transparent; margin: 0;">
                <div class="input-group">
                    <label>Nama</label>
                    <input type="text" name="nama" placeholder="Masukkan nama Anda" required>
                </div>
                <div class="input-group">
                    <label>Email</label>
                    <input type="email" name="email" placeholder="Masukkan email Anda" required>
                </div>
                <div class="input-group">
                    <label>Pesan</label>
                    <textarea name="pesan" rows="5" placeholder="Tuliskan pesan Anda di sini" required></textarea>
                </div>
                <button type="submit" class="btn-checkout" style="margin-top: 10px; width: 100%;">Kirim Pesan</button>
            </form>
        </div>
    </div>

</div>

