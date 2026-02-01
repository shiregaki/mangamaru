<?php
session_start();
// Mundur 2 kali untuk sampai ke root 'mangamaru'
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../Config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $manga_id = $_POST['manga_id'];
    $number = $_POST['chapter_number'];
    $title = $_POST['chapter_title'];
    
    // Pastikan kolom 'slug' sudah dibuat di database
    $slug = "ch-" . $number;

    // 1. Simpan ke database
    $database->insert("chapters", [
        "manga_id" => $manga_id,
        "chapter_number" => $number,
        "chapter_title" => $title,
        "slug" => $slug
    ]);

    // 2. Tentukan folder tujuan (uploads/chapters/[manga_id]/[nomor_chapter]/)
    $target_dir = __DIR__ . "/../../public/uploads/chapters/{$manga_id}/{$number}/";
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    // 3. Pindahkan file gambar
    if (isset($_FILES['chapter_images'])) {
        foreach ($_FILES['chapter_images']['tmp_name'] as $key => $tmp_name) {
            $file_name = $_FILES['chapter_images']['name'][$key];
            move_uploaded_file($tmp_name, $target_dir . $file_name);
        }
    }

    // Redirect kembali ke dashboard admin
    header("Location: /views/admin/dashboard.php?msg=Chapter Berhasil Ditambah");
    exit;
}