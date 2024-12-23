<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

require_once 'config/database.php';

// Ambil data tidur dari MongoDB
$sleepRecords = $collection->aggregate([
    [
        '$lookup' => [
            'from' => 'users',              // collection yang ingin di-join
            'localField' => 'user_id',      // field di sleep_records
            'foreignField' => '_id',        // field di users
            'as' => 'user_details'          // nama field hasil join
        ]
    ],
    [
        '$match' => [
            'user_id' => new MongoDB\BSON\ObjectId($_SESSION['user_id'])
        ]
    ],
    [
        '$sort' => ['date' => -1]
    ]
]);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sleep Tracker</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    
    <!-- Tambahkan SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="container">
        <header class="animate__animated animate__fadeIn">
            <img src="https://cdn-icons-png.flaticon.com/512/3094/3094837.png" alt="Sleep Tracker Logo" class="logo">
            <h1 data-text="Sleep Tracker">Sleep Tracker</h1>
            <div class="user-nav">
                <span>Halo, <?php echo htmlspecialchars($_SESSION['email']); ?></span>
                <a href="logout.php" class="btn btn-secondary">Logout</a>
            </div>
        </header>

        <div class="nav-links">
            <a href="statistics.php" class="btn">Lihat Statistik</a>
        </div>

        <main>
            <section class="input-section animate__animated animate__fadeInUp">
                <h2>Catat Jam Tidur</h2>
                <form action="process/save_sleep.php" method="POST">
                    <div class="form-group">
                        <label for="sleep_time">Jam Tidur:</label>
                        <input type="datetime-local" id="sleep_time" name="sleep_time" required>
                    </div>
                    <div class="form-group">
                        <label for="wake_time">Jam Bangun:</label>
                        <input type="datetime-local" id="wake_time" name="wake_time" required>
                    </div>
                    <div class="form-group">
                        <label for="mood">Mood Setelah Bangun:</label>
                        <select id="mood" name="mood" required>
                            <option value="sangat-segar">Sangat Segar</option>
                            <option value="segar">Segar</option>
                            <option value="biasa">Biasa Saja</option>
                            <option value="lelah">Masih Lelah</option>
                            <option value="sangat-lelah">Sangat Lelah</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="notes">Catatan:</label>
                        <textarea id="notes" name="notes" rows="3" placeholder="Misalnya: Tidur nyenyak, bermimpi, dll"></textarea>
                    </div>
                    <button type="submit" class="btn">Simpan</button>
                </form>
            </section>

            <section class="records-section animate__animated animate__fadeInUp">
                <h2>Riwayat Tidur</h2>
                <div class="records-container">
                    <?php foreach ($sleepRecords as $record): ?>
                        <div class="record-card">
                            <div class="card-actions">
                                <button class="btn-edit" onclick="openEditModal(
                                    '<?php echo $record->_id; ?>', 
                                    '<?php echo $record->sleep_time; ?>', 
                                    '<?php echo $record->wake_time; ?>', 
                                    '<?php echo isset($record->mood) ? $record->mood : ''; ?>', 
                                    '<?php echo isset($record->notes) ? htmlspecialchars($record->notes) : ''; ?>'
                                )">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                                <button class="btn-delete" onclick="confirmDelete('<?php echo $record->_id; ?>')">
                                    <i class="fas fa-trash-alt"></i> Hapus
                                </button>
                            </div>
                            <div class="date"><?php 
                                // Konversi MongoDB UTCDateTime ke timestamp PHP
                                $timestamp = $record->date->toDateTime()->getTimestamp();
                                echo date('d/m/Y', $timestamp); 
                            ?></div>
                            <div class="times">
                                <p>Tidur: <?php echo date('H:i', strtotime($record->sleep_time)); ?></p>
                                <p>Bangun: <?php echo date('H:i', strtotime($record->wake_time)); ?></p>
                                <p>Durasi: <?php echo $record->duration; ?> jam</p>
                            </div>
                            <?php if (isset($record->mood) && !empty($record->mood)): ?>
                            <div class="mood">
                                <p>Mood: <?php echo ucfirst(str_replace('-', ' ', $record->mood)); ?></p>
                            </div>
                            <?php endif; ?>
                            <?php if (isset($record->notes) && !empty($record->notes)): ?>
                            <div class="notes">
                                <p><?php echo htmlspecialchars($record->notes); ?></p>
                            </div>
                            <?php endif; ?>
                            <div class="status <?php echo $record->status; ?>">
                                <?php echo $record->status === 'normal' ? 'Normal' : 'Perlu Diperbaiki'; ?>
                            </div>
                            <div class="user-info">
                                <p>Dicatat oleh: <?php 
                                    if (isset($record->user_details[0])) {
                                        echo htmlspecialchars($record->user_details[0]->name);
                                    } else {
                                        echo "Unknown User";
                                    }
                                ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>
        </main>
        
        <footer class="site-footer">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>Sleep Tracker</h3>
                    <p class="footer-description">
                        Aplikasi untuk membantu Anda memantau dan meningkatkan kualitas tidur sehari-hari.
                    </p>
                    <div class="social-links">
                        <a href="#" class="social-icon">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="social-icon">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="social-icon">
                            <i class="fab fa-instagram"></i>
                        </a>
                    </div>
                </div>
                
                <div class="footer-section">
                    <h3>Fitur Utama</h3>
                    <ul class="footer-links">
                        <li><a href="#">Pencatatan Tidur</a></li>
                        <li><a href="#">Analisis Pola Tidur</a></li>
                        <li><a href="#">Statistik Tidur</a></li>
                        <li><a href="#">Tips Tidur Sehat</a></li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h3>Bantuan</h3>
                    <ul class="footer-links">
                        <li><a href="#">FAQ</a></li>
                        <li><a href="#">Panduan Pengguna</a></li>
                        <li><a href="#">Kontak Kami</a></li>
                        <li><a href="#">Kebijakan Privasi</a></li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h3>Berlangganan</h3>
                    <p>Dapatkan tips tidur sehat mingguan.</p>
                    <form class="subscribe-form">
                        <input type="email" placeholder="Email Anda">
                        <button type="submit" class="btn btn-subscribe">Langganan</button>
                    </form>
                </div>
            </div>
            
            <div class="footer-bottom">
                <div class="footer-wave">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
                        <path fill="#4a90e2" fill-opacity="0.2" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,112C672,96,768,96,864,112C960,128,1056,160,1152,160C1248,160,1344,128,1392,112L1440,96L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path>
                    </svg>
                </div>
                <p>&copy; <?php echo date('Y'); ?> Sleep Tracker. All rights reserved.</p>
            </div>
        </footer>
    </div>

    <!-- Tambahkan Font Awesome untuk ikon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="assets/js/main.js"></script>

    <!-- Add Modal for editing -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Edit Catatan Tidur</h2>
            <form action="process/update_sleep.php" method="POST">
                <input type="hidden" id="edit_record_id" name="record_id">
                <div class="form-group">
                    <label for="edit_sleep_time">Jam Tidur:</label>
                    <input type="datetime-local" id="edit_sleep_time" name="sleep_time" required>
                </div>
                <div class="form-group">
                    <label for="edit_wake_time">Jam Bangun:</label>
                    <input type="datetime-local" id="edit_wake_time" name="wake_time" required>
                </div>
                <div class="form-group">
                    <label for="edit_mood">Mood:</label>
                    <select id="edit_mood" name="mood" required>
                        <option value="sangat-segar">Sangat Segar</option>
                        <option value="segar">Segar</option>
                        <option value="biasa">Biasa Saja</option>
                        <option value="lelah">Masih Lelah</option>
                        <option value="sangat-lelah">Sangat Lelah</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="edit_notes">Catatan:</label>
                    <textarea id="edit_notes" name="notes" rows="3"></textarea>
                </div>
                <button type="submit" class="btn">Simpan Perubahan</button>
            </form>
        </div>
    </div>

    <?php if (isset($_SESSION['success_message'])): ?>
        <div class="alert alert-success">
            <?php 
                echo $_SESSION['success_message'];
                unset($_SESSION['success_message']);
            ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error_message'])): ?>
        <div class="alert alert-error">
            <?php 
                echo $_SESSION['error_message'];
                unset($_SESSION['error_message']);
            ?>
        </div>
    <?php endif; ?>
</body>
</html> 