<h3>Laporan Penjualan</h3>

    <form method="post" action="<?= base_url('reportadmin') ?>">
        <div class="row">
            <div class="col-md-3">
            <label>Tanggal Mulai</label>
            <input type="date" name="start_date" class="form-control" value="<?= $start_date ?>" required>
            </div>
            <div class="col-md-3">
            <label>Tanggal Selesai</label>
            <input type="date" name="end_date" class="form-control" value="<?= $end_date ?>" required>
            </div>
            <div class="col-md-3">
            <button type="submit" class="btn btn-primary mt-4">Tampilkan Laporan</button>
            </div>
        </div>
        </form>

        <hr>

        <h4>Rekap Penjualan</h4>
        <p>Total Pesanan: <?= $sales_report->total_orders ?></p>
        <p>Total Pendapatan: Rp <?= number_format($sales_report->total_sales, 0, ',', '.') ?></p>

        <hr>

        <h4>Daftar Pesanan</h4>
        <table class="table table-bordered">
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Nama Pemesan</th>
                <th>Nama Paket</th>
                <th>Kategori</th>
                <th>Harga</th>
                <th>Tanggal Pesanan</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($order_report as $order): ?>
                <tr>
                    <td><?= $order->order_id ?></td>
                    <td><?= $order->name ?></td>
                    <td><?= $order->package_name ?></td>
                    <td><?= $order->categories ?></td>
                    <td>Rp <?= number_format($order->price, 0, ',', '.') ?></td>
                    <td><?= $order->created_at ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>

        </table>

        <hr>

        <form method="post" action="<?= base_url('reportadmin/download_report') ?>" class="d-flex">
        <input type="hidden" name="start_date" value="<?= $start_date ?>">
        <input type="hidden" name="end_date" value="<?= $end_date ?>">
        <button type="submit" class="btn btn-success">Download Laporan</button>
</form>
