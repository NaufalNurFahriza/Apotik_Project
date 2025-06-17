<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Tambah Transaksi Penjualan</h1>
    <a href="<?= base_url('transaksi-penjualan'); ?>" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
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
        <h6 class="m-0 font-weight-bold">Form Tambah Transaksi Penjualan</h6>
    </div>
    <div class="card-body">
        <form action="<?= base_url('transaksi-penjualan/simpan'); ?>" method="post" id="formTransaksi">
            <?= csrf_field(); ?>
            <div class="row mb-3">
                <label for="nama_pembeli" class="col-sm-2 col-form-label">Nama Pembeli</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="nama_pembeli" name="nama_pembeli" placeholder="Masukkan nama pembeli atau biarkan kosong untuk 'Pembeli Umum'">
                </div>
            </div>
            <div class="row mb-3">
    <label class="col-sm-2 col-form-label">Member</label>
    <div class="col-sm-10">
        <div class="row">
            <div class="col-md-6">
                <label for="search_nama" class="form-label small">Cari berdasarkan Nama</label>
                <input type="text" class="form-control" id="search_nama" placeholder="Ketik nama member..." autocomplete="off">
                <div id="dropdown_nama" class="dropdown-menu w-100" style="max-height: 200px; overflow-y: auto;"></div>
            </div>
            <div class="col-md-5">
    <label for="search_hp" class="form-label small">Cari berdasarkan No. HP</label>
    <input type="text" class="form-control" id="search_hp" placeholder="Ketik nomor HP..." autocomplete="off">
    <div id="dropdown_hp" class="dropdown-menu w-100" style="max-height: 200px; overflow-y: auto;"></div>
</div>
<div class="col-md-1 d-flex align-items-end">
    <button type="button" class="btn btn-outline-secondary btn-sm" id="btnResetMember" title="Reset Member">
        <i class="fas fa-times"></i>
    </button>
