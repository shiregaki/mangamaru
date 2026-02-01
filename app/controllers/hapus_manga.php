<?php
session_start();
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../Config/koneksi.php';

// 1. Proteksi Admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: /auth/login.php");
    exit;
}

// 2. Ambil ID Manga dari URL
$id = $_GET['id'] ?? null;

if ($id) {
    // 3. Ambil informasi manga untuk menghapus file fisik
    $manga = $database->get("manga", ["cover_image"], ["id" => $id]);

    if ($manga) {
        // --- HAPUS FILE COVER ---
        $cover_path = __DIR__ . "/../../public/uploads/covers/" . $manga['cover_image'];
        if (file_exists($cover_path) && $manga['cover_image'] !== 'default_cover.jpg') {
            unlink($cover_path);
        }

        // --- HAPUS FOLDER CHAPTERS ---
        $chapter_folder = __DIR__ . "/../../public/uploads/chapters/" . $id;
        if (is_dir($chapter_folder)) {
            deleteDirectory($chapter_folder);
        }

        // --- HAPUS DATA DI DATABASE ---
        // Karena kita menggunakan ON DELETE CASCADE di database, 
        // menghapus manga akan otomatis menghapus chapter terkait di tabel chapters.
        $database->delete("manga", ["id" => $id]);

        header("Location: /views/admin/dashboard.php?msg=Manga dan seluruh kontennya berhasil dihapus");
        exit;
    }
}

header("Location: /views/admin/dashboard.php?error=Gagal menghapus data");
exit;

/**
 * Fungsi pembantu untuk menghapus folder beserta isinya
 */
function deleteDirectory($dir) {
    if (!file_exists($dir)) return true;
    if (!is_dir($dir)) return unlink($dir);
    foreach (scandir($dir) as $item) {
        if ($item == '.' || $item == '..') continue;
        if (!deleteDirectory($dir . DIRECTORY_SEPARATOR . $item)) return false;
    }
    return rmdir($dir);
}