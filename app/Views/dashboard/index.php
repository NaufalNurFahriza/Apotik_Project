<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
    <div class="d-none d-sm-inline-block">
        <span class="text-xs text-gray-500">Selamat datang, <?= session()->get('nama'); ?> (<?= ucfirst(session()->get('role')); ?>)</span>
    </div>
</div>

<!-- Alert untuk stok minimum -->
<?php if (isset($obatStokMinim) && $obatStokMinim > 0): ?>
<div class="alert alert-warning alert-dismissible fade show" role="alert">
    <i class="fas fa-exclamation-triangle"></i>
    <strong>Perhatian!</strong> Ada <?= $obatStokMinim; ?> obat dengan stok di bawah 10. 
    <a href="<?= base_url('obat'); ?>" class="alert-link">Lihat detail</a>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<?php endif; ?>

<div class="row">
    <!-- Jumlah Obat Card -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Total Obat</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $totalObat ?? 0; ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-pills fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Jumlah TTK Card -->
    <?php if (session()->get('role') === 'pemilik'): ?>
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Total TTK</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $totalTTK ?? 0; ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-user-shield fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Jumlah Supplier Card -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            Total Supplier</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $totalSupplier ?? 0; ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-truck fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Jumlah Member Card -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Total Member</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $totalMember ?? 0; ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-users fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Transaksi Hari Ini Card -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-danger shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                            Transaksi Hari Ini</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $transaksiHariIni ?? 0; ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-cash-register fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Penjualan Hari Ini Card -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Penjualan Hari Ini</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            Rp <?= number_format($penjualanHariIni ?? 0, 0, ',', '.'); ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-money-bill-wave fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stok Minimum Card -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Obat Stok Minimum</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $obatStokMinim ?? 0; ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Charts and Analytics Row -->
<div class="row">
    <!-- Obat Terlaris -->
    <div class="col-lg-6">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-chart-bar"></i> Top 5 Obat Terlaris Bulan Ini
                </h6>
            </div>
            <div class="card-body">
                <?php if (!empty($obatTerlaris)): ?>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nama Obat</th>
                                    <th>Total Terjual</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; ?>
                                <?php foreach ($obatTerlaris as $obat): ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    <td><?= $obat['nama_obat']; ?></td>
                                    <td>
                                        <span class="badge bg-primary"><?= $obat['total_terjual']; ?></span>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-chart-bar fa-3x mb-3"></i>
                        <p>Belum ada data penjualan bulan ini</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="col-lg-6">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-bolt"></i> Aksi Cepat
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <a href="<?= base_url('transaksi-penjualan/tambah'); ?>" class="btn btn-primary btn-block">
                            <i class="fas fa-plus"></i> Transaksi Baru
                        </a>
                    </div>
                    <div class="col-md-6 mb-3">
                        <a href="<?= base_url('obat/tambah'); ?>" class="btn btn-success btn-block">
                            <i class="fas fa-pills"></i> Tambah Obat
                        </a>
                    </div>
                    <div class="col-md-6 mb-3">
                        <a href="<?= base_url('member/tambah'); ?>" class="btn btn-info btn-block">
                            <i class="fas fa-user-plus"></i> Tambah Member
                        </a>
                    </div>
                    <div class="col-md-6 mb-3">
                        <a href="<?= base_url('obat'); ?>?filter=stok_minimum" class="btn btn-warning btn-block">
                            <i class="fas fa-exclamation-triangle"></i> Cek Stok
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Welcome Message -->
<div class="row">
    <div class="col-lg-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-info-circle"></i> Selamat Datang di Sistem Apotek Kita Farma
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <p class="mb-2">
                            <strong>Halo, <?= session()->get('nama'); ?>!</strong> 
                            Anda login sebagai <span class="badge bg-primary"><?= ucfirst(session()->get('role')); ?></span>
                        </p>
                        <p class="text-muted mb-0">
                            Sistem ini membantu Anda mengelola data obat, transaksi penjualan, pembelian, dan laporan apotek dengan mudah dan efisien.
                        </p>
                    </div>
                    <div class="col-md-4 text-center">
                        <img src="<?= base_url('assets/img/logo_apotek_pic_only.png'); ?>" alt="Logo Apotek" class="img-fluid" style="max-height: 80px;">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>
