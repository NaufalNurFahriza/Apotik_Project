<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Beli dari Supplier</h1>
    <a href="<?= base_url('transaksi-pembelian'); ?>" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
        <i class="fas fa-arrow-left fa-sm text-white-50"></i> Kembali
    </a>
</div>

<?php if (session()->getFlashdata('error')) : ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?= session()->getFlashdata('error'); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="card shadow mb-4">
    <div class="card-header py-3 bg-primary text-white">
        <h6 class="m-0 font-weight-bold">Form Pembelian dari Supplier</h6>
    </div>
    <div class="card-body">
        <form action="<?= base_url('transaksi-pembelian/simpan'); ?>" method="post" id="formPembelian">
            <?= csrf_field(); ?>
            
            <div class="row mb-3">
                <label for="supplier_id" class="col-sm-2 col-form-label">Supplier</label>
                <div class="col-sm-10">
                    <select class="form-select" id="supplier_id" name="supplier_id" required>
                        <option value="" selected disabled>Pilih Supplier</option>
                        <?php foreach ($supplier as $s) : ?>
                            <option value="<?= $s['id']; ?>"><?= $s['nama_supplier']; ?> - <?= $s['alamat']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            
            <div class="row mb-3">
                <label for="nomor_faktur_supplier" class="col-sm-2 col-form-label">Nomor Faktur Supplier</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="nomor_faktur_supplier" name="nomor_faktur_supplier" required>
                </div>
            </div>
            
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h6 class="m-0 font-weight-bold">Detail Obat</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="detailObat">
                            <thead class="bg-light">
                                <tr>
                                    <th>Obat</th>
                                    <th>Harga Beli</th>
                                    <th>Jumlah</th>
                                    <th>Subtotal</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr id="row1">
                                    <td>
                                        <select class="form-select obat-select" name="obat_id[]" required>
                                            <option value="" selected disabled>Pilih Obat</option>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" class="form-control harga-beli" name="harga_beli[]" min="0" step="0.01" required>
                                    </td>
                                    <td>
                                        <input type="number" class="form-control qty" name="qty[]" min="1" value="1" required>
                                    </td>
                                    <td>
                                        <input type="number" class="form-control subtotal" readonly>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-danger btn-sm btn-hapus" disabled>
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="5">
                                        <button type="button" class="btn btn-success btn-sm" id="btnTambahObat">
                                            <i class="fas fa-plus"></i> Tambah Obat
                                        </button>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            
            <div class="row mb-3">
                <label for="total" class="col-sm-2 col-form-label fw-bold">Total Pembelian</label>
                <div class="col-sm-10">
                    <input type="number" class="form-control form-control-lg bg-light fw-bold" id="total" name="total" readonly>
                </div>
            </div>
            
            <div class="row mb-3">
                <label for="keterangan" class="col-sm-2 col-form-label">Keterangan</label>
                <div class="col-sm-10">
                    <textarea class="form-control" id="keterangan" name="keterangan" rows="3"></textarea>
                </div>
            </div>
            
            <div class="row mb-3">
                <div class="col-sm-10 offset-sm-2">
                    <button type="submit" class="btn btn-primary">Simpan Pembelian</button>
                    <button type="reset" class="btn btn-secondary">Reset</button>
                </div>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection(); ?>

<?= $this->section('scripts'); ?>
<script>
    $(document).ready(function() {
        let rowCount = 1;
        
        // Fungsi untuk menghitung subtotal
        function hitungSubtotal(row) {
            const harga = parseFloat($(row).find('.harga-beli').val()) || 0;
            const qty = parseFloat($(row).find('.qty').val()) || 0;
            const subtotal = harga * qty;
            $(row).find('.subtotal').val(subtotal);
            hitungTotal();
        }
        
        // Fungsi untuk menghitung total
        function hitungTotal() {
            let total = 0;
            $('.subtotal').each(function() {
                total += parseFloat($(this).val()) || 0;
            });
            $('#total').val(total);
        }
        
        // Event ketika memilih supplier
        $('#supplier_id').change(function() {
            const supplier_id = $(this).val();
            
            if (supplier_id) {
                // Load obat berdasarkan supplier
                $.ajax({
                    url: '<?= base_url('transaksi-pembelian/getObatBySupplier'); ?>',
                    type: 'POST',
                    data: {
                        supplier_id: supplier_id,
                        <?= csrf_token(); ?>: '<?= csrf_hash(); ?>'
                    },
                    dataType: 'json',
                    success: function(response) {
                        $('.obat-select').each(function() {
                            $(this).empty().append('<option value="" selected disabled>Pilih Obat</option>');
                            
                            if (response && response.length > 0) {
                                response.forEach(function(obat) {
                                    $(this).append(`<option value="${obat.id}">${obat.nama_obat} (Stok: ${obat.stok})</option>`);
                                }.bind(this));
                            }
                        });
                    },
                    error: function() {
                        alert('Gagal memuat data obat');
                    }
                });
            }
        });
        
        // Event ketika mengubah harga beli atau qty
        $(document).on('change', '.harga-beli, .qty', function() {
            const row = $(this).closest('tr');
            hitungSubtotal(row);
        });
        
        // Tambah baris obat
        $('#btnTambahObat').click(function() {
            const supplier_id = $('#supplier_id').val();
            
            if (!supplier_id) {
                alert('Pilih supplier terlebih dahulu!');
                return;
            }
            
            rowCount++;
            const newRow = `
                <tr id="row${rowCount}">
                    <td>
                        <select class="form-select obat-select" name="obat_id[]" required>
                            <option value="" selected disabled>Pilih Obat</option>
                        </select>
                    </td>
                    <td>
                        <input type="number" class="form-control harga-beli" name="harga_beli[]" min="0" step="0.01" required>
                    </td>
                    <td>
                        <input type="number" class="form-control qty" name="qty[]" min="1" value="1" required>
                    </td>
                    <td>
                        <input type="number" class="form-control subtotal" readonly>
                    </td>
                    <td>
                        <button type="button" class="btn btn-danger btn-sm btn-hapus">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `;
            $('#detailObat tbody').append(newRow);
            
            // Load obat untuk baris baru
            const newSelect = $(`#row${rowCount} .obat-select`);
            $.ajax({
                url: '<?= base_url('transaksi-pembelian/getObatBySupplier'); ?>',
                type: 'POST',
                data: {
                    supplier_id: supplier_id,
                    <?= csrf_token(); ?>: '<?= csrf_hash(); ?>'
                },
                dataType: 'json',
                success: function(response) {
                    if (response && response.length > 0) {
                        response.forEach(function(obat) {
                            newSelect.append(`<option value="${obat.id}">${obat.nama_obat} (Stok: ${obat.stok})</option>`);
                        });
                    }
                }
            });
            
            // Enable tombol hapus jika ada lebih dari 1 baris
            if ($('#detailObat tbody tr').length > 1) {
                $('.btn-hapus').prop('disabled', false);
            }
        });
        
        // Hapus baris obat
        $(document).on('click', '.btn-hapus', function() {
            $(this).closest('tr').remove();
            hitungTotal();
            
            // Disable tombol hapus jika hanya ada 1 baris
            if ($('#detailObat tbody tr').length <= 1) {
                $('.btn-hapus').prop('disabled', true);
            }
        });
        
        // Validasi form sebelum submit
        $('#formPembelian').submit(function(e) {
            const total = parseFloat($('#total').val()) || 0;
            
            if (total <= 0) {
                e.preventDefault();
                alert('Silakan isi detail obat terlebih dahulu!');
                return false;
            }
            
            return true;
        });
    });
</script>
<?= $this->endSection(); ?>
