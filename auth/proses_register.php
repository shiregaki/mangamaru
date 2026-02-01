<?php
session_start();
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../app/Config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Ambil data dan bersihkan spasi
    $username    = trim($_POST['username'] ?? '');
    $email       = trim($_POST['email'] ?? '');
    $password    = $_POST['password'] ?? '';
    $re_password = $_POST['re_password'] ?? '';

    // 2. Validasi Password Match
    if ($password !== $re_password) {
        header("Location: register.php?error=Konfirmasi password tidak cocok!");
        exit;
    }

    // 3. Validasi Panjang Password (Opsional tapi disarankan)
    if (strlen($password) < 6) {
        header("Location: register.php?error=Password minimal 6 karakter!");
        exit;
    }

    // 4. Cek apakah Username atau Email sudah ada di database
    $is_exist = $database->get("users", "id", [
        "OR" => [
            "username" => $username,
            "email"    => $email
        ]
    ]);

    if ($is_exist) {
        header("Location: register.php?error=Username atau Email sudah terdaftar!");
        exit;
    }

    // 5. Hash Password (Standar Keamanan)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // 6. Simpan ke Database
    // Role dipaksa 'member' demi keamanan agar tidak bisa dimanipulasi dari form
    $database->insert("users", [
        "username"   => $username,
        "email"      => $email,
        "password"   => $hashed_password,
        "role"       => "member", 
        "avatar"     => "default.jpg",
        "created_at" => date("Y-m-d H:i:s")
    ]);

    // Berhasil, arahkan ke login dengan pesan sukses
    header("Location: login.php?msg=Registrasi Berhasil! Silakan Login.");
    exit;

} else {
    // Jika akses langsung tanpa POST, balikkan ke halaman register
    header("Location: register.php");
    exit;
}