<nav class="navbar navbar-expand-lg sticky-top">
    <div class="container">
        <a class="navbar-brand" href="/mangamaru/public/index.php">
            MANGA<span>MARU</span>
        </a>

        <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4">
                <li class="nav-item">
                    <a class="nav-link px-3" href="/mangamaru/public/index.php">
                        <i class="fas fa-home me-1 d-lg-none"></i> Beranda
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-3" href="/mangamaru/public/manga_list.php">
                        <i class="fas fa-list me-1 d-lg-none"></i> Daftar Manga
                    </a>
                </li>
            </ul>

            <div class="d-lg-flex align-items-center gap-3">
                <form action="/mangamaru/public/manga_list.php" method="GET" class="search-container mb-3 mb-lg-0">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-0"><i class="fas fa-search text-muted"></i></span>
                        <input type="text" name="search" class="form-control border-0 bg-light shadow-none" placeholder="Cari manga..." style="width: 200px;">
                    </div>
                </form>

                <div class="d-flex gap-2">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <div class="dropdown">
                            <button class="btn btn-light rounded-pill px-3 dropdown-toggle fw-bold" type="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user-circle me-1"></i> <?= htmlspecialchars($_SESSION['username']); ?>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end border-0 shadow-sm mt-2 rounded-4">
                                <?php if ($_SESSION['role'] === 'admin'): ?>
                                    <li><a class="dropdown-item py-2" href="/mangamaru/views/admin/dashboard.php"><i class="fas fa-tachometer-alt me-2 text-primary"></i>Dashboard</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                <?php endif; ?>
                                <li><a class="dropdown-item py-2 text-danger" href="/mangamaru/auth/logout.php"><i class="fas fa-sign-out-alt me-2"></i>Keluar</a></li>
                            </ul>
                        </div>
                    <?php else: ?>
                        <a href="/mangamaru/auth/login.php" class="btn btn-light rounded-pill px-4 fw-bold">Masuk</a>
                        <a href="/mangamaru/auth/register.php" class="btn btn-primary rounded-pill px-4 fw-bold">Daftar</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</nav>