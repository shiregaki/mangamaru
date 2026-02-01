<?php
// 1. Mulai session agar sistem tahu session mana yang akan dihapus
session_start();

// 2. Kosongkan semua variabel session
$_SESSION = [];

// 3. Hapus cookie session jika ada (opsional tapi lebih bersih)
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// 4. Hancurkan session secara total
session_destroy();

// 5. Redirect ke halaman login atau index dengan pesan sukses
header("Location: login.php?pesan=logout_berhasil");
exit;