<h3>Daftar Katalog</h3>
<a href="<?= base_url('catalogue/create') ?>" class="btn btn-primary mb-3">Tambah Katalog</a>
<table class="table table-bordered">
    <thead>
        <tr>
        <th>#</th>
        <th>Nama Paket</th>
        <th>Kategori</th>
        <th>Harga</th>
        <th>Status</th>
        <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php $no=1; foreach($catalogues as $row): ?>
        <tr>
        <td><?= $no++ ?></td>
        <td><?= $row->package_name ?></td>
        <td><?= $row->categories ?></td>
        <td>Rp <?= number_format($row->price, 0, ',', '.') ?></td>
        <td><?= $row->status_publish ?></td>
        <td>
            <a href="<?= base_url('catalogue/edit/'.$row->catalogue_id) ?>" class="btn btn-sm btn-warning">Edit</a>
            <a href="<?= base_url('catalogue/delete/'.$row->catalogue_id) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus katalog ini?')">Hapus</a>
        </td>
        </tr>
        <?php endforeach ?>
    </tbody>
</table>
