<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Laporan Penjualan</h1>
</div>

<!-- Filter Card -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Filter Laporan</h6>
    </div>
    <div class="card-body">
        <form action="<?= base_url('laporan/penjualan'); ?>" method="get" id="formFilter">
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
                            <button type="button" class="btn btn-success" onclick="printReport()">
                                <i class="fas fa-print"></i> Print
                            </button>
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
                        <i class="fas fa-shopping-cart fa-2x text-gray-300"></i>
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
                            Total Penjualan</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            Rp <?= number_format($summary['total_penjualan'] ?? 0, 0, ',', '.'); ?>
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
                            Total Item Terjual</div>
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
        <h6 class="m-0 font-weight-bold text-primary">Detail Laporan Penjualan</h6>
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
                        <th>Pembeli</th>
                        <th>Member</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1; ?>
                    <?php foreach ($data as $d) : ?>
                        <tr>
                            <td><?= $i++; ?></td>
                            <td><?= date('d-m-Y H:i', strtotime($d['tanggal_transaksi'])); ?></td>
                             <td><?= $d['no_faktur_jual'] ?? 'PJ-' . str_pad($d['id'], 5, '0', STR_PAD_LEFT); ?></td>
                            <td><?= $d['nama_user'] ?? '-'; ?></td>
                            <td><?= $d['nama_pembeli'] ?? '-'; ?></td>
                            <td><?= $d['nama_member'] ?? '-'; ?></td>
                            <td>Rp <?= number_format($d['total'], 0, ',', '.'); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="6" class="text-right">Total</th>
                        <th>Rp <?= number_format($summary['total_penjualan'] ?? 0, 0, ',', '.'); ?></th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<!-- Hidden Print Content -->
<div id="printContent" style="display: none;">
    <div style="text-align: center; margin-bottom: 30px;">
        <h2>APOTEK KITA FARMA</h2>
        <h3>LAPORAN PENJUALAN</h3>
        <p>Periode: <?= date('d-m-Y', strtotime($start_date)); ?> s/d <?= date('d-m-Y', strtotime($end_date)); ?></p>
    </div>

    <!-- Summary Section -->
    <div style="margin-bottom: 30px;">
        <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
            <tr>
                <td style="border: 1px solid #ddd; padding: 8px; background-color: #f8f9fa;"><strong>Total Transaksi</strong></td>
                <td style="border: 1px solid #ddd; padding: 8px;"><?= $summary['total_transaksi'] ?? 0; ?></td>
                <td style="border: 1px solid #ddd; padding: 8px; background-color: #f8f9fa;"><strong>Total Penjualan</strong></td>
                <td style="border: 1px solid #ddd; padding: 8px;">Rp <?= number_format($summary['total_penjualan'] ?? 0, 0, ',', '.'); ?></td>
            </tr>
            <tr>
                <td style="border: 1px solid #ddd; padding: 8px; background-color: #f8f9fa;"><strong>Rata-rata per Transaksi</strong></td>
                <td style="border: 1px solid #ddd; padding: 8px;">Rp <?= number_format($summary['rata_rata'] ?? 0, 0, ',', '.'); ?></td>
                <td style="border: 1px solid #ddd; padding: 8px; background-color: #f8f9fa;"><strong>Total Item Terjual</strong></td>
                <td style="border: 1px solid #ddd; padding: 8px;"><?= $summary['total_item'] ?? 0; ?></td>
            </tr>
        </table>
    </div>

    <!-- Detail Table -->
    <table style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr style="background-color: #f8f9fa;">
                <th style="border: 1px solid #ddd; padding: 8px; text-align: center;">#</th>
                <th style="border: 1px solid #ddd; padding: 8px;">Tanggal</th>
                <th style="border: 1px solid #ddd; padding: 8px;">No. Faktur</th>
                <th style="border: 1px solid #ddd; padding: 8px;">TTK</th>
                <th style="border: 1px solid #ddd; padding: 8px;">Pembeli</th>
                <th style="border: 1px solid #ddd; padding: 8px;">Member</th>
                <th style="border: 1px solid #ddd; padding: 8px; text-align: right;">Total</th>
            </tr>
        </thead>
        <tbody>
            <?php $i = 1; ?>
            <?php foreach ($data as $d) : ?>
                <tr>
                    <td style="border: 1px solid #ddd; padding: 8px; text-align: center;"><?= $i++; ?></td>
                    <td style="border: 1px solid #ddd; padding: 8px;"><?= date('d-m-Y H:i', strtotime($d['tanggal_transaksi'])); ?></td>
                    <td style="border: 1px solid #ddd; padding: 8px;"><?= $d['no_faktur_jual'] ?? 'PJ-' . str_pad($d['id'], 5, '0', STR_PAD_LEFT); ?></td>
                    <td style="border: 1px solid #ddd; padding: 8px;"><?= $d['nama_user'] ?? '-'; ?></td>
                    <td style="border: 1px solid #ddd; padding: 8px;"><?= $d['nama_pembeli'] ?? '-'; ?></td>
                    <td style="border: 1px solid #ddd; padding: 8px;"><?= $d['nama_member'] ?? '-'; ?></td>
                    <td style="border: 1px solid #ddd; padding: 8px; text-align: right;">Rp <?= number_format($d['total'], 0, ',', '.'); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr style="background-color: #f8f9fa; font-weight: bold;">
                <td colspan="6" style="border: 1px solid #ddd; padding: 8px; text-align: right;"><strong>TOTAL</strong></td>
                <td style="border: 1px solid #ddd; padding: 8px; text-align: right;"><strong>Rp <?= number_format($summary['total_penjualan'] ?? 0, 0, ',', '.'); ?></strong></td>
            </tr>
        </tfoot>
    </table>

    <div style="margin-top: 30px; text-align: right;">
        <p>Dicetak pada: <?= date('d-m-Y H:i:s'); ?></p>
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
    });
    
    function resetFilter() {
        $('#start_date').val('<?= date('Y-m-01'); ?>');
        $('#end_date').val('<?= date('Y-m-d'); ?>');
    }
    
    function printReport() {
        // Get the print content
        var printContent = document.getElementById('printContent').innerHTML;
        
        // Create a new window for printing
        var printWindow = window.open('', '_blank', 'width=800,height=600');
        
        // Write the HTML content to the new window
        printWindow.document.write(`
            <!DOCTYPE html>
            <html>
            <head>
                <title>Laporan Penjualan - Apotek Kita Farma</title>
                <style>
                    body {
                        font-family: Arial, sans-serif;
                        margin: 20px;
                        font-size: 12px;
                    }
                    table {
                        width: 100%;
                        border-collapse: collapse;
                        margin-bottom: 20px;
                    }
                    th, td {
                        border: 1px solid #ddd;
                        padding: 8px;
                        text-align: left;
                    }
                    th {
                        background-color: #f8f9fa;
                        font-weight: bold;
                    }
                    .text-center {
                        text-align: center;
                    }
                    .text-right {
                        text-align: right;
                    }
                    h2, h3 {
                        margin: 10px 0;
                    }
                    @media print {
                        body { margin: 0; }
                        .no-print { display: none; }
                    }
                </style>
            </head>
            <body>
                ${printContent}
            </body>
            </html>
        `);
        
        // Close the document and focus on the window
        printWindow.document.close();
        printWindow.focus();
        
        // Wait for content to load then print
        setTimeout(function() {
            printWindow.print();
            printWindow.close();
        }, 500);
    }
</script>
<?= $this->endSection(); ?>
