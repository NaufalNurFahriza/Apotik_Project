<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Tambah Obat</h1>
    <a href="<?= base_url('obat'); ?>" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
        <i class="fas fa-arrow-left fa-sm text-white-50"></i> Kembali
    </a>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Form Tambah Obat</h6>
    </div>
    <div class="card-body">
        <form action="<?= base_url('obat/simpan'); ?>" method="post">
            <?= csrf_field(); ?>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="bpom">BPOM <span class="text-danger">*</span></label>
                        <input type="text" class="form-control <?= ($validation->hasError('bpom')) ? 'is-invalid' : ''; ?>" 
                               id="bpom" name="bpom" value="<?= old('bpom'); ?>" required>
                        <div class="invalid-feedback">
                            <?= $validation->getError('bpom'); ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="nama_obat">Nama Obat <span class="text-danger">*</span></label>
                        <input type="text" class="form-control <?= ($validation->hasError('nama_obat')) ? 'is-invalid' : ''; ?>" 
                               id="nama_obat" name="nama_obat" value="<?= old('nama_obat'); ?>" required>
                        <div class="invalid-feedback">
                            <?= $validation->getError('nama_obat'); ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="produsen">Produsen <span class="text-danger">*</span></label>
                        <input type="text" class="form-control <?= ($validation->hasError('produsen')) ? 'is-invalid' : ''; ?>" 
                               id="produsen" name="produsen" value="<?= old('produsen'); ?>" required>
                        <div class="invalid-feedback">
                            <?= $validation->getError('produsen'); ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="supplier_id">Supplier <span class="text-danger">*</span></label>
                        <select class="form-control <?= ($validation->hasError('supplier_id')) ? 'is-invalid' : ''; ?>" 
                                id="supplier_id" name="supplier_id" required>
                            <option value="">Pilih Supplier</option>
                            <?php foreach (
                                $supplier as $s) : ?>
                                <option value="<?= $s['id']; ?>" <?= (old('supplier_id') == $s['id']) ? 'selected' : ''; ?>>
                                    <?= $s['nama_supplier']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <div class="invalid-feedback">
                            <?= $validation->getError('supplier_id'); ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Kategori <span class="text-danger">*</span></label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="kategori" id="resep" value="resep" 
                                   <?= (old('kategori') == 'resep') ? 'checked' : ''; ?> required>
                            <label class="form-check-label" for="resep">
                                <i class="fas fa-prescription-bottle-alt text-warning"></i> Obat Resep
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="kategori" id="non-resep" value="non-resep" 
                                   <?= (old('kategori') == 'non-resep') ? 'checked' : ''; ?> required>
                            <label class="form-check-label" for="non-resep">
                                <i class="fas fa-pills text-success"></i> Obat Bebas
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="satuan">Satuan <span class="text-danger">*</span></label>
                        <select class="form-control <?= ($validation->hasError('satuan')) ? 'is-invalid' : ''; ?>" 
                                id="satuan" name="satuan" required>
                            <option value="">Pilih Satuan</option>
                            <option value="Strip" <?= (old('satuan') == 'Strip') ? 'selected' : ''; ?>>Strip</option>
                            <option value="Botol" <?= (old('satuan') == 'Botol') ? 'selected' : ''; ?>>Botol</option>
                            <option value="Tube" <?= (old('satuan') == 'Tube') ? 'selected' : ''; ?>>Tube</option>
                            <option value="Sachet" <?= (old('satuan') == 'Sachet') ? 'selected' : ''; ?>>Sachet</option>
                            <option value="Kaplet" <?= (old('satuan') == 'Kaplet') ? 'selected' : ''; ?>>Kaplet</option>
                            <option value="Tablet" <?= (old('satuan') == 'Tablet') ? 'selected' : ''; ?>>Tablet</option>
                        </select>
                        <div class="invalid-feedback">
                            <?= $validation->getError('satuan'); ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="harga_beli">Harga Beli <span class="text-danger">*</span></label>
                        <input type="number" class="form-control <?= ($validation->hasError('harga_beli')) ? 'is-invalid' : ''; ?>" 
                               id="harga_beli" name="harga_beli" value="<?= old('harga_beli'); ?>" required>
                        <div class="invalid-feedback">
                            <?= $validation->getError('harga_beli'); ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="harga_jual">Harga Jual <span class="text-danger">*</span></label>
                        <input type="number" class="form-control <?= ($validation->hasError('harga_jual')) ? 'is-invalid' : ''; ?>" 
                               id="harga_jual" name="harga_jual" value="<?= old('harga_jual'); ?>" required>
                        <div class="invalid-feedback">
                            <?= $validation->getError('harga_jual'); ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan
                </button>
                <button type="reset" class="btn btn-secondary">
                    <i class="fas fa-undo"></i> Reset
                </button>
                <a href="<?= base_url('obat'); ?>" class="btn btn-light">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </form>

        <script>
        // Auto calculate margin
        document.getElementById('harga_beli').addEventListener('input', function() {
            const hargaBeli = parseInt(this.value) || 0;
            const margin = Math.round(hargaBeli * 0.3); // 30% margin
            const hargaJual = hargaBeli + margin;
            document.getElementById('harga_jual').value = hargaJual;
        });
        </script>
    </div>
</div>
<?= $this->endSection(); ?>
