<h2>Form Ulasan</h2>

<?php if ($this->session->flashdata('success')): ?>
    <div class="alert alert-success"><?= $this->session->flashdata('success') ?></div>
    <?php endif; ?>

    <form method="post" action="<?= base_url('review/store') ?>">
    <div class="mb-3">
        <label>Nama Anda</label>
        <input type="text" name="name" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Pilih Pesanan</label>
        <select name="order_id" class="form-control" required>
        <option value="">-- Pilih Paket --</option>
        <?php foreach ($orders as $o): ?>
            <option value="<?= $o->order_id ?>"><?= $o->name ?> - <?= $o->project_deadline ?></option>
        <?php endforeach ?>
        </select>
    </div>
    <div class="mb-3">
        <label>Rating (1 - 5)</label>
        <input type="number" name="rating" min="1" max="5" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Ulasan</label>
        <textarea name="comment" class="form-control" required></textarea>
    </div>
    <button class="btn btn-primary">Kirim Ulasan</button>
</form>
