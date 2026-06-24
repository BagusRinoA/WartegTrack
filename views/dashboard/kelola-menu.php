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
            <a href="index.php?page=kelola-menu" class="active">
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
                        <h1 class="main-title">DATA MENU</h1>
                        <p class="sub-title">Admin</p>
                    </div>
                </div>
            </div>
            <div class="header-center">
                <div class="search-box-header">
                    <span class="search-icon">🔍</span>
                    <input type="text" id="searchMenuTable" placeholder="Search..." class="search-input" onkeyup="searchTable('searchMenuTable', 'menu-table')">
                </div>
            </div>
        </div>

        <div class="menu-content">
            <?php if (!empty($success)): ?>
                <div class="alert-success" style="margin-bottom: 20px;"><?= htmlspecialchars($success) ?></div>
            <?php endif; ?>
            
            <?php if (!empty($error)): ?>
                <div class="alert-error" style="margin-bottom: 20px;"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            
            <button class="btn-tambah-menu" onclick="showAddMenuForm()">+ Tambah Menu</button>
            
            <div class="table-section-menu">
                <div class="table-wrapper">
                    <table class="menu-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama Produk</th>
                                <th>Harga</th>
                                <th>Stok</th>
                                <th>Kategori</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>

                        <!-- ============================= -->
                        <!--   TABEL BODY (DESCENDING ID)   -->
                        <!-- ============================= -->
                        <tbody>
                            <?php if (!empty($menus)): ?>

                                <?php 
                                // Hitung jumlah menu -> jadi ID descending
                                $counter = count($menus);
                                ?>

                                <?php foreach ($menus as $menu): ?>
                                    <tr data-menu-id="<?= $menu['id'] ?>"
                                        data-menu-nama="<?= htmlspecialchars($menu['nama_produk']) ?>"
                                        data-menu-harga="<?= $menu['harga'] ?>"
                                        data-menu-stok="<?= $menu['stok'] ?>"
                                        data-menu-kategori="<?= htmlspecialchars($menu['kategori']) ?>"
                                        data-menu-deskripsi="<?= htmlspecialchars($menu['deskripsi']) ?>">

                                        <!-- AUTO ID DESCENDING -->
                                        <td><?= $counter-- ?></td>

                                        <td><?= htmlspecialchars($menu['nama_produk']) ?></td>
                                        <td>Rp <?= number_format($menu['harga'], 0, ',', '.') ?></td>
                                        <td><?= $menu['stok'] ?></td>
                                        <td><?= htmlspecialchars($menu['kategori']) ?></td>
                                        <td>
                                            <button class="btn-edit" onclick="showEditMenuForm(<?= $menu['id'] ?>)">Edit</button>
                                            <button class="btn-delete" onclick="deleteMenu(<?= $menu['id'] ?>)">Hapus</button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>

                            <?php else: ?>
                                <tr>
                                    <td colspan="6" style="text-align: center; padding: 40px;">Belum ada data menu</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>

                    </table>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- MODAL TAMBAH MENU -->
<div id="addMenuModal" class="modal" style="display: none;">
    <div class="modal-content">
        <div class="modal-header">
            <div class="modal-title-section">
                <span class="title-line">|</span>
                <div>
                    <h2 class="modal-title">Tambah Menu Baru</h2>
                    <p class="modal-subtitle">Admin</p>
                </div>
            </div>
            <span class="close-btn" onclick="closeAddMenuForm()">&times;</span>
        </div>
        <form class="menu-form" method="post" action="index.php?page=kelola-menu">
            <input type="hidden" name="action" value="add">
            <div class="form-group">
                <label>Nama Produk</label>
                <input type="text" name="nama_produk" required>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Harga (Rp)</label>
                    <input type="number" name="harga" min="1" required>
                </div>
                <div class="form-group">
                    <label>Stok</label>
                    <input type="number" name="stok" min="0" required>
                </div>
            </div>
            <div class="form-group">
                <label>Kategori</label>
                <input type="text" name="kategori" required>
            </div>
            <div class="form-group">
                <label>Deskripsi</label>
                <textarea name="deskripsi" rows="4"></textarea>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn-save">Tambah Menu</button>
                <button type="button" class="btn-cancel" onclick="closeAddMenuForm()">Batal</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL EDIT MENU -->
