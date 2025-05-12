<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Edit Admin</h1>
    <a href="<?= base_url('admin'); ?>" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
        <i class="fas fa-arrow-left fa-sm text-white-50"></i> Kembali
    </a>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Form Edit Admin</h6>
    </div>
    <div class="card-body">
        <form action="<?= base_url('admin/update/' . $admin['id']); ?>" method="post">
            <?= csrf_field(); ?>
            <input type="hidden" name="id" value="<?= $admin['id']; ?>">
            <div class="row mb-3">
                <label for="nama_admin" class="col-sm-2 col-form-label">Nama Admin</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control <?= ($validation->hasError('nama_admin')) ? 'is-invalid' : ''; ?>" id="nama_admin" name="nama_admin" value="<?= (old('nama_admin')) ? old('nama_admin') : $admin['nama_admin']; ?>" required>
                    <div class="invalid-feedback">
                        <?= $validation->getError('nama_admin'); ?>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <label for="username" class="col-sm-2 col-form-label">Username</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control <?= ($validation->hasError('username')) ? 'is-invalid' : ''; ?>" id="username" name="username" value="<?= (old('username')) ? old('username') : $admin['username']; ?>" required>
                    <div class="invalid-feedback">
                        <?= $validation->getError('username'); ?>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <label for="password" class="col-sm-2 col-form-label">Password</label>
                <div class="col-sm-10">
                    <input type="password" class="form-control <?= ($validation->hasError('password')) ? 'is-invalid' : ''; ?>" id="password" name="password">
                    <div class="invalid-feedback">
                        <?= $validation->getError('password'); ?>
                    </div>
                    <div class="form-text">Kosongkan jika tidak ingin mengubah password</div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-10 offset-sm-2">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <button type="reset" class="btn btn-secondary">Reset</button>
                </div>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection(); ?>
