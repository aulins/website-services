<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= $title ?? 'Admin Dashboard' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
        display: flex;
        }
        aside {
        width: 220px;
        min-height: 100vh;
        background: #f8f9fa;
        }
        main {
        flex: 1;
        padding: 20px;
        }
        .nav-link.active {
        font-weight: bold;
        color: #0d6efd !important;
        }
    </style>
    </head>
    <body>
    <aside class="p-3">
        <h4>Admin Panel</h4>
        <nav class="nav flex-column">
        <a class="nav-link <?= uri_string() == 'admin' ? 'active' : '' ?>" href="<?= base_url('admin') ?>">Dashboard</a>
        <a class="nav-link" href="#">Katalog</a>
        <a class="nav-link" href="#">Pesanan</a>
        <a class="nav-link" href="#">Laporan</a>
        <a class="nav-link" href="#">Ulasan</a>
        <a class="nav-link text-danger" href="<?= base_url('logout') ?>">Logout</a>
        </nav>
    </aside>
    <main>
