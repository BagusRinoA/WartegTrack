<div class="dashboard-wrapper">

    <!-- SIDEBAR ADMIN -->
    <div class="sidebar">
        <div class="owner-section">
            <div class="owner-box">
                <span class="owner-icon">👤</span>
                <span class="owner-text">ADMIN</span>
            </div>
        </div>
        <nav class="sidebar-nav">
            <a href="index.php?page=admin-dashboard" class="active">
                <span class="nav-icon">🏠</span> Dashboard
            </a>
            <a href="index.php?page=kelola-menu">
                <span class="nav-icon">🍽️</span> Menu
            </a>
            <a href="index.php?page=owner-pembelian">
                <span class="nav-icon">🛒</span> Pembelian
            </a>
            <a href="index.php?page=owner-supplier">
                <span class="nav-icon">📋</span> Supplier
            </a>
            <a href="index.php?page=owner-bahan-baku">
                <span class="nav-icon">⚖️</span> Bahan Baku
            </a>
            <a href="index.php?page=owner-laporan-keuangan">
                <span class="nav-icon">📊</span> Laporan Keuangan
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

    <!-- KONTEN ADMIN -->
    <div class="content">
        <div class="dashboard-header">
            <div class="header-left">
                <div class="title-section">
                    <span class="title-line">|</span>
                    <div>
                        <h1 class="main-title">DASHBOARD</h1>
                        <p class="sub-title">Admin</p>
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

        <!-- Kartu Statistik -->
        <div class="cards" style="padding: 0 40px 30px 40px;">
            <div class="card card-warning">
                <div class="card-icon">💰</div>
                <div class="card-content">
                    <h3>Omzet</h3>
                    <p class="card-number">Rp <?= number_format($stats['omzet'] ?? 0, 0, ',', '.') ?></p>
                </div>
            </div>

            <div class="card card-info">
                <div class="card-icon">📦</div>
                <div class="card-content">
                    <h3>Stok Bahan Baku</h3>
                    <p class="card-number"><?= number_format($stats['stok_bahan_baku'] ?? 0) ?> Unit</p>
                </div>
            </div>

            <div class="card card-success">
                <div class="card-icon">📋</div>
                <div class="card-content">
                    <h3>Transaksi Terbaru</h3>
                    <p class="card-number"><?= number_format($stats['total_orders'] ?? 0) ?> Transaksi</p>
                </div>
            </div>
        </div>

        <!-- Tabel Pesanan Terbaru -->
        <div class="table-section" style="margin: 0 40px 40px 40px;">
            <h2>📋 Pesanan Terbaru</h2>
            <?php if (!empty($recent_orders)): ?>
                <div class="table-wrapper">
                    <table>
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama User</th>
                                <th>Menu</th>
                                <th>Jumlah</th>
                                <th>Total Harga</th>
                                <th>Tanggal</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recent_orders as $index => $order): ?>
                                <tr>
                                    <td><?= $index + 1 ?></td>
                                    <td><?= htmlspecialchars($order['username'] ?? $order['nama'] ?? 'N/A') ?></td>
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
            <?php else: ?>
                <div class="empty-state">
                    <p>📭 Belum ada pesanan. Pesanan akan muncul di sini setelah ada yang memesan.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

</div>
