<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Data Obat</h1>
    <a href="<?= base_url('obat/tambah'); ?>" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
        <i class="fas fa-plus fa-sm text-white-50"></i> Tambah Obat
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

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">List Obat</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>BPOM</th>
                        <th>Nama Obat</th>
                        <th>Kategori</th>
                        <th>Satuan</th>
                        <th>Harga Beli</th>
                        <th>Harga Jual</th>
                        <th>Produsen</th>
                        <th>Supplier</th>
                        <th>Stok</th>
                        <th>Opsi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1; ?>
                    <?php foreach ($obat as $o) : ?>
                        <tr>
                            <td><?= $i++; ?></td>
                            <td><?= $o['bpom']; ?></td>
                            <td><?= $o['nama_obat']; ?></td>
                            <td>
                                <?php if ($o['kategori'] == 'resep') : ?>
                                    <span class="text-dark font-weight-bold">Resep</span>
                                <?php else : ?>
                                    <span class="text-dark font-weight-bold">Non-Resep</span>
                                <?php endif; ?>
                            </td>
                            <td><?= $o['satuan']; ?></td>
                            <td>Rp <?= number_format($o['harga_beli'], 0, ',', '.'); ?></td>
                            <td>Rp <?= number_format($o['harga_jual'], 0, ',', '.'); ?></td>
                            <td><?= $o['produsen']; ?></td>
                            <td><?= $o['nama_supplier']; ?></td>
                            <td>
                                <span class="text-dark font-weight-bold"><?= $o['stok']; ?></span>
                            </td>
                            <td>
                                <a href="<?= base_url('obat/edit/' . $o['id']); ?>" class="btn btn-info btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="<?= base_url('obat/hapus/' . $o['id']); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
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
