    <!-- <?php session_start(); ?>
    <!DOCTYPE html>
    <html lang="id">
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | Warung Pojok</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
        --primary: #2a4365;
        --secondary: #f6ad55;
        --dark: #1a202c;
        --light: #f7fafc;
        --success: #38a169;
        --danger: #e53e3e;
        --gray: #e2e8f0;
        }
        
        body {
        background-color: var(--light);
        color: var(--dark);
        font-family: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
        min-height: 100vh;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        }
        
        .jumbotron {
        background: linear-gradient(135deg, var(--primary) 0%, #1e365a 100%);
        color: white;
        padding: 2rem 1rem;
        margin-bottom: 2rem;
        border-radius: 0;
        position: relative;
        overflow: hidden;
        width: 100%;
        text-align: center;
        }
        
        .jumbotron::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('https://images.unsplash.com/photo-1504674900247-0877df9cc836?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80') center/cover;
        opacity: 0.15;
        z-index: 0;
        }
        
        .jumbotron .container {
        position: relative;
        z-index: 1;
        }
        
        .display-4 {
        font-weight: 700;
        text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.2);
        }
        
        .display-4 span {
        color: var(--secondary);
        }
        
        .register-container {
        max-width: 500px;
        width: 100%;
        padding: 2rem;
        background-color: white;
        border-radius: 0.5rem;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        margin: 0 auto;
        }
        
        .btn-primary {
        background-color: var(--secondary);
        border: none;
        color: var(--dark);
        font-weight: 600;
        transition: all 0.3s ease;
        padding: 10px 20px;
        }
        
        .btn-primary:hover {
        background-color: #f6a029;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(246, 173, 85, 0.3);
        }
        
        .form-control {
        border-radius: 0.375rem;
        border: 1px solid var(--gray);
        padding: 0.75rem 1rem;
        margin-bottom: 1rem;
        }
        
        .form-control:focus {
        border-color: var(--secondary);
        box-shadow: 0 0 0 0.2rem rgba(246, 173, 85, 0.25);
        }
        
        .alert {
        border-radius: 0.375rem;
        margin-bottom: 1.5rem;
        }
        
        .login-link {
        color: var(primary);
        font-weight: 500;
        text-decoration: none;
        }
        
        .login-link:hover {
        text-decoration: underline;
        color: #1e365a;
        }
        
        footer {
        background-color: var(primary);
        color: white;
        padding: 1.5rem 0;
        margin-top: 2rem;
        width: 100%;
        text-align: center;
        }
    </style>
    </head>
    <body>
    
    <div class="jumbotron jumbotron-fluid">
        <div class="container">
        <h1 class="display-4 font-weight-bold">DAFTAR <span>AKUN</span></h1>
        <hr class="my-4 bg-light" />
        <p class="lead font-weight-bold">
            Buat akun baru untuk mengakses Warung Pojok
        </p>
        </div>
    </div>

    
    <div class="container">
        <div class="register-container">
        <h3 class="text-center mb-4"><i class="fas fa-user-plus mr-2"></i> Form Pendaftaran</h3>

        <?php if (isset($_SESSION['flash'])): ?>
            <div class="alert alert-<?= $_SESSION['flash']['type'] ?>">
            <?= $_SESSION['flash']['msg'] ?>
            </div>
            <?php unset($_SESSION['flash']); ?>
        <?php endif; ?>

        <form action="process_register.php" method="POST">
            <div class="mb-3">
            <label class="form-label">Nama Lengkap</label>
            <input type="text" name="fullname" class="form-control" required>
            </div>
            <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" required>
            </div>
            <div class="mb-3">
            <label class="form-label">Username</label>
            <input type="text" name="username" class="form-control" required>
            </div>
            <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" required minlength="6">
            </div>
            <div class="mb-3">
            <label class="form-label">Konfirmasi Password</label>
            <input type="password" name="confirm" class="form-control" required>
            </div>
            <button class="btn btn-primary w-100">
            <i class="fas fa-user-plus mr-2"></i> Daftar Sekarang
            </button>
            <p class="text-center mt-3">Sudah punya akun? <a href="login.php" class="login-link">Login disini</a></p>
        </form>
        </div>
    </div>

    <footer>
        <div class="container">
        <p>&copy; 2024 Warung Pojok. All rights reserved.</p>
        </div>
    </footer>

    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>
    </html> -->