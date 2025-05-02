<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Tambah Transaksi</h1>
    <a href="<?= base_url('transaksi'); ?>" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
        <i class="fas fa-arrow-left fa-sm text-white-50"></i> Kembali
    </a>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Form Tambah Transaksi</h6>
    </div>
    <div class="card-body">
        <form action="<?= base_url('transaksi/simpan'); ?>" method="post" id="formTransaksi">
            <?= csrf_field(); ?>
            <div class="row mb-3">
                <label for="nama_pembeli" class="col-sm-2 col-form-label">Nama Pembeli</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="nama_pembeli" name="nama_pembeli" required>
                </div>
            </div>
            <div class="row mb-3">
                <label for="member_id" class="col-sm-2 col-form-label">Member</label>
                <div class="col-sm-10">
                    <select class="form-select" id="member_id" name="member_id">
                        <option value="" selected>Pilih Member (Opsional)</option>
                        <?php foreach ($member as $m) : ?>
                            <option value="<?= $m['id']; ?>" data-nama="<?= $m['nama']; ?>"><?= $m['nama']; ?> - <?= $m['no_hp']; ?></option>
                        <?php endforeach; ?>
                        <option value="tambah_baru">+ Tambah Member baru</option>
                    </select>
                </div>
            </div>
            
            <div class="row mb-3">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h6 class="m-0 font-weight-bold">Detail Obat</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="detailObat">
                                    <thead>
                                        <tr>
                                            <th>Obat</th>
                                            <th>Harga</th>
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
                                                    <?php foreach ($obat as $o) : ?>
                                                        <option value="<?= $o['id']; ?>" data-harga="<?= $o['harga']; ?>" data-stok="<?= $o['stok']; ?>"><?= $o['nama_obat']; ?> (Stok: <?= $o['stok']; ?>)</option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </td>
                                            <td>
                                                <input type="number" class="form-control harga" name="harga[]" readonly>
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
                </div>
            </div>
            
            <div class="row mb-3">
                <label for="total" class="col-sm-2 col-form-label">Total</label>
                <div class="col-sm-10">
                    <input type="number" class="form-control" id="total" name="total" readonly>
                </div>
            </div>
            
            <div class="row mb-3">
                <div class="col-sm-10 offset-sm-2">
                    <button type="submit" class="btn btn-primary">Simpan Transaksi</button>
                    <button type="reset" class="btn btn-secondary">Reset</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal Tambah Member -->
<div class="modal fade" id="modalTambahMember" tabindex="-1" aria-labelledby="modalTambahMemberLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTambahMemberLabel">Tambah Member Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formTambahMember">
                    <div class="mb-3">
                        <label for="nama_member" class="form-label">Nama</label>
                        <input type="text" class="form-control" id="nama_member" name="nama_member" required>
                    </div>
                    <div class="mb-3">
                        <label for="no_hp_member" class="form-label">No. HP</label>
                        <input type="text" class="form-control" id="no_hp_member" name="no_hp_member" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="btnSimpanMember">Simpan</button>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>

<?= $this->section('scripts'); ?>
<script>
    $(document).ready(function() {
        let rowCount = 1;
        
        // Fungsi untuk menghitung subtotal
        function hitungSubtotal(row) {
            const harga = parseFloat($(row).find('.harga').val()) || 0;
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
        
        // Event ketika memilih obat
        $(document).on('change', '.obat-select', function() {
            const row = $(this).closest('tr');
            const harga = $(this).find(':selected').data('harga');
            const stok = $(this).find(':selected').data('stok');
            
            $(row).find('.harga').val(harga);
            $(row).find('.qty').attr('max', stok);
            hitungSubtotal(row);
        });
        
        // Event ketika mengubah jumlah
        $(document).on('change', '.qty', function() {
            const row = $(this).closest('tr');
            const max = parseInt($(this).attr('max')) || 0;
            let val = parseInt($(this).val()) || 0;
            
            if (val > max) {
                alert('Stok tidak mencukupi!');
                $(this).val(max);
                val = max;
            }
            
            if (val < 1) {
                $(this).val(1);
                val = 1;
            }
            
            hitungSubtotal(row);
        });
        
        // Tambah baris obat
        $('#btnTambahObat').click(function() {
            rowCount++;
            const newRow = `
                <tr id="row${rowCount}">
                    <td>
                        <select class="form-select obat-select" name="obat_id[]" required>
                            <option value="" selected disabled>Pilih Obat</option>
                            <?php foreach ($obat as $o) : ?>
                                <option value="<?= $o['id']; ?>" data-harga="<?= $o['harga']; ?>" data-stok="<?= $o['stok']; ?>"><?= $o['nama_obat']; ?> (Stok: <?= $o['stok']; ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                    <td>
                        <input type="number" class="form-control harga" name="harga[]" readonly>
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
        $('#formTransaksi').submit(function(e) {
            const total = parseFloat($('#total').val()) || 0;
            
            if (total <= 0) {
                e.preventDefault();
                alert('Silakan pilih obat terlebih dahulu!');
                return false;
            }
            
            return true;
        });
        
        // Event ketika memilih member
        $('#member_id').change(function() {
            const selectedOption = $(this).val();
            
            if (selectedOption === 'tambah_baru') {
                // Tampilkan modal tambah member
                $('#modalTambahMember').modal('show');
                $(this).val(''); // Reset pilihan
            } else if (selectedOption !== '') {
                // Auto-fill nama pembeli dengan nama member
                const namaMember = $('option:selected', this).data('nama');
                $('#nama_pembeli').val(namaMember);
            }
        });
        
        // Simpan member baru via AJAX
        $('#btnSimpanMember').click(function() {
            const nama = $('#nama_member').val();
            const no_hp = $('#no_hp_member').val();
            
            if (!nama || !no_hp) {
                alert('Nama dan No. HP harus diisi!');
                return;
            }
            
            $.ajax({
                url: '<?= base_url('member/simpanAjax'); ?>',
                type: 'POST',
                data: {
                    nama: nama,
                    no_hp: no_hp,
                    <?= csrf_token(); ?>: '<?= csrf_hash(); ?>'
                },
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        // Tambahkan member baru ke dropdown
                        $('#member_id').append(`<option value="${response.id}" data-nama="${nama}" selected>${nama} - ${no_hp}</option>`);
                        
                        // Auto-fill nama pembeli
                        $('#nama_pembeli').val(nama);
                        
                        // Tutup modal
                        $('#modalTambahMember').modal('hide');
                        
                        // Reset form
                        $('#formTambahMember')[0].reset();
                        
                        // Tampilkan pesan sukses
                        alert('Member baru berhasil ditambahkan!');
                    } else {
                        alert('Gagal menambahkan member: ' + response.message);
                    }
                },
                error: function() {
                    alert('Terjadi kesalahan. Silakan coba lagi.');
                }
            });
        });
    });
</script>
<?= $this->endSection(); ?>