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
                        <h1 class="main-title">PEMESANAN</h1>
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

        <div class="menu-grid">
            <?php if (!empty($menus)): ?>
                <?php foreach ($menus as $menu): ?>
                    <div class="menu-card">
                        <?php if (!empty($menu['gambar'])): ?>
                            <div class="menu-card-img">
                                <img src="<?= htmlspecialchars($menu['gambar']) ?>" alt="<?= htmlspecialchars($menu['nama_produk']) ?>">
                            </div>
                        <?php endif; ?>
                        <div class="menu-card-body">
                            <h3 class="menu-card-title"><?= htmlspecialchars($menu['nama_produk']) ?></h3>
                            <p class="menu-card-harga">Rp <?= number_format($menu['harga'], 0, ',', '.') ?></p>
                            <?php if (!empty($menu['deskripsi'])): ?>
                                <p class="menu-card-desk"><?= htmlspecialchars($menu['deskripsi']) ?></p>
                            <?php endif; ?>
                            <form method="post" style="margin-top: 10px;">
                                <input type="hidden" name="menu_id" value="<?= $menu['id'] ?>">
                                <input type="number" name="jumlah" value="1" min="1" style="width: 60px; padding: 5px; margin-right: 5px;">
                                <button type="submit" name="add_to_cart" class="btn-pesan">PESAN</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="empty-state">
                    <p>📭 Belum ada menu tersedia.</p>
                </div>
            <?php endif; ?>
        </div>

        <?php if (!empty($_SESSION['cart'])): ?>
            <div class="checkout-section" style="margin-top: 30px;">
                <h2>Keranjang Anda</h2>
                <table style="width: 100%; margin-top: 15px;">
                    <thead>
                        <tr>
                            <th>Menu</th>
                            <th>Jumlah</th>
                            <th>Harga</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $total = 0;
                        foreach ($_SESSION['cart'] as $item): 
                            $subtotal = $item['harga'] * $item['jumlah'];
                            $total += $subtotal;
                        ?>
                            <tr>
                                <td><?= htmlspecialchars($item['nama_produk'] ?? $item['nama'] ?? 'Produk') ?></td>
                                <td><?= $item['jumlah'] ?></td>
                                <td>Rp <?= number_format($item['harga'], 0, ',', '.') ?></td>
                                <td>Rp <?= number_format($subtotal, 0, ',', '.') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="3">Total</th>
                            <th>Rp <?= number_format($total, 0, ',', '.') ?></th>
                        </tr>
                    </tfoot>
                </table>
                <div style="margin-top: 20px;">
                    <a href="index.php?page=user-transaksi" class="btn-checkout">Lanjut ke Transaksi</a>
                </div>
            </div>
        <?php endif; ?>
    </div>

</div>

