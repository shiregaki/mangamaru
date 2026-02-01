<?php
session_start();
// Proteksi halaman: Pastikan hanya Admin yang bisa mengakses
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../../auth/login.php?error=Akses ditolak!");
    exit;
}

$title = "Tambah Manga Baru";
// Anda bisa menyertakan header/layout Anda di sini
// include __DIR__ . '/../layout/header.php'; 
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title; ?> - MangaMaru</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { background-color: #f8f9fa; font-family: 'Plus Jakarta Sans', sans-serif; }
        .card { border: none; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); }
        .form-label { font-weight: 600; font-size: 0.9rem; color: #4a5568; }
    </style>
</head>
<body>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="fw-bold m-0">Tambah Manga Baru</h4>
                <a href="dashboard.php" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
            </div>

            <div class="card p-4">
                <form action="../../app/controllers/proses_tambah_manga.php" method="POST" enctype="multipart/form-data">
                    
                    <div class="mb-3">
                        <label class="form-label">Judul Manga</label>
                        <input type="text" name="title" class="form-control" placeholder="Masukkan judul manga" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Deskripsi / Sinopsis</label>
                        <textarea name="description" class="form-control" rows="5" placeholder="Tuliskan deskripsi singkat..."></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tipe</label>
                            <select name="type" class="form-select">
                                <option value="Manga">Manga</option>
                                <option value="Manhwa">Manhwa</option>
                                <option value="Manhua">Manhua</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="Ongoing">Ongoing</option>
                                <option value="Completed">Completed</option>
                                <option value="Hiatus">Hiatus</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Cover Image (Gambar Sampul)</label>
                        <input type="file" name="cover_image" class="form-control" accept="image/*">
                        <div class="form-text text-muted">Format: JPG, PNG, atau WEBP. Rekomendasi 3:4.</div>
                    </div>

                    <hr class="my-4 text-muted">

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary py-2 fw-bold">
                            <i class="fas fa-save me-1"></i> Simpan Data Manga
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>