<?php
session_start();
require_once __DIR__ . '/../app/Config/koneksi.php';

// 1. Ambil Slug Manga dan Slug Chapter dari URL
$manga_slug = $_GET['manga'] ?? '';
$chapter_slug = $_GET['slug'] ?? '';

if (empty($manga_slug) || empty($chapter_slug)) {
    header("Location: index.php");
    exit;
}

// 2. Kueri Spesifik: Ambil data berdasarkan keunikan kombinasi Manga & Chapter
$chapter = $database->get("chapters", [
    "[>]manga" => ["manga_id" => "id"]
], [
    "chapters.id",
    "chapters.manga_id",
    "chapters.chapter_number",
    "manga.title(manga_title)",
    "manga.slug(manga_slug)"
], [
    "AND" => [
        "manga.slug" => $manga_slug,
        "chapters.slug" => $chapter_slug
    ]
]);

if (!$chapter) {
    echo "Chapter tidak ditemukan untuk manga ini.";
    exit;
}

// 3. Ambil SEMUA Chapter untuk Sidebar (Filter berdasarkan manga_id)
$all_chapters = $database->select("chapters", ["chapter_number", "slug"], [
    "manga_id" => $chapter['manga_id'],
    "ORDER" => ["chapter_number" => "DESC"]
]);

// 4. Logika Navigasi Prev/Next
$prev_chapter = $database->get("chapters", "slug", [
    "AND" => [
        "manga_id" => $chapter['manga_id'],
        "chapter_number[<]" => $chapter['chapter_number']
    ],
    "ORDER" => ["chapter_number" => "DESC"]
]);

$next_chapter = $database->get("chapters", "slug", [
    "AND" => [
        "manga_id" => $chapter['manga_id'],
        "chapter_number[>]" => $chapter['chapter_number']
    ],
    "ORDER" => ["chapter_number" => "ASC"]
]);

// 5. Sinkronisasi Folder uploads/chapters/[manga_id]/[chapter_number]/
$m_id = (int)$chapter['manga_id']; 
$c_num = (int)$chapter['chapter_number']; 

$relative_path = "uploads/chapters/{$m_id}/{$c_num}/";
$absolute_path = __DIR__ . "/" . $relative_path;

$images = [];
if (is_dir($absolute_path)) {
    $files = glob($absolute_path . "*.{jpg,jpeg,png,webp,JPG,JPEG,PNG}", GLOB_BRACE);
    if ($files) {
        natsort($files);
        foreach ($files as $file) {
            $images[] = $relative_path . basename($file);
        }
    }
}

include __DIR__ . '/../views/layout/header.php';
?>

