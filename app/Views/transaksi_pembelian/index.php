<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Data Pembelian</h1>
    <a href="<?= base_url('transaksi-pembelian/tambah'); ?>" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
        <i class="fas fa-plus fa-sm text-white-50"></i> Beli dari Supplier
    </a>
</div>

<?php if (session()->getFlashdata('pesan')) : ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= session()->getFlashdata('pesan'); ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')) : ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?= session()->getFlashdata('error'); ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
<?php endif; ?>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Filter Tanggal</h6>
    </div>
    <div class="card-body">
        <form action="<?= base_url('transaksi-pembelian'); ?>" method="get" id="formFilter">
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
                        <label for="filter_button">&nbsp;</label>
                        <div>
                            <button type="submit" id="filter_button" class="btn btn-primary" style="height: 38px;">
                                <i class="fas fa-search"></i> Filter
                            </button>
                            <a href="<?= base_url('transaksi-pembelian'); ?>" class="btn btn-secondary" style="height: 38px;">
                                <i class="fas fa-refresh"></i> Reset
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Daftar Transaksi Pembelian</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered dataTable" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Tanggal</th>
                        <th>No. Faktur</th>
                        <th>Supplier</th>
                        <th>TTK</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1; ?>
                    <?php foreach ($transaksi as $t) : ?>
                        <tr>
                            <td><?= $i++; ?></td>
                            <td><?= date('d-m-Y H:i', strtotime($t['tanggal_transaksi'])); ?></td>
                            <td>
                                <strong><?= $t['nomor_faktur'] ?? 'PB-' . str_pad($t['id'], 5, '0', STR_PAD_LEFT); ?></strong><br>
                                <small class="text-muted">Supplier: <?= $t['nomor_faktur_supplier'] ?? '-'; ?></small>
                            </td>
                            <td>
                                <strong><?= $t['nama_supplier']; ?></strong><br>
                                <small class="text-muted"><?= $t['alamat_supplier']; ?></small>
                            </td>
                            <td><?= $t['nama_user']; ?></td>
                            <td><strong>Rp <?= number_format($t['total'], 0, ',', '.'); ?></strong></td>
                            <td>
                                <span class="badge badge-success">Selesai</span>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="<?= base_url('transaksi-pembelian/detail/' . $t['id']); ?>" class="btn btn-info btn-sm" title="Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="<?= base_url('transaksi-pembelian/faktur/' . $t['id']); ?>" class="btn btn-success btn-sm" title="Faktur">
                                        <i class="fas fa-file-invoice"></i>
                                    </a>
                                    <a href="<?= base_url('transaksi-pembelian/hapus/' . $t['id']); ?>" class="btn btn-danger btn-sm" title="Hapus" onclick="return confirm('Apakah Anda yakin ingin menghapus transaksi ini? Stok obat akan dikurangi sesuai pembelian.')">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <?php if (empty($transaksi)): ?>
            <div class="text-center py-4">
                <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">Belum ada transaksi pembelian</h5>
                <p class="text-muted">Klik tombol "Beli dari Supplier" untuk menambah transaksi pembelian</p>
                <a href="<?= base_url('transaksi-pembelian/tambah'); ?>" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Beli dari Supplier
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Summary Card -->
<?php if (!empty($transaksi)): ?>
<div class="row">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Total Transaksi</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= count($transaksi); ?></div>
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
                            Total Pembelian</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            Rp <?= number_format(array_sum(array_column($transaksi, 'total')), 0, ',', '.'); ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
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
                            Rp <?= count($transaksi) > 0 ? number_format(array_sum(array_column($transaksi, 'total')) / count($transaksi), 0, ',', '.') : 0; ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-chart-line fa-2x text-gray-300"></i>
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
                            Supplier Aktif</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?= count(array_unique(array_column($transaksi, 'supplier_id'))); ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-truck fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>
<?= $this->endSection(); ?>

<?= $this->section('scripts'); ?>
<script>
    $(document).ready(function() {
        // Initialize DataTable
        $('#dataTable').DataTable({
            "order": [[1, "desc"]], // Sort by date (column 1) in descending order
            "pageLength": 25, // Show 25 entries per page
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
            },
            "columnDefs": [
                { "orderable": false, "targets": [7] } // Disable sorting on action column
            ]
        });
    });
</script>
<?= $this->endSection(); ?>
