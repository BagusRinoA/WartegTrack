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
                <span class="nav-icon">👤</span> User
            </a>
            <a href="index.php?page=user-menu">
                <span class="nav-icon">🍽️</span> Menu
            </a>
            <a href="index.php?page=user-hubungi">
                <span class="nav-icon">📞</span> Hubungi
            </a>
            <a href="index.php?page=user-transaksi" class="active">
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
                        <h1 class="main-title">PESANAN SAYA</h1>
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

        <?php if (!empty($user_orders)): ?>
            <div class="orders-section">
                <div class="table-wrapper">
                    <table>
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Menu</th>
                                <th>Jumlah</th>
                                <th>Catatan</th>
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
                                    <td><?= htmlspecialchars($order['catatan'] ?? '-') ?></td>
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
        <?php else: ?>
            <div class="empty-state">
                <p>📭 Anda belum memiliki pesanan. <a href="index.php?page=order">Buat pesanan sekarang</a></p>
            </div>
        <?php endif; ?>
    </div>

</div>

