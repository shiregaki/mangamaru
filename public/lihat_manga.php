<?php
session_start();
require_once __DIR__ . '/../app/Config/koneksi.php';

// Ambil slug dari URL
$slug = $_GET['slug'] ?? '';

if (empty($slug)) {
    header("Location: index.php");
    exit;
}

// 1. Ambil Detail Manga Berdasarkan Slug
$manga = $database->get("manga", "*", ["slug" => $slug]);

if (!$manga) {
    echo "Manga tidak ditemukan.";
    exit;
}

// 2. Ambil Semua Chapter Terkait (Terbaru di atas)
$chapters = $database->select("chapters", "*", [
    "manga_id" => $manga['id'],
    "ORDER" => ["chapter_number" => "DESC"]
]);

$title = $manga['title'];
include __DIR__ . '/../views/layout/header.php';
?>

<style>
    :root { --primary-blue: #007bff; }
    
    .manga-detail-header {
        position: relative;
        padding: 80px 0 60px;
        background: linear-gradient(rgba(0,0,0,0.8), rgba(0,0,0,0.95)), 
                    url('uploads/covers/<?= $manga['cover_image'] ?>');
        background-size: cover;
        background-position: center;
        color: white;
        overflow: hidden;
    }
    .manga-cover-big {
        width: 100%;
        max-width: 250px;
        border-radius: 12px;
        box-shadow: 0 15px 35px rgba(0,0,0,0.6);
        border: 4px solid rgba(255,255,255,0.1);
    }
    .info-badge { 
        background: rgba(255,255,255,0.15); 
        backdrop-filter: blur(5px);
        border-radius: 8px; 
        padding: 6px 15px; 
        font-size: 0.85rem; 
        font-weight: 600;
    }

    .chapter-list-container {
        background: #fff;
        border-radius: 15px;
        border: 1px solid #eef0f2;
        overflow: hidden;
    }
    .chapter-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 18px 25px;
        border-bottom: 1px solid #f1f3f5;
        transition: all 0.3s ease;
        text-decoration: none !important;
        color: #333;
    }
    .chapter-item:hover {
        background-color: #f8faff;
        padding-left: 35px;
        color: var(--primary-blue);
    }
    .chapter-item:last-child { border-bottom: none; }
    .ch-number { font-weight: 800; font-size: 1.05rem; color: #1a1a1a; }
    .ch-date { font-size: 0.8rem; color: #999; }
    .ch-icon {
        width: 40px;
        height: 40px;
        background: #f1f3f5;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: 0.3s;
    }
    .chapter-item:hover .ch-icon {
        background: var(--primary-blue);
        color: white;
        transform: translateX(5px);
    }

    @media (max-width: 768px) {
        .manga-detail-header { padding: 40px 0; text-align: center; }
        .manga-cover-big { max-width: 180px; margin-bottom: 25px; }
        .chapter-item { padding: 15px; }
    }
</style>

<div class="manga-detail-header shadow-sm animate__animated animate__fadeIn">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-3">
                <img src="uploads/covers/<?= $manga['cover_image'] ?>" 
                     class="manga-cover-big shadow" alt="<?= $manga['title'] ?>"
                     onerror="this.src='uploads/covers/default_cover.jpg'">
            </div>
            <div class="col-md-9">
                <h1 class="display-5 fw-800 mb-3"><?= htmlspecialchars($manga['title']) ?></h1>
                <div class="d-flex flex-wrap gap-2 mb-4">
                    <span class="info-badge text-uppercase"><?= $manga['type'] ?></span>
                    <span class="info-badge text-capitalize"><?= $manga['status'] ?></span>
                    <span class="info-badge text-warning"><i class="fas fa-star me-1"></i> 4.9</span>
                </div>
                <div class="d-flex gap-3 mb-4 justify-content-center justify-content-md-start">
                    <?php if(!empty($chapters)): 
                        $first_ch = end($chapters); // Mengambil chapter paling lama (pertama) ?>
                        <a href="baca.php?manga=<?= $manga['slug'] ?>&slug=<?= $first_ch['slug'] ?>" class="btn btn-primary btn-lg rounded-pill px-5 fw-bold shadow-sm">
                            <i class="fas fa-play me-2"></i>Mulai Baca
                        </a>
                    <?php endif; ?>
                    <button class="btn btn-outline-light btn-lg rounded-pill px-4 fw-bold">
                        <i class="far fa-bookmark me-2"></i>Bookmark
                    </button>
                </div>
                <p class="lead opacity-75 small" style="max-width: 850px; line-height: 1.8;">
                    <?= nl2br(htmlspecialchars($manga['description'])) ?>
                </p>
            </div>
        </div>
    </div>
</div>

<main class="container py-5" id="chapters">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
                <h4 class="fw-800 m-0 text-dark">
                    <i class="fas fa-layer-group text-primary me-2"></i>Daftar Chapter
                </h4>
                <span class="badge bg-light text-dark border rounded-pill px-3 py-2">
                    Total: <?= count($chapters) ?> Chapter
                </span>
            </div>

            <div class="chapter-list-container shadow-sm animate__animated animate__fadeInUp">
                <?php if(!empty($chapters)): ?>
                    <?php foreach($chapters as $c): ?>
                    <?php 
                        // Normalisasi angka agar tidak muncul .0
                        $clean_num = (string)floatval($c['chapter_number']); 
                    ?>
                    
                    <a href="baca.php?manga=<?= $manga['slug'] ?>&slug=<?= $c['slug'] ?>" class="chapter-item">
                        <div class="d-flex align-items-center">
                            <div class="bg-primary text-white rounded-3 px-3 py-1 me-3 fw-bold small shadow-sm">
                                CH
                            </div>
                            <div>
                                <div class="ch-number">Chapter <?= $clean_num ?></div>
                                <div class="ch-date">
                                    <i class="far fa-calendar-alt me-1"></i> <?= date('d M Y', strtotime($c['created_at'])) ?>
                                </div>
                            </div>
                        </div>
                        <div class="ch-icon">
                            <i class="fas fa-arrow-right"></i>
                        </div>
                    </a>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="text-center py-5">
                        <i class="fas fa-ghost fa-3x text-light-emphasis opacity-25 mb-3"></i>
                        <p class="text-muted">Belum ada chapter yang diunggah.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main>

<?php include __DIR__ . '/../views/layout/footer.php'; ?>