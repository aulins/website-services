<div class="row">
  <?php foreach ($catalogues as $k): ?>
    <div class="col-6 mx-auto mb-4">
      <div class="card h-100 d-flex flex-column">
        <?php if ($k->image): ?><img src="<?= base_url('uploads/catalogue/'.$k->image) ?>" class="card-img-top" style="height: 250px; object-fit: cover;" alt="<?= $k->package_name ?>">
        <?php endif ?>
        <div class="card-body d-flex flex-column">
          <h5 class="card-title"><?= $k->package_name ?></h5>
          <p class="flex-grow-1"><?= $k->description ?></p>
          <p><strong>Rp <?= number_format($k->price, 0, ',', '.') ?></strong></p>
          <a href="<?= base_url('order/create?catalogue_id=' . $k->catalogue_id) ?>" class="btn btn-info mt-auto">Pesan Sekarang</a>
        </div>
      </div>
    </div>
  <?php endforeach; ?>
</div>

