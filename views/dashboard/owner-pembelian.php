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
            <a href="index.php?page=owner-pembelian" class="active">
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
        </div>

        <div class="menu-content">
            <?php if (!empty($success)): ?>
                <div class="alert-success" style="margin-bottom: 20px;"><?= htmlspecialchars($success) ?></div>
            <?php endif; ?>
            <?php if (!empty($error)): ?>
                <div class="alert-error" style="margin-bottom: 20px;"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <button class="btn-tambah-menu" onclick="showAddPembelianForm()">+ Tambah Pembelian</button>

            <div class="table-section-menu">
                <div class="table-wrapper">
                    <table class="menu-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tanggal</th>
                                <th>Supplier</th>
                                <th>Bahan</th>
                                <th>Kuantitas</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($pembelians)): ?>
                                <?php $counter = count($pembelians); ?>
                                <?php foreach ($pembelians as $p): ?>
                                    <tr data-id="<?= $p['id'] ?>" data-tanggal="<?= htmlspecialchars($p['tanggal']) ?>" data-supplier="<?= htmlspecialchars($p['supplier_nama'] ?? '') ?>" data-supplier-id="<?= $p['supplier_id'] ?? 0 ?>" data-bahan="<?= htmlspecialchars($p['bahan_nama'] ?? '') ?>" data-bahan-id="<?= $p['bahan_baku_id'] ?? 0 ?>" data-kuantitas="<?= $p['kuantitas'] ?>" data-harga="<?= $p['harga_satuan'] ?>" data-total="<?= $p['total_biaya'] ?>" data-status="<?= htmlspecialchars($p['status_pembayaran']) ?>">
                                        <td><?= $counter-- ?></td>
                                        <td><?= htmlspecialchars($p['tanggal']) ?></td>
                                        <td><?= htmlspecialchars($p['supplier_nama'] ?? '') ?></td>
                                        <td><?= htmlspecialchars($p['bahan_nama'] ?? '') ?></td>
                                        <td><?= number_format($p['kuantitas'] ?? 0, 2) ?></td>
                                        <td>Rp <?= number_format($p['total_biaya'] ?? 0, 0) ?></td>
                                        <td><?= htmlspecialchars($p['status_pembayaran'] ?? 'Belum') ?></td>
                                        <td>
                                            <button class="btn-edit" onclick="showEditPembelianForm(<?= $p['id'] ?>)">Edit</button>
                                            <button class="btn-delete" onclick="deletePembelian(<?= $p['id'] ?>)">Hapus</button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="8" style="text-align:center; padding:40px;">Belum ada pembelian</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Pembelian -->
