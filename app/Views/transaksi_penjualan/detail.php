<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Detail Transaksi Penjualan</h1>
    <div>
        <a href="<?= base_url('transaksi-penjualan'); ?>" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Kembali
        </a>
        <a href="<?= base_url('transaksi-penjualan/struk/' . $transaksi['id']); ?>" class="d-none d-sm-inline-block btn btn-sm btn-success shadow-sm">
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
                        <th>Nomor Faktur</th>
                        <td>: <?= $transaksi['no_faktur_jual'] ?? 'PJ-' . str_pad($transaksi['id'], 5, '0', STR_PAD_LEFT); ?></td>
                    </tr>
                    <tr>
                        <th>Tanggal</th>
                        <td>: <?= date('d-m-Y H:i', strtotime($transaksi['tanggal_transaksi'])); ?></td>
                    </tr>
                    <tr>
                        <th>TTK</th>
                        <td>: <?= $transaksi['nama_admin'] ?? $transaksi['nama_user']; ?></td>
                    </tr>
                    <tr>
                        <th>Pembeli</th>
                        <td>: <?= $transaksi['nama_pembeli']; ?></td>
                    </tr>
                    <tr>
                        <th>Member</th>
                        <td>: <?= $transaksi['nama_member'] ? $transaksi['nama_member'] : '-'; ?></td>
                    </tr>
                    <?php if ($transaksi['member_id']): ?>
                        <tr>
                            <th>Poin Didapat</th>
                            <td>: <?= $transaksi['poin_didapat']; ?></td>
                        </tr>
                        <tr>
                            <th>Poin Digunakan</th>
                            <td>: <?= $transaksi['poin_digunakan'] ?? 0; ?></td>
                        </tr>
                    <?php endif; ?>
                    <tr>
                        <th>Subtotal</th>
                        <td>: Rp <?= number_format($transaksi['total'] + ($transaksi['potongan_harga'] ?? 0), 0, ',', '.'); ?></td>
                    </tr>
                    <?php if (isset($transaksi['potongan_harga']) && $transaksi['potongan_harga'] > 0): ?>
                        <tr>
                            <th>Potongan Poin</th>
                            <td>: Rp <?= number_format($transaksi['potongan_harga'], 0, ',', '.'); ?></td>
                        </tr>
                    <?php endif; ?>
                    <tr>
                        <th>Total</th>
                        <td>: Rp <?= number_format($transaksi['total'], 0, ',', '.'); ?></td>
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
                        <th>Kode Obat</th>
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
                            <td><?= $d['bpom'] ?? $d['kode_obat']; ?></td>
                            <td><?= $d['nama_obat']; ?></td>
                            <td>Rp <?= number_format($d['harga_saat_ini'] ?? $d['harga_jual'], 0, ',', '.'); ?></td>
                            <td><?= $d['qty']; ?></td>
                            <td>Rp <?= number_format(($d['harga_saat_ini'] ?? $d['harga_jual']) * $d['qty'], 0, ',', '.'); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="5" class="text-right">Subtotal</th>
                        <th>Rp <?= number_format($transaksi['total'] + ($transaksi['potongan_harga'] ?? 0), 0, ',', '.'); ?></th>
                    </tr>
                    <?php if (isset($transaksi['potongan_harga']) && $transaksi['potongan_harga'] > 0): ?>
                        <tr>
                            <th colspan="5" class="text-right">Potongan Poin (<?= $transaksi['poin_digunakan']; ?> poin)</th>
                            <th>Rp <?= number_format($transaksi['potongan_harga'], 0, ',', '.'); ?></th>
                        </tr>
                    <?php endif; ?>
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
