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
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
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
                <label for="supplier_search" class="col-sm-2 col-form-label">Supplier</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" id="supplier_search" placeholder="Cari supplier berdasarkan nama..." autocomplete="off">
                    <input type="hidden" id="supplier_id" name="supplier_id" required>
                    <div id="supplier_dropdown" class="dropdown-menu w-100" style="display: none; max-height: 200px; overflow-y: auto;"></div>
                </div>
                <div class="col-sm-1">
                    <button type="button" class="btn btn-outline-secondary" id="clearSupplier" title="Reset Supplier">
                        <i class="fas fa-times"></i>
                    </button>
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
                            <thead class="thead-light">
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
                                        <select class="form-control obat-select" name="obat_id[]" required>
                                            <option value="" selected disabled>Pilih Obat</option>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" class="form-control harga-beli" name="harga_beli[]" min="0" step="0.01" required readonly>
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
                <label for="total" class="col-sm-2 col-form-label font-weight-bold">Total Pembelian</label>
                <div class="col-sm-10">
                    <input type="number" class="form-control form-control-lg bg-light font-weight-bold" id="total" name="total" readonly>
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
        let supplierData = <?= json_encode($supplier); ?>;

        // Supplier Search Functionality
        let searchTimeout;
        $('#supplier_search').on('input', function() {
            clearTimeout(searchTimeout);
            const query = $(this).val().trim();
            
            if (query.length >= 2) {
                searchTimeout = setTimeout(() => {
                    searchSupplier(query);
                }, 300);
            } else {
                $('#supplier_dropdown').hide();
            }
        });

        function searchSupplier(query) {
            const results = supplierData.filter(supplier => 
                supplier.nama_supplier.toLowerCase().includes(query.toLowerCase()) ||
                supplier.alamat.toLowerCase().includes(query.toLowerCase())
            );

            let dropdown = $('#supplier_dropdown');
            dropdown.empty();

            if (results.length > 0) {
                results.forEach(supplier => {
                    dropdown.append(`
                        <a class="dropdown-item supplier-item" href="#" data-id="${supplier.id}" data-nama="${supplier.nama_supplier}" data-alamat="${supplier.alamat}">
                            <div>
                                <strong>${supplier.nama_supplier}</strong>
                                <br><small class="text-muted">${supplier.alamat}</small>
                            </div>
                        </a>
                    `);
                });
                dropdown.show();
            } else {
                dropdown.append('<div class="dropdown-item-text">Supplier tidak ditemukan</div>');
                dropdown.show();
            }
        }

        // Select Supplier
        $(document).on('click', '.supplier-item', function(e) {
            e.preventDefault();
            const id = $(this).data('id');
            const nama = $(this).data('nama');
            const alamat = $(this).data('alamat');

            $('#supplier_search').val(`${nama} - ${alamat}`);
            $('#supplier_id').val(id);
            $('#supplier_dropdown').hide();

            // Load obat berdasarkan supplier
            loadObatBySupplier(id);
        });

        // Clear Supplier
        $('#clearSupplier').click(function() {
            $('#supplier_search').val('');
            $('#supplier_id').val('');
            $('#supplier_dropdown').hide();
            
            // Clear obat options
            $('.obat-select').each(function() {
                $(this).empty().append('<option value="" selected disabled>Pilih Obat</option>');
            });
            
            // Reset form
            $('.harga-beli, .qty, .subtotal').val('');
            $('#total').val('');
        });

        // Hide dropdown when clicking outside
        $(document).click(function(e) {
            if (!$(e.target).closest('#supplier_search, #supplier_dropdown').length) {
                $('#supplier_dropdown').hide();
            }
        });

        // Load Obat by Supplier
        function loadObatBySupplier(supplier_id) {
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
                                $(this).append(`<option value="${obat.id}" data-harga="${obat.harga_beli}" data-stok="${obat.stok}">${obat.nama_obat} (Stok: ${obat.stok})</option>`);
                            }.bind(this));
                        }
                    });
                },
                error: function() {
                    alert('Gagal memuat data obat');
                }
            });
        }

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

        // Event ketika memilih obat - auto fill harga beli
        $(document).on('change', '.obat-select', function() {
            const selectedOption = $(this).find('option:selected');
            const hargaBeli = selectedOption.data('harga') || 0;
            const row = $(this).closest('tr');

            // Set harga beli otomatis
            row.find('.harga-beli').val(hargaBeli);

            // Hitung subtotal
            hitungSubtotal(row);
        });

        // Event ketika mengubah qty
        $(document).on('change', '.qty', function() {
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
                        <select class="form-control obat-select" name="obat_id[]" required>
                            <option value="" selected disabled>Pilih Obat</option>
                        </select>
                    </td>
                    <td>
                        <input type="number" class="form-control harga-beli" name="harga_beli[]" min="0" step="0.01" required readonly>
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
            loadObatBySupplier(supplier_id);

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
            const supplier_id = $('#supplier_id').val();
            const total = parseFloat($('#total').val()) || 0;

            if (!supplier_id) {
                e.preventDefault();
                alert('Silakan pilih supplier terlebih dahulu!');
                return false;
            }

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