<div id="addPembelianModal" class="modal" style="display:none;">
    <div class="modal-content">
        <div class="modal-header">
            <div class="modal-title-section">
                <span class="title-line">|</span>
                <div>
                    <h2 class="modal-title">Tambah Pembelian</h2>
                    <p class="modal-subtitle">Admin</p>
                </div>
            </div>
            <span class="close-btn" onclick="closeAddPembelianForm()">&times;</span>
        </div>
        <form method="post" action="index.php?page=owner-pembelian" class="menu-form">
            <input type="hidden" name="action" value="add">
            <div class="form-group"><label>Tanggal</label><input type="date" name="tanggal" required></div>
            <div class="form-row">
                <div class="form-group"><label>Supplier</label>
                    <select name="supplier_id" style="width: 100%; padding: 12px 18px; border: 2px solid #E0E0E0; border-radius: 10px; font-size: 14px; font-family: inherit;">
                        <?php foreach ($suppliers as $s): ?>
                            <option value="<?= $s['id'] ?>"><?= htmlspecialchars($s['nama']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group"><label>Bahan Baku</label>
                    <select name="bahan_baku_id" style="width: 100%; padding: 12px 18px; border: 2px solid #E0E0E0; border-radius: 10px; font-size: 14px; font-family: inherit;">
                        <?php foreach ($bahan_bakus as $b): ?>
                            <option value="<?= $b['id'] ?>"><?= htmlspecialchars($b['nama']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group"><label>Kuantitas</label><input type="number" name="kuantitas" step="0.01" value="1" required></div>
                <div class="form-group"><label>Harga Satuan</label><input type="number" name="harga_satuan" step="1" value="0" required></div>
            </div>
            <div class="form-group"><label>Total Biaya</label><input type="number" name="total_biaya" id="add_total_biaya" step="1" value="0" readonly></div>
            <div class="form-group"><label>Status Pembayaran</label>
                <select name="status_pembayaran" style="width: 100%; padding: 12px 18px; border: 2px solid #E0E0E0; border-radius: 10px; font-size: 14px; font-family: inherit;">
                    <option>Belum</option>
                    <option>Sudah</option>
                </select>
            </div>
            <div class="form-actions"><button type="submit" class="btn-save">Simpan</button><button type="button" class="btn-cancel" onclick="closeAddPembelianForm()">Batal</button></div>
        </form>
    </div>
</div>

<!-- Modal Edit Pembelian -->
<div id="editPembelianModal" class="modal" style="display:none;">
    <div class="modal-content">
        <div class="modal-header">
            <div class="modal-title-section">
                <span class="title-line">|</span>
                <div>
                    <h2 class="modal-title">Edit Pembelian</h2>
                    <p class="modal-subtitle">Admin</p>
                </div>
            </div>
            <span class="close-btn" onclick="closeEditPembelianForm()">&times;</span>
        </div>
        <form method="post" action="index.php?page=owner-pembelian" class="menu-form">
            <input type="hidden" name="action" value="edit">
            <input type="hidden" name="id" id="edit_pembelian_id">
            <div class="form-group"><label>Tanggal</label><input type="date" name="tanggal" id="edit_pembelian_tanggal" required></div>
            <div class="form-row">
                <div class="form-group"><label>Supplier</label>
                    <select name="supplier_id" id="edit_pembelian_supplier" style="width: 100%; padding: 12px 18px; border: 2px solid #E0E0E0; border-radius: 10px; font-size: 14px; font-family: inherit;">
                        <?php foreach ($suppliers as $s): ?>
                            <option value="<?= $s['id'] ?>"><?= htmlspecialchars($s['nama']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group"><label>Bahan Baku</label>
                    <select name="bahan_baku_id" id="edit_pembelian_bahan" style="width: 100%; padding: 12px 18px; border: 2px solid #E0E0E0; border-radius: 10px; font-size: 14px; font-family: inherit;">
                        <?php foreach ($bahan_bakus as $b): ?>
                            <option value="<?= $b['id'] ?>"><?= htmlspecialchars($b['nama']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group"><label>Kuantitas</label><input type="number" name="kuantitas" id="edit_pembelian_kuantitas" step="0.01" required></div>
                <div class="form-group"><label>Harga Satuan</label><input type="number" name="harga_satuan" id="edit_pembelian_harga" step="1" required></div>
            </div>
            <div class="form-group"><label>Total Biaya</label><input type="number" name="total_biaya" id="edit_total_biaya" step="1" value="0" readonly></div>
            <div class="form-group"><label>Status Pembayaran</label>
                <select name="status_pembayaran" id="edit_pembelian_status" style="width: 100%; padding: 12px 18px; border: 2px solid #E0E0E0; border-radius: 10px; font-size: 14px; font-family: inherit;">
                    <option>Belum</option>
                    <option>Sudah</option>
                </select>
            </div>
            <div class="form-actions"><button type="submit" class="btn-save">Update</button><button type="button" class="btn-cancel" onclick="closeEditPembelianForm()">Batal</button></div>
        </form>
    </div>
</div>

<script>
function showAddPembelianForm(){ document.getElementById('addPembelianModal').style.display='flex'; }
function closeAddPembelianForm(){ document.getElementById('addPembelianModal').style.display='none'; }
function showEditPembelianForm(id){
    const row = document.querySelector(`tr[data-id="${id}"]`);
    if(!row) return;
    document.getElementById('edit_pembelian_id').value = id;
    document.getElementById('edit_pembelian_tanggal').value = row.dataset.tanggal || '';
    document.getElementById('edit_pembelian_supplier').value = row.dataset.supplierId || 0;
    document.getElementById('edit_pembelian_bahan').value = row.dataset.bahanId || 0;
    document.getElementById('edit_pembelian_kuantitas').value = row.dataset.kuantitas || 0;
    document.getElementById('edit_pembelian_harga').value = row.dataset.harga || 0;
    document.getElementById('edit_total_biaya').value = row.dataset.total || 0;
    document.getElementById('edit_pembelian_status').value = row.dataset.status || 'Belum';
    document.getElementById('editPembelianModal').style.display='flex';
}
function closeEditPembelianForm(){ document.getElementById('editPembelianModal').style.display='none'; }
function deletePembelian(id){ 
    if(!confirm('Hapus pembelian ini?')) return; 
    const f=document.createElement('form'); 
    f.method='POST'; 
    f.action='index.php?page=owner-pembelian'; 
    f.innerHTML=`<input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="${id}">`; 
    document.body.appendChild(f); 
    f.submit(); 
}
const addQty = document.querySelector('form[action*="owner-pembelian"] input[name="kuantitas"]');
const addHarga = document.querySelector('form[action*="owner-pembelian"] input[name="harga_satuan"]');
if(addQty && addHarga){
    function calcAddTotal(){ const q = parseFloat(addQty.value||0); const h = parseFloat(addHarga.value||0); document.getElementById('add_total_biaya').value = Math.round(q*h); }
    addQty.addEventListener('input', calcAddTotal); addHarga.addEventListener('input', calcAddTotal);
}
const editQty = document.getElementById('edit_pembelian_kuantitas');
const editHarga = document.getElementById('edit_pembelian_harga');
if(editQty && editHarga){
    function calcEditTotal(){ const q = parseFloat(editQty.value||0); const h = parseFloat(editHarga.value||0); document.getElementById('edit_total_biaya').value = Math.round(q*h); }
    editQty.addEventListener('input', calcEditTotal); editHarga.addEventListener('input', calcEditTotal);
}
window.onclick = function(e){ if(e.target.classList.contains('modal')) e.target.style.display='none'; }
</script>






