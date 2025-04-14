<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | Apotek Kita Farma</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        :root {
            --primary-color: #37b24d;
            --secondary-color: #0c8599;
            --background-color: #f5f5f5;
        }
        
        body {
            background-color: var(--background-color);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            padding: 0;
        }
        
        .register-container {
            max-width: 500px;
            width: 100%;
            background-color: #fff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            padding: 2rem;
            margin: 0 auto;
        }
        
        .register-logo {
            text-align: center;
            margin-bottom: 1.5rem;
        }
        
        .register-logo img {
            width: 120px;
            height: auto;
        }
        
        .register-title {
            text-align: center;
            margin-bottom: 2rem;
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--secondary-color);
        }
        
        .form-control {
            padding: 0.75rem;
            border-radius: 10px;
            margin-bottom: 1rem;
        }
        
        .btn-register {
            padding: 0.75rem;
            border-radius: 10px;
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            font-weight: 600;
            width: 100%;
        }
        
        .btn-register:hover {
            background-color: #2b9e3f;
            border-color: #2b9e3f;
        }
        
        .login-link {
            text-align: center;
            margin-top: 1rem;
        }
        
        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            width: 100%;
            padding: 0;
            max-width: 100%;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="register-container">
            <div class="register-logo">
                <img src="<?= base_url('assets/img/logo_apotek_Kita_Farma.png'); ?>" alt="Logo Apotek Kita Farma">
            </div>
            
            <h2 class="register-title">Daftar Akun Admin</h2>
            
            <?php if (session()->getFlashdata('error')) : ?>
                <div class="alert alert-danger">
                    <?= session()->getFlashdata('error'); ?>
                </div>
            <?php endif; ?>
            
            <?php if (session()->getFlashdata('success')) : ?>
                <div class="alert alert-success">
                    <?= session()->getFlashdata('success'); ?>
                </div>
            <?php endif; ?>
            
            <form action="<?= base_url('auth/doRegister'); ?>" method="post">
                <?= csrf_field(); ?>
                <div class="mb-3">
                    <label for="nama_admin" class="form-label">Nama Lengkap</label>
                    <input type="text" class="form-control <?= (session()->getFlashdata('validation_errors') && isset(session()->getFlashdata('validation_errors')['nama_admin'])) ? 'is-invalid' : ''; ?>" 
                           id="nama_admin" name="nama_admin" value="<?= old('nama_admin'); ?>" required>
                    <?php if (session()->getFlashdata('validation_errors') && isset(session()->getFlashdata('validation_errors')['nama_admin'])) : ?>
                        <div class="invalid-feedback">
                            <?= session()->getFlashdata('validation_errors')['nama_admin']; ?>
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control <?= (session()->getFlashdata('validation_errors') && isset(session()->getFlashdata('validation_errors')['username'])) ? 'is-invalid' : ''; ?>" 
                           id="username" name="username" value="<?= old('username'); ?>" required>
                    <?php if (session()->getFlashdata('validation_errors') && isset(session()->getFlashdata('validation_errors')['username'])) : ?>
                        <div class="invalid-feedback">
                            <?= session()->getFlashdata('validation_errors')['username']; ?>
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control <?= (session()->getFlashdata('validation_errors') && isset(session()->getFlashdata('validation_errors')['password'])) ? 'is-invalid' : ''; ?>" 
                           id="password" name="password" required>
                    <?php if (session()->getFlashdata('validation_errors') && isset(session()->getFlashdata('validation_errors')['password'])) : ?>
                        <div class="invalid-feedback">
                            <?= session()->getFlashdata('validation_errors')['password']; ?>
                        </div>
                    <?php endif; ?>
                    <div class="form-text">Password minimal 6 karakter</div>
                </div>
                
                <div class="mb-3">
                    <label for="confirm_password" class="form-label">Konfirmasi Password</label>
                    <input type="password" class="form-control <?= (session()->getFlashdata('validation_errors') && isset(session()->getFlashdata('validation_errors')['confirm_password'])) ? 'is-invalid' : ''; ?>" 
                           id="confirm_password" name="confirm_password" required>
                    <?php if (session()->getFlashdata('validation_errors') && isset(session()->getFlashdata('validation_errors')['confirm_password'])) : ?>
                        <div class="invalid-feedback">
                            <?= session()->getFlashdata('validation_errors')['confirm_password']; ?>
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="mb-3">
                    <label for="registration_code" class="form-label">Kode Registrasi</label>
                    <input type="text" class="form-control <?= (session()->getFlashdata('validation_errors') && isset(session()->getFlashdata('validation_errors')['registration_code'])) ? 'is-invalid' : ''; ?>" 
                           id="registration_code" name="registration_code" required>
                    <?php if (session()->getFlashdata('validation_errors') && isset(session()->getFlashdata('validation_errors')['registration_code'])) : ?>
                        <div class="invalid-feedback">
                            <?= session()->getFlashdata('validation_errors')['registration_code']; ?>
                        </div>
                    <?php endif; ?>
                    <div class="form-text">Masukkan kode registrasi yang diberikan oleh administrator</div>
                </div>
                
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary btn-register">Daftar</button>
                </div>
            </form>
            
            <div class="login-link">
                Sudah punya akun? <a href="<?= base_url('auth'); ?>">Login</a>
            </div>
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>