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
                <label for="bpom" class="col-sm-2 col-form-label">BPOM</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control <?= ($validation->hasError('bpom')) ? 'is-invalid' : ''; ?>" id="bpom" name="bpom" value="<?= (old('bpom')) ? old('bpom') : $obat['bpom']; ?>" required>
                    <div class="invalid-feedback">
                        <?= $validation->getError('bpom'); ?>
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
                <label for="kategori_id" class="col-sm-2 col-form-label">Kategori</label>
                <div class="col-sm-10">
                    <select class="form-select <?= ($validation->hasError('kategori_id')) ? 'is-invalid' : ''; ?>" id="kategori_id" name="kategori_id" required>
                        <option value="" selected disabled>Pilih Kategori</option>
                        <?php foreach ($kategori as $k) : ?>
                            <option value="<?= $k['id']; ?>" <?= ((old('kategori_id')) ? old('kategori_id') : $obat['kategori_id']) == $k['id'] ? 'selected' : ''; ?>><?= $k['nama_kategori']; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <div class="invalid-feedback">
                        <?= $validation->getError('kategori_id'); ?>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <label for="satuan_id" class="col-sm-2 col-form-label">Satuan</label>
                <div class="col-sm-10">
                    <select class="form-select <?= ($validation->hasError('satuan_id')) ? 'is-invalid' : ''; ?>" id="satuan_id" name="satuan_id" required>
                        <option value="" selected disabled>Pilih Satuan</option>
                        <?php foreach ($satuan as $s) : ?>
                            <option value="<?= $s['id']; ?>" <?= ((old('satuan_id')) ? old('satuan_id') : $obat['satuan_id']) == $s['id'] ? 'selected' : ''; ?>><?= $s['nama_satuan']; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <div class="invalid-feedback">
                        <?= $validation->getError('satuan_id'); ?>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <label for="harga_beli" class="col-sm-2 col-form-label">Harga Beli</label>
                <div class="col-sm-10">
                    <input type="number" class="form-control <?= ($validation->hasError('harga_beli')) ? 'is-invalid' : ''; ?>" id="harga_beli" name="harga_beli" value="<?= (old('harga_beli')) ? old('harga_beli') : $obat['harga_beli']; ?>" required>
                    <div class="invalid-feedback">
                        <?= $validation->getError('harga_beli'); ?>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <label for="harga_jual" class="col-sm-2 col-form-label">Harga Jual</label>
                <div class="col-sm-10">
                    <input type="number" class="form-control <?= ($validation->hasError('harga_jual')) ? 'is-invalid' : ''; ?>" id="harga_jual" name="harga_jual" value="<?= (old('harga_jual')) ? old('harga_jual') : $obat['harga_jual']; ?>" required>
                    <div class="invalid-feedback">
                        <?= $validation->getError('harga_jual'); ?>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <label for="margin" class="col-sm-2 col-form-label">Margin (%)</label>
                <div class="col-sm-10">
                    <input type="number" class="form-control" id="margin" name="margin" value="<?= (old('margin')) ? old('margin') : $obat['margin']; ?>" readonly>
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
                <div class="col-sm-10 offset-sm-2">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <button type="reset" class="btn btn-secondary">Reset</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    const hargaBeli = document.getElementById('harga_beli');
    const hargaJual = document.getElementById('harga_jual');
    const margin = document.getElementById('margin');

    hargaBeli.addEventListener('keyup', function() {
        calculateMargin();
    });

    hargaJual.addEventListener('keyup', function() {
        calculateMargin();
    });

    function calculateMargin() {
        const beli = parseFloat(hargaBeli.value);
        const jual = parseFloat(hargaJual.value);

        if (isNaN(beli) || isNaN(jual)) {
            margin.value = '';
            return;
        }

        const calculatedMargin = ((jual - beli) / beli) * 100;
        margin.value = calculatedMargin.toFixed(2);
    }
</script>
<?= $this->endSection(); ?>
