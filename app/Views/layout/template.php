<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title; ?> | Apotek Sederhana</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
    
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #4e73df;
            --secondary-color: #858796;
            --success-color: #1cc88a;
            --info-color: #36b9cc;
            --warning-color: #f6c23e;
            --danger-color: #e74a3b;
            --light-color: #f8f9fc;
            --dark-color: #5a5c69;
        }
        
        body {
            font-family: 'Nunito', sans-serif;
            background-color: #f8f9fc;
        }
        
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(180deg, var(--primary-color) 10%, #224abe 100%);
            color: white;
            position: fixed;
            width: 250px;
            z-index: 1;
        }

        
        
        .sidebar .sidebar-brand {
            height: 4.375rem;
            text-decoration: none;
            font-size: 1rem;
            font-weight: 800;
            padding: 1.5rem 1rem;
            text-align: center;
            letter-spacing: 0.05rem;
            z-index: 1;
        }
        
        .sidebar hr.sidebar-divider {
            margin: 0 1rem 1rem;
        }
        
        .sidebar .nav-item {
            position: relative;
        }
        
        .sidebar .nav-item .nav-link {
            display: block;
            width: 100%;
            text-align: left;
            padding: 0.75rem 1rem;
            color: rgba(255, 255, 255, 0.8);
        }
        
        .sidebar .nav-item .nav-link:hover {
            color: #fff;
        }
        
        .sidebar .nav-item .nav-link i {
            margin-right: 0.25rem;
        }
        
        .sidebar .nav-item .nav-link.active {
            font-weight: 700;
            color: white;
        }
        
        .sidebar .nav-item .collapse {
            position: relative;
            left: 0;
            z-index: 1;
            top: 0;
        }
        
        .sidebar .nav-item .collapse .collapse-inner {
            border-radius: 0.35rem;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        }
        
        .sidebar .nav-item .collapse .collapse-inner .collapse-item {
            padding: 0.5rem 1rem;
            margin: 0 0.5rem;
            display: block;
            color: rgba(0, 0, 0, 0.8);
            text-decoration: none;
            border-radius: 0.35rem;
            white-space: nowrap;
        }
        
        .sidebar .nav-item .collapse .collapse-inner .collapse-item:hover {
            background-color: rgba(172, 172, 172, 0.1);
            color: black;
        }
        
        .sidebar-toggled .sidebar {
            width: 6.5rem !important;
        }
        
        .sidebar-toggled .sidebar .nav-item .nav-link {
            text-align: center;
            padding: 0.75rem 1rem;
            width: 6.5rem;
        }
        
        .sidebar-toggled .sidebar .nav-item .nav-link span {
            display: none;
        }
        
        .sidebar-toggled .sidebar .nav-item .nav-link i {
            margin-right: 0;
            font-size: 1.25rem;
        }
        
        .sidebar-toggled .sidebar .nav-item .collapse {
            position: absolute;
            left: calc(6.5rem + 1.5rem / 2);
            top: 2px;
            animation-name: growIn;
            animation-duration: 200ms;
            animation-timing-function: transform cubic-bezier(0.18, 1.25, 0.4, 1), opacity cubic-bezier(0, 1, 0.4, 1);
        }
        
        .sidebar-toggled .sidebar .nav-item .collapse .collapse-inner {
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            border-radius: 0.35rem;
        }
        
        .sidebar-toggled .sidebar .nav-item .collapse .collapse-inner .collapse-item {
            color: var(--dark-color);
        }
        
        .sidebar-toggled .sidebar .sidebar-brand {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem 1rem;
        }
        
        .sidebar-toggled .sidebar .sidebar-brand .sidebar-brand-text {
            display: none;
        }
        
        .content {
            margin-left: 250px;
            padding: 1.5rem;
        }
        
        .sidebar-toggled .content {
            margin-left: 6.5rem;
        }
        
        .topbar {
            height: 4.375rem;
            background-color: white;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        }
        
        .topbar .navbar-nav .nav-item .nav-link {
            color: var(--secondary-color);
            padding: 0 0.75rem;
            position: relative;
        }
        
        .topbar .navbar-nav .nav-item .nav-link:hover {
            color: var(--primary-color);
        }
        
        .card {
            margin-bottom: 1.5rem;
            border: none;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        }
        
        .card-header {
            background-color: #f8f9fc;
            border-bottom: 1px solid #e3e6f0;
        }
        
        .card-body {
            padding: 1.25rem;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-primary:hover {
            background-color: #2e59d9;
            border-color: #2653d4;
        }
        
        .btn-success {
            background-color: var(--success-color);
            border-color: var(--success-color);
        }
        
        .btn-success:hover {
            background-color: #17a673;
            border-color: #169b6b;
        }
        
        .btn-info {
            background-color: var(--info-color);
            border-color: var(--info-color);
        }
        
        .btn-info:hover {
            background-color: #2c9faf;
            border-color: #2a96a5;
        }
        
        .btn-warning {
            background-color: var(--warning-color);
            border-color: var(--warning-color);
        }
        
        .btn-warning:hover {
            background-color: #f4b619;
            border-color: #f4b30d;
        }
        
        .btn-danger {
            background-color: var(--danger-color);
            border-color: var(--danger-color);
        }
        
        .btn-danger:hover {
            background-color: #e02d1b;
            border-color: #d52a1a;
        }
        
        .btn-circle {
            border-radius: 100%;
            height: 2.5rem;
            width: 2.5rem;
            font-size: 1rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        
        .btn-circle.btn-sm {
            height: 1.8rem;
            width: 1.8rem;
            font-size: 0.75rem;
        }
        
        .btn-icon-split {
            padding: 0;
            overflow: hidden;
            display: inline-flex;
            align-items: stretch;
            justify-content: center;
        }
        
        .btn-icon-split .icon {
            background: rgba(0, 0, 0, 0.15);
            display: inline-block;
            padding: 0.375rem 0.75rem;
        }
        
        .btn-icon-split .text {
            display: inline-block;
            padding: 0.375rem 0.75rem;
        }
        
        .bg-primary {
            background-color: var(--primary-color) !important;
        }
        
        .bg-success {
            background-color: var(--success-color) !important;
        }
        
        .bg-info {
            background-color: var(--info-color) !important;
        }
        
        .bg-warning {
            background-color: var(--warning-color) !important;
        }
        
        .bg-danger {
            background-color: var(--danger-color) !important;
        }
        
        .text-primary {
            color: var(--primary-color) !important;
        }
        
        .text-success {
            color: var(--success-color) !important;
        }
        
        .text-info {
            color: var(--info-color) !important;
        }
        
        .text-warning {
            color: var(--warning-color) !important;
        }
        
        .text-danger {
            color: var(--danger-color) !important;
        }
        
        .border-left-primary {
            border-left: 0.25rem solid var(--primary-color) !important;
        }
        
        .border-left-success {
            border-left: 0.25rem solid var(--success-color) !important;
        }
        
        .border-left-info {
            border-left: 0.25rem solid var(--info-color) !important;
        }
        
        .border-left-warning {
            border-left: 0.25rem solid var(--warning-color) !important;
        }
        
        .border-left-danger {
            border-left: 0.25rem solid var(--danger-color) !important;
        }
        
        .sidebar-dark .sidebar-brand {
            color: #fff;
        }
        
        .sidebar-dark hr.sidebar-divider {
            border-top: 1px solid rgba(255, 255, 255, 0.15);
        }
        
        .sidebar-dark .sidebar-heading {
            color: rgba(255, 255, 255, 0.4);
        }
        
        .sidebar-dark .nav-item .nav-link {
            color: rgba(255, 255, 255, 0.8);
        }
        
        .sidebar-dark .nav-item .nav-link i {
            color: rgba(255, 255, 255, 0.3);
        }
        
        .sidebar-dark .nav-item .nav-link:active, .sidebar-dark .nav-item .nav-link:focus, .sidebar-dark .nav-item .nav-link:hover {
            color: #fff;
        }
        
        .sidebar-dark .nav-item .nav-link:active i, .sidebar-dark .nav-item .nav-link:focus i, .sidebar-dark .nav-item .nav-link:hover i {
            color: #fff;
        }
        
        .sidebar-dark .nav-item .nav-link[data-toggle="collapse"]::after {
            color: rgba(255, 255, 255, 0.5);
        }
        
        .sidebar-dark .nav-item.active .nav-link {
            color: #fff;
        }
        
        .sidebar-dark .nav-item.active .nav-link i {
            color: #fff;
        }
        
        .sidebar-dark #sidebarToggle {
            background-color: rgba(255, 255, 255, 0.2);
        }
        
        .sidebar-dark #sidebarToggle::after {
            color: rgba(255, 255, 255, 0.5);
        }
        
        .sidebar-dark #sidebarToggle:hover {
            background-color: rgba(255, 255, 255, 0.25);
        }
        
        .sidebar-dark.toggled #sidebarToggle::after {
            color: rgba(255, 255, 255, 0.5);
        }
        
        .btn-toggle-sidebar {
            background-color: rgba(255, 255, 255, 0.2);
            color: rgba(255, 255, 255, 0.5);
            border: none;
            border-radius: 50%;
            width: 2.5rem;
            height: 2.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
        }
        
        .btn-toggle-sidebar:hover {
            background-color: rgba(255, 255, 255, 0.25);
            color: rgba(255, 255, 255, 0.75);
        }
        
        .footer {
            padding: 1rem;
            background-color: white;
            box-shadow: 0 -0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        }
        
        @media (max-width: 768px) {
            .sidebar {
                width: 6.5rem !important;
            }
            
            .sidebar .nav-item .nav-link {
                text-align: center;
                padding: 0.75rem 1rem;
                width: 6.5rem;
            }
            
            .sidebar .nav-item .nav-link span {
                display: none;
            }
            
            .sidebar .nav-item .nav-link i {
                margin-right: 0;
                font-size: 1.25rem;
            }
            
            .sidebar .sidebar-brand {
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 1.5rem 1rem;
            }
            
            .sidebar .sidebar-brand .sidebar-brand-text {
                display: none;
            }
            
            .content {
                margin-left: 6.5rem;
            }
        }
        
        @media (max-width: 576px) {
            .content {
                margin-left: 0;
            }
            
            .sidebar {
                display: none;
            }
            
            .sidebar-toggled .sidebar {
                display: block;
            }
        }
        
        /* Print styles */
        @media print {
            .sidebar, .topbar, .btn, .no-print {
                display: none !important;
            }
            
            .content {
                margin-left: 0 !important;
                padding: 0 !important;
            }
            
            .card {
                box-shadow: none !important;
                border: 1px solid #ddd !important;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar sidebar-dark">
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="javascript:void(0);" id="sidebarBrandToggle">
            <div class="sidebar-brand-icon">
                <img src="<?= base_url('assets/img/logo_apotek_gray.png'); ?>" alt="Logo" width="40">
            </div>
            <div class="sidebar-brand-text mx-3">Kita Farma</div>
        </a>
        
        <hr class="sidebar-divider my-0">
        
        <ul class="nav flex-column">
            <!-- Dashboard - Visible to all -->
            <li class="nav-item <?= uri_string() == 'dashboard' ? 'active' : ''; ?>">
                <a class="nav-link" href="<?= base_url('dashboard'); ?>">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            
            <hr class="sidebar-divider">
            
            <!-- Obat - Only visible to pemilik -->
            <?php if (session()->get('role') === 'pemilik' || session()->get('role') === 'admin'): ?>
            <li class="nav-item <?= strpos(uri_string(), 'obat') !== false ? 'active' : ''; ?>">
                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseObat">
                    <i class="fas fa-fw fa-pills"></i>
                    <span>Obat</span>
                </a>
                <div id="collapseObat" class="collapse <?= strpos(uri_string(), 'obat') !== false ? 'show' : ''; ?>">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item" href="<?= base_url('obat'); ?>">Data Obat</a>
                        <a class="collapse-item" href="<?= base_url('obat/tambah'); ?>">Tambah Obat</a>
                    </div>
                </div>
            </li>
            
            <!-- Supplier - Only visible to pemilik -->
            <li class="nav-item <?= strpos(uri_string(), 'supplier') !== false ? 'active' : ''; ?>">
                <a class="nav-link" href="<?= base_url('supplier'); ?>">
                    <i class="fas fa-fw fa-truck"></i>
                    <span>Data Supplier</span>
                </a>
            </li>
            <?php endif; ?>

            <!-- TTK - Only visible to pemilik -->
            <?php if (session()->get('role') === 'pemilik'): ?>
            <li class="nav-item <?= strpos(uri_string(), 'user') !== false ? 'active' : ''; ?>">
                <a class="nav-link" href="<?= base_url('user'); ?>">
                    <i class="fas fa-fw fa-user-shield"></i>
                    <span>Data TTK</span>
                </a>
            </li>
            <?php endif; ?>
            
            <!-- Member - Visible to all -->
            <li class="nav-item <?= strpos(uri_string(), 'member') !== false ? 'active' : ''; ?>">
                <a class="nav-link" href="<?= base_url('member'); ?>">
                    <i class="fas fa-fw fa-users"></i>
                    <span>Data Member</span>
                </a>
            </li>
            
            <!-- Penjualan - Visible to all -->
            <li class="nav-item <?= strpos(uri_string(), 'penjualan') !== false ? 'active' : ''; ?>">
                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapsePenjualan">
                    <i class="fas fa-fw fa-shopping-cart"></i>
                    <span>Penjualan</span>
                </a>
                <div id="collapsePenjualan" class="collapse <?= strpos(uri_string(), 'penjualan') !== false ? 'show' : ''; ?>">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item" href="<?= base_url('transaksi-penjualan'); ?>">Data Penjualan</a>
                        <a class="collapse-item" href="<?= base_url('transaksi-penjualan/tambah'); ?>">Tambah Transaksi Penjualan</a>
                    </div>
                </div>
            </li>
            
            <!-- Pembelian - Only visible to pemilik -->
            <?php if (session()->get('role') === 'pemilik'): ?>
            <li class="nav-item <?= strpos(uri_string(), 'pembelian') !== false ? 'active' : ''; ?>">
                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapsePembelian">
                    <i class="fas fa-fw fa-truck-loading"></i>
                    <span>Pembelian</span>
                </a>
                <div id="collapsePembelian" class="collapse <?= strpos(uri_string(), 'pembelian') !== false ? 'show' : ''; ?>">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item" href="<?= base_url('transaksi-pembelian'); ?>">Data Pembelian</a>
                        <a class="collapse-item" href="<?= base_url('transaksi-pembelian/tambah'); ?>">Beli dari Supplier</a>
                    </div>
                </div>
            </li>
            <?php endif; ?>
            
            <!-- Laporan - Visible based on role -->
            <li class="nav-item <?= strpos(uri_string(), 'laporan') !== false ? 'active' : ''; ?>">
                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLaporan">
                    <i class="fas fa-fw fa-chart-line"></i>
                    <span>Laporan</span>
                </a>
                <div id="collapseLaporan" class="collapse <?= strpos(uri_string(), 'laporan') !== false ? 'show' : ''; ?>">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item" href="<?= base_url('laporan/penjualan'); ?>">Laporan Penjualan</a>
                        <?php if (session()->get('role') === 'pemilik'): ?>
                        <div class="dropdown-divider"></div>
                        <a class="collapse-item" href="<?= base_url('laporan/pembelian'); ?>">Laporan Pembelian</a>
                        <?php endif; ?>
                    </div>
                </div>
            </li>
            
        </ul>
    </div>
    
    <!-- Content Wrapper -->
    <div class="content">
        <!-- Topbar -->
        <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
            <!-- Sidebar Toggle (Topbar) -->
            <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                <i class="fa fa-bars"></i>
            </button>
            
            <!-- Topbar Navbar -->
            <ul class="navbar-nav ms-auto">
                <div class="topbar-divider d-none d-sm-block"></div>
                
                <!-- Nav Item - User Information -->
                <li class="nav-item dropdown no-arrow">
                    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <span class="mr-2 d-none d-lg-inline text-gray-600 small">
                            <?= session()->get('nama'); ?> 
                            <span class="badge bg-primary"><?= ucfirst(session()->get('role')); ?></span>
                        </span>
                        <i class="fas fa-user-circle fa-fw"></i>
                    </a>
                    <!-- Dropdown - User Information -->
                    <div class="dropdown-menu dropdown-menu-end shadow animated--grow-in" aria-labelledby="userDropdown">
                        <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#logoutModal">
                            <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                            Logout
                        </a>
                    </div>
                </li>
            </ul>
        </nav>
        
        <!-- Begin Page Content -->
        <div class="container-fluid">
            <?= $this->renderSection('content'); ?>
        </div>
        
        <!-- Footer -->
        <footer class="footer mt-auto">
            <div class="container-fluid">
                <div class="copyright text-center my-auto">
                    <span>Copyright &copy; Apotek Kita Farma <?= date('Y'); ?></span>
                </div>
            </div>
        </footer>
    </div>
    
    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Yakin ingin keluar?</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Pilih "Logout" di bawah jika Anda siap untuk mengakhiri sesi Anda saat ini.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <a class="btn btn-primary" href="<?= base_url('auth/logout'); ?>">Logout</a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    
    <!-- Custom scripts -->
    <script>
            // Toggle sidebar via klik logo Apotek
    document.getElementById('sidebarBrandToggle').addEventListener('click', function () {
        document.body.classList.toggle('sidebar-toggled');
    });


    // DataTables
    $(document).ready(function () {
        $('.dataTable').DataTable();
    });
    </script>
    
    <?= $this->renderSection('scripts'); ?>
</body>
</html>
