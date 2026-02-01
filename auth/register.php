<?php
session_start();
// Proteksi: Jika sudah login tidak bisa akses halaman register
if (isset($_SESSION['user_id'])) {
    header("Location: /index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Member - MangaMaru</title>

    <link rel="icon" type="image/png" href="assets/logo.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        :root { --primary-blue: #007bff; --bg-light: #f8f9fa; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: var(--bg-light); display: flex; align-items: center; justify-content: center; min-height: 100vh; margin: 0; padding: 20px 0; }
        .register-card { width: 100%; max-width: 450px; padding: 20px; }
        .card { border: none; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
        .brand-logo { text-align: center; margin-bottom: 25px; }
        .brand-logo img { width: 50px; margin-bottom: 8px; }
        .brand-logo h4 { font-weight: 800; letter-spacing: -1px; color: #1a1a1a; }
        .brand-logo h4 span { color: var(--primary-blue); }
        .form-label { font-size: 0.85rem; font-weight: 700; margin-bottom: 8px; color: #4a5568; }
        .form-control { border-radius: 12px; padding: 10px 15px; background-color: #f1f3f5; border: 1px solid transparent; transition: 0.3s; font-size: 0.95rem; }
        .form-control:focus { background-color: #fff; border-color: var(--primary-blue); box-shadow: 0 0 0 4px rgba(0, 123, 255, 0.1); outline: none; }
        .btn-register { border-radius: 12px; padding: 12px; font-weight: 700; background-color: var(--primary-blue); border: none; transition: 0.3s; margin-top: 10px; color: white; }
        .btn-register:hover { background-color: #0056b3; transform: translateY(-2px); }
    </style>
</head>
<body>

<div class="register-card">
    <div class="brand-logo">
        <img src="assets/logo.png" alt="Logo">
        <h4>MANGA<span>MARU</span></h4>
    </div>

    <div class="card p-4 shadow-sm">
        <h5 class="fw-bold mb-1 text-center">Buat Akun Member</h5>
        <p class="text-muted small text-center mb-4">Daftar untuk mulai membaca dan simpan favoritmu</p>
        
        <?php if(isset($_GET['error'])): ?>
            <div class="alert alert-danger py-2 small border-0 mb-3 text-center">
                <i class="fas fa-exclamation-circle me-1"></i> <?= htmlspecialchars($_GET['error']) ?>
            </div>
        <?php endif; ?>

        <form action="proses_register.php" method="POST">
            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-control" placeholder="buat username" required>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" placeholder="nama@email.com" required>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" placeholder="min. 6 karakter" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Konfirmasi</label>
                    <input type="password" name="re_password" class="form-control" placeholder="ulangi password" required>
                </div>
            </div>
            
            <button type="submit" class="btn btn-primary btn-register w-100 mb-3">Daftar Sekarang</button>
            
            <div class="text-center">
                <small class="text-muted">Sudah punya akun? <a href="login.php" class="text-primary fw-bold text-decoration-none">Masuk di sini</a></small>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>