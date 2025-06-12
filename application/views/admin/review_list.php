<h3>Moderasi Ulasan</h3>

<table class="table table-bordered">
    <thead>
        <tr>
        <th>Nama</th>
        <th>Paket</th>
        <th>Rating</th>
        <th>Ulasan</th>
        <th>Status</th>
        <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($reviews as $r): ?>
        <tr>
        <td><?= $r->name ?></td>
        <td><?= $r->package_name ?></td>
        <td><?= $r->rating ?></td>
        <td><?= $r->comment ?></td>
        <td><?= $r->is_approved == 'Y' ? 'Disetujui' : 'Menunggu' ?></td>
        <td>
            <?php if ($r->is_approved == 'N'): ?>
            <a href="<?= base_url('reviewadmin/approve/'.$r->review_id) ?>" class="btn btn-sm btn-success">Setujui</a>
            <?php else: ?>
            <span class="text-success">✔</span>
            <?php endif ?>
        </td>
        </tr>
        <?php endforeach ?>
    </tbody>
</table>
