<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
   <h1 class="h3 mb-0 text-gray-800">Tambah TTK</h1>
   <a href="<?= base_url('user'); ?>" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
       <i class="fas fa-arrow-left fa-sm text-white-50"></i> Kembali
   </a>
</div>

<!-- Alert untuk error -->
<?php if (session()->getFlashdata('error')): ?>
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <strong>Error!</strong> <?= session()->getFlashdata('error'); ?>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<?php endif; ?>

<div class="card shadow mb-4">
   <div class="card-header py-3">
       <h6 class="m-0 font-weight-bold text-primary">
           <i class="fas fa-user-plus"></i> Form Tambah TTK
       </h6>
   </div>
   <div class="card-body">
       <form action="<?= base_url('user/simpan'); ?>" method="post">
           <?= csrf_field(); ?>
           
           <div class="row mb-3">
               <label for="nama" class="col-sm-3 col-form-label">Nama Lengkap <span class="text-danger">*</span></label>
               <div class="col-sm-9">
                   <input type="text" class="form-control <?= ($validation->hasError('nama')) ? 'is-invalid' : ''; ?>" 
                          id="nama" name="nama" value="<?= old('nama'); ?>" required>
                   <div class="invalid-feedback">
                       <?= $validation->getError('nama'); ?>
                   </div>
                   <small class="form-text text-muted">Masukkan nama lengkap TTK</small>
               </div>
           </div>

           <div class="row mb-3">
               <label for="username" class="col-sm-3 col-form-label">Username <span class="text-danger">*</span></label>
               <div class="col-sm-9">
                   <input type="text" class="form-control <?= ($validation->hasError('username')) ? 'is-invalid' : ''; ?>" 
                          id="username" name="username" value="<?= old('username'); ?>" required>
                   <div class="invalid-feedback">
                       <?= $validation->getError('username'); ?>
                   </div>
                   <small class="form-text text-muted">Username untuk login (minimal 4 karakter)</small>
               </div>
           </div>

           <div class="row mb-3">
               <label for="password" class="col-sm-3 col-form-label">Password <span class="text-danger">*</span></label>
               <div class="col-sm-9">
                   <div class="input-group">
                       <input type="password" class="form-control <?= ($validation->hasError('password')) ? 'is-invalid' : ''; ?>" 
                              id="password" name="password" required>
                       <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                           <i class="fas fa-eye"></i>
                       </button>
                   </div>
                   <div class="invalid-feedback">
                       <?= $validation->getError('password'); ?>
                   </div>
                   <small class="form-text text-muted">Password minimal 6 karakter</small>
               </div>
           </div>

           <div class="row mb-3">
               <label for="confirm_password" class="col-sm-3 col-form-label">Konfirmasi Password <span class="text-danger">*</span></label>
               <div class="col-sm-9">
                   <div class="input-group">
                       <input type="password" class="form-control <?= ($validation->hasError('confirm_password')) ? 'is-invalid' : ''; ?>" 
                              id="confirm_password" name="confirm_password" required>
                       <button class="btn btn-outline-secondary" type="button" id="toggleConfirmPassword">
                           <i class="fas fa-eye"></i>
                       </button>
                   </div>
                   <div class="invalid-feedback">
                       <?= $validation->getError('confirm_password'); ?>
                   </div>
               </div>
           </div>

           <div class="row mb-3">
               <label for="role" class="col-sm-3 col-form-label">Role <span class="text-danger">*</span></label>
               <div class="col-sm-9">
                   <select class="form-select <?= ($validation->hasError('role')) ? 'is-invalid' : ''; ?>" 
                           id="role" name="role" required>
                       <option value="">Pilih Role</option>
                       <option value="ttk" <?= (old('role') == 'ttk') ? 'selected' : ''; ?>>TTK (Tenaga Teknis Kefarmasian)</option>
                       <option value="pemilik" <?= (old('role') == 'pemilik') ? 'selected' : ''; ?>>Pemilik</option>
                   </select>
                   <div class="invalid-feedback">
                       <?= $validation->getError('role'); ?>
                   </div>
                   <small class="form-text text-muted">
                       <strong>TTK:</strong> Akses data obat, member, transaksi penjualan<br>
                       <strong>Pemilik:</strong> Akses penuh termasuk mengelola TTK dan transaksi pembelian
                   </small>
               </div>
           </div>

           <hr>

           <div class="row">
               <div class="col-sm-9 offset-sm-3">
                   <button type="submit" class="btn btn-primary">
                       <i class="fas fa-save"></i> Simpan
                   </button>
                   <button type="reset" class="btn btn-secondary">
                       <i class="fas fa-undo"></i> Reset
                   </button>
                   <a href="<?= base_url('user'); ?>" class="btn btn-outline-secondary">
                       <i class="fas fa-times"></i> Batal
                   </a>
               </div>
           </div>
       </form>
   </div>
</div>

<!-- Info Card -->
<div class="card border-left-info shadow">
   <div class="card-body">
       <div class="row no-gutters align-items-center">
           <div class="col mr-2">
               <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                   Catatan Penting
               </div>
               <div class="text-sm text-gray-600">
                   <ul class="mb-0">
                       <li>Pastikan username unik dan mudah diingat</li>
                       <li>Password akan dienkripsi secara otomatis</li>
                       <li>Role menentukan hak akses dalam sistem</li>
                       <li>Data TTK dapat diubah setelah disimpan</li>
                   </ul>
               </div>
           </div>
           <div class="col-auto">
               <i class="fas fa-info-circle fa-2x text-gray-300"></i>
           </div>
       </div>
   </div>
</div>
<?= $this->endSection(); ?>

<?= $this->section('scripts'); ?>
<script>
// Toggle password visibility
document.getElementById('togglePassword').addEventListener('click', function() {
   const password = document.getElementById('password');
   const icon = this.querySelector('i');
   
   if (password.type === 'password') {
       password.type = 'text';
       icon.classList.remove('fa-eye');
       icon.classList.add('fa-eye-slash');
   } else {
       password.type = 'password';
       icon.classList.remove('fa-eye-slash');
       icon.classList.add('fa-eye');
   }
});

document.getElementById('toggleConfirmPassword').addEventListener('click', function() {
   const confirmPassword = document.getElementById('confirm_password');
   const icon = this.querySelector('i');
   
   if (confirmPassword.type === 'password') {
       confirmPassword.type = 'text';
       icon.classList.remove('fa-eye');
       icon.classList.add('fa-eye-slash');
   } else {
       confirmPassword.type = 'password';
       icon.classList.remove('fa-eye-slash');
       icon.classList.add('fa-eye');
   }
});

// Password confirmation validation
document.getElementById('confirm_password').addEventListener('input', function() {
   const password = document.getElementById('password').value;
   const confirmPassword = this.value;
   
   if (password !== confirmPassword) {
       this.setCustomValidity('Password tidak cocok');
   } else {
       this.setCustomValidity('');
   }
});
</script>
<?= $this->endSection(); ?>
