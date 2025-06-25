<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<style>
@media print {
    .no-print { display: none !important; }
    body { margin: 0; }
    .card { border: none !important; box-shadow: none !important; }
}
.invoice-header {
    border-bottom: 2px solid #dee2e6;
    padding-bottom: 20px;
    margin-bottom: 30px;
}
.invoice-table th {
    background-color: #f8f9fa;
    font-weight: 600;
    border: 1px solid #dee2e6;
}
.invoice-table td {
    border: 1px solid #dee2e6;
    vertical-align: middle;
}
.total-section {
    background-color: #e3f2fd;
    font-weight: 600;
}
.signature-section {
    margin-top: 60px;
    border-top: 1px solid #dee2e6;
    padding-top: 40px;
}
</style>

<div class="d-sm-flex align-items-center justify-content-between mb-4 no-print">
    <h1 class="h3 mb-0 text-gray-800">Faktur Pembelian</h1>
    <div>
        <a href="<?= base_url('transaksi-pembelian'); ?>" class="btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm"></i> Kembali
        </a>
        <button onclick="window.print()" class="btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-print fa-sm"></i> Cetak
        </button>
    </div>
</div>

<div class="card shadow mb-4">
    <div class="card-body">
        <!-- Header Invoice -->
        <div class="invoice-header">
            <div class="row align-items-center">
                <div class="col-md-2 text-center">
                    <div class="bg-primary rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 70px; height: 70px;">
                        <i class="fas fa-pills fa-2x text-white"></i>
                    </div>
                </div>
                <div class="col-md-6">
                    <h5 class="mb-1 text-primary font-weight-bold"><?= $transaksi['nama_supplier']; ?></h5>
                    <p class="mb-0 text-muted small">
                        <?= $transaksi['alamat_supplier']; ?><br>
                        <?= $transaksi['kota_supplier']; ?><br>
                        <strong>Telp:</strong> <?= $transaksi['telepon_supplier']; ?>
                    </p>
                </div>
                <div class="col-md-4 text-end">
                    <h2 class="text-primary mb-0 font-weight-bold">FAKTUR</h2>
                </div>
            </div>
        </div>

        <!-- Info Transaksi -->
        <div class="row mb-4">
            <div class="col-md-4">
                <p class="mb-1"><strong>TTK:</strong></p>
                <p class="text-muted"><?= $transaksi['nama_user']; ?></p>
            </div>
            <div class="col-md-4">
                <p class="mb-1"><strong>Tanggal:</strong></p>
                <p class="text-muted"><?= date('d F Y', strtotime($transaksi['tanggal'])); ?></p>
            </div>
            <div class="col-md-4">
                <p class="mb-1"><strong>No. Faktur:</strong></p>
                <p class="text-muted">
                    <?php if (!empty($transaksi['no_faktur_beli'])): ?>
                        <strong class="text-primary"><?= $transaksi['no_faktur_beli']; ?></strong>
                    <?php else: ?>
                        <span class="text-muted">-</span>
                    <?php endif; ?>
                </p>
            </div>
        </div>

        <!-- Tabel Detail -->
        <div class="table-responsive">
            <table class="table invoice-table mb-0">
                <thead>
                   <tr>
                        <th class="text-center" style="width: 4%;">No</th>
                        <th style="width: 30%;">Nama Barang</th>
                        <th class="text-center" style="width: 7%;">Qty</th>
                        <th class="text-center" style="width: 8%;">Satuan</th>
                        <th class="text-center" style="width: 12%;">Batch</th>
                        <th class="text-center" style="width: 9%;">Exp Date</th>
                        <th class="text-end" style="width: 12%;">Harga</th>
                        <th class="text-end" style="width: 13%;">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; ?>
                    <?php foreach ($detail as $d) : ?>
                        <tr>
                            <td class="text-center"><?= $no++; ?></td>
                            <td>
                                <strong><?= $d['nama_obat']; ?></strong><br>
                                <small class="text-muted"><?= $d['bpom'] ?? '-'; ?></small>
                            </td>
                            <td class="text-center"><?= number_format($d['qty'], 0, ',', '.'); ?></td>
                            <td class="text-center"><?= $d['satuan'] ?? 'tablet'; ?></td>
                            <td class="text-center">
                                <small>
                                    <?= $d['nomor_batch'] ?? '-'; ?>
                                </small>
                            </td>
                            <td class="text-center">
                                <small class="text-muted">
                                    <?= isset($d['expired_date']) ? date('m/Y', strtotime($d['expired_date'])) : '-'; ?>
                                </small>
                            </td>
                            <td class="text-end">Rp <?= number_format($d['harga_beli'], 0, ',', '.'); ?></td>
                            <td class="text-end">Rp <?= number_format($d['qty'] * $d['harga_beli'], 0, ',', '.'); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <?php 
                    // Calculate correct total from detail items
                    $calculated_total = 0;
                    foreach ($detail as $d) {
                        $calculated_total += ($d['qty'] * $d['harga_beli']);
                    }
                    ?>
                    <tr>
                        <td colspan="7" class="text-end font-weight-bold border-0 pt-3">
                            <strong>Total</strong>
                        </td>
                        <td class="text-end font-weight-bold border-0 pt-3">
                            <strong>Rp <?= number_format($calculated_total, 0, ',', '.'); ?></strong>
                        </td>
                    </tr>
                    <tr class="total-section">
                        <td colspan="7" class="text-end font-weight-bold py-3">
                            <strong>Grand Total</strong>
                        </td>
                        <td class="text-end font-weight-bold py-3">
                            <strong>Rp <?= number_format($calculated_total, 0, ',', '.'); ?></strong>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- Footer Signature -->
        <div class="signature-section">
            <div class="row">
                <div class="col-md-6 text-center">
                    <p class="mb-4"><strong>Penerima / Pemesan</strong></p>
                    <div style="height: 80px;"></div>
                    <hr style="width: 200px; margin: 0 auto; border-top: 1px solid #000;">
                    <p class="mt-3 mb-0 font-weight-bold"><?= $transaksi['nama_user']; ?></p>
                </div>
                <div class="col-md-6 text-center">
                    <p class="mb-4"><strong>Supplier farmasi</strong></p>
                    <div style="height: 80px;"></div>
                    <hr style="width: 200px; margin: 0 auto; border-top: 1px solid #000;">
                    <p class="mt-3 mb-0 font-weight-bold"><?= $transaksi['nama_supplier']; ?></p>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>
