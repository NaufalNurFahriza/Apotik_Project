<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Detail Transaksi</h1>
    <div>
        <a href="<?= base_url('transaksi'); ?>" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Kembali
        </a>
        <a href="<?= base_url('transaksi/struk/' . $transaksi['id']); ?>" class="d-none d-sm-inline-block btn btn-sm btn-success shadow-sm">
            <i class="fas fa-print fa-sm text-white-50"></i> Cetak Struk
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-6">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Informasi Transaksi</h6>
            </div>
            <div class="card-body">
                <table class="table">
                    <tr>
                        <th>Tanggal</th>
                        <td>: <?= date('d-m-Y H:i', strtotime($transaksi['tanggal_transaksi'])); ?></td>
                    </tr>
                    <tr>
                        <th>Admin</th>
                        <td>: <?= $transaksi['nama_admin']; ?></td>
                    </tr>
                    <tr>
                        <th>Pembeli</th>
                        <td>: <?= $transaksi['nama_pembeli']; ?></td>
                    </tr>
                    <tr>
                        <th>Member</th>
                        <td>: <?= $transaksi['nama_member'] ? $transaksi['nama_member'] : '-'; ?></td>
                    </tr>
                    <tr>
                        <th>Total</th>
                        <td>: Rp <?= number_format($transaksi['total'], 0, ',', '.'); ?></td>
                    </tr>
                    <tr>
                        <th>Poin Didapat</th>
                        <td>: <?= $transaksi['poin_didapat']; ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Detail Obat</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>BPOM</th>
                        <th>Nama Obat</th>
                        <th>Harga</th>
                        <th>Jumlah</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1; ?>
                    <?php foreach ($detail as $d) : ?>
                        <tr>
                            <td><?= $i++; ?></td>
                            <td><?= $d['bpom']; ?></td>
                            <td><?= $d['nama_obat']; ?></td>
                            <td>Rp <?= number_format($d['harga_saat_ini'], 0, ',', '.'); ?></td>
                            <td><?= $d['qty']; ?></td>
                            <td>Rp <?= number_format($d['harga_saat_ini'] * $d['qty'], 0, ',', '.'); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="5" class="text-right">Total</th>
                        <th>Rp <?= number_format($transaksi['total'], 0, ',', '.'); ?></th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>