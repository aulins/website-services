<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= $title ?? 'Jasa Website' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="<?= base_url() ?>">Jasa Website Profesional</a>
        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
        <ul class="navbar-nav">
            <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="<?= base_url() ?>">Home</a>
            </li>
            <li class="nav-item">
            <a class="nav-link" href="<?= base_url('user/katalog') ?>">Katalog</a>
            </li>
            <li class="nav-item">
            <a class="nav-link" href="<?= base_url('order/create') ?>">Order</a>
            </li>
            <li class="nav-item">
            <a class="nav-link" href="<?= base_url('review/create') ?>">Ulasan</a>
            </li>
            <li class="nav-item">
            <a class="nav-link" href="<?= base_url('contact') ?>">Kontak</a>
            </li>
        </ul>
        </div>
        </div>
    </nav>
<div class="container mt-4">
