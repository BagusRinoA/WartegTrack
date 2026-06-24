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
                        <h1 class="main-title">TRANSAKSI</h1>
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

        <?php if (!empty($_SESSION['success'])): ?>
            <div class="alert-success"><?= htmlspecialchars($_SESSION['success']) ?></div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <?php if (!empty($_SESSION['error'])): ?>
            <div class="alert-error"><?= htmlspecialchars($_SESSION['error']) ?></div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <?php if (!empty($_SESSION['cart'])): ?>
            <div class="checkout-section">
                <h2>Ringkasan Pesanan</h2>
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
                            <th colspan="3">Total Harga</th>
                            <th>Rp <?= number_format($total, 0, ',', '.') ?></th>
                        </tr>
                    </tfoot>
                </table>
                <form method="post" style="margin-top: 20px;">
                    <button type="submit" name="checkout" class="btn-checkout">Selesaikan Pembayaran</button>
                </form>
            </div>
        <?php endif; ?>

        <div class="table-section">
            <h2>Riwayat Transaksi</h2>
            <?php if (!empty($transactions)): ?>
                <div class="table-wrapper" style="margin-top: 20px;">
                    <table class="menu-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tanggal</th>
                                <th>Menu</th>
                                <th>Jumlah</th>
                                <th>Total Harga</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($transactions as $trans): ?>
                                <tr>
                                    <td>#<?= $trans['id'] ?></td>
                                    <td><?= date('d/m/Y H:i', strtotime($trans['created_at'])) ?></td>
                                    <td><?= htmlspecialchars($trans['menu']) ?></td>
                                    <td><?= $trans['jumlah'] ?></td>
                                    <td>Rp <?= number_format($trans['total_harga'], 0, ',', '.') ?></td>
                                    <td>
                                        <span class="status-badge status-<?= strtolower($trans['status']) ?>">
                                            <?= htmlspecialchars($trans['status']) ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <p>📭 Belum ada transaksi.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

</div>

