<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Faktur Pembelian</h1>
    <div>
        <a href="<?= base_url('transaksi-pembelian'); ?>" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm no-print">
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
            <h3>FAKTUR PEMBELIAN</h3>
            <h4>APOTEK KITA FARMA</h4>
            <p>Jl. Raya Purwogondo Guo Sobo Kerto KM 3, Cikal, Telukwetan, Kec. Welahan, Kabupaten Jepara, Jawa Tengah<br>Telp: 085292115588</p>
            <hr>
        </div>
        
        <div class="row mb-4">
            <div class="col-md-6">
                <h5>Dari:</h5>
                <p>
                    <strong><?= $transaksi['nama_supplier']; ?></strong><br>
                    <?= $transaksi['alamat_supplier']; ?><br>
                    <?= $transaksi['kota_supplier']; ?><br>
                    Telp: <?= $transaksi['telepon_supplier']; ?>
                </p>
            </div>
            <div class="col-md-6 text-right">
                <h5>Kepada:</h5>
                <p>
                    <strong>APOTEK KITA FARMA</strong><br>
                    Jl. Raya Purwogondo Guo Sobo Kerto KM 3<br>
                    Cikal, Telukwetan, Kec. Welahan<br>
                    Kabupaten Jepara, Jawa Tengah
                </p>
            </div>
        </div>
        
        <div class="row mb-3">
            <div class="col-md-6">
                <p>
                    <strong>No. Faktur Internal:</strong> <?= $transaksi['nomor_faktur'] ?? 'PB-' . str_pad($transaksi['id'], 5, '0', STR_PAD_LEFT); ?><br>
                    <strong>No. Faktur Supplier:</strong> <?= $transaksi['nomor_faktur_supplier']; ?><br>
                    <strong>Tanggal:</strong> <?= date('d-m-Y H:i', strtotime($transaksi['tanggal_transaksi'])); ?>
                </p>
            </div>
            <div class="col-md-6 text-right">
                <p>
                    <strong>TTK:</strong> <?= $transaksi['nama_user']; ?><br>
                    <strong>Status:</strong> <span class="badge badge-success">Selesai</span>
                </p>
            </div>
        </div>
        
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="thead-light">
                    <tr>
                        <th>#</th>
                        <th>Kode Obat</th>
                        <th>Nama Obat</th>
                        <th class="text-center">Harga Beli</th>
                        <th class="text-center">Qty</th>
                        <th class="text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; ?>
                    <?php foreach ($detail as $d) : ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= $d['bpom'] ?? '-'; ?></td>
                            <td><?= $d['nama_obat']; ?></td>
                            <td class="text-center">Rp <?= number_format($d['harga_beli'], 0, ',', '.'); ?></td>
                            <td class="text-center"><?= $d['qty']; ?></td>
                            <td class="text-right">Rp <?= number_format($d['subtotal'], 0, ',', '.'); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr class="font-weight-bold">
                        <th colspan="5" class="text-right">Total Pembelian</th>
                        <th class="text-right">Rp <?= number_format($transaksi['total'], 0, ',', '.'); ?></th>
                    </tr>
                </tfoot>
            </table>
        </div>
        
        <?php if (!empty($transaksi['keterangan'])): ?>
        <div class="mt-3">
            <strong>Keterangan:</strong><br>
            <?= nl2br(htmlspecialchars($transaksi['keterangan'])); ?>
        </div>
        <?php endif; ?>
        
        <div class="row mt-5">
            <div class="col-md-6">
                <div class="text-center">
                    <p>Supplier</p>
                    <br><br><br>
                    <p>_____________________</p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="text-center">
                    <p>TTK Apotek</p>
                    <br><br><br>
                    <p>_____________________<br><?= $transaksi['nama_user']; ?></p>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>
