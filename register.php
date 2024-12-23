<?php
require_once 'config/database.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validasi
    $errors = [];
    
    if ($password !== $confirm_password) {
        $errors[] = "Password tidak cocok!";
    }

    // Cek email sudah terdaftar
    $existingUser = $database->users->findOne(['email' => $email]);
    if ($existingUser) {
        $errors[] = "Email sudah terdaftar!";
    }

    if (empty($errors)) {
        // Hash password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        // Simpan user baru
        $result = $database->users->insertOne([
            'name' => $name,
            'email' => $email,
            'password' => $hashedPassword,
            'created_at' => new MongoDB\BSON\UTCDateTime()
        ]);

        if ($result->getInsertedCount()) {
            $_SESSION['register_success'] = true;
            header('Location: login.php');
            exit;
        } else {
            $errors[] = "Gagal mendaftar. Silakan coba lagi.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - Sleep Tracker</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/auth.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
</head>
<body class="auth-page">
    <div class="auth-container animate__animated animate__fadeIn">
        <div class="auth-card">
            <img src="https://cdn-icons-png.flaticon.com/512/3094/3094837.png" alt="Sleep Tracker Logo" class="auth-logo">
            <h2>Daftar Sleep Tracker</h2>
            
            <?php if (!empty($errors)): ?>
                <div class="alert alert-error">
                    <?php foreach ($errors as $error): ?>
                        <p><?php echo $error; ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <form action="register.php" method="POST" class="auth-form">
                <div class="form-group">
                    <label for="name">Nama Lengkap</label>
                    <input type="text" id="name" name="name" required placeholder="Masukkan nama lengkap">
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required placeholder="Masukkan email">
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required placeholder="Masukkan password">
                </div>
                <div class="form-group">
                    <label for="confirm_password">Konfirmasi Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" required placeholder="Konfirmasi password">
                </div>
                <button type="submit" class="btn">Daftar Sekarang</button>
            </form>
            
            <div class="auth-links">
                <p>Sudah punya akun? <a href="login.php">Masuk</a></p>
                <a href="landing.php">Kembali ke Beranda</a>
            </div>
        </div>
    </div>
</body>
</html> 