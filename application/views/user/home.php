<h1 class="text-center">Pembuatan Website Profesional</h1>
<div class="row text-center mt-4">
	<div class="col-md-4"><div class="border p-3">Katalog 1</div></div>
	<div class="col-md-4"><div class="border p-3">Katalog 2</div></div>
	<div class="col-md-4"><div class="border p-3">Katalog 3</div></div>
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
