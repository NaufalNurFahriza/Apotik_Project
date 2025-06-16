<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Laporan Stok</h1>
    <div>
        <button onclick="exportExcel()" class="d-none d-sm-inline-block btn btn-sm btn-success shadow-sm">
            <i class="fas fa-file-excel fa-sm text-white-50"></i> Export Excel
        </button>
        <button onclick="window.print()" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-print fa-sm text-white-50"></i> Cetak
        </button>
    </div>
</div>

<!-- Summary Cards -->
<div class="row">
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card border-left-danger shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                            Stok Habis</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= count($stok_habis ?? []); ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-exclamation-circle fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Stok Minimum</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= count($stok_minim ?? []); ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Stok Aman</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= count($stok_aman ?? []); ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Data Table -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Detail Laporan Stok</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered dataTable" width="100%" cellspacing="0" id="dataTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama Obat</th>
                        <th>Kategori</th>
                        <th>Satuan</th>
                        <th>Supplier</th>
                        <th>Stok</th>
                        <th>Harga Beli</th>
                        <th>Harga Jual</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1; ?>
                    <?php foreach ($laporan_stok as $obat) : ?>
                        <tr>
                            <td><?= $i++; ?></td>
                            <td><?= $obat['nama_obat']; ?></td>
                            <td><?= $obat['kategori']; ?></td>
                            <td><?= $obat['satuan']; ?></td>
                            <td><?= $obat['nama_supplier']; ?></td>
                            <td>
                                <span class="badge bg-<?= $obat['stok'] == 0 ? 'danger' : ($obat['stok'] < 10 ? 'warning' : 'success'); ?>">
                                    <?= $obat['stok']; ?>
                                </span>
                            </td>
                            <td>Rp <?= number_format($obat['harga_beli'], 0, ',', '.'); ?></td>
                            <td>Rp <?= number_format($obat['harga_jual'], 0, ',', '.'); ?></td>
                            <td>
                                <?php if ($obat['stok'] == 0): ?>
                                    <span class="badge bg-danger">Habis</span>
                                <?php elseif ($obat['stok'] < 10): ?>
                                    <span class="badge bg-warning">Minimum</span>
                                <?php else: ?>
                                    <span class="badge bg-success">Aman</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>

<?= $this->section('scripts'); ?>
<script>
    $(document).ready(function() {
        // Initialize DataTable
        $('#dataTable').DataTable({
            "order": [[5, "asc"]], // Sort by stok
            "pageLength": 25
        });
    });
    
    function exportExcel() {
        window.location.href = '<?= base_url('laporan/stok/excel'); ?>';
    }
</script>
<?= $this->endSection(); ?>
