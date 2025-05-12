<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Apotek Kita Farma</title>
    
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
        
        .login-container {
            max-width: 400px;
            width: 100%;
            background-color: #fff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            padding: 2rem;
            margin: 0 auto;
        }
        
        .login-logo {
            text-align: center;
            margin-bottom: 1.5rem;
        }
        
        .login-logo img {
            width: 150px;
            height: auto;
        }
        
        .login-title {
            text-align: center;
            margin-bottom: 2rem;
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--secondary-color);
        }
        
        .form-control {
            padding: 1rem 0.75rem;
            border-radius: 10px;
            margin-bottom: 1rem;
        }
        
        .btn-login {
            padding: 0.75rem;
            border-radius: 10px;
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            font-weight: 600;
            width: 100%;
        }
        
        .btn-login:hover {
            background-color: #2b9e3f;
            border-color: #2b9e3f;
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
        <div class="login-container">
            <div class="login-logo">
                <img src="<?= base_url('assets/img/logo_apotek_Kita_Farma.png'); ?>" alt="Logo Apotek Kita Farma">
            </div>
            
            <?php if (session()->getFlashdata('error')) : ?>
                <div class="alert alert-danger">
                    <?= session()->getFlashdata('error'); ?>
                </div>
            <?php endif; ?>
            
            <form action="<?= base_url('auth/login'); ?>" method="post">
                <div class="mb-3">
                    <input type="text" class="form-control" name="username" placeholder="Username" required>
                </div>
                <div class="mb-4">
                    <input type="password" class="form-control" name="password" placeholder="Password" required>
                </div>
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary btn-login">Masuk</button>
                </div>
                <div class="text-center mt-3">
                    <p>Belum punya akun? <a href="<?= base_url('auth/register'); ?>">Daftar</a></p>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
