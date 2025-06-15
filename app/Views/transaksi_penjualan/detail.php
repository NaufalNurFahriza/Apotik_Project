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

## 5. Update View Struk Transaksi

\`\`\`php project="Apotek Kita Farma" file="app/Views/transaksi/struk.php" type="code"
<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Struk Transaksi</h1>
    <div>
        <a href="<?= base_url('transaksi'); ?>" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm no-print">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Kembali
        </a>
        <button onclick="window.print()" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm no-print">
            <i class="fas fa-print fa-sm text-white-50"></i> Cetak
        </button>
    </div>
</div>

<div class="card shadow mb-4">
    <div class="card-body">
        <div class="text-center mb-4">
            <h3>APOTEK KITA FARMA</h3>
            <p>Jl. Contoh No. 123, Kota Contoh<br>Telp: 021-1234567</p>
            <hr>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <p>
                    <strong>No. Transaksi:</strong> #<?= str_pad($transaksi['id'], 5, '0', STR_PAD_LEFT); ?><br>
                    <strong>Tanggal:</strong> <?= date('d-m-Y H:i', strtotime($transaksi['tanggal_transaksi'])); ?><br>
                    <strong>Kasir:</strong> <?= $transaksi['nama_admin']; ?>
                </p>
            </div>
            <div class="col-md-6 text-md-end">
                <p>
                    <strong>Pembeli:</strong> <?= $transaksi['nama_pembeli']; ?><br>
                    <?php if ($transaksi['nama_member']) : ?>
                        <strong>Member:</strong> <?= $transaksi['nama_member']; ?><br>
                        <strong>Poin Didapat:</strong> <?= $transaksi['poin_didapat']; ?>
                        <?php if (isset($transaksi['poin_digunakan']) && $transaksi['poin_digunakan'] > 0): ?>
                            <br><strong>Poin Digunakan:</strong> <?= $transaksi['poin_digunakan']; ?>
                        <?php endif; ?>
                    <?php endif; ?>
                </p>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Obat</th>
                        <th class="text-center">Harga</th>
                        <th class="text-center">Qty</th>
                        <th class="text-end">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($detail as $d) : ?>
                        <tr>
                            <td><?= $d['nama_obat']; ?></td>
                            <td class="text-center">Rp <?= number_format($d['harga_saat_ini'], 0, ',', '.'); ?></td>
                            <td class="text-center"><?= $d['qty']; ?></td>
                            <td class="text-end">Rp <?= number_format($d['harga_saat_ini'] * $d['qty'], 0, ',', '.'); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="3" class="text-end">Subtotal</th>
                        <th class="text-end">Rp <?= number_format($transaksi['total'] + ($transaksi['potongan_harga'] ?? 0), 0, ',', '.'); ?></th>
                    </tr>
                    <?php if (isset($transaksi['potongan_harga']) && $transaksi['potongan_harga'] > 0): ?>
                        <tr>
                            <th colspan="3" class="text-end">Potongan Poin (<?= $transaksi['poin_digunakan']; ?> poin)</th>
                            <th class="text-end">Rp <?= number_format($transaksi['potongan_harga'], 0, ',', '.'); ?></th>
                        </tr>
                    <?php endif; ?>
                    <tr>
                        <th colspan="3" class="text-end">Total</th>
                        <th class="text-end">Rp <?= number_format($transaksi['total'], 0, ',', '.'); ?></th>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="text-center mt-4">
            <p>Terima kasih telah berbelanja di Apotek Kita Farma</p>
            <p>Semoga lekas sembuh</p>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>
