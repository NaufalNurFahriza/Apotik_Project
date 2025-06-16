<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Data Supplier</h1>
    <a href="<?= base_url('supplier/tambah'); ?>" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
        <i class="fas fa-plus fa-sm text-white-50"></i> Tambah Supplier
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
        <h6 class="m-0 font-weight-bold text-primary">List Supplier</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama Supplier</th>
                        <th>Alamat</th>
                        <th>Kota</th>
                        <th>Telepon</th>
                        <th>Jumlah Obat</th>
                        <th>Opsi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1; ?>
                    <?php foreach ($supplier as $s) : ?>
                        <tr>
                            <td><?= $i++; ?></td>
                            <td>
                                <strong><?= esc($s['nama_supplier']); ?></strong>
                            </td>
                            <td><?= esc($s['alamat']); ?></td>
                            <td>
                                <i class="fas fa-map-marker-alt text-primary"></i>
                                <?= esc($s['kota']); ?>
                            </td>
                            <td>
                                <i class="fas fa-phone text-success"></i>
                                <?= esc($s['telepon']); ?>
                            </td>
                            <td>
                                <span class="badge badge-info">
                                    <?= $s['jumlah_obat']; ?> obat
                                </span>
                            </td>
                            <td>
                                <a href="<?= base_url('supplier/edit/' . $s['id']); ?>" class="btn btn-info btn-sm" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="<?= base_url('supplier/hapus/' . $s['id']); ?>" class="btn btn-danger btn-sm" 
                                   onclick="return confirm('Apakah Anda yakin ingin menghapus supplier ini?')" title="Hapus">
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
