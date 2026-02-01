<?php
session_start();
require_once __DIR__ . '/../app/Config/koneksi.php';

$title = "Daftar Manga";

// Logika Pencarian
$search = $_GET['search'] ?? '';
$where = [];

if (!empty($search)) {
    $where["title[~]"] = $search; // Mencari judul yang mirip menggunakan Medoo
}

$where["ORDER"] = ["title" => "ASC"];

$all_manga = $database->select("manga", "*", $where);

include __DIR__ . '/../views/layout/header.php';
?>

<main class="container py-5">
    <div class="row mb-4 align-items-center">
        <div class="col-md-6">
            <h4 class="fw-bold m-0"><i class="fas fa-list text-primary me-2"></i>Semua Koleksi</h4>
        </div>
        <div class="col-md-6">
            <form action="" method="GET" class="d-flex">
                <div class="input-group">
                    <input type="text" name="search" class="form-control border-end-0 shadow-none" 
                           placeholder="Cari judul manga..." value="<?= htmlspecialchars($search) ?>">
                    <button class="btn btn-outline-secondary border-start-0 bg-white text-muted" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <hr class="mb-5 opacity-10">

    <div class="row g-4">
        <?php if (!empty($all_manga)): ?>
            <?php foreach ($all_manga as $m): ?>
            <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                <div class="card h-100 border-0 shadow-sm rounded-3 overflow-hidden">
                    <div class="position-relative">
                        <img src="uploads/covers/<?= $m['cover_image'] ?>" 
                             class="card-img-top" style="aspect-ratio: 3/4; object-fit: cover;"
                             onerror="this.src='uploads/covers/default_cover.jpg'">
                        <span class="badge bg-dark position-absolute top-0 start-0 m-2" style="font-size: 0.6rem;">
                            <?= $m['type'] ?>
                        </span>
                    </div>
                    <div class="card-body p-2">
                        <a href="detail.php?slug=<?= $m['slug'] ?>" class="text-decoration-none text-dark fw-bold small d-block text-truncate">
                            <?= htmlspecialchars($m['title']) ?>
                        </a>
                        <div class="text-muted" style="font-size: 0.65rem;"><?= $m['status'] ?></div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12 text-center py-5">
                <p class="text-muted">Manga dengan judul "<strong><?= htmlspecialchars($search) ?></strong>" tidak ditemukan.</p>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php include __DIR__ . '/../views/layout/footer.php'; ?>