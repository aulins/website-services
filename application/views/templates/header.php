<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= $title ?? 'Jasa Website' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
    </head>
    <body>
        <section>
            <div class="container">
                <header>
                    <a href="<?= base_url('') ?>" class="logo">Buat Website</a>
                    <ul>
                        <li><a href="<?= base_url('') ?>" >Home</a></li>
                        <li><a href="<?= base_url('user/katalog') ?>">Katalog</a></li>
                        <li><a href="<?= base_url('order/create') ?>">Pemesanan</a></li>
                        <li><a href="<?= base_url('review/create') ?>">Ulasan</a></li>
                        <li><a href="<?= base_url('contact') ?>">Kontak</a></li>
                    </ul>
                </header>

