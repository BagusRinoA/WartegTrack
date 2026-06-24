<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - WartegTrack</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="auth-bg">

<!-- Container utama untuk form login -->
<div class="auth-container">
    <div class="login-container">
        <!-- Logo -->
        <img src="logo/logo-WT-no-bg.png" alt="WartegTrack" style="width: 200px; margin: 0 auto 30px; display: block;">
        <h1 class="page-title">Login</h1>
        
        <!-- MENAMPILKAN PESAN ERROR JIKA LOGIN GAGAL -->
        <?php if (!empty($error)): ?>
            <div class="alert-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <!-- Form login -->
        <form method="post" class="login-form">
            <input 
                type="text" 
                name="username" 
                placeholder="Username" 
                required
                autocomplete="username"
            >
            <input 
                type="password" 
                name="password" 
                placeholder="Password" 
                required
                autocomplete="current-password"
            >
            <button type="submit">Login</button>
        </form>

        <!-- Link ke halaman signup untuk user yang belum punya akun -->
        <div class="signup-link" style="text-align: center; margin-top: 15px;">
            <p style="margin: 5px 0; color: #184F77;">Belum punya akun? <a href="index.php?page=signup">Sign Up</a></p>
            <p style="margin: 5px 0;"><a href="index.php" style="color: #349DB5; text-decoration: none;">← Kembali ke Beranda</a></p>
        </div>
    </div>
</div>

</body>
</html>
