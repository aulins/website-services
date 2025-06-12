<h3><?= $title ?></h3>
<form method="post">
    <div class="mb-3">
        <label>Nama Paket</label>
        <input type="text" name="package_name" class="form-control" required value="<?= $katalog->package_name ?? '' ?>">
    </div>
    <div class="mb-3">
        <label>Kategori</label>
        <select name="categories" class="form-control" required>
        <option value="Toko Online" <?= (isset($katalog) && $katalog->categories == 'Toko Online') ? 'selected' : '' ?>>Toko Online</option>
        <option value="Perusahaan" <?= (isset($katalog) && $katalog->categories == 'Perusahaan') ? 'selected' : '' ?>>Perusahaan</option>
        <option value="Custom" <?= (isset($katalog) && $katalog->categories == 'Custom') ? 'selected' : '' ?>>Custom</option>
        </select>
    </div>
    <div class="mb-3">
        <label>Deskripsi</label>
        <textarea name="description" class="form-control"><?= $katalog->description ?? '' ?></textarea>
    </div>
    <div class="mb-3">
        <label>Harga</label>
        <input type="number" name="price" class="form-control" required value="<?= $katalog->price ?? '' ?>">
    </div>
    <div class="mb-3">
        <label>Status Publish</label>
        <select name="status_publish" class="form-control" required>
        <option value="Y" <?= (isset($katalog) && $katalog->status_publish == 'Y') ? 'selected' : '' ?>>Y</option>
        <option value="N" <?= (isset($katalog) && $katalog->status_publish == 'N') ? 'selected' : '' ?>>N</option>
        </select>
    </div>
    <button class="btn btn-success">Simpan</button>
</form>
