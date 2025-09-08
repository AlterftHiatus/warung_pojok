<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Warung Pojok - Login</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome -->
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
      padding: 4rem 1rem;
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
      margin: 3rem auto;
      padding: 2rem;
      background-color: white;
      border-radius: 0.5rem;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
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
    
    .btn-outline-primary {
      border-color: var(--secondary);
      color: var(--secondary);
      font-weight: 600;
      transition: all 0.3s ease;
      padding: 10px 20px;
    }
    
    .btn-outline-primary:hover {
      background-color: var(--secondary);
      color: white;
      transform: translateY(-2px);
      box-shadow: 0 4px 8px rgba(246, 173, 85, 0.3);
    }
    
    footer {
      background-color: var(--primary);
      color: white;
      padding: 2rem 0;
      margin-top: auto;
    }
    
    .form-control {
      border-radius: 0.375rem;
      border: 1px solid var(--gray);
    }
    
    .form-control:focus {
      border-color: var(--secondary);
      box-shadow: 0 0 0 0.2rem rgba(246, 173, 85, 0.25);
    }
    
    .login-option {
      display: flex;
      flex-direction: column;
      gap: 1rem;
      margin-top: 2rem;
    }
    
    .login-option a {
      text-decoration: none;
    }
  </style>
</head>
<body>
  <!-- Jumbotron -->
  <div class="jumbotron jumbotron-fluid text-center mb-4">
    <div class="container">
      <h1 class="display-4 font-weight-bold">WARUNG <span>POJOK</span></h1>
      <hr class="my-4 bg-light" />
      <p class="lead font-weight-bold">
        Silahkan Pilih Jenis Login<br />
        Selamat Datang
      </p>
    </div>
  </div>

  <!-- Login Options -->
  <div class="container">
    <div class="login-container text-center">
      <h3 class="mb-4">Pilih Jenis Login</h3>
      <div class="login-option">
        <a href="login.php">
          <button class="btn btn-primary w-100">
            <i class="fas fa-user-shield mr-2"></i> Masuk sebagai Admin
          </button>
        </a>
        <a href="pesanan.php">
          <button class="btn btn-outline-primary w-100">
            <i class="fas fa-user mr-2"></i> Masuk sebagai Pembeli
          </button>
        </a>
      </div>
    </div>
  </div>

  <footer>
    <div class="container text-center">
      <p>&copy; 2025 Warung Pojok. All rights reserved.</p>
    </div>
  </footer>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>