<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Detail Transaksi Pembelian</h1>
    <div>
        <a href="<?= base_url('transaksi-pembelian'); ?>" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Kembali
        </a>
        <a href="<?= base_url('transaksi-pembelian/faktur/' . $transaksi['id']); ?>" class="d-none d-sm-inline-block btn btn-sm btn-success shadow-sm">
            <i class="fas fa-print fa-sm text-white-50"></i> Cetak Faktur
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
                        <th>Nomor Faktur</th>
                        <td>: <?= $transaksi['nomor_faktur'] ?? 'PB-' . str_pad($transaksi['id'], 5, '0', STR_PAD_LEFT); ?></td>
                    </tr>
                    <tr>
                        <th>Tanggal</th>
                        <td>: <?= date('d-m-Y H:i', strtotime($transaksi['tanggal'])); ?></td>
                    </tr>
                    <tr>
                        <th>TTK</th>
                        <td>: <?= $transaksi['nama_user']; ?></td>
                    </tr>
                    <tr>
                        <th>Supplier</th>
                        <td>: <?= $transaksi['nama_supplier']; ?></td>
                    </tr>
                    <tr>
                        <th>Total</th>
                        <td>: Rp <?= number_format($transaksi['total'], 0, ',', '.'); ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    
    <div class="col-lg-6">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Informasi Supplier</h6>
            </div>
            <div class="card-body">
                <table class="table">
                    <tr>
                        <th>Nama Supplier</th>
                        <td>: <?= $transaksi['nama_supplier']; ?></td>
                    </tr>
                    <tr>
                        <th>Alamat</th>
                        <td>: <?= $transaksi['alamat_supplier']; ?></td>
                    </tr>
                    <tr>
                        <th>Kota</th>
                        <td>: <?= $transaksi['kota_supplier']; ?></td>
                    </tr>
                    <tr>
                        <th>Telepon</th>
                        <td>: <?= $transaksi['telepon_supplier']; ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<?php if (!empty($transaksi['keterangan'])): ?>
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Keterangan</h6>
    </div>
    <div class="card-body">
        <p><?= nl2br(htmlspecialchars($transaksi['keterangan'])); ?></p>
    </div>
</div>
<?php endif; ?>

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
                        <th>Kode Obat</th>
                        <th>Nama Obat</th>
                        <th>Harga Beli</th>
                        <th>Jumlah</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1; ?>
                    <?php foreach ($detail as $d) : ?>
                        <tr>
                            <td><?= $i++; ?></td>
                            <td><?= $d['bpom'] ?? '-'; ?></td>
                            <td><?= $d['nama_obat']; ?></td>
                            <td>Rp <?= number_format($d['harga_beli'], 0, ',', '.'); ?></td>
                            <td><?= $d['qty']; ?> <?= $d['satuan'] ?? 'pcs'; ?></td>
                            <td>Rp <?= number_format($d['qty'] * $d['harga_beli'], 0, ',', '.'); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="5" class="text-right">Total Pembelian</th>
                        <th>Rp <?= number_format($transaksi['total'], 0, ',', '.'); ?></th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>
