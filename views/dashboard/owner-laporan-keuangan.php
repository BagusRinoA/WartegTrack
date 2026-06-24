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
            <a href="index.php?page=owner-laporan-keuangan" class="active">
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
                        <h1 class="main-title">LAPORAN KEUANGAN</h1>
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

        <div class="menu-content">
            <div class="report-print">
                <!-- Filter Periode -->
                <div class="filter-section print-exclude">
                    <form method="get" class="filter-row">
                        <input type="hidden" name="page" value="owner-laporan-keuangan">
                        <div class="filter-field">
                            <label>Periode Waktu</label>
                            <select name="periode">
                                <option value="harian" <?= ($periode ?? '') == 'harian' ? 'selected' : '' ?>>Harian</option>
                                <option value="mingguan" <?= ($periode ?? '') == 'mingguan' ? 'selected' : '' ?>>Mingguan</option>
                                <option value="bulanan" <?= ($periode ?? 'bulanan') == 'bulanan' ? 'selected' : '' ?>>Bulanan</option>
                                <option value="kustom" <?= ($periode ?? '') == 'kustom' ? 'selected' : '' ?>>Kustom</option>
                            </select>
                        </div>
                        <div class="filter-field">
                            <label>Tanggal Mulai</label>
                            <input type="date" name="start_date" value="<?= $start_date ?? date('Y-m-01') ?>">
                        </div>
                        <div class="filter-field">
                            <label>Tanggal Akhir</label>
                            <input type="date" name="end_date" value="<?= $end_date ?? date('Y-m-t') ?>">
                        </div>
                        <div class="filter-actions no-print">
                            <button type="button" onclick="resetFilter()" class="btn-reset">Reset</button>
                            <button type="submit" class="btn-filter">Filter</button>
                            <button type="button" onclick="exportPDF()" class="btn-export">Ekspor PDF</button>
                        </div>
                    </form>
                </div>

                <!-- Ringkasan Keuangan -->
                <div class="cards print-exclude" style="margin-bottom: 30px;">
                    <div class="card card-warning">
                        <div class="card-icon">💰</div>
                        <div class="card-content">
                            <h3>Total Omzet</h3>
                            <p class="card-number">Rp <?= number_format($omzet ?? 0, 0, ',', '.') ?></p>
                        </div>
                    </div>
                    <div class="card card-danger">
                        <div class="card-icon">📉</div>
                        <div class="card-content">
                            <h3>Total Pengeluaran</h3>
                            <p class="card-number">Rp <?= number_format($pengeluaran ?? 0, 0, ',', '.') ?></p>
                        </div>
                    </div>
                    <div class="card card-success">
                        <div class="card-icon">📈</div>
                        <div class="card-content">
                            <h3>Laba Bersih</h3>
                            <p class="card-number">Rp <?= number_format($laba_bersih ?? 0, 0, ',', '.') ?></p>
                        </div>
                    </div>
                </div>

                <!-- Tabel Transaksi Penjualan -->
                <div class="table-section-menu" style="margin-bottom: 25px;">
                    <h2 style="margin-bottom: 15px;">Detail Transaksi Penjualan (Pemasukan)</h2>
                    <div class="table-wrapper">
                        <table class="menu-table">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>ID Transaksi</th>
                                    <th>Total Harga</th>
                                    <th>Status</th>
                                    <th class="no-print">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($penjualan)): ?>
                                    <?php foreach ($penjualan as $p): ?>
                                        <tr>
                                            <td><?= date('d/m/Y', strtotime($p['created_at'])) ?></td>
                                            <td><?= $p['id'] ?></td>
                                            <td>Rp <?= number_format($p['total_harga'], 0, ',', '.') ?></td>
                                            <td>
                                                <span class="status-badge status-<?= strtolower($p['status']) ?>">
                                                    <?= htmlspecialchars($p['status']) ?>
                                                </span>
                                            </td>
                                            <td class="no-print">
                                                <?php if (strtolower($p['status']) === 'pending'): ?>
                                                    <form method="post" style="display:inline;">
                                                        <input type="hidden" name="update_order_status" value="1">
                                                        <input type="hidden" name="order_id" value="<?= $p['id'] ?>">
                                                        <input type="hidden" name="new_status" value="complete">
                                                        <button type="submit" class="btn-primary" style="padding:8px 12px;">Tandai Complete</button>
                                                    </form>
                                                <?php else: ?>
                                                    <span style="color:#2ecc71; font-weight:600;">✔</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" style="text-align: center; padding: 40px;">Belum ada transaksi penjualan</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Tabel Pengeluaran -->
                <div class="table-section-menu print-exclude">
                    <h2 style="margin-bottom: 15px;">Detail Pengeluaran (Pembelian Bahan Baku)</h2>
                    <div class="table-wrapper">
                        <table class="menu-table">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>ID Faktur</th>
                                    <th>Nama Supplier</th>
                                    <th>Total Biaya</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($pembelians)): ?>
                                    <?php foreach ($pembelians as $p): ?>
                                        <tr>
                                            <td><?= date('d/m/Y', strtotime($p['tanggal'])) ?></td>
                                            <td>#<?= $p['id'] ?></td>
                                            <td><?= htmlspecialchars($p['supplier_nama'] ?? 'N/A') ?></td>
                                            <td>Rp <?= number_format($p['total_biaya'], 0, ',', '.') ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="4" style="text-align: center; padding: 40px;">Belum ada pengeluaran</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<style>.filter-actions {
    display: flex;
    gap: 10px;
    margin-top: 10px;
}

