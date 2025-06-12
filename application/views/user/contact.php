<h2>Kontak Kami</h2>

<div class="card">
    <div class="card-body">
        <h5 class="card-title"><?= $settings->website_name ?></h5>
        <p><strong>Telepon:</strong> <?= $settings->phone_number1 ?></p>
        <p><strong>Email:</strong> <?= $settings->email1 ?></p>
        <p><strong>Alamat:</strong> <?= $settings->address ?></p>
        <p><strong>Map:</strong> <?= $settings->maps ?></p>

        <?php if ($settings->logo): ?>
        <img src="<?= base_url('uploads/' . $settings->logo) ?>" alt="Logo" class="img-fluid">
        <?php endif; ?>
    </div>
</div>
