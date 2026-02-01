<?php
session_start();
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../app/Config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    // Ambil data user
    $user = $database->get("users", ["id", "username", "password", "role"], [
        "username" => $username
    ]);

    if ($user) {
        // Verifikasi password (teks dari form vs hash dari DB)
        if (password_verify($password, $user['password'])) {
            
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = strtolower(trim($user['role']));

            // Update login terakhir
            $database->update("users", ["last_login" => date("Y-m-d H:i:s")], ["id" => $user['id']]);

            // Redirect sesuai role
            if ($_SESSION['role'] === 'admin') {
                header("Location: /mangamaru/views/admin/dashboard.php");
            } else {
                header("Location: /mangamaru/public/index.php");
            }
            exit;
        } else {
            header("Location: login.php?error=Password salah! Hash tidak cocok.");
            exit;
        }
    } else {
        header("Location: login.php?error=Username tidak ditemukan.");
        exit;
    }
}