<h1 class="text-center">Pembuatan Website Profesional</h1>
<!-- Menampilkan katalog -->
<div class="row text-center mt-4">
    <?php foreach ($catalogues as $catalogue): ?>
        <div class="col-md-4">
            <div class="border p-3">
                <h5><?= $catalogue->package_name ?></h5>
                <p><?= $catalogue->description ?></p>
                <p><strong>Rp <?= number_format($catalogue->price, 0, ',', '.') ?></strong></p>
            </div>
        </div>
    <?php endforeach; ?>
</div>
<h3 class="mt-5">Ulasan Pengguna</h3>
<div class="row">
	<?php foreach ($reviews as $review): ?>
		<div class="col-md-4">
		<div class="border p-3 mb-3 rounded shadow-sm">
			<strong><?= $review->name ?></strong><br>
			Rating: <?= str_repeat('⭐', $review->rating) ?><br>
			<p><?= $review->comment ?></p>
		</div>
		</div>
	<?php endforeach; ?>
</div>
