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
                        <h1 class="main-title">KELOLA USER</h1>
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
                <img src="logo/logo-WT-no-bg.png" alt="WartegTrack" class="header-logo">
                <span class="header-brand">WartegTrack</span>
            </div>
        </div>

        <div class="menu-content">
            <div class="table-section-menu">
            <?php if (!empty($users)): ?>
                <div class="table-wrapper">
                    <table class="menu-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Tanggal Daftar</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $index => $user): ?>
                                <tr>
                                    <td><?= $index + 1 ?></td>
                                    <td><?= htmlspecialchars($user['username'] ?? 'N/A') ?></td>
                                    <td><?= htmlspecialchars($user['email'] ?? 'N/A') ?></td>
                                    <td>
                                        <span class="status-badge status-<?= strtolower($user['role'] ?? 'user') ?>">
                                            <?= htmlspecialchars($user['role'] ?? 'User') ?>
                                        </span>
                                    </td>
                                    <td><?= isset($user['created_at']) ? date('d/m/Y H:i', strtotime($user['created_at'])) : 'N/A' ?></td>
                                    <td>
                                        <button class="btn-edit" onclick="alert('Fitur edit akan segera tersedia')">Edit</button>
                                        <button class="btn-delete" onclick="alert('Fitur hapus akan segera tersedia')">Hapus</button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <p>📭 Belum ada user.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

</div>