<style>
    /* ZEN MODE: Hapus Navbar Utama */
    nav.navbar, footer { display: none !important; }
    body { background-color: #000; color: #fff; cursor: pointer; overflow-x: hidden; margin: 0; padding: 0; }
    
    .reader-container { max-width: 800px; margin: 0 auto; }
    .reader-img { width: 100%; display: block; margin-bottom: 0; }

    /* UI Navigation */
    .floating-nav {
        position: fixed; bottom: -120px; left: 50%;
        transform: translateX(-50%); width: 90%; max-width: 500px;
        background: rgba(15, 15, 15, 0.98); backdrop-filter: blur(15px);
        border: 1px solid rgba(255,255,255,0.1); padding: 15px;
        border-radius: 25px; display: flex; justify-content: space-around;
        align-items: center; transition: 0.4s; z-index: 9999;
    }
    .floating-nav.show { bottom: 30px; }

    .top-info {
        position: fixed; top: -120px; left: 0; width: 100%;
        background: rgba(10, 10, 10, 0.9); padding: 15px; text-align: center;
        transition: 0.4s; z-index: 9998;
    }
    .top-info.show { top: 0; }

    /* Sidebar */
    #sidebarChapters {
        position: fixed; top: 0; right: -320px; width: 320px; height: 100%;
        background: #0a0a0a; border-left: 1px solid #222; transition: 0.4s;
        z-index: 10000; padding: 25px; overflow-y: auto;
    }
    #sidebarChapters.open { right: 0; }
    .sidebar-overlay {
        position: fixed; top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(0,0,0,0.7); display: none; z-index: 9999;
    }
    .sidebar-overlay.show { display: block; }
    
    .chapter-link { display: block; padding: 15px; color: #888; text-decoration: none; border-bottom: 1px solid #111; }
    .chapter-link.active { color: #fff; font-weight: bold; background: #007bff22; border-left: 3px solid #007bff; }
    .nav-btn { color: #fff; text-decoration: none; text-align: center; opacity: 0.6; background: none; border: none; }
    .nav-btn:hover { opacity: 1; color: #007bff; }
    .nav-btn i { font-size: 1.3rem; display: block; }
    .nav-btn span { font-size: 0.6rem; text-transform: uppercase; }
</style>

<div class="sidebar-overlay" id="overlay"></div>

<div id="sidebarChapters">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="m-0 fw-bold">Daftar Chapter</h5>
        <button class="btn btn-sm btn-dark" id="closeSidebar"><i class="fas fa-times"></i></button>
    </div>
    <?php foreach($all_chapters as $c): ?>
        <a href="baca.php?manga=<?= $chapter['manga_slug'] ?>&slug=<?= $c['slug'] ?>" class="chapter-link <?= $c['slug'] == $chapter_slug ? 'active' : '' ?>">
            Chapter <?= (string)floatval($c['chapter_number']) ?>
        </a>
    <?php endforeach; ?>
</div>

<div class="top-info" id="topBar">
    <h6 class="m-0 fw-bold text-white"><?= htmlspecialchars($chapter['manga_title']) ?></h6>
    <small class="text-primary">Chapter <?= $c_num ?></small>
</div>

<main class="reader-container" id="readerArea">
    <?php if (!empty($images)): ?>
        <?php foreach ($images as $img): ?>
            <img src="<?= $img ?>" class="reader-img">
        <?php endforeach; ?>
    <?php else: ?>
        <div class="text-center py-5">
            <i class="fas fa-image fa-3x mb-3 opacity-25"></i>
            <h5>Gagal memuat gambar.</h5>
            <p class="text-muted small">Folder: <code class="text-danger"><?= $relative_path ?></code></p>
        </div>
    <?php endif; ?>
</main>

<div class="floating-nav" id="popupBar">
    <a href="index.php" class="nav-btn"><i class="fas fa-home"></i><br><span>Home</span></a>
    
    <a href="baca.php?manga=<?= $chapter['manga_slug'] ?>&slug=<?= $prev_chapter ?>" 
       class="nav-btn <?= !$prev_chapter ? 'opacity-25 pointer-events-none' : '' ?>">
        <i class="fas fa-chevron-left"></i><br><span>Prev</span>
    </a>

    <button class="nav-btn" id="btnChapters"><i class="fas fa-list"></i><br><span>List</span></button>

    <a href="baca.php?manga=<?= $chapter['manga_slug'] ?>&slug=<?= $next_chapter ?>" 
       class="nav-btn <?= !$next_chapter ? 'opacity-25 pointer-events-none' : '' ?>">
        <i class="fas fa-chevron-right"></i><br><span>Next</span>
    </a>
</div>

<script>
    const popupBar = document.getElementById('popupBar');
    const topBar = document.getElementById('topBar');
    const sidebar = document.getElementById('sidebarChapters');
    const overlay = document.getElementById('overlay');
    const btnChapters = document.getElementById('btnChapters');
    const closeSidebar = document.getElementById('closeSidebar');
    let isShowNav = false;

    document.addEventListener('click', function(e) {
        if (e.target.closest('.floating-nav') || e.target.closest('#sidebarChapters') || e.target.closest('.top-info')) return;
        if (!isShowNav) {
            popupBar.classList.add('show');
            topBar.classList.add('show');
        } else {
            popupBar.classList.remove('show');
            topBar.classList.remove('show');
        }
        isShowNav = !isShowNav;
    });

    window.addEventListener('scroll', function() {
        popupBar.classList.remove('show');
        topBar.classList.remove('show');
        isShowNav = false;
    });

    btnChapters.addEventListener('click', function(e) {
        e.stopPropagation();
        sidebar.classList.add('open');
        overlay.classList.add('show');
        popupBar.classList.remove('show');
        topBar.classList.remove('show');
        isShowNav = false;
    });

    function closeAll() {
        sidebar.classList.remove('open');
        overlay.classList.remove('show');
    }
    closeSidebar.addEventListener('click', closeAll);
    overlay.addEventListener('click', closeAll);
</script>

<?php include __DIR__ . '/../views/layout/footer.php'; ?>