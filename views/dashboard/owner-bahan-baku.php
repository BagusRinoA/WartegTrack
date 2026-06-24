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
            <a href="index.php?page=owner-bahan-baku" class="active">
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
                        <h1 class="main-title">BAHAN BAKU</h1>
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

            <button class="btn-tambah-menu" onclick="showAddBahanForm()">+ Tambah Bahan Baku</button>

            <div class="table-section-menu">
                <div class="table-wrapper">
                    <table class="menu-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama</th>
                                <th>Unit</th>
                                <th>Stok Saat Ini</th>
                                <th>Stok Minimum</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($bahan_bakus)): ?>
                                <?php $counter = count($bahan_bakus); ?>
                                <?php foreach ($bahan_bakus as $bb): ?>
                                    <tr data-id="<?= $bb['id'] ?>" data-nama="<?= htmlspecialchars($bb['nama']) ?>" data-unit="<?= htmlspecialchars($bb['unit_satuan'] ?? 'Kg') ?>" data-stok="<?= $bb['stok_saat_ini'] ?>" data-min="<?= $bb['stok_minimum'] ?>">
                                        <td><?= $counter-- ?></td>
                                        <td><?= htmlspecialchars($bb['nama']) ?></td>
                                        <td><?= htmlspecialchars($bb['unit_satuan'] ?? 'Kg') ?></td>
                                        <td><?= number_format($bb['stok_saat_ini'] ?? 0, 2) ?></td>
                                        <td><?= number_format($bb['stok_minimum'] ?? 0, 2) ?></td>
                                        <td>
                                            <button class="btn-edit" onclick="showEditBahanForm(<?= $bb['id'] ?>)">Edit</button>
                                            <button class="btn-delete" onclick="deleteBahan(<?= $bb['id'] ?>)">Hapus</button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="6" style="text-align:center; padding:40px;">Belum ada bahan baku</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Bahan -->
<div id="addBahanModal" class="modal" style="display:none;">
    <div class="modal-content">
        <div class="modal-header">
            <div class="modal-title-section">
                <span class="title-line">|</span>
                <div>
                    <h2 class="modal-title">Tambah Bahan Baku</h2>
                    <p class="modal-subtitle">Admin</p>
                </div>
            </div>
            <span class="close-btn" onclick="closeAddBahanForm()">&times;</span>
        </div>
        <form method="post" action="index.php?page=owner-bahan-baku" class="menu-form">
            <input type="hidden" name="action" value="add">
            <div class="form-group"><label>Nama</label><input type="text" name="nama" required></div>
            <div class="form-row">
                <div class="form-group"><label>Unit Satuan</label>
                    <select name="unit_satuan" style="width: 100%; padding: 12px 18px; border: 2px solid #E0E0E0; border-radius: 10px; font-size: 14px; font-family: inherit;">
                        <option>Kg</option><option>Ikat</option><option>Pcs</option><option>Liter</option><option>Gram</option>
                    </select>
                </div>
                <div class="form-group"><label>Stok Awal</label><input type="number" name="stok_awal" step="0.01" value="0" required></div>
            </div>
            <div class="form-group"><label>Stok Minimum</label><input type="number" name="stok_minimum" step="0.01" value="0"></div>
            <div class="form-actions"><button type="submit" class="btn-save">Simpan</button><button type="button" class="btn-cancel" onclick="closeAddBahanForm()">Batal</button></div>
        </form>
    </div>
</div>

<!-- Modal Edit Bahan -->
<div id="editBahanModal" class="modal" style="display:none;">
    <div class="modal-content">
        <div class="modal-header">
            <div class="modal-title-section">
                <span class="title-line">|</span>
                <div>
                    <h2 class="modal-title">Edit Bahan Baku</h2>
                    <p class="modal-subtitle">Admin</p>
                </div>
            </div>
            <span class="close-btn" onclick="closeEditBahanForm()">&times;</span>
        </div>
        <form method="post" action="index.php?page=owner-bahan-baku" class="menu-form">
            <input type="hidden" name="action" value="edit">
            <input type="hidden" name="id" id="edit_bahan_id">
            <div class="form-group"><label>Nama</label><input type="text" name="nama" id="edit_bahan_nama" required></div>
            <div class="form-row">
                <div class="form-group"><label>Unit Satuan</label>
                    <select name="unit_satuan" id="edit_bahan_unit" style="width: 100%; padding: 12px 18px; border: 2px solid #E0E0E0; border-radius: 10px; font-size: 14px; font-family: inherit;">
                        <option>Kg</option><option>Ikat</option><option>Pcs</option><option>Liter</option><option>Gram</option>
                    </select>
                </div>
                <div class="form-group"><label>Stok Saat Ini</label><input type="number" name="stok_saat_ini" id="edit_bahan_stok" step="0.01" required></div>
            </div>
            <div class="form-group"><label>Stok Minimum</label><input type="number" name="stok_minimum" id="edit_bahan_min" step="0.01"></div>
            <div class="form-actions"><button type="submit" class="btn-save">Update</button><button type="button" class="btn-cancel" onclick="closeEditBahanForm()">Batal</button></div>
        </form>
    </div>
</div>

<script>
function showAddBahanForm(){ document.getElementById('addBahanModal').style.display='flex'; }
function closeAddBahanForm(){ document.getElementById('addBahanModal').style.display='none'; }
function showEditBahanForm(id){
    const row = document.querySelector(`tr[data-id="${id}"]`);
    if(!row) return;
    document.getElementById('edit_bahan_id').value = id;
    document.getElementById('edit_bahan_nama').value = row.dataset.nama || '';
    document.getElementById('edit_bahan_unit').value = row.dataset.unit || 'Kg';
    document.getElementById('edit_bahan_stok').value = row.dataset.stok || 0;
    document.getElementById('edit_bahan_min').value = row.dataset.min || 0;
    document.getElementById('editBahanModal').style.display='flex';
}
function closeEditBahanForm(){ document.getElementById('editBahanModal').style.display='none'; }
function deleteBahan(id){ 
    if(!confirm('Hapus bahan baku ini?')) return; 
    const f=document.createElement('form'); 
    f.method='POST'; 
    f.action='index.php?page=owner-bahan-baku'; 
    f.innerHTML=`<input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="${id}">`; 
    document.body.appendChild(f); 
    f.submit(); 
}
window.onclick = function(e){ if(e.target.classList.contains('modal')) e.target.style.display='none'; }
</script>




