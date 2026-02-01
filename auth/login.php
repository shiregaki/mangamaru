<?php
session_start();

// Jika sudah login, langsung lempar ke beranda
if (isset($_SESSION['user_id'])) {
    header("Location: /mangamaru/");
    exit;
}

// Tangkap pesan error dari URL jika ada
$error = isset($_GET['error']) ? $_GET['error'] : '';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - MangaMaru</title>
    <link rel="icon" type="image/png" href="/mangamaru/assets/logo.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* ... (Style tetap sama) ... */
        :root { --primary-blue: #007bff; --bg-light: #f8f9fa; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: var(--bg-light); display: flex; align-items: center; justify-content: center; min-height: 100vh; margin: 0; }
        .login-card { width: 100%; max-width: 400px; padding: 20px; }
        .card { border: none; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
        .brand-logo { text-align: center; margin-bottom: 25px; }
        .brand-logo img { width: 60px; margin-bottom: 10px; }
        .brand-logo h4 { font-weight: 800; letter-spacing: -1px; color: #1a1a1a; }
        .brand-logo h4 span { color: var(--primary-blue); }
        .form-control { border-radius: 12px; padding: 12px 15px; background-color: #f1f3f5; border: 1px solid transparent; transition: 0.3s; }
        .form-control:focus { background-color: #fff; border-color: var(--primary-blue); box-shadow: 0 0 0 4px rgba(0, 123, 255, 0.1); }
        .btn-login { border-radius: 12px; padding: 12px; font-weight: 700; background-color: var(--primary-blue); border: none; transition: 0.3s; }
        .btn-login:hover { background-color: #0056b3; transform: translateY(-2px); }
        .back-link { text-decoration: none; color: #6c757d; font-size: 0.9rem; transition: 0.3s; }
        .back-link:hover { color: var(--primary-blue); }
    </style>
</head>
<body>

<div class="login-card">
    <div class="brand-logo">
        <img src="/mangamaru/assets/logo.png" alt="Logo">
        <h4>MANGA<span>MARU</span></h4>
    </div>

    <div class="card p-4">
        <h5 class="fw-bold mb-4 text-center">Selamat Datang Kembali</h5>
        
        <?php if ($error): ?>
            <div class="alert alert-danger border-0 small py-2 mb-4 animate__animated animate__shakeX">
                <i class="fas fa-exclamation-circle me-1"></i> <?= htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <form action="cek_login.php" method="POST">
            <div class="mb-3">
                <label class="form-label small fw-bold">Username</label>
                <input type="text" name="username" class="form-control" placeholder="Masukkan username" required>
            </div>
            <div class="mb-4">
                <label class="form-label small fw-bold">Password</label>
                <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
            </div>
            <button type="submit" class="btn btn-primary btn-login w-100 mb-3">Login Sekarang</button>
            
            <div class="text-center">
                <p class="small text-muted mb-1">Belum punya akun?</p>
                <a href="register.php" class="fw-bold text-decoration-none small text-primary">Daftar Member Baru</a>
            </div>
        </form>
    </div>

    <div class="text-center mt-4">
        <a href="/mangamaru/" class="back-link">
            <i class="fas fa-arrow-left me-1"></i> Kembali ke Beranda
        </a>
    </div>
</div>
</body>
</html>