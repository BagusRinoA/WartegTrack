<!-- JUDUL HALAMAN MENU -->
<h1 class="page-title">Menu Warteg</h1>

<!-- FORM PENCARIAN MENU -->
<div class="menu-search-box">
    <!-- Input pencarian dengan event onkeyup untuk filter real-time -->
    <input type="text" class="menu-search-input" placeholder="Cari Menu..." id="menuSearch" onkeyup="filterMenu()">
</div>

<!-- GRID TAMPILAN DAFTAR MENU -->
<!-- Container grid yang menampilkan semua menu dalam bentuk card -->
<div class="menu-grid" id="menuGrid">
    <!-- Loop melalui array $menus yang didapat dari controller -->
    <?php foreach ($menus as $m): ?>
        <!-- Card untuk setiap item menu -->
        <div class="menu-card">
            <!-- Gambar placeholder untuk menu (akan diganti dengan gambar asli) -->
            <div class="menu-card-img">
                <img src="img/food-placeholder.png" alt="Menu" />
            </div>

            <!-- Nama produk menu dengan htmlspecialchars untuk keamanan -->
            <div class="menu-card-title"><?= htmlspecialchars($m['nama_produk']) ?></div>

            <!-- Harga menu diformat dengan number_format (Rp 15.000) -->
            <div class="menu-card-harga">Rp <?= number_format($m['harga'],0,',','.') ?></div>

            <!-- Deskripsi menu dengan htmlspecialchars untuk keamanan -->
            <div class="menu-card-desk"><?= htmlspecialchars($m['deskripsi']) ?></div>
        </div>
    <?php endforeach; ?>
</div>

<!-- JAVASCRIPT UNTUK FITUR PENCARIAN REAL-TIME -->
<script>
// Fungsi filter menu berdasarkan input pencarian
function filterMenu() {
    // Ambil elemen input pencarian
    var input = document.getElementById('menuSearch');

    // Ambil nilai input dan ubah ke lowercase untuk pencarian case-insensitive
    var filter = input.value.toLowerCase();

    // Ambil semua elemen card menu
    var cards = document.querySelectorAll('.menu-card');

    // Loop melalui setiap card menu
    cards.forEach(function(card) {
        // Ambil nama menu dari card dan ubah ke lowercase
        var nama = card.querySelector('.menu-card-title').textContent.toLowerCase();

        // Tampilkan/hide card berdasarkan apakah nama mengandung filter
        // Jika mengandung filter, tampilkan (''); jika tidak, hide ('none')
        card.style.display = nama.includes(filter) ? '' : 'none';
    });
}
</script>