</div>
        </div>
        <div class="mt-2">
            <small class="text-muted">
                <i class="fas fa-info-circle"></i> 
                Pilih member untuk mendapatkan poin. Atau <a href="#" id="btnTambahMemberBaru">tambah member baru</a>
            </small>
        </div>
        <!-- Hidden fields -->
        <input type="hidden" id="member_id" name="member_id">
        <input type="hidden" id="member_nama" name="member_nama">
        <input type="hidden" id="member_hp" name="member_hp">
        <input type="hidden" id="member_poin" name="member_poin" value="0">
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
                                                <option value="<?= $o['id']; ?>" data-harga="<?= $o['harga_jual']; ?>" data-stok="<?= $o['stok']; ?>"><?= $o['nama_obat']; ?> (Stok: <?= $o['stok']; ?>)</option>
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
            
            <!-- Bagian Poin Member - Awalnya disembunyikan -->
            <div class="row mb-3" id="poinMemberSection" style="display: none;">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="poin_tersedia" class="form-label">Poin Tersedia</label>
                                    <input type="number" class="form-control" id="poin_tersedia" readonly>
                                </div>
                                <div class="col-md-4">
                                    <label for="poin_digunakan" class="form-label">Poin Digunakan</label>
                                    <input type="number" class="form-control" id="poin_digunakan" name="poin_digunakan" value="0" min="0">
                                </div>
                                <div class="col-md-4">
                                    <label for="potongan_harga" class="form-label">Potongan (Rp)</label>
                                    <input type="number" class="form-control" id="potongan_harga" name="potongan_harga" value="0" readonly>
                                </div>
                            </div>
                            <div class="mt-2">
                                <small class="text-muted">1 poin = Rp 1.000. Maksimal penggunaan poin 50% dari total belanja.</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row mb-3">
                <label for="subtotal" class="col-sm-2 col-form-label">Subtotal</label>
                <div class="col-sm-10">
                    <input type="number" class="form-control" id="subtotal" name="subtotal" readonly>
                </div>
            </div>
            
            <div class="row mb-3">
                <label for="total" class="col-sm-2 col-form-label fw-bold">Total Bayar</label>
                <div class="col-sm-10">
                    <input type="number" class="form-control form-control-lg bg-light fw-bold" id="total" name="total" readonly>
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
            let subtotal = 0;
            $('.subtotal').each(function() {
                subtotal += parseFloat($(this).val()) || 0;
            });
            
            // Set subtotal
            $('#subtotal').val(subtotal);
            
            // Hitung potongan dari poin
            const potonganHarga = parseInt($('#potongan_harga').val()) || 0;
            
            // Hitung total setelah potongan
            const total = Math.max(0, subtotal - potonganHarga);
            $('#total').val(total);
            
            // Update maksimal poin yang bisa digunakan (50% dari subtotal)
            updateMaksimalPoin(subtotal);
        }
        
        // Fungsi untuk update maksimal poin yang bisa digunakan
        function updateMaksimalPoin(subtotal) {
            if ($('#poinMemberSection').is(':visible')) {
                const poinTersedia = parseInt($('#poin_tersedia').val()) || 0;
                const maksPotongan = Math.floor(subtotal * 0.5); // 50% dari subtotal
                const maksimalPoin = Math.min(poinTersedia, Math.floor(maksPotongan / 1000)); // 1 poin = Rp 1.000
                
                // Update atribut max pada input poin_digunakan
                $('#poin_digunakan').attr('max', maksimalPoin);
                
                // Jika poin yang digunakan melebihi maksimal, sesuaikan
                const poinDigunakan = parseInt($('#poin_digunakan').val()) || 0;
                if (poinDigunakan > maksimalPoin) {
                    $('#poin_digunakan').val(maksimalPoin);
                    // Update potongan harga
                    const potonganBaru = maksimalPoin * 1000;
                    $('#potongan_harga').val(potonganBaru);
                }
                
                // Hitung ulang total
                const potonganHarga = parseInt($('#potongan_harga').val()) || 0;
                const total = Math.max(0, subtotal - potonganHarga);
                $('#total').val(total);
            }
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
                                <option value="<?= $o['id']; ?>" data-harga="<?= $o['harga_jual']; ?>" data-stok="<?= $o['stok']; ?>"><?= $o['nama_obat']; ?> (Stok: <?= $o['stok']; ?>)</option>
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
        
        // Event ketika mengubah poin yang digunakan
        $('#poin_digunakan').on('change', function() {
            const poinDigunakan = parseInt($(this).val()) || 0;
            const poinTersedia = parseInt($('#poin_tersedia').val()) || 0;
            const subtotal = parseInt($('#subtotal').val()) || 0;
            
            // Validasi poin tidak melebihi yang tersedia
            if (poinDigunakan > poinTersedia) {
                alert('Poin yang digunakan tidak boleh melebihi poin tersedia!');
                $(this).val(poinTersedia);
            }
            
            // Validasi poin tidak melebihi maksimal (50% dari subtotal)
            const maksPotongan = Math.floor(subtotal * 0.5); // 50% dari subtotal
            const maksimalPoin = Math.min(poinTersedia, Math.floor(maksPotongan / 1000)); // 1 poin = Rp 1.000
            
            if (poinDigunakan > maksimalPoin) {
                alert('Maksimal penggunaan poin adalah 50% dari total belanja!');
                $(this).val(maksimalPoin);
            }
            
            // Hitung potongan harga
            const potonganHarga = parseInt($(this).val()) * 1000; // 1 poin = Rp 1.000
            $('#potongan_harga').val(potonganHarga);
            
            // Hitung ulang total
            hitungTotal();
        });
        
        // Enhanced form submission handler - add this before the existing submit handler
        $('#formTransaksi').on('submit', function(e) {
            // Always ensure nama_pembeli has a value before submission
            let namaPembeli = $('#nama_pembeli').val().trim();
            if (namaPembeli === '' || namaPembeli === null || namaPembeli === undefined) {
                $('#nama_pembeli').val('Pembeli Umum');
                console.log('Set nama pembeli to: Pembeli Umum');
            }
            
            const subtotal = parseFloat($('#subtotal').val()) || 0;
            
            if (subtotal <= 0) {
                e.preventDefault();
                alert('Silakan pilih obat terlebih dahulu!');
                return false;
            }
            
            // Debug log
            console.log('Form submitted with nama_pembeli:', $('#nama_pembeli').val());
            return true;
        });
        
        // Backup handler - add this after the existing submit handler
        $('#formTransaksi').on('beforeunload submit', function() {
            let namaPembeli = $('#nama_pembeli').val().trim();
            if (!namaPembeli) {
                $('#nama_pembeli').val('Pembeli Umum');
            }
        });
        
        // Data member untuk search
const memberData = <?= json_encode($member); ?>;

// Fungsi untuk filter member berdasarkan nama
function filterMemberByNama(query) {
    return memberData.filter(member => 
        member.nama.toLowerCase().includes(query.toLowerCase())
    );
}

// Fungsi untuk filter member berdasarkan HP
function filterMemberByHP(query) {
    return memberData.filter(member => 
        member.no_hp.includes(query)
    );
}

// Fungsi untuk menampilkan dropdown hasil search
function showDropdown(results, dropdownId, type) {
    const dropdown = $(dropdownId);
    dropdown.empty();
    
    if (results.length === 0) {
        dropdown.append('<div class="dropdown-item-text text-muted">Tidak ada hasil</div>');
    } else {
        results.forEach(member => {
            const displayText = type === 'nama' 
                ? `${member.nama} - ${member.no_hp} (Poin: ${member.poin})`
                : `${member.no_hp} - ${member.nama} (Poin: ${member.poin})`;
                
            dropdown.append(`
                <a class="dropdown-item member-item" href="#" 
                   data-id="${member.id}" 
                   data-nama="${member.nama}" 
                   data-hp="${member.no_hp}" 
                   data-poin="${member.poin}">
                    ${displayText}
                </a>
            `);
        });
    }
    
    dropdown.addClass('show');
}

// Event handler untuk search nama
$('#search_nama').on('input', function() {
    const query = $(this).val().trim();
    $('#dropdown_hp').removeClass('show'); // Hide HP dropdown
    
    if (query.length >= 2) {
        const results = filterMemberByNama(query);
        showDropdown(results, '#dropdown_nama', 'nama');
    } else {
        $('#dropdown_nama').removeClass('show');
    }
});

// Event handler untuk search HP
$('#search_hp').on('input', function() {
    const query = $(this).val().trim();
    $('#dropdown_nama').removeClass('show'); // Hide nama dropdown
    
    if (query.length >= 3) {
        const results = filterMemberByHP(query);
        showDropdown(results, '#dropdown_hp', 'hp');
    } else {
        $('#dropdown_hp').removeClass('show');
    }
});

// Event handler untuk memilih member dari dropdown
$(document).on('click', '.member-item', function(e) {
    e.preventDefault();
    
    const memberId = $(this).data('id');
    const memberNama = $(this).data('nama');
    const memberHP = $(this).data('hp');
    const memberPoin = $(this).data('poin');
    
    // Set hidden fields
    $('#member_id').val(memberId);
    $('#member_nama').val(memberNama);
    $('#member_hp').val(memberHP);
    $('#member_poin').val(memberPoin);
    
    // Auto-fill both search fields
    $('#search_nama').val(memberNama);
    $('#search_hp').val(memberHP);
    
    // Auto-fill nama pembeli
    $('#nama_pembeli').val(memberNama);
    
    // Hide dropdowns
    $('.dropdown-menu').removeClass('show');
    
    // Show/hide poin section
    if (memberPoin > 0) {
        $('#poinMemberSection').show();
        $('#poin_tersedia').val(memberPoin);
        $('#poin_digunakan').val(0);
        $('#potongan_harga').val(0);
        
        // Update maksimal poin yang bisa digunakan
        const subtotal = parseInt($('#subtotal').val()) || 0;
        updateMaksimalPoin(subtotal);
    } else {
        $('#poinMemberSection').hide();
        $('#poin_tersedia').val(0);
        $('#poin_digunakan').val(0);
        $('#potongan_harga').val(0);
    }
    
    // Hitung ulang total
    hitungTotal();
});

// Hide dropdown ketika klik di luar
$(document).on('click', function(e) {
    if (!$(e.target).closest('.dropdown-menu, #search_nama, #search_hp').length) {
        $('.dropdown-menu').removeClass('show');
    }
});

// Clear member selection
function clearMemberSelection() {
    $('#member_id').val('');
    $('#member_nama').val('');
    $('#member_hp').val('');
    $('#member_poin').val('0');
    $('#search_nama').val('');
    $('#search_hp').val('');
    $('#nama_pembeli').val('Pembeli Umum'); // Set default instead of empty
    $('#poinMemberSection').hide();
    $('#poin_tersedia').val(0);
    $('#poin_digunakan').val(0);
    $('#potongan_harga').val(0);
    $('.dropdown-menu').removeClass('show');
    hitungTotal();
}

// Event untuk tombol tambah member baru
$('#btnTambahMemberBaru').click(function(e) {
    e.preventDefault();
    clearMemberSelection();
    $('#modalTambahMember').modal('show');
});

// Update event handler untuk reset form
$('button[type="reset"]').click(function() {
    // Set default name after reset
    setTimeout(function() {
        $('#nama_pembeli').val('Pembeli Umum');
    }, 100);
    clearMemberSelection();
});

// Event handler untuk reset member
$('#btnResetMember').click(function() {
    clearMemberSelection();
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
                        $('#member_id').append(`<option value="${response.id}" data-nama="${nama}" data-poin="0" selected>${nama} - ${no_hp} (Poin: 0)</option>`);
                        
                        // Auto-fill nama pembeli
                        $('#nama_pembeli').val(nama);
                        
                        // Tutup modal
                        $('#modalTambahMember').modal('hide');
                        
                        // Reset form
                        $('#formTambahMember')[0].reset();
                        
                        // Tampilkan pesan sukses
                        alert('Member baru berhasil ditambahkan!');
                        
                        // Sembunyikan bagian poin (member baru poin = 0)
                        $('#poinMemberSection').hide();
                        $('#poin_tersedia').val(0);
                        $('#poin_digunakan').val(0);
                        $('#potongan_harga').val(0);
                        
                        // Hitung ulang total
                        hitungTotal();
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
