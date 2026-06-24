<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - WartegTrack</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body class="auth-bg">

    <!-- Container utama untuk form signup -->
    <div class="auth-container">
        <div class="login-container">
            <h1 class="page-title">Daftar Akun</h1>
            
            <!-- MENAMPILKAN PESAN ERROR VALIDASI -->
            <?php if (!empty($errors)): ?>
                <div class="alert-error">
                    <ul style="margin: 0; padding-left: 20px;">
                        <?php foreach ($errors as $err): ?>
                            <li><?= htmlspecialchars($err) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <!-- MENAMPILKAN PESAN SUKSES -->
            <?php if (!empty($success)): ?>
                <div class="alert-success"><?= htmlspecialchars($success) ?></div>
            <?php endif; ?>

            <!-- Form pendaftaran user baru -->
            <form method="post" class="login-form">
                <input
                    type="email"
                    name="email"
                    placeholder="Email"
                    value="<?= htmlspecialchars($old['email'] ?? '') ?>"
                    required
                    autocomplete="email"
                >
                <input
                    type="text"
                    name="username"
                    placeholder="Username"
                    value="<?= htmlspecialchars($old['username'] ?? '') ?>"
                    required
                    autocomplete="username"
                >
                <input
                    type="password"
                    name="password"
                    placeholder="Password (minimal 8 karakter)"
                    required
                    autocomplete="new-password"
                >
                <button type="submit">Sign Up</button>
            </form>

            <!-- Link ke halaman login untuk user yang sudah punya akun -->
            <div class="signup-link" style="text-align: center; margin-top: 15px;">
                <p style="margin: 5px 0; color: #184F77;">Sudah punya akun? <a href="index.php?page=login">Login</a></p>
                <p style="margin: 5px 0;"><a href="index.php" style="color: #349DB5; text-decoration: none;">← Kembali ke Beranda</a></p>
            </div>
        </div>
    </div>

</body>
</html>
