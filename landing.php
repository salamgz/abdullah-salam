<?php
// Hapus session_start() jika ada
// Halaman landing tidak memerlukan session
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Sleep Tracker</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/landing.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
</head>
<body class="landing-page">
    <div class="landing-container animate__animated animate__fadeIn">
        <header class="landing-header">
            <img src="https://cdn-icons-png.flaticon.com/512/3094/3094837.png" alt="Sleep Tracker Logo" class="logo">
            <h1>Sleep Tracker</h1>
            <p class="tagline">Pantau dan tingkatkan kualitas tidur Anda</p>
        </header>

        <main class="landing-main">
            <div class="features">
                <div class="feature-card animate__animated animate__fadeInUp">
                    <i class="feature-icon">�</i>
                    <h3>Analisis Tidur</h3>
                    <p>Dapatkan insight tentang pola tidur Anda</p>
                </div>
                <div class="feature-card animate__animated animate__fadeInUp" style="animation-delay: 0.2s">
                    <i class="feature-icon">📱</i>
                    <h3>Mudah Digunakan</h3>
                    <p>Interface yang simpel dan user-friendly</p>
                </div>
                <div class="feature-card animate__animated animate__fadeInUp" style="animation-delay: 0.4s">
                    <i class="feature-icon">🌙</i>
                    <h3>Tracking Otomatis</h3>
                    <p>Catat jam tidur dan bangun dengan mudah</p>
                </div>
            </div>

            <div class="cta-buttons">
                <a href="login.php" class="btn btn-primary animate__animated animate__fadeInUp">Masuk</a>
                <a href="register.php" class="btn btn-secondary animate__animated animate__fadeInUp">Daftar</a>
            </div>
        </main>
    </div>
</body>
</html> 