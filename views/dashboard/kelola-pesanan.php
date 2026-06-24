<div class="dashboard-wrapper">

    <div class="sidebar">
        <div class="owner-section">
            <div class="owner-box">
                <span class="owner-icon">👤</span>
                <span class="owner-text">ADMIN</span>
            </div>
        </div>
        <nav class="sidebar-nav">
            <a href="index.php?page=admin-dashboard">
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

    <div class="content">
        <div class="dashboard-header">
            <div class="header-left">
                <div class="title-section">
                    <span class="title-line">|</span>
                    <div>
                        <h1 class="main-title">PEMBELIAN</h1>
                        <p class="sub-title">Admin</p>
                    </div>
                </div>
            </div>
            <div class="header-center">
                <div class="search-box-header">
                    <span class="search-icon">🔍</span>
                    <input type="text" id="searchUserTable" placeholder="Search..." class="search-input" onkeyup="searchTable('searchUserTable', 'menu-table')">
                </div>
            </div>
            <div class="header-right">
                <img src="logo/logo-WT-no-bg.png" alt="WartegTrack" class="header-logo">
                <span class="header-brand">WartegTrack</span>
            </div>
        </div>

        <div class="menu-content">
            <div class="table-section-menu">
            <?php if (!empty($orders)): ?>
                <div class="table-wrapper">
                    <table class="menu-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Pemesan</th>
                                <th>Username</th>
                                <th>Menu</th>
                                <th>Jumlah</th>
                                <th>Total Harga</th>
                                <th>Tanggal</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($orders as $index => $order): ?>
                                <tr>
                                    <td><?= $index + 1 ?></td>
                                    <td><?= htmlspecialchars($order['nama'] ?? 'N/A') ?></td>
                                    <td><?= htmlspecialchars($order['username'] ?? 'Guest') ?></td>
                                    <td><?= htmlspecialchars($order['menu'] ?? 'N/A') ?></td>
                                    <td><?= htmlspecialchars($order['jumlah'] ?? 'N/A') ?></td>
                                    <td>Rp <?= number_format($order['total_harga'] ?? 0, 0, ',', '.') ?></td>
                                    <td><?= isset($order['created_at']) ? date('d/m/Y H:i', strtotime($order['created_at'])) : 'N/A' ?></td>
                                    <td>
                                        <span class="status-badge status-<?= strtolower($order['status'] ?? 'pending') ?>">
                                            <?= htmlspecialchars($order['status'] ?? 'Pending') ?>
                                        </span>
                                    </td>
                                    <td>
                                        <button class="btn-edit" onclick="alert('Fitur edit status akan segera tersedia')">Ubah Status</button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <p>📭 Belum ada pesanan.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <script>
// Fungsi search universal untuk tabel
function searchTable(inputId, tableClass) {
    const input = document.getElementById(inputId);
    const filter = input.value.toLowerCase();
    const table = document.querySelector('.' + tableClass);
    
    if (!table) return;
    
    const tbody = table.querySelector('tbody');
    if (!tbody) return;
    
    const rows = tbody.getElementsByTagName('tr');
    
    for (let i = 0; i < rows.length; i++) {
        const row = rows[i];
        const cells = row.getElementsByTagName('td');
        let found = false;
        
        // Cari di semua kolom kecuali kolom terakhir (Aksi)
        for (let j = 0; j < cells.length - 1; j++) {
            const cellText = cells[j].textContent || cells[j].innerText;
            if (cellText.toLowerCase().indexOf(filter) > -1) {
                found = true;
                break;
            }
        }
        
        row.style.display = found ? '' : 'none';
    }
}
</script>