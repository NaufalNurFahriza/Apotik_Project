<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Edit Obat</h1>
    <a href="<?= base_url('obat'); ?>" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
        <i class="fas fa-arrow-left fa-sm text-white-50"></i> Kembali
    </a>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Form Edit Obat</h6>
    </div>
    <div class="card-body">
        <form action="<?= base_url('obat/update/' . $obat['id']); ?>" method="post" enctype="multipart/form-data">
            <?= csrf_field(); ?>
            <input type="hidden" name="id" value="<?= $obat['id']; ?>">
            <div class="row mb-3">
                <label for="kode_obat" class="col-sm-2 col-form-label">Kode Obat</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control <?= ($validation->hasError('kode_obat')) ? 'is-invalid' : ''; ?>" id="kode_obat" name="kode_obat" value="<?= (old('kode_obat')) ? old('kode_obat') : $obat['kode_obat']; ?>" required>
                    <div class="invalid-feedback">
                        <?= $validation->getError('kode_obat'); ?>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <label for="nama_obat" class="col-sm-2 col-form-label">Nama Obat</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control <?= ($validation->hasError('nama_obat')) ? 'is-invalid' : ''; ?>" id="nama_obat" name="nama_obat" value="<?= (old('nama_obat')) ? old('nama_obat') : $obat['nama_obat']; ?>" required>
                    <div class="invalid-feedback">
                        <?= $validation->getError('nama_obat'); ?>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <label for="harga" class="col-sm-2 col-form-label">Harga</label>
                <div class="col-sm-10">
                    <input type="number" class="form-control <?= ($validation->hasError('harga')) ? 'is-invalid' : ''; ?>" id="harga" name="harga" value="<?= (old('harga')) ? old('harga') : $obat['harga']; ?>" required>
                    <div class="invalid-feedback">
                        <?= $validation->getError('harga'); ?>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <label for="produsen" class="col-sm-2 col-form-label">Produsen</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control <?= ($validation->hasError('produsen')) ? 'is-invalid' : ''; ?>" id="produsen" name="produsen" value="<?= (old('produsen')) ? old('produsen') : $obat['produsen']; ?>" required>
                    <div class="invalid-feedback">
                        <?= $validation->getError('produsen'); ?>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <label for="supplier_id" class="col-sm-2 col-form-label">Supplier</label>
                <div class="col-sm-10">
                    <select class="form-select <?= ($validation->hasError('supplier_id')) ? 'is-invalid' : ''; ?>" id="supplier_id" name="supplier_id" required>
                        <option value="" selected disabled>Pilih Supplier</option>
                        <?php foreach ($supplier as $s) : ?>
                            <option value="<?= $s['id']; ?>" <?= ((old('supplier_id')) ? old('supplier_id') : $obat['supplier_id']) == $s['id'] ? 'selected' : ''; ?>><?= $s['nama_supplier']; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <div class="invalid-feedback">
                        <?= $validation->getError('supplier_id'); ?>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <label for="stok" class="col-sm-2 col-form-label">Stok</label>
                <div class="col-sm-10">
                    <input type="number" class="form-control <?= ($validation->hasError('stok')) ? 'is-invalid' : ''; ?>" id="stok" name="stok" value="<?= (old('stok')) ? old('stok') : $obat['stok']; ?>" required>
                    <div class="invalid-feedback">
                        <?= $validation->getError('stok'); ?>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <label for="gambar" class="col-sm-2 col-form-label">Gambar</label>
                <div class="col-sm-10">
                    <div class="row">
                        <div class="col-sm-3">
                            <img src="<?= base_url('uploads/obat/' . $obat['gambar']); ?>" class="img-thumbnail img-preview" alt="<?= $obat['nama_obat']; ?>">
                        </div>
                        <div class="col-sm-9">
                            <input type="file" class="form-control <?= ($validation->hasError('gambar')) ? 'is-invalid' : ''; ?>" id="gambar" name="gambar">
                            <div class="invalid-feedback">
                                <?= $validation->getError('gambar'); ?>
                            </div>
                            <div class="form-text">Upload gambar obat (opsional). Format: jpg, jpeg, png. Maks: 2MB</div>
                        </div>
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