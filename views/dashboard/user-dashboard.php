<div class="dashboard-wrapper">

    <div class="sidebar">
        <div class="owner-section">
            <div class="owner-box">
                <span class="owner-icon">👤</span>
                <span class="owner-text">USER</span>
            </div>
        </div>
        <nav class="sidebar-nav">
            <a href="index.php?page=user-dashboard" class="active">
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
                        <h1 class="main-title">DASHBOARD</h1>
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

        <!-- Quick Stats -->
        <div class="user-cards">
            <div class="user-card">
                <div class="user-card-icon">📦</div>
                <div class="user-card-content">
                    <h3>Pesanan Saya</h3>
                    <p><?= count($user_orders ?? []) ?> Pesanan</p>
                </div>
            </div>
            <div class="user-card">
                <div class="user-card-icon">🍽️</div>
                <div class="user-card-content">
                    <h3>Menu Tersedia</h3>
                    <p><?= count($menus ?? []) ?> Menu</p>
                </div>
            </div>
        </div>

        <!-- Menu Populer -->
        <div class="menu-section">
            <h2>🍽️ Menu Populer</h2>
            <?php if (!empty($menus)): ?>
                <div class="menu-grid-dashboard">
                    <?php foreach (array_slice($menus, 0, 6) as $menu): ?>
                        <div class="menu-card-dashboard">
                            <?php if (!empty($menu['gambar'])): ?>
                                <div class="menu-card-img">
                                    <img src="<?= htmlspecialchars($menu['gambar']) ?>" alt="<?= htmlspecialchars($menu['nama'] ?? 'Menu') ?>">
                                </div>
                            <?php endif; ?>
                            <div class="menu-card-body">
                                <h3 class="menu-card-title"><?= htmlspecialchars($menu['nama'] ?? 'Menu') ?></h3>
                                <p class="menu-card-harga">Rp <?= number_format($menu['harga'] ?? 0, 0, ',', '.') ?></p>
                                <?php if (!empty($menu['deskripsi'])): ?>
                                    <p class="menu-card-desk"><?= htmlspecialchars(substr($menu['deskripsi'], 0, 50)) ?>...</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="view-all-link">
                    <a href="index.php?page=menu" class="btn-view-all">Lihat Semua Menu →</a>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <p>📭 Belum ada menu tersedia.</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Pesanan Terbaru User -->
        <?php if (!empty($user_orders)): ?>
            <div class="orders-section">
                <h2>📋 Pesanan Terbaru Saya</h2>
                <div class="table-wrapper">
                    <table>
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Menu</th>
                                <th>Jumlah</th>
                                <th>Total Harga</th>
                                <th>Tanggal</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($user_orders as $index => $order): ?>
                                <tr>
                                    <td><?= $index + 1 ?></td>
                                    <td><?= htmlspecialchars($order['menu'] ?? 'N/A') ?></td>
                                    <td><?= htmlspecialchars($order['jumlah'] ?? 'N/A') ?></td>
                                    <td>Rp <?= number_format($order['total_harga'] ?? 0, 0, ',', '.') ?></td>
                                    <td><?= isset($order['created_at']) ? date('d/m/Y H:i', strtotime($order['created_at'])) : 'N/A' ?></td>
                                    <td>
                                        <span class="status-badge status-<?= strtolower($order['status'] ?? 'pending') ?>">
                                            <?= htmlspecialchars($order['status'] ?? 'Pending') ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endif; ?>
    </div>

</div>
