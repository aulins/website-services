<h2>Daftar Katalog</h2>

<div class="row">
    <?php foreach ($catalogues as $k): ?>
        <div class="col-md-4">
        <div class="card mb-3">
            <?php if ($k->image): ?>
            <img src="<?= base_url('uploads/catalogue/'.$k->image) ?>" class="card-img-top" alt="<?= $k->package_name ?>">
            <?php endif ?>
            <div class="card-body">
            <h5 class="card-title"><?= $k->package_name ?></h5>
            <p><?= $k->description ?></p>
            <p><strong>Rp <?= number_format($k->price, 0, ',', '.') ?></strong></p>
            </div>
        </div>
        </div>
    <?php endforeach; ?>
</div>
