<div class="order-container">
    <h1 class="page-title">Form Pemesanan Katering</h1>
    
    <?php if (!empty($error)): ?>
      <div class="alert-error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    
    <?php if (!empty($success)): ?>
      <div class="alert-success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>
    
    <form class="order-form" method="post">
        <input type="text" name="nama" placeholder="Nama Pemesan" value="<?= htmlspecialchars($defaultNama ?? '') ?>" required><br>
        <input type="text" name="menu" placeholder="Paket / Menu Katering yang Dipesan" required><br>
        <input type="number" name="jumlah" min="1" placeholder="Jumlah Porsi (Min. pemesanan biasanya berlaku)" required><br>
        <input type="date" name="tanggal_pengiriman" placeholder="Tanggal Pengiriman" required><br>
        <textarea name="alamat_pengiriman" placeholder="Alamat Pengiriman Katering Lengkap" required></textarea><br>
        <textarea name="catatan" placeholder="Catatan Tambahan (Misal: Tingkat pedas, dll)"></textarea><br>
        <button type="submit">Pesan Katering Sekarang</button>
    </form>
</div> 
