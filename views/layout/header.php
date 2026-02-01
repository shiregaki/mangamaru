<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? $title . " - MangaMaru" : "MangaMaru - Jelajahi Dunia Komik"; ?></title>
    
    <link rel="icon" type="image/png" href="/mangamaru/assets/logo.png">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

    <style>
        :root {
            --bg-white: #ffffff;
            --bg-light: #f8f9fa;
            --primary-blue: #007bff;
            --text-black: #1a1a1a;
            --text-muted: #6c757d;
        }

        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            background-color: var(--bg-light); 
            color: var(--text-black);
            overflow-x: hidden;
        }

        /* Navbar Styling */
        .navbar {
            background: var(--bg-white) !important;
            box-shadow: 0 2px 15px rgba(0,0,0,0.05);
            padding: 0.8rem 0;
        }

        .navbar-brand { 
            font-weight: 800; 
            font-size: 1.5rem; 
            color: var(--text-black) !important;
            letter-spacing: -1px; 
        }
        .navbar-brand span { color: var(--primary-blue); }

        .nav-link { color: var(--text-black) !important; font-weight: 600; }
        .nav-link:hover { color: var(--primary-blue) !important; }

        /* Search Bar Light */
        .search-container input {
            background: #f1f3f5;
            border: 1px solid #e9ecef;
            color: var(--text-black); 
            border-radius: 10px;
        }

        /* Manga Card UI */
        .manga-card { 
            background: var(--bg-white);
            border: 1px solid #edf2f7;
            border-radius: 12px; 
            overflow: hidden; 
            transition: all 0.3s ease;
            height: 100%;
        }

        .manga-card:hover { 
            transform: translateY(-8px);
            box-shadow: 0 12px 20px rgba(0,0,0,0.08);
            border-color: var(--primary-blue);
        }

        .manga-img-container { position: relative; width: 100%; height: 260px; overflow: hidden; }
        .manga-img-container img { width: 100%; height: 100%; object-fit: cover; }
        
        .badge-type { 
            position: absolute; top: 10px; left: 10px; 
            background: var(--primary-blue); color: #fff; font-weight: 700;
            border-radius: 6px; padding: 4px 10px; font-size: 0.65rem;
        }

        @media (max-width: 576px) {
            .manga-img-container { height: 190px; }
            .navbar-brand { font-size: 1.2rem; }
        }
    </style>
</head>
<body>
<?php include __DIR__ . '/navbar.php'; ?>