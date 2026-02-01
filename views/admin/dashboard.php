<?php
session_start();

// PERBAIKAN PATH: Mundur 3 kali untuk sampai ke root 'mangamaru'
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../app/config/koneksi.php';

// Proteksi Admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: /mangamaru/auth/login.php");
    exit;
}

// Ambil data dari database menggunakan Medoo
$all_manga = $database->select("manga", "*");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - MangaMaru</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { background-color: #f4f7f6; }
        .manga-cover {
            width: 50px;
            height: 70px;
            object-fit: cover;
            border-radius: 8px;
        }
        .card { border-radius: 15px; }
        .table thead { border-radius: 15px 15px 0 0; }
        .btn-add-chapter { font-size: 0.7rem; padding: 2px 8px; }
    </style>
</head>
<body>

<div class="container py-5">
    <div class="row mb-4">
        <div class="col-md-6">
            <h2 class="fw-bold"><i class="fas fa-chart-line me-2 text-primary"></i>Panel Admin</h2>
        </div>
        <div class="col-md-6 text-md-end">
            <a href="/mangamaru/" class="btn btn-outline-secondary rounded-pill me-2">
                <i class="fas fa-external-link-alt me-1"></i> Lihat Situs
            </a>
            <a href="tambah_manga.php" class="btn btn-primary rounded-pill shadow-sm">
                <i class="fas fa-plus-circle me-1"></i> Tambah Manga Baru
            </a>
        </div>
    </div>

    <?php if(isset($_GET['msg'])): ?>
        <div class="alert alert-success border-0 shadow-sm alert-dismissible fade show mb-4" role="alert">
            <i class="fas fa-check-circle me-1"></i> <?= htmlspecialchars($_GET['msg']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 border-0">
            <h5 class="mb-0 fw-bold">Manajemen Konten</h5>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Cover</th>
                        <th>Judul & Kontrol</th>
                        <th>Tipe</th>
                        <th>Status</th>
                        <th class="text-center">Opsi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($all_manga)): ?>
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">
                            <i class="fas fa-folder-open fa-3x mb-3 opacity-20"></i>
                            <p>Belum ada data manga. Silakan klik tombol <strong>Tambah Manga</strong>.</p>
                        </td>
                    </tr>
                    <?php else: ?>
                        <?php foreach($all_manga as $m): ?>
                        <tr>
                            <td class="ps-4">
                                <img src="../../uploads/covers/<?= htmlspecialchars($m['cover_image']) ?>" 
                                     class="manga-cover shadow-sm" 
                                     onerror="this.src='../../uploads/covers/default_cover.jpg'">
                            </td>
                            <td>
                                <div class="fw-bold text-dark mb-1"><?= htmlspecialchars($m['title']) ?></div>
                                <a href="tambah_chapter.php?manga_id=<?= $m['id'] ?>" class="btn btn-sm btn-outline-primary btn-add-chapter rounded-pill">
                                    <i class="fas fa-plus me-1"></i> Tambah Chapter
                                </a>
                            </td>
                            <td><span class="badge bg-light text-dark border"><?= $m['type'] ?></span></td>
                            <td>
                                <span class="badge bg-<?= ($m['status'] == 'Ongoing') ? 'success' : 'secondary' ?> rounded-pill">
                                    <?= $m['status'] ?>
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="btn-group shadow-sm rounded">
                                    <a href="edit_manga.php?id=<?= $m['id'] ?>" class="btn btn-sm btn-white border" title="Edit Info">
                                        <i class="fas fa-edit text-warning"></i>
                                    </a>
                                    <a href="../../app/controllers/hapus_manga.php?id=<?= $m['id'] ?>" 
                                       class="btn btn-sm btn-white border" 
                                       onclick="return confirm('Hapus manga ini beserta seluruh chapternya?')">
                                        <i class="fas fa-trash text-danger"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>