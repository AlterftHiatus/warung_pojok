<?php
session_start();
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    $query = "SELECT * FROM admin WHERE username = '$username' AND password = '$password'";
    $result = mysqli_query($conn, $query);
    $admin = mysqli_fetch_assoc($result);

    if ($admin) {
        $_SESSION['role'] = 'admin';
        $_SESSION['nama'] = $admin['nama']; 
        $_SESSION['admin_id'] = $admin['id'];
        header("Location: admin_dashboard.php");
        exit;
    } else {
        $error = "Username atau password salah!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Warung Pojok - Admin Login</title>
    
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
        }
        
        .navbar {
            background: linear-gradient(135deg, var(--primary) 0%, #1e365a 100%);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        
        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
        }
        
        .navbar-brand span {
            color: var(--secondary);
        }
        
        .jumbotron {
            background: linear-gradient(135deg, var(--primary) 0%, #1e365a 100%);
            color: white;
            padding: 2rem 1rem;
            margin-bottom: 2rem;
            border-radius: 0;
            position: relative;
            overflow: hidden;
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
        
        .lead {
            font-weight: 400;
        }
        
        .login-container {
            max-width: 500px;
            margin: 2rem auto;
            padding: 2rem;
            background-color: white;
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .btn-primary {
            background-color: var(secondary);
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
        
        .btn-outline-primary {
            border-color: var(secondary);
            color: var(secondary);
            font-weight: 600;
            transition: all 0.3s ease;
            padding: 10px 20px;
        }
        
        .btn-outline-primary:hover {
            background-color: var(secondary);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(246, 173, 85, 0.3);
        }
        
        footer {
            background-color: var(primary);
            color: white;
            padding: 2rem 0;
            margin-top: auto;
        }
        
        .form-control {
            border-radius: 0.375rem;
            border: 1px solid var(gray);
            padding: 0.75rem 1rem;
            margin-bottom: 1rem;
        }
        
        .form-control:focus {
            border-color: var(secondary);
            box-shadow: 0 0 0 0.2rem rgba(246, 173, 85, 0.25);
        }
        
        .error-message {
            color: var(danger);
            margin-bottom: 1rem;
            font-weight: 500;
        }
        
        .register-link {
            color: var(primary);
            font-weight: 500;
            text-decoration: none;
        }
        
        .register-link:hover {
            text-decoration: underline;
            color: #1e365a;
        }
    </style>
</head>
<body>
    
    <div class="jumbotron jumbotron-fluid text-center mb-4">
        <div class="container">
            <h1 class="display-4 font-weight-bold">LOGIN <span>ADMIN</span></h1>
            <hr class="my-4 bg-light" />
            <p class="lead font-weight-bold">
                Masukkan username dan password Anda<br />
                untuk mengakses panel admin
            </p>
        </div>
    </div>

    
    <div class="container">
        <div class="login-container">
            <h3 class="mb-4 text-center"><i class="fas fa-user-shield mr-2"></i> Admin Login</h3>
            
            <form action="" method="POST">
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" name="username" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary">Login</button>
            </form>
            
            <!-- <div class="text-center mt-3">
                <p>Belum punya akun? <a href="register.php" class="register-link">Daftar sebagai admin</a></p>
            </div> -->
        </div>
    </div>

    <footer>
        <div class="container text-center">
            <p>&copy; 2024 Warung Pojok. All rights reserved.</p>
        </div>
    </footer>

    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>