<h3>Daftar Pesanan</h3>

<table class="table table-bordered">
    <thead>
        <tr>
        <th>Nama</th>
        <th>Email</th>
        <th>No HP</th>
        <th>Paket</th>
        <th>Deadline</th>
        <th>Logo</th>
        <th>Status</th>
        <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($orders as $row): ?>
        <tr>
        <td><?= $row->name ?></td>
        <td><?= $row->email ?></td>
        <td><?= $row->phone_number ?></td>
        <td><?= $row->package_name ?></td>
        <td><?= $row->project_deadline ?></td>
        <td>
            <?php if ($row->logo): ?>
            <a href="<?= base_url('uploads/logos/'.$row->logo) ?>" target="_blank">Lihat</a>
            <?php else: ?>
            -
            <?php endif ?>
        </td>
        <td><strong><?= strtoupper($row->status) ?></strong></td>
        <td>
            <form method="post" action="<?= base_url('orderadmin/update_status/'.$row->order_id) ?>" class="d-flex">
            <select name="status" class="form-select form-select-sm me-2">
                <option value="requested" <?= $row->status == 'requested' ? 'selected' : '' ?>>Requested</option>
                <option value="approved" <?= $row->status == 'approved' ? 'selected' : '' ?>>Approved</option>
                <option value="completed" <?= $row->status == 'completed' ? 'selected' : '' ?>>Completed</option>
                <option value="rejected" <?= $row->status == 'rejected' ? 'selected' : '' ?>>Rejected</option>
            </select>
            <button class="btn btn-sm btn-primary">Update</button>
            </form>
        </td>
        </tr>
        <?php endforeach ?>
    </tbody>
</table>