.btn-reset,
.btn-filter,
.btn-export {
    padding: 10px 20px;
    border: 1px solid #ddd;
    background: #f5f5f5;
    color: #333;
    cursor: pointer;
    border-radius: 5px;
    font-size: 14px;
}

.btn-filter {
    background: #667eea;
    color: white;
    border-color: #667eea;
}

.btn-reset,
.btn-export {
    background: #f5f5f5;
    color: #333;
}

.btn-reset:hover,
.btn-export:hover {
    background: #e8e8e8;
}

.btn-filter:hover {
    background: #5568d3;
}

@media print {
    .print-exclude {
        display: none;
    }
    .no-print {
        display: none;
    }
}

@media print {
    .print-exclude {
        display: none;
    }
    .no-print {
        display: none;
    }
}
</style>

<script>
/**
 * Filter & Export PDF Functions
 */

// ========================================
// 1. RESET FILTER
// ========================================
function resetFilter() {
    const today = new Date();
    const firstDay = new Date(today.getFullYear(), today.getMonth(), 1);
    const lastDay = new Date(today.getFullYear(), today.getMonth() + 1, 0);
    
    const formatDate = (date) => date.toISOString().split('T')[0];
    
    // Redirect dengan filter default (bulan ini)
    window.location.href = '?page=owner-laporan-keuangan&periode=bulanan&start_date=' + 
        formatDate(firstDay) + '&end_date=' + formatDate(lastDay);
}

// ========================================
// 2. AUTO-UPDATE TANGGAL BERDASARKAN PERIODE
// ========================================
document.addEventListener('DOMContentLoaded', function() {
    const periodeSelect = document.querySelector('select[name="periode"]');
    const startDateInput = document.querySelector('input[name="start_date"]');
    const endDateInput = document.querySelector('input[name="end_date"]');
    
    if (periodeSelect && startDateInput && endDateInput) {
        periodeSelect.addEventListener('change', function() {
            const periode = this.value;
            const today = new Date();
            
            let startDate, endDate;
            
            switch(periode) {
                case 'harian':
                    // Tanggal hari ini
                    startDate = today;
                    endDate = today;
                    break;
                    
                case 'mingguan':
                    // 7 hari terakhir
                    startDate = new Date(today.getTime() - 7 * 24 * 60 * 60 * 1000);
                    endDate = today;
                    break;
                    
                case 'bulanan':
                    // Bulan ini (tanggal 1 sampai akhir bulan)
                    startDate = new Date(today.getFullYear(), today.getMonth(), 1);
                    endDate = new Date(today.getFullYear(), today.getMonth() + 1, 0);
                    break;
                    
                case 'kustom':
                    // Keep current values (user input manual)
                    return;
                    
                default:
                    return;
            }
            
            // Format date ke YYYY-MM-DD
            startDateInput.value = formatDateForInput(startDate);
            endDateInput.value = formatDateForInput(endDate);
        });
    }
});

// Helper function: Format date untuk input type="date"
function formatDateForInput(date) {
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');
    return `${year}-${month}-${day}`;
}

// ========================================
// 3. EXPORT PDF dengan window.print()
// ========================================
function exportPDF() {
    // Set print title
    const originalTitle = document.title;
    document.title = 'Laporan Keuangan WartegTrack';
    
    // Print
    window.print();
    
    // Restore title
    document.title = originalTitle;
}

// ========================================
// 4. VALIDASI FILTER SEBELUM SUBMIT
// ========================================
document.addEventListener('DOMContentLoaded', function() {
    const filterForm = document.querySelector('.filter-row');
    
    if (filterForm) {
        filterForm.addEventListener('submit', function(e) {
            const startDate = new Date(document.querySelector('input[name="start_date"]').value);
            const endDate = new Date(document.querySelector('input[name="end_date"]').value);
            
            // Cek apakah tanggal mulai > tanggal akhir
            if (startDate > endDate) {
                e.preventDefault();
                alert('⚠️ Tanggal mulai tidak boleh lebih besar dari tanggal akhir!');
                return false;
            }
            
            // Cek apakah tanggal lebih dari 1 tahun (optional, buat loading lebih cepat)
            const diffTime = Math.abs(endDate - startDate);
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
            
            if (diffDays > 365) {
                if (!confirm('⚠️ Range tanggal lebih dari 1 tahun. Ini bisa membuat query lambat. Lanjutkan?')) {
                    e.preventDefault();
                    return false;
                }
            }
        });
    }
});

// ========================================
// 5. PRINT-FRIENDLY STYLING
// ========================================
// Kode di atas sudah di-CSS dengan @media print
// Tapi bisa tambah JavaScript untuk hide/show elemen saat print

window.addEventListener('beforeprint', function() {
    // Bisa add custom logic sebelum print
    console.log('📄 Preparing PDF export...');
});

window.addEventListener('afterprint', function() {
    // Logic setelah print dialog ditutup
    console.log('✅ PDF export selesai');
});

// ========================================
// 6. SHORTCUT FILTER VIA URL
// ========================================
function applyQuickFilter(days, periode) {
    const today = new Date();
    let startDate = new Date(today.getTime() - days * 24 * 60 * 60 * 1000);
    const endDate = today;
    
    const formatDate = (date) => {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    };
    
    window.location.href = `?page=owner-laporan-keuangan&periode=${periode}&start_date=${formatDate(startDate)}&end_date=${formatDate(endDate)}`;
}
</script>
