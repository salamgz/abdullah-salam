<?php
require_once 'config/database.php';
session_start();

// Jika sudah login, redirect ke index
if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $user = $database->users->findOne(['email' => $email]);

    if ($user && password_verify($password, $user->password)) {
        $_SESSION['user_id'] = (string)$user->_id;
        $_SESSION['email'] = $user->email;
        header('Location: index.php');
        exit;
    } else {
        $error = "Email atau password salah!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sleep Tracker</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/auth.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
</head>
<body class="auth-page">
    <div class="auth-container animate__animated animate__fadeIn">
        <div class="auth-card">
            <img src="https://cdn-icons-png.flaticon.com/512/3094/3094837.png" alt="Sleep Tracker Logo" class="auth-logo">
            <h2>Masuk ke Sleep Tracker</h2>
            
            <?php if (isset($error)): ?>
                <div class="alert alert-error"><?php echo $error; ?></div>
            <?php endif; ?>

            <?php if (isset($_SESSION['register_success'])): ?>
                <div class="alert alert-success">Registrasi berhasil! Silakan login.</div>
                <?php unset($_SESSION['register_success']); ?>
            <?php endif; ?>

            <form action="login.php" method="POST" class="auth-form">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required 
                           placeholder="Masukkan email Anda"
                           autocomplete="email">
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required 
                           placeholder="Masukkan password Anda"
                           autocomplete="current-password">
                </div>
                <button type="submit" class="btn">
                    <span>Masuk</span>
                </button>
            </form>
            
            <div class="auth-links">
                <p>Belum punya akun? <a href="register.php" class="animate__animated animate__pulse">Daftar</a></p>
                <a href="landing.php" class="back-link">Kembali ke Beranda</a>
            </div>
        </div>
    </div>

    <script>
        // Animasi untuk form input saat focus
        document.querySelectorAll('.auth-form input').forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.classList.add('focused');
            });
            input.addEventListener('blur', function() {
                if (!this.value) {
                    this.parentElement.classList.remove('focused');
                }
            });
        });

        // Animasi untuk button saat hover
        const loginButton = document.querySelector('.auth-form button');
        loginButton.addEventListener('mouseover', function() {
            this.classList.add('animate__animated', 'animate__pulse');
        });
        loginButton.addEventListener('animationend', function() {
            this.classList.remove('animate__animated', 'animate__pulse');
        });
    </script>
</body>
</html> 