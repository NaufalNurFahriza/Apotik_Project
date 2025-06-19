<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Beli dari Supplier</h1>
    <a href="<?= base_url('transaksi-pembelian'); ?>" class="btn btn-secondary btn-sm shadow-sm">
        <i class="fas fa-arrow-left fa-sm text-white-50"></i> Kembali
    </a>
</div>

<?php if (session()->getFlashdata('error')) : ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?= session()->getFlashdata('error'); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('pesan')) : ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= session()->getFlashdata('pesan'); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="card shadow mb-4">
    <div class="card-header py-3 bg-primary text-white">
        <h6 class="m-0 fw-bold">Form Pembelian dari Supplier</h6>
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
                    <input type="text" class="form-control" id="nomor_faktur_supplier" name="nomor_faktur_supplier" placeholder="Masukkan nomor faktur dari supplier" required>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h6 class="m-0 fw-bold">Detail Obat</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="detailObat">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 25%;">Obat</th>
                                    <th style="width: 12%;">Harga Beli</th>
                                    <th style="width: 8%;">Jumlah</th>
                                    <th style="width: 15%;">Batch</th>
                                    <th style="width: 12%;">Exp Date</th>
                                    <th style="width: 12%;">Subtotal</th>
                                    <th style="width: 8%;">Aksi</th>
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
                                        <input type="number" class="form-control harga-beli" name="harga_beli[]" min="0" step="0.01" required disabled>
                                    </td>
                                    <td>
                                        <input type="number" class="form-control qty" name="qty[]" min="1" value="1" required disabled>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control batch" name="nomor_batch[]" placeholder="Batch" required disabled>
                                    </td>
                                    <td>
                                        <input type="date" class="form-control expired" name="expired_date[]" required disabled>
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
                                    <td colspan="7">
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
                <div class="col-sm-10 offset-sm-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan Pembelian
                    </button>
                    <button type="reset" class="btn btn-secondary">
                        <i class="fas fa-undo"></i> Reset
                    </button>
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
        let supplierData = <?= json_encode($supplier ?? []); ?>;
        let currentObatData = [];

        // Set default expiration date (2 years from now)
        function setDefaultExpDate(element) {
            const defaultDate = new Date();
            defaultDate.setFullYear(defaultDate.getFullYear() + 2);
            const formattedDate = defaultDate.toISOString().split('T')[0];
            $(element).val(formattedDate);
        }

        // Function to enable/disable row fields based on medicine selection
        function toggleRowFields(row, enable) {
            const inputs = row.find('.harga-beli, .qty, .batch, .expired');
            if (enable) {
                inputs.prop('disabled', false).removeClass('bg-light');
            } else {
                inputs.prop('disabled', true).addClass('bg-light').val('');
                row.find('.subtotal').val('');
            }
            hitungTotal();
        }

        // Set default expiration date for first row
        setDefaultExpDate('#row1 .expired');

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

            loadObatBySupplier(id);
        });

        // Clear Supplier
        $('#clearSupplier').click(function() {
            $('#supplier_search').val('');
            $('#supplier_id').val('');
            $('#supplier_dropdown').hide();
            currentObatData = [];
            
            $('.obat-select').each(function() {
                $(this).empty().append('<option value="" selected disabled>Pilih Obat</option>');
                toggleRowFields($(this).closest('tr'), false);
            });
            
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
                    currentObatData = response || [];
                    populateObatDropdowns();
                },
                error: function() {
                    alert('Gagal memuat data obat');
                    currentObatData = [];
                }
            });
        }

        // Populate all obat dropdowns with current data
        function populateObatDropdowns() {
            $('.obat-select').each(function() {
                const currentValue = $(this).val();
                $(this).empty().append('<option value="" selected disabled>Pilih Obat</option>');

                if (currentObatData.length > 0) {
                    currentObatData.forEach(function(obat) {
                        const selected = currentValue == obat.id ? 'selected' : '';
                        $(this).append(`<option value="${obat.id}" data-harga="${obat.harga_beli}" data-stok="${obat.stok}" ${selected}>${obat.nama_obat} (Stok: ${obat.stok})</option>`);
                    }.bind(this));
                }
            });
        }

        // Fungsi untuk menghitung subtotal
        function hitungSubtotal(row) {
            const harga = parseFloat($(row).find('.harga-beli').val()) || 0;
            const qty = parseFloat($(row).find('.qty').val()) || 0;
            const subtotal = harga * qty;
            $(row).find('.subtotal').val(Math.round(subtotal));
            hitungTotal();
        }

        // Fungsi untuk menghitung total
        function hitungTotal() {
            let total = 0;
            $('.subtotal').each(function() {
                total += parseFloat($(this).val()) || 0;
            });
            $('#total').val(Math.round(total));
        }

        // Event ketika memilih obat - enable fields and auto fill harga beli
        $(document).on('change', '.obat-select', function() {
            const selectedOption = $(this).find('option:selected');
            const row = $(this).closest('tr');
            
            if ($(this).val()) {
                toggleRowFields(row, true);
                
                // Auto fill harga beli
                const hargaBeli = selectedOption.data('harga') || 0;
                row.find('.harga-beli').val(hargaBeli);
                
                setDefaultExpDate(row.find('.expired'));
                hitungSubtotal(row);
            } else {
                toggleRowFields(row, false);
            }
        });

        // Event ketika mengubah harga beli atau qty
        $(document).on('change input', '.harga-beli, .qty', function() {
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

            if (currentObatData.length === 0) {
                alert('Tidak ada obat tersedia untuk supplier ini!');
                return;
            }

            rowCount++;
            const defaultExpDate = new Date();
            defaultExpDate.setFullYear(defaultExpDate.getFullYear() + 2);
            const formattedDate = defaultExpDate.toISOString().split('T')[0];

            const newRow = `
                <tr id="row${rowCount}">
                    <td>
                        <select class="form-select obat-select" name="obat_id[]" required>
                            <option value="" selected disabled>Pilih Obat</option>
                        </select>
                    </td>
                    <td>
                        <input type="number" class="form-control harga-beli bg-light" name="harga_beli[]" min="0" step="0.01" required disabled>
                    </td>
                    <td>
                        <input type="number" class="form-control qty bg-light" name="qty[]" min="1" value="1" required disabled>
                    </td>
                    <td>
                        <input type="text" class="form-control batch bg-light" name="nomor_batch[]" placeholder="Batch" required disabled>
                    </td>
                    <td>
                        <input type="date" class="form-control expired bg-light" name="expired_date[]" value="${formattedDate}" required disabled>
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

            populateObatDropdowns();

            if ($('#detailObat tbody tr').length > 1) {
                $('.btn-hapus').prop('disabled', false);
            }
        });

        // Hapus baris obat
        $(document).on('click', '.btn-hapus', function() {
            $(this).closest('tr').remove();
            hitungTotal();

            if ($('#detailObat tbody tr').length <= 1) {
                $('.btn-hapus').prop('disabled', true);
            }
        });

        // Validasi form sebelum submit
        $('#formPembelian').submit(function(e) {
            const supplier_id = $('#supplier_id').val();
            const total = parseFloat($('#total').val()) || 0;
            const nomor_faktur_supplier = $('#nomor_faktur_supplier').val().trim();

            if (!supplier_id) {
                e.preventDefault();
                alert('Silakan pilih supplier terlebih dahulu!');
                return false;
            }

            if (!nomor_faktur_supplier) {
                e.preventDefault();
                alert('Silakan masukkan nomor faktur supplier!');
                return false;
            }

            if (total <= 0) {
                e.preventDefault();
                alert('Silakan isi detail obat terlebih dahulu!');
                return false;
            }

            // Validasi setiap baris obat
            let validationError = false;
            $('#detailObat tbody tr').each(function() {
                const obatSelect = $(this).find('.obat-select');
                const hargaBeli = $(this).find('.harga-beli');
                const qty = $(this).find('.qty');
                const batch = $(this).find('.batch');
                const expired = $(this).find('.expired');

                if (!obatSelect.val()) {
                    validationError = true;
                    obatSelect.focus();
                    alert('Silakan pilih obat untuk semua baris!');
                    return false;
                }

                if (!hargaBeli.val() || parseFloat(hargaBeli.val()) <= 0) {
                    validationError = true;
                    hargaBeli.focus();
                    alert('Silakan isi harga beli yang valid!');
                    return false;
                }

                if (!qty.val() || parseInt(qty.val()) <= 0) {
                    validationError = true;
                    qty.focus();
                    alert('Silakan isi jumlah yang valid!');
                    return false;
                }

                if (!batch.val().trim()) {
                    validationError = true;
                    batch.focus();
                    alert('Silakan isi nomor batch!');
                    return false;
                }

                if (!expired.val()) {
                    validationError = true;
                    expired.focus();
                    alert('Silakan isi tanggal kadaluarsa!');
                    return false;
                }
            });

            if (validationError) {
                e.preventDefault();
                return false;
            }

            return true;
        });
    });
</script>
<?= $this->endSection(); ?>
