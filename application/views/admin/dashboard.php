<h2>Ringkasan dan Statistik Pesanan</h2>

<div class="row my-4">
    <div class="col-md-6">
        <div class="bg-primary text-white p-4 rounded">
        <h5>Pesanan Selesai Bulan Ini</h5>
        <h2><?= $pesanan_selesai ?></h2>
        </div>
    </div>
    <div class="col-md-6">
        <div class="bg-success text-white p-4 rounded">
        <h5>Total Pendapatan Bulan Ini</h5>
        <h2>Rp <?= number_format($pendapatan, 0, ',', '.') ?></h2>
        </div>
    </div>
</div>
