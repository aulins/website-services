<h3><?= $title ?></h3>
<form method="post" enctype="multipart/form-data">
    <div class="mb-3">
        <label>Nama Paket</label>
        <input type="text" name="package_name" class="form-control" required value="<?= $katalog->package_name ?? '' ?>">
    </div>
    <div class="mb-3">
        <label>Kategori</label>
        <select name="categories" class="form-control" required>
            <?php
            $opsi = [
                'Toko online (e-Commerce)',
                'Company Profile',
                'Personal Profile Website',
                'Wedding',
                'Web3 / Blockchain Project',
                'Blog / Artikel Pribadi',
                'Landing Page',
                'Portfolio',
                'Kursus Online (e-Learning)',
                'Custom'
            ];
            foreach ($opsi as $kategori) {
                $selected = (isset($katalog) && $katalog->categories == $kategori) ? 'selected' : '';
                echo "<option value=\"$kategori\" $selected>$kategori</option>";
            }
            ?>
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
    <div class="mb-3">
        <label>Gambar (opsional)</label>
        <input type="file" name="image" class="form-control">
        <?php if (isset($katalog->image) && $katalog->image): ?>
            <img src="<?= base_url('uploads/catalogue/'.$katalog->image) ?>" alt="gambar" width="120" class="mt-2">
        <?php endif ?>
    </div>

    <button class="btn btn-success">Simpan</button>
</form>
