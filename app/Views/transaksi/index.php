<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Data Transaksi</h1>
    <a href="<?= base_url('transaksi/tambah'); ?>" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
        <i class="fas fa-plus fa-sm text-white-50"></i> Tambah Transaksi
    </a>
</div>

<?php if (session()->getFlashdata('pesan')) : ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= session()->getFlashdata('pesan'); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<!-- Filter Tanggal -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Filter Tanggal</h6>
    </div>
    <div class="card-body">
        <form action="<?= base_url('transaksi'); ?>" method="get" id="formFilter">
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
                        <button type="submit" class="btn btn-primary btn-block">Filter</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">List Transaksi</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="transaksiTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Tanggal</th>
                        <th>Admin</th>
                        <th>Pembeli</th>
                        <th>Member</th>
                        <th>Total</th>
                        <th>Opsi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1; ?>
                    <?php foreach ($transaksi as $t) : ?>
                        <tr>
                            <td><?= $i++; ?></td>
                            <td><?= date('d-m-Y H:i', strtotime($t['tanggal_transaksi'])); ?></td>
                            <td><?= $t['nama_admin']; ?></td>
                            <td><?= $t['nama_pembeli']; ?></td>
                            <td><?= $t['nama_member'] ? $t['nama_member'] : '-'; ?></td>
                            <td>Rp <?= number_format($t['total'], 0, ',', '.'); ?></td>
                            <td>
                                <a href="<?= base_url('transaksi/detail/' . $t['id']); ?>" class="btn btn-info btn-sm">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="<?= base_url('transaksi/struk/' . $t['id']); ?>" class="btn btn-success btn-sm">
                                    <i class="fas fa-print"></i>
                                </a>
                                <a href="<?= base_url('transaksi/hapus/' . $t['id']); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                    <i class="fas fa-trash"></i>
                                </a>
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
        // Inisialisasi DataTable dengan ID yang spesifik
        $('#transaksiTable').DataTable({
            "order": [[1, "desc"]], // Urutkan berdasarkan tanggal (kolom 1) secara descending
            "pageLength": 25, // Tampilkan 25 data per halaman
            "destroy": true // Penting: memungkinkan reinisialisasi
        });
    });
</script>
<?= $this->endSection(); ?>
