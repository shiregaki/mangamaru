<?php
session_start();
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $slug  = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));

    $cover_name = "default_cover.jpg";

    if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] === 0) {
        $extension = pathinfo($_FILES['cover_image']['name'], PATHINFO_EXTENSION);
        $cover_name = "cover_" . time() . "." . $extension;

        // PATH PERBAIKAN:
        // __DIR__ adalah folder 'app/controllers'
        // Mundur 2x (../../) untuk sampai ke Root (MangaMaru)
        // Lalu masuk ke public/uploads/covers/
        $upload_dir = __DIR__ . '/../../public/uploads/covers/';

        // Buat folder otomatis jika belum ada
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        $upload_path = $upload_dir . $cover_name;

        if (!move_uploaded_file($_FILES['cover_image']['tmp_name'], $upload_path)) {
            header("Location: /views/admin/tambah_manga.php?error=Gagal%20simpan%20gambar");
            exit;
        }
    }

    $database->insert("manga", [
        "title"       => $title,
        "slug"        => $slug,
        "description" => $_POST['description'],
        "type"        => $_POST['type'],
        "status"      => $_POST['status'],
        "cover_image" => $cover_name
    ]);

    header("Location: /views/admin/dashboard.php?msg=Manga%20Berhasil%20Ditambah");
    exit;
}