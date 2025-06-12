<h2>Form Pemesanan</h2>

<?php if ($this->session->flashdata('success')): ?>
    <div class="alert alert-success"><?= $this->session->flashdata('success') ?></div>
    <?php elseif ($this->session->flashdata('error')): ?>
    <div class="alert alert-danger"><?= $this->session->flashdata('error') ?></div>
    <?php endif; ?>

    <form method="post" action="<?= base_url('order/store') ?>" enctype="multipart/form-data">
    <div class="mb-3">
        <label>Nama</label>
        <input type="text" name="name" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Email</label>
        <input type="email" name="email" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>No HP</label>
        <input type="text" name="phone_number" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Pilihan Paket</label>
        <select name="catalogue_id" class="form-control" required>
        <option value="">-- Pilih Paket --</option>
        <?php foreach ($catalogues as $row): ?>
            <option value="<?= $row->catalogue_id ?>"><?= $row->package_name ?> - Rp <?= number_format($row->price,0,',','.') ?></option>
        <?php endforeach ?>
        </select>
    </div>
    <div class="mb-3">
        <label>Deadline Proyek</label>
        <input type="date" name="project_deadline" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Logo (Opsional)</label>
        <input type="file" name="logo" class="form-control">
    </div>
    <button type="submit" class="btn btn-success">Kirim Pemesanan</button>
</form>