<div id="editMenuModal" class="modal" style="display: none;">
    <div class="modal-content">
        <div class="modal-header">
            <div class="modal-title-section">
                <span class="title-line">|</span>
                <div>
                    <h2 class="modal-title">Edit Menu</h2>
                    <p class="modal-subtitle">Admin</p>
                </div>
            </div>
            <span class="close-btn" onclick="closeEditMenuForm()">&times;</span>
        </div>
        <form class="menu-form" method="post" action="index.php?page=kelola-menu">
            <input type="hidden" name="action" value="edit">
            <input type="hidden" name="id" id="edit_menu_id">

            <div class="form-group">
                <label>Nama Produk</label>
                <input type="text" name="nama_produk" id="edit_nama" required>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Harga (Rp)</label>
                    <input type="number" name="harga" id="edit_harga" min="1" required>
                </div>
                <div class="form-group">
                    <label>Stok</label>
                    <input type="number" name="stok" id="edit_stok" min="0" required>
                </div>
            </div>
            <div class="form-group">
                <label>Kategori</label>
                <input type="text" name="kategori" id="edit_kategori" required>
            </div>
            <div class="form-group">
                <label>Deskripsi</label>
                <textarea name="deskripsi" id="edit_deskripsi" rows="4"></textarea>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-save">Update Menu</button>
                <button type="button" class="btn-cancel" onclick="closeEditMenuForm()">Batal</button>
            </div>
        </form>
    </div>
</div>

<script>
function showAddMenuForm() {
    document.getElementById('addMenuModal').style.display = 'flex';
}

function closeAddMenuForm() {
    document.getElementById('addMenuModal').style.display = 'none';
}

function showEditMenuForm(id) {
    const row = document.querySelector(`tr[data-menu-id="${id}"]`);
    if (!row) return;

    document.getElementById('edit_menu_id').value = row.dataset.menuId;
    document.getElementById('edit_nama').value = row.dataset.menuNama;
    document.getElementById('edit_harga').value = row.dataset.menuHarga;
    document.getElementById('edit_stok').value = row.dataset.menuStok;
    document.getElementById('edit_kategori').value = row.dataset.menuKategori;
    document.getElementById('edit_deskripsi').value = row.dataset.menuDeskripsi;

    document.getElementById('editMenuModal').style.display = 'flex';
}

function closeEditMenuForm() {
    document.getElementById('editMenuModal').style.display = 'none';
}

function deleteMenu(id) {
    if (confirm('Hapus menu ini?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = 'index.php?page=kelola-menu';

        form.innerHTML = `
            <input type="hidden" name="action" value="delete">
            <input type="hidden" name="id" value="${id}">
        `;

        document.body.appendChild(form);
        form.submit();
    }
}

window.onclick = function(event) {
    const addModal = document.getElementById('addMenuModal');
    const editModal = document.getElementById('editMenuModal');

    if (event.target == addModal) closeAddMenuForm();
    if (event.target == editModal) closeEditMenuForm();
}

function searchTable(inputId, tableClass) {
    const input = document.getElementById(inputId).value.toLowerCase();
    const rows = document.querySelectorAll(`.${tableClass} tbody tr`);

    rows.forEach(row => {
        const cells = row.querySelectorAll("td");
        let found = false;

        for (let i = 0; i < cells.length - 1; i++) {
            if (cells[i].textContent.toLowerCase().includes(input)) {
                found = true;
                break;
            }
        }

        row.style.display = found ? "" : "none";
    });
}
</script>