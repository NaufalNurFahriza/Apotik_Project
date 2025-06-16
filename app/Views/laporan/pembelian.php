<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Laporan Pembelian</h1>
</div>

<!-- Filter Card -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Filter Laporan</h6>
    </div>
    <div class="card-body">
        <form action="<?= base_url('laporan/pembelian'); ?>" method="get" id="formFilter">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="periode">Periode</label>
                        <select class="form-select" id="periode" name="periode">
                            <option value="harian" <?= ($periode ?? 'harian') == 'harian' ? 'selected' : ''; ?>>Harian</option>
                            <option value="mingguan" <?= ($periode ?? '') == 'mingguan' ? 'selected' : ''; ?>>Mingguan</option>
                            <option value="bulanan" <?= ($periode ?? '') == 'bulanan' ? 'selected' : ''; ?>>Bulanan</option>
                            <option value="custom" <?= ($periode ?? '') == 'custom' ? 'selected' : ''; ?>>Custom</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="supplier_id">Supplier</label>
                        <select class="form-select" id="supplier_id" name="supplier_id">
                            <option value="">Semua Supplier</option>
                            <?php foreach ($supplier as $s) : ?>
                                <option value="<?= $s['id']; ?>" <?= ($supplier_id ?? '') == $s['id'] ? 'selected' : ''; ?>><?= $s['nama_supplier']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-3" id="tanggal-section">
                    <div class="form-group">
                        <label for="tanggal">Tanggal</label>
                        <input type="date" class="form-control" id="tanggal" name="tanggal" value="<?= $tanggal ?? date('Y-m-d'); ?>">
                    </div>
                </div>
                <div class="col-md-3" id="custom-section" style="display: none;">
                    <div class="form-group">
                        <label for="start_date">Tanggal Awal</label>
                        <input type="date" class="form-control" id="start_date" name="start_date" value="<?= $start_date ?? date('Y-m-01'); ?>">
                    </div>
                </div>
                <div class="col-md-3" id="custom-section-end" style="display: none;">
                    <div class="form-group">
                        <label for="end_date">Tanggal Akhir</label>
                        <input type="date" class="form-control" id="end_date" name="end_date" value="<?= $end_date ?? date('Y-m-d'); ?>">
                    </div>
                </div>
                <div class="col-md-3">
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
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Total Transaksi</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $summary['total_transaksi'] ?? 0; ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-truck-loading fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Total Pembelian</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            Rp <?= number_format($summary['total_pembelian'] ?? 0, 0, ',', '.'); ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-money-bill-wave fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            Rata-rata per Transaksi</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            Rp <?= number_format($summary['rata_rata'] ?? 0, 0, ',', '.'); ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-calculator fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Total Item Dibeli</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $summary['total_item'] ?? 0; ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-pills fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Data Table -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Detail Laporan Pembelian</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered dataTable" width="100%" cellspacing="0" id="dataTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Tanggal</th>
                        <th>No. Faktur</th>
                        <th>TTK</th>
                        <th>Supplier</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1; ?>
                    <?php foreach ($data as $d) : ?>
                        <tr>
                            <td><?= $i++; ?></td>
                            <td><?= date('d-m-Y H:i', strtotime($d['tanggal'])); ?></td>
                            <td>PB-<?= str_pad($d['id'], 5, '0', STR_PAD_LEFT); ?></td>
                            <td><?= $d['nama_user'] ?? '-'; ?></td>
                            <td><?= $d['nama_supplier']; ?></td>
                            <td>Rp <?= number_format($d['total'], 0, ',', '.'); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="5" class="text-right">Total</th>
                        <th>Rp <?= number_format($summary['total_pembelian'] ?? 0, 0, ',', '.'); ?></th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>

<?= $this->section('scripts'); ?>
<script>
    $(document).ready(function() {
        // Destroy existing DataTable if exists
        if ($.fn.DataTable.isDataTable('#dataTable')) {
            $('#dataTable').DataTable().destroy();
        }
        
        // Initialize DataTable
        $('#dataTable').DataTable({
            "order": [[1, "desc"]], // Sort by date
            "pageLength": 25,
            "destroy": true // Allow reinitializing
        });
        
        // Handle periode change
        $('#periode').change(function() {
            const periode = $(this).val();
            
            if (periode === 'custom') {
                $('#tanggal-section').hide();
                $('#custom-section, #custom-section-end').show();
            } else {
                $('#custom-section, #custom-section-end').hide();
                $('#tanggal-section').show();
            }
        });
        
        // Trigger change on load
        $('#periode').trigger('change');
    });
    
    function resetFilter() {
        $('#periode').val('harian');
        $('#supplier_id').val('');
        $('#tanggal').val('<?= date('Y-m-d'); ?>');
        $('#start_date').val('<?= date('Y-m-01'); ?>');
        $('#end_date').val('<?= date('Y-m-d'); ?>');
        $('#periode').trigger('change');
    }
    
</script>
<?= $this->endSection(); ?>
