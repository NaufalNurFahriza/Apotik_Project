<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Beli Obat dari Supplier</h1>
    <a href="<?= base_url('transaksi'); ?>" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
        <i class="fas fa-arrow-left fa-sm text-white-50"></i> Kembali
    </a>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Form Pembelian Obat dari Supplier</h6>
    </div>
    <div class="card-body">
        <form action="<?= base_url('transaksi/simpanPembelian'); ?>" method="post">
            <?= csrf_field(); ?>
            <div class="row mb-3">
                <label for="obat_id" class="col-sm-2 col-form-label">Obat</label>
                <div class="col-sm-10">
                    <select class="form-select" id="obat_id" name="obat_id" required>
                        <option value="" selected disabled>Pilih Obat</option>
                        <?php foreach ($obat as $o) : ?>
                            <option value="<?= $o['id']; ?>"><?= $o['nama_obat']; ?> - Supplier: <?= $o['nama_supplier']; ?> (Stok: <?= $o['stok']; ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="row mb-3">
                <label for="jumlah" class="col-sm-2 col-form-label">Jumlah</label>
                <div class="col-sm-10">
                    <input type="number" class="form-control" id="jumlah" name="jumlah" min="1" required>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-10 offset-sm-2">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <button type="reset" class="btn btn-secondary">Reset</button>
                </div>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection(); ?>