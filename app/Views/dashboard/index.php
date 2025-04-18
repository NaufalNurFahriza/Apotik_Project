<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
</div>

<div class="row">
    <!-- Jumlah Obat Card -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Jumlah Obat</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $jumlah_obat; ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-pills fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Jumlah Admin Card -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Jumlah Admin</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $jumlah_admin; ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-user-shield fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Jumlah Supplier Card -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            Jumlah Supplier</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $jumlah_supplier; ?></div>
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
                            Jumlah Member</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $jumlah_member; ?></div>
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
    <!-- Jumlah Transaksi Card -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-danger shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                            Jumlah Transaksi</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $jumlah_transaksi; ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-cash-register fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Selamat Datang</h6>
            </div>
            <div class="card-body">
                <p>Selamat Datang di Dashboard Admin Apotek Kita Firma.</p>
                    <p>Terima kasih telah masuk ke sistem administrasi Apotek Kita Firma.</p>
                    <p>Melalui platform ini, Anda dapat mengelola berbagai data penting yang mendukung operasional apotek secara efisien dan tertata. Sistem ini menyediakan fitur-fitur sebagai berikut:</p>
                    <ul>
                        <li>Manajemen data obat (input, edit, hapus, lihat)</li>
                        <li>Manajemen data supplier</li>
                        <li>Manajemen data member</li>
                        <li>Transaksi penjualan obat</li>
                        <li>Pembelian obat dari supplier</li>
                        <li>Cetak struk transaksi</li>
                    </ul>
                    <p>Kami berkomitmen mendukung kelancaran kerja Anda melalui sistem yang responsif dan mudah digunakan.</p>
                    <p>Segera mulai pengelolaan data, dan pastikan setiap informasi tercatat dengan baik demi menjaga mutu pelayanan apotek.</p>
                    <p>Selamat bekerja, dan tetap semangat menjaga layanan apotek yang profesional.</p>
    
                </ul>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>