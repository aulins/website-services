<div class="row mx-auto justify-content-center">
    <div class="col ">
        <h3 class="text-center">Login Admin</h3>
        <?php if ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger"><?= $this->session->flashdata('error') ?></div>
        <?php endif; ?>
        <form method="post" action="<?= base_url('auth/login') ?>">
        <div class="mb-3">
            <label for="username">Username</label>
            <input type="text" class="form-control" name="username" required>
        </div>
        <div class="mb-3">
            <label for="password">Password</label>
            <input type="password" class="form-control" name="password" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Login</button>
        </form>
    </div>
</div>
