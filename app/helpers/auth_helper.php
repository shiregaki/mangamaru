<?php
if (session_status() === PHP_SESSION_NONE) { 
    session_start(); 
}

/**
 * Fungsi untuk memproteksi halaman berdasarkan Role
 * @param string $role_required 'admin' atau 'member'
 */
function proteksi_halaman($role_required = 'member') {
    // 1. Cek apakah user sudah login
    if (!isset($_SESSION['user_id'])) {
        // Gunakan jalur absolut lengkap agar tidak error 404
        header("Location: /mangamaru/auth/login.php");
        exit;
    }

    // 2. Cek apakah role sesuai (Case Sensitive)
    // Kita gunakan strtolower untuk menghindari kesalahan penulisan 'Admin' vs 'admin'
    $user_role = strtolower($_SESSION['role'] ?? '');
    $required = strtolower($role_required);

    if ($required === 'admin' && $user_role !== 'admin') {
        // Jika bukan admin, lempar kembali ke index dengan pesan error (opsional)
        header("Location: /mangamaru/public/index.php?error=akses_ditolak");
        exit;
    }
}