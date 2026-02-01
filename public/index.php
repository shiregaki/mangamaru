<?php
session_start();
require_once __DIR__ . '/../app/Config/koneksi.php';

$title = "Beranda"; 

// Menangkap filter tipe dari URL (jika ada)
$filterType = $_GET['type'] ?? 'All';

// Menyiapkan kueri database berdasarkan filter
$where = ["ORDER" => ["created_at" => "DESC"], "LIMIT" => 12];
if ($filterType !== 'All') {
    $where["type"] = $filterType;
}

$mangas = $database->select("manga", [
    "id", "title", "slug", "cover_image", "type", "status"
], $where);

include __DIR__ . '/../views/layout/header.php'; 
?>

<style>
    :root { --primary-blue: #007bff; }
    /* Style Tab */
    .nav-tabs-manga { border: none; margin-bottom: 25px; gap: 10px; }
    .nav-tabs-manga .nav-link {
        border: none;
        background: #eee;
        color: #666;
        border-radius: 50px;
        padding: 8px 20px;
        font-weight: 700;
        font-size: 0.85rem;
        transition: 0.3s;
    }
    .nav-tabs-manga .nav-link.active {
        background: var(--primary-blue);
        color: white;
        box-shadow: 0 4px 12px rgba(0, 123, 255, 0.3);
    }
    /* Style Card */
    .manga-card {
        border-radius: 12px;
        overflow: hidden;
        transition: 0.3s;
        background: #fff;
        border: 1px solid #edf2f7;
    }
    .manga-card:hover { 
        transform: translateY(-8px); 
        box-shadow: 0 12px 20px rgba(0,0,0,0.08) !important; 
        border-color: var(--primary-blue);
    }
    .manga-img-container { position: relative; aspect-ratio: 3/4; overflow: hidden; }
    .manga-img-container img { width: 100%; height: 100%; object-fit: cover; }
    .badge-type { 
        position: absolute; top: 10px; left: 10px; z-index: 2; 
        padding: 4px 10px; font-size: 0.65rem; border-radius: 6px; font-weight: 800; 
    }
</style>

<main class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold m-0"><i class="fas fa-bolt text-warning me-2"></i>Rilis Terbaru</h4>
        <a href="manga_list.php" class="text-primary text-decoration-none small fw-bold">
            Lihat Semua <i class="fas fa-chevron-right ms-1"></i>
        </a>
    </div>

    <ul class="nav nav-tabs nav-tabs-manga">
        <li class="nav-item">
            <a class="nav-link <?= $filterType == 'All' ? 'active' : '' ?>" href="index.php?type=All">Semua</a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= $filterType == 'Manga' ? 'active' : '' ?>" href="index.php?type=Manga">Manga</a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= $filterType == 'Manhwa' ? 'active' : '' ?>" href="index.php?type=Manhwa">Manhwa</a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= $filterType == 'Manhua' ? 'active' : '' ?>" href="index.php?type=Manhua">Manhua</a>
        </li>
    </ul>
    
    <div class="row g-3 g-md-4">
        <?php if(!empty($mangas)): ?>
            <?php foreach($mangas as $m): ?>
            <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                <div class="manga-card shadow-sm">
                    <div class="manga-img-container">
                        <?php 
                            $badgeColor = ($m['type'] == 'Manhwa') ? 'bg-primary' : (($m['type'] == 'Manhua') ? 'bg-danger' : 'bg-dark');
                        ?>
                        <span class="badge <?= $badgeColor ?> badge-type"><?= $m['type'] ?></span>
                        <a href="lihat_manga.php?slug=<?= $m['slug'] ?>">
                            <img src="uploads/covers/<?= $m['cover_image'] ?>" 
                                 alt="<?= $m['title'] ?>"
                                 onerror="this.src='uploads/covers/default_cover.jpg'">
                        </a>
                    </div>
                    <div class="card-body p-3 text-center">
                        <a href="lihat_manga.php?slug=<?= $m['slug'] ?>" class="text-decoration-none text-dark fw-bold d-block text-truncate small">
                            <?= htmlspecialchars($m['title']) ?>
                        </a>
                        <div class="d-flex justify-content-between align-items-center mt-2 border-top pt-2">
                            <small class="text-muted" style="font-size: 0.65rem;">
                                <i class="fas fa-circle text-success me-1" style="font-size: 0.5rem;"></i> <?= $m['status'] ?>
                            </small>
                            <small class="fw-bold"><i class="fas fa-star text-warning"></i> 4.8</small>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12 text-center py-5 bg-white rounded-3 border">
                <p class="text-muted m-0">Tidak ada manga rilis terbaru untuk kategori ini.</p>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php include __DIR__ . '/../views/layout/footer.php'; ?>