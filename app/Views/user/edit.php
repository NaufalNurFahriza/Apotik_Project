<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Data TTK (Tenaga Teknis Kefarmasian)</h1>
    <a href="<?= base_url('user/tambah'); ?>" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
        <i class="fas fa-plus fa-sm text-white-50"></i> Tambah TTK
    </a>
</div>

<?php if (session()->getFlashdata('pesan')) : ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= session()->getFlashdata('pesan'); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">
            <i class="fas fa-user-shield"></i> Daftar TTK
        </h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th width="5%">#</th>
                        <th width="30%">Nama TTK</th>
                        <th width="25%">Username</th>
                        <th width="20%">Role</th>
                        <th width="20%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1; ?>
                    <?php foreach ($users as $user) : ?>
                        <tr>
                            <td><?= $i++; ?></td>
                            <td>
                                <strong><?= $user['nama']; ?></strong>
                            </td>
                            <td><?= $user['username']; ?></td>
                            <td>
                                <?php if ($user['role'] === 'pemilik'): ?>
                                    <span class="badge bg-danger">Pemilik</span>
                                <?php else: ?>
                                    <span class="badge bg-primary">TTK</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="<?= base_url('user/edit/' . $user['id']); ?>" class="btn btn-info btn-sm" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <?php if ($user['id'] != session()->get('id')): ?>
                                    <a href="<?= base_url('user/hapus/' . $user['id']); ?>" class="btn btn-danger btn-sm" 
                                       onclick="return confirm('Apakah Anda yakin ingin menghapus TTK ini?')" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Info Card -->
<div class="row">
    <div class="col-lg-12">
        <div class="card border-left-info shadow">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            Informasi TTK
                        </div>
                        <div class="text-sm text-gray-600">
                            <ul class="mb-0">
                                <li><strong>TTK (Tenaga Teknis Kefarmasian)</strong>: Dapat mengelola data obat, member, dan transaksi penjualan</li>
                                <li><strong>Pemilik</strong>: Memiliki akses penuh termasuk mengelola TTK dan transaksi pembelian</li>
                                <li>Hanya pemilik yang dapat menambah, edit, atau menghapus data TTK</li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-info-circle fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>

<?= $this->section('scripts'); ?>
<style>
</style>
<?= $this->endSection(); ?>
