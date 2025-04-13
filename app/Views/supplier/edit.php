<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Edit Supplier</h1>
    <a href="<?= base_url('supplier'); ?>" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
        <i class="fas fa-arrow-left fa-sm text-white-50"></i> Kembali
    </a>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Form Edit Supplier</h6>
    </div>
    <div class="card-body">
        <form action="<?= base_url('supplier/update/' . $supplier['id']); ?>" method="post">
            <?= csrf_field(); ?>
            <input type="hidden" name="id" value="<?= $supplier['id']; ?>">
            <div class="row mb-3">
                <label for="nama_supplier" class="col-sm-2 col-form-label">Nama Supplier</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control <?= ($validation->hasError('nama_supplier')) ? 'is-invalid' : ''; ?>" id="nama_supplier" name="nama_supplier" value="<?= (old('nama_supplier')) ? old('nama_supplier') : $supplier['nama_supplier']; ?>" required>
                    <div class="invalid-feedback">
                        <?= $validation->getError('nama_supplier'); ?>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <label for="alamat" class="col-sm-2 col-form-label">Alamat</label>
                <div class="col-sm-10">
                    <textarea class="form-control <?= ($validation->hasError('alamat')) ? 'is-invalid' : ''; ?>" id="alamat" name="alamat" rows="3" required><?= (old('alamat')) ? old('alamat') : $supplier['alamat']; ?></textarea>
                    <div class="invalid-feedback">
                        <?= $validation->getError('alamat'); ?>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <label for="kota" class="col-sm-2 col-form-label">Kota</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control <?= ($validation->hasError('kota')) ? 'is-invalid' : ''; ?>" id="kota" name="kota" value="<?= (old('kota')) ? old('kota') : $supplier['kota']; ?>" required>
                    <div class="invalid-feedback">
                        <?= $validation->getError('kota'); ?>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <label for="telepon" class="col-sm-2 col-form-label">Telepon</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control <?= ($validation->hasError('telepon')) ? 'is-invalid' : ''; ?>" id="telepon" name="telepon" value="<?= (old('telepon')) ? old('telepon') : $supplier['telepon']; ?>" required>
                    <div class="invalid-feedback">
                        <?= $validation->getError('telepon'); ?>
                    </div>
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