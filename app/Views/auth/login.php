<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Apotek Sederhana</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        body {
            background: linear-gradient(180deg, #4e73df 10%, #224abe 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .login-container {
            max-width: 800px;
            width: 100%;
            background-color: #fff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        }
        
        .login-row {
            display: flex;
            flex-wrap: wrap;
        }
        
        .login-image {
            flex: 1;
            background-size: cover;
            background-position: center;
            min-height: 400px;
        }
        
        .login-form {
            flex: 1;
            padding: 2rem;
        }
        
        .login-logo {
            text-align: center;
            margin-bottom: 1.5rem;
        }
        
        .login-logo img {
            width: 80px;
            height: 80px;
        }
        
        .login-title {
            text-align: center;
            margin-bottom: 2rem;
            font-size: 1.5rem;
            font-weight: 700;
            color: #5a5c69;
        }
        
        .form-control {
            padding: 1rem 0.75rem;
            border-radius: 10px;
        }
        
        .btn-login {
            padding: 0.75rem;
            border-radius: 10px;
            background-color: #4e73df;
            border-color: #4e73df;
            font-weight: 600;
        }
        
        .btn-login:hover {
            background-color: #2e59d9;
            border-color: #2653d4;
        }
        
        @media (max-width: 768px) {
            .login-image {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="login-container">
            <div class="login-row">
                <div class="login-image" style="background-image: url('https://images.unsplash.com/photo-1587854692152-cbe660dbde88?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxzZWFyY2h8Mnx8cGhhcm1hY3l8ZW58MHx8MHx8&auto=format&fit=crop&w=500&q=60');"></div>
                <div class="login-form">
                    <div class="login-logo">
                        <h1 class="text-primary"><i class="fas fa-clinic-medical"></i></h1>
                    </div>
                    <h2 class="login-title">Apotek Sederhana</h2>
                    
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
                            <button type="submit" class="btn btn-primary btn-login">Login</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>