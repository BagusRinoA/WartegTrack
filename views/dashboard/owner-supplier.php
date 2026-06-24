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
            <a href="index.php?page=owner-supplier" class="active">
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
                        <h1 class="main-title">SUPPLIER</h1>
                        <p class="sub-title">Admin</p>
                    </div>
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

            <button class="btn-tambah-menu" onclick="showAddSupplierForm()">+ Tambah Supplier</button>

            <div class="table-section-menu">
                <div class="table-wrapper">
                    <table class="menu-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama</th>
                                <th>Kontak</th>
                                <th>Alamat</th>
                                <th>Catatan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($suppliers)): ?>
                                <?php $counter = count($suppliers); ?>
                                <?php foreach ($suppliers as $s): ?>
                                    <tr data-id="<?= $s['id'] ?>" data-nama="<?= htmlspecialchars($s['nama']) ?>" data-kontak="<?= htmlspecialchars($s['kontak'] ?? '') ?>" data-alamat="<?= htmlspecialchars($s['alamat'] ?? '') ?>" data-catatan="<?= htmlspecialchars($s['catatan'] ?? '') ?>">
                                        <td><?= $counter-- ?></td>
                                        <td><?= htmlspecialchars($s['nama']) ?></td>
                                        <td><?= htmlspecialchars($s['kontak'] ?? '-') ?></td>
                                        <td><?= htmlspecialchars($s['alamat'] ?? '-') ?></td>
                                        <td><?= htmlspecialchars(substr($s['catatan'] ?? '-', 0, 30)) ?>...</td>
                                        <td>
                                            <button class="btn-edit" onclick="showEditSupplierForm(<?= $s['id'] ?>)">Edit</button>
                                            <button class="btn-delete" onclick="deleteSupplier(<?= $s['id'] ?>)">Hapus</button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="6" style="text-align:center; padding:40px;">Belum ada supplier</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- Modal Tambah Supplier -->
<div id="addSupplierModal" class="modal" style="display:none;">
    <div class="modal-content">
        <div class="modal-header">
            <div class="modal-title-section">
                <span class="title-line">|</span>
                <div>
                    <h2 class="modal-title">Tambah Supplier</h2>
                    <p class="modal-subtitle">Admin</p>
                </div>
            </div>
            <span class="close-btn" onclick="closeAddSupplierForm()">&times;</span>
        </div>
        <form method="post" action="index.php?page=owner-supplier" class="menu-form">
            <input type="hidden" name="action" value="add">
            <div class="form-group"><label>Nama</label><input type="text" name="nama" required></div>
            <div class="form-group"><label>Kontak</label><input type="text" name="kontak"></div>
            <div class="form-group"><label>Alamat</label><textarea name="alamat" rows="3"></textarea></div>
            <div class="form-group"><label>Catatan</label><textarea name="catatan" rows="3"></textarea></div>
            <div class="form-actions"><button type="submit" class="btn-save">Simpan</button><button type="button" class="btn-cancel" onclick="closeAddSupplierForm()">Batal</button></div>
        </form>
    </div>
</div>

<!-- Modal Edit Supplier -->
<div id="editSupplierModal" class="modal" style="display:none;">
    <div class="modal-content">
        <div class="modal-header">
            <div class="modal-title-section">
                <span class="title-line">|</span>
                <div>
                    <h2 class="modal-title">Edit Supplier</h2>
                    <p class="modal-subtitle">Admin</p>
                </div>
            </div>
            <span class="close-btn" onclick="closeEditSupplierForm()">&times;</span>
        </div>
        <form method="post" action="index.php?page=owner-supplier" class="menu-form">
            <input type="hidden" name="action" value="edit">
            <input type="hidden" name="id" id="edit_supplier_id">
            <div class="form-group"><label>Nama</label><input type="text" name="nama" id="edit_supplier_nama" required></div>
            <div class="form-group"><label>Kontak</label><input type="text" name="kontak" id="edit_supplier_kontak"></div>
            <div class="form-group"><label>Alamat</label><textarea name="alamat" id="edit_supplier_alamat" rows="3"></textarea></div>
            <div class="form-group"><label>Catatan</label><textarea name="catatan" id="edit_supplier_catatan" rows="3"></textarea></div>
            <div class="form-actions"><button type="submit" class="btn-save">Update</button><button type="button" class="btn-cancel" onclick="closeEditSupplierForm()">Batal</button></div>
        </form>
    </div>
</div>

<script>
function showAddSupplierForm(){ document.getElementById('addSupplierModal').style.display='flex'; }
function closeAddSupplierForm(){ document.getElementById('addSupplierModal').style.display='none'; }
function showEditSupplierForm(id){
    const row = document.querySelector(`tr[data-id="${id}"]`);
    if(!row) return;
    document.getElementById('edit_supplier_id').value = id;
    document.getElementById('edit_supplier_nama').value = row.dataset.nama || '';
    document.getElementById('edit_supplier_kontak').value = row.dataset.kontak || '';
    document.getElementById('edit_supplier_alamat').value = row.dataset.alamat || '';
    document.getElementById('edit_supplier_catatan').value = row.dataset.catatan || '';
    document.getElementById('editSupplierModal').style.display='flex';
}
function closeEditSupplierForm(){ document.getElementById('editSupplierModal').style.display='none'; }
function deleteSupplier(id){
    if(!confirm('Hapus supplier ini?')) return;
    const f=document.createElement('form');
    f.method='POST';
    f.action='index.php?page=owner-supplier';
    f.innerHTML=`<input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="${id}">`;
    document.body.appendChild(f);
    f.submit();
}
window.onclick = function(e){ if(e.target.classList.contains('modal')) e.target.style.display='none'; }
</script>


