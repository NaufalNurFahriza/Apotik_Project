<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Laporan Keuangan</h1>
    <div>
        <button onclick="exportExcel()" class="d-none d-sm-inline-block btn btn-sm btn-success shadow-sm">
            <i class="fas fa-file-excel fa-sm text-white-50"></i> Export Excel
        </button>
        <button onclick="window.print()" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-print fa-sm text-white-50"></i> Cetak
        </button>
    </div>
</div>

<!-- Filter Card -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Filter Laporan</h6>
    </div>
    <div class="card-body">
        <form action="<?= base_url('laporan/keuangan'); ?>" method="get" id="formFilter">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="start_date">Tanggal Awal</label>
                        <input type="date" class="form-control" id="start_date" name="start_date" value="<?= $start_date ?? date('Y-m-01'); ?>">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="end_date">Tanggal Akhir</label>
                        <input type="date" class="form-control" id="end_date" name="end_date" value="<?= $end_date ?? date('Y-m-d'); ?>">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>&nbsp;</label>
                        <div>
                            <button type="submit" class="btn btn-primary">Filter</button>
                            <button type="button" class="btn btn-secondary" onclick="resetFilter()">Reset</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Summary Cards -->
<div class="row">
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Total Penjualan</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            Rp <?= number_format($total_penjualan ?? 0, 0, ',', '.'); ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-arrow-up fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card border-left-danger shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                            Total Pembelian</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            Rp <?= number_format($total_pembelian ?? 0, 0, ',', '.'); ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-arrow-down fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card border-left-<?= ($profit_kotor ?? 0) >= 0 ? 'primary' : 'warning'; ?> shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-<?= ($profit_kotor ?? 0) >= 0 ? 'primary' : 'warning'; ?> text-uppercase mb-1">
                            Profit Kotor</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            Rp <?= number_format($profit_kotor ?? 0, 0, ',', '.'); ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-chart-line fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart Card -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Ringkasan Keuangan</h6>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <h5>Periode: <?= date('d/m/Y', strtotime($start_date)); ?> - <?= date('d/m/Y', strtotime($end_date)); ?></h5>
                <hr>
                <table class="table table-borderless">
                    <tr>
                        <td><strong>Total Penjualan:</strong></td>
                        <td class="text-right text-success">
                            <strong>Rp <?= number_format($total_penjualan ?? 0, 0, ',', '.'); ?></strong>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Total Pembelian:</strong></td>
                        <td class="text-right text-danger">
                            <strong>Rp <?= number_format($total_pembelian ?? 0, 0, ',', '.'); ?></strong>
                        </td>
                    </tr>
                    <tr class="border-top">
                        <td><strong>Profit Kotor:</strong></td>
                        <td class="text-right text-<?= ($profit_kotor ?? 0) >= 0 ? 'primary' : 'warning'; ?>">
                            <strong>Rp <?= number_format($profit_kotor ?? 0, 0, ',', '.'); ?></strong>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="col-md-6">
                <div class="text-center">
                    <?php if (($profit_kotor ?? 0) >= 0): ?>
                        <i class="fas fa-chart-line fa-5x text-success mb-3"></i>
                        <h5 class="text-success">Profit Positif</h5>
                        <p class="text-muted">Bisnis berjalan dengan baik</p>
                    <?php else: ?>
                        <i class="fas fa-chart-line-down fa-5x text-warning mb-3"></i>
                        <h5 class="text-warning">Perlu Evaluasi</h5>
                        <p class="text-muted">Pembelian melebihi penjualan</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>

<?= $this->section('scripts'); ?>
<script>
    function resetFilter() {
        $('#start_date').val('<?= date('Y-m-01'); ?>');
        $('#end_date').val('<?= date('Y-m-d'); ?>');
    }
    
    function exportExcel() {
        const form = $('#formFilter');
        const action = form.attr('action');
        form.attr('action', action + '/excel');
        form.submit();
        form.attr('action', action);
    }
</script>
<?= $this->endSection(); ?>
