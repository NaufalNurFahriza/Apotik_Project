<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Laporan Analitik</h1>
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
        <form action="<?= base_url('laporan/analitik'); ?>" method="get" id="formFilter">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="jenis_laporan">Jenis Laporan</label>
                        <select class="form-select" id="jenis_laporan" name="jenis_laporan">
                            <option value="obat_terlaris" <?= ($jenis_laporan ?? 'obat_terlaris') == 'obat_terlaris' ? 'selected' : ''; ?>>Obat Terlaris</option>
                            <option value="supplier_performance" <?= ($jenis_laporan ?? '') == 'supplier_performance' ? 'selected' : ''; ?>>Performance Supplier</option>
                            <option value="member_aktif" <?= ($jenis_laporan ?? '') == 'member_aktif' ? 'selected' : ''; ?>>Member Aktif</option>
                            <option value="stok_minimum" <?= ($jenis_laporan ?? '') == 'stok_minimum' ? 'selected' : ''; ?>>Stok Minimum</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="periode">Periode</label>
                        <select class="form-select" id="periode" name="periode">
                            <option value="bulanan" <?= ($periode ?? 'bulanan') == 'bulanan' ? 'selected' : ''; ?>>Bulanan</option>
                            <option value="mingguan" <?= ($periode ?? '') == 'mingguan' ? 'selected' : ''; ?>>Mingguan</option>
                            <option value="custom" <?= ($periode ?? '') == 'custom' ? 'selected' : ''; ?>>Custom</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3" id="bulan-section">
                    <div class="form-group">
                        <label for="bulan">Bulan</label>
                        <input type="month" class="form-control" id="bulan" name="bulan" value="<?= $bulan ?? date('Y-m'); ?>">
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

<!-- Content berdasarkan jenis laporan -->
<?php if (($jenis_laporan ?? 'obat_terlaris') == 'obat_terlaris'): ?>
    <!-- Obat Terlaris -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Top 10 Obat Terlaris</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Ranking</th>
                            <th>Nama Obat</th>
                            <th>Produsen</th>
                            <th>Total Terjual</th>
                            <th>Total Pendapatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $ranking = 1; ?>
                        <?php foreach ($data as $d) : ?>
                            <tr>
                                <td>
                                    <?php if ($ranking <= 3): ?>
                                        <span class="badge bg-<?= $ranking == 1 ? 'warning' : ($ranking == 2 ? 'secondary' : 'info'); ?>">
                                            #<?= $ranking; ?>
                                        </span>
                                    <?php else: ?>
                                        #<?= $ranking; ?>
                                    <?php endif; ?>
                                </td>
                                <td><?= $d['nama_obat']; ?></td>
                                <td><?= $d['produsen']; ?></td>
                                <td><?= $d['total_terjual']; ?> pcs</td>
                                <td>Rp <?= number_format($d['total_pendapatan'], 0, ',', '.'); ?></td>
                            </tr>
                            <?php $ranking++; ?>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

<?php elseif ($jenis_laporan == 'supplier_performance'): ?>
    <!-- Performance Supplier -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Performance Supplier</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Ranking</th>
                            <th>Nama Supplier</th>
                            <th>Kota</th>
                            <th>Total Transaksi</th>
                            <th>Total Pembelian</th>
                            <th>Rata-rata per Transaksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $ranking = 1; ?>
                        <?php foreach ($data as $d) : ?>
                            <tr>
                                <td>
                                    <?php if ($ranking <= 3): ?>
                                        <span class="badge bg-<?= $ranking == 1 ? 'warning' : ($ranking == 2 ? 'secondary' : 'info'); ?>">
                                            #<?= $ranking; ?>
                                        </span>
                                    <?php else: ?>
                                        #<?= $ranking; ?>
                                    <?php endif; ?>
                                </td>
                                <td><?= $d['nama_supplier']; ?></td>
                                <td><?= $d['kota']; ?></td>
                                <td><?= $d['total_transaksi']; ?></td>
                                <td>Rp <?= number_format($d['total_pembelian'], 0, ',', '.'); ?></td>
                                <td>Rp <?= number_format($d['rata_rata'], 0, ',', '.'); ?></td>
                            </tr>
                            <?php $ranking++; ?>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

<?php elseif ($jenis_laporan == 'member_aktif'): ?>
    <!-- Member Aktif -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Top 10 Member Aktif</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Ranking</th>
                            <th>Nama Member</th>
                            <th>No. HP</th>
                            <th>Total Transaksi</th>
                            <th>Total Belanja</th>
                            <th>Poin Saat Ini</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $ranking = 1; ?>
                        <?php foreach ($data as $d) : ?>
                            <tr>
                                <td>
                                    <?php if ($ranking <= 3): ?>
                                        <span class="badge bg-<?= $ranking == 1 ? 'warning' : ($ranking == 2 ? 'secondary' : 'info'); ?>">
                                            #<?= $ranking; ?>
                                        </span>
                                    <?php else: ?>
                                        #<?= $ranking; ?>
                                    <?php endif; ?>
                                </td>
                                <td><?= $d['nama']; ?></td>
                                <td><?= $d['no_hp']; ?></td>
                                <td><?= $d['total_transaksi']; ?></td>
                                <td>Rp <?= number_format($d['total_belanja'], 0, ',', '.'); ?></td>
                                <td><?= $d['poin']; ?> poin</td>
                            </tr>
                            <?php $ranking++; ?>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

<?php else: ?>
    <!-- Stok Minimum -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Obat dengan Stok Minimum</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Kode Obat</th>
                            <th>Nama Obat</th>
                            <th>Produsen</th>
                            <th>Supplier</th>
                            <th>Stok Saat Ini</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1; ?>
                        <?php foreach ($data as $d) : ?>
                            <tr>
                                <td><?= $i++; ?></td>
                                <td><?= $d['bpom']; ?></td>
                                <td><?= $d['nama_obat']; ?></td>
                                <td><?= $d['produsen']; ?></td>
                                <td><?= $d['nama_supplier']; ?></td>
                                <td>
                                    <span class="badge bg-<?= $d['stok'] <= 5 ? 'danger' : 'warning'; ?>">
                                        <?= $d['stok']; ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($d['stok'] <= 5): ?>
                                        <span class="badge bg-danger">Kritis</span>
                                    <?php else: ?>
                                        <span class="badge bg-warning">Rendah</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php endif; ?>
<?= $this->endSection(); ?>

<?= $this->section('scripts'); ?>
<script>
    $(document).ready(function() {
        // Handle periode change
        $('#periode').change(function() {
            const periode = $(this).val();
            
            if (periode === 'custom') {
                $('#bulan-section').hide();
                $('#custom-section, #custom-section-end').show();
            } else {
                $('#custom-section, #custom-section-end').hide();
                $('#bulan-section').show();
            }
        });
        
        // Trigger change on load
        $('#periode').trigger('change');
    });
    
    function resetFilter() {
        $('#jenis_laporan').val('obat_terlaris');
        $('#periode').val('bulanan');
        $('#bulan').val('<?= date('Y-m'); ?>');
        $('#start_date').val('<?= date('Y-m-01'); ?>');
        $('#end_date').val('<?= date('Y-m-d'); ?>');
        $('#periode').trigger('change');
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
