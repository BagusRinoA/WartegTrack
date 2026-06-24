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
            <a href="index.php?page=user-menu" class="active">
                <span class="nav-icon">🍽️</span> Menu
            </a>
            <a href="index.php?page=user-hubungi">
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
                        <h1 class="main-title">MENU</h1>
                        <p class="sub-title">User</p>
                    </div>
                </div>
            </div>
            <div class="header-center">
                <div class="search-box-header">
                    <span class="search-icon">🔍</span>
                    <input type="text" id="searchMenu" placeholder="Cari Menu..." class="search-input">
                </div>
            </div>
            <div class="header-right">
                <!-- Logo dihilangkan -->
            </div>
        </div>

        <div class="menu-grid">
            <?php if (!empty($menus)): ?>
                <?php foreach ($menus as $menu): ?>
                    <div class="menu-card">
                        <?php if (!empty($menu['gambar'])): ?>
                            <div class="menu-card-img">
                                <img src="<?= htmlspecialchars($menu['gambar']) ?>" alt="<?= htmlspecialchars($menu['nama']) ?>">
                            </div>
                        <?php endif; ?>
                        <div class="menu-card-body">
                            <h3 class="menu-card-title"><?= htmlspecialchars($menu['nama_produk']) ?></h3>
                            <p class="menu-card-harga">Rp <?= number_format($menu['harga'], 0, ',', '.') ?></p>
                            <?php if (!empty($menu['deskripsi'])): ?>
                                <p class="menu-card-desk"><?= htmlspecialchars($menu['deskripsi']) ?></p>
                            <?php endif; ?>
                            <a href="index.php?page=user-pesan&menu_id=<?= $menu['id'] ?>" class="btn-pesan">PESAN</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="empty-state">
                    <p>📭 Belum ada menu tersedia.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

</div>

<script>
document.getElementById('searchMenu').addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    const menuCards = document.querySelectorAll('.menu-card');
    
    menuCards.forEach(card => {
        const title = card.querySelector('.menu-card-title').textContent.toLowerCase();
        if (title.includes(searchTerm)) {
            card.style.display = 'block';
        } else {
            card.style.display = 'none';
        }
    });
});
</script>

