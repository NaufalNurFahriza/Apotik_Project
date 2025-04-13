<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Riwayat Transaksi Member</h1>
    <a href="<?= base_url('member'); ?>" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
        <i class="fas fa-arrow-left fa-sm text-white-50"></i> Kembali
    </a>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Detail Member</h6>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <table class="table">
                    <tr>
                        <th>Nama</th>
                        <td>: <?= $member['nama']; ?></td>
                    </tr>
                    <tr>
                        <th>No. HP</th>
                        <td>: <?= $member['no_hp']; ?></td>
                    </tr>
                    <tr>
                        <th>Poin</th>
                        <td>: <?= $member['poin']; ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Riwayat Transaksi</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Tanggal</th>
                        <th>Admin</th>
                        <th>Total</th>
                        <th>Poin Didapat</th>
                        <th>Opsi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($riwayat)) : ?>
                        <tr>
                            <td colspan="6" class="text-center">Belum ada riwayat transaksi</td>
                        </tr>
                    <?php else : ?>
                        <?php $i = 1; ?>
                        <?php foreach ($riwayat as $r) : ?>
                            <tr>
                                <td><?= $i++; ?></td>
                                <td><?= date('d-m-Y H:i', strtotime($r['tanggal_transaksi'])); ?></td>
                                <td><?= $r['nama_admin']; ?></td>
                                <td>Rp <?= number_format($r['total'], 0, ',', '.'); ?></td>
                                <td><?= $r['poin_didapat']; ?></td>
                                <td>
                                    <a href="<?= base_url('transaksi/detail/' . $r['id']); ?>" class="btn btn-info btn-sm">
                                        <i class="fas fa-eye"></i> Detail
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>