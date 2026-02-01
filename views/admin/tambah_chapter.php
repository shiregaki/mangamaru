<?php
session_start();
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../app/Config/koneksi.php';

// Proteksi Admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: /mangamaru/auth/login.php");
    exit;
}

// Ambil ID dari URL jika ada (kiriman dari tombol di dashboard)
$selected_manga_id = $_GET['manga_id'] ?? null;

$mangas = $database->select("manga", ["id", "title"]);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Chapter - MangaMaru</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />

    <style>
        body { background-color: #f4f7f6; }
        .card { border-radius: 15px; }
        /* Memperbaiki tinggi select2 agar pas dengan bootstrap 5 */
        .select2-container--bootstrap-5 .select2-selection {
            border-radius: 8px;
            padding: 0.375rem 0.75rem;
            height: calc(3.5rem + 2px); /* Menyamakan dengan form-control */
        }
    </style>
</head>
<body class="bg-light">

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="mb-3">
                <a href="dashboard.php" class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Kembali ke Dashboard
                </a>
            </div>

            <div class="card border-0 shadow-sm p-4">
                <h4 class="fw-bold mb-4 text-primary"><i class="fas fa-file-upload me-2"></i>Tambah Chapter Baru</h4>
                
                <form action="../../app/controllers/proses_tambah_chapter.php" method="POST" enctype="multipart/form-data">
                    
                    <div class="mb-4">
                        <label class="form-label fw-bold">Cari & Pilih Manga</label>
                        <select name="manga_id" id="mangaSelector" class="form-select" required>
                            <option value="">-- Ketik judul manga di sini --</option>
                            <?php foreach($mangas as $m): ?>
                                <option value="<?= $m['id'] ?>" <?= ($selected_manga_id == $m['id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($m['title']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">Nomor Chapter</label>
                            <input type="number" step="0.1" name="chapter_number" class="form-control form-control-lg" placeholder="1" required>
                        </div>
                        <div class="col-md-8 mb-3">
                            <label class="form-label fw-bold">Judul Chapter (Opsional)</label>
                            <input type="text" name="chapter_title" class="form-control form-control-lg" placeholder="Contoh: Awal Mula Petualangan">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Upload Halaman Komik</label>
                        <div class="border rounded p-3 bg-white">
                            <input type="file" name="chapter_images[]" class="form-control" accept="image/*" multiple required>
                            <div class="form-text mt-2">
                                <i class="fas fa-info-circle me-1 text-info"></i>
                                Tips: Tekan <strong>Ctrl + A</strong> untuk memilih semua gambar di folder. Pastikan nama file berurutan (01, 02, dst).
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <button type="submit" class="btn btn-primary btn-lg w-100 fw-bold shadow">
                        <i class="fas fa-save me-2"></i> Simpan Chapter
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(document).ready(function() {
        // Inisialisasi Fitur Pencarian pada Dropdown
        $('#mangaSelector').select2({
            theme: 'bootstrap-5',
            placeholder: "Ketik judul manga...",
            allowClear: true,
            width: '100%'
        });
    });
</script>

</body>
</html>