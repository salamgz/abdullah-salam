<?php
require_once 'config/database.php';
session_start();

// Ambil data untuk 7 hari terakhir
$endDate = new MongoDB\BSON\UTCDateTime(strtotime('now') * 1000);
$startDate = new MongoDB\BSON\UTCDateTime(strtotime('-7 days') * 1000);

$sleepRecords = $collection->find(
    ['date' => ['$gte' => $startDate, '$lte' => $endDate]],
    ['sort' => ['date' => 1]]
);

$dates = [];
$durations = [];
foreach ($sleepRecords as $record) {
    $dates[] = date('d/m', strtotime($record->date));
    $durations[] = $record->duration;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistik Tidur</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="container">
        <header>
            <h1>Statistik Tidur</h1>
            <a href="index.php" class="btn">Kembali</a>
        </header>

        <main>
            <section class="stats-section">
                <div class="stats-card">
                    <h2>Grafik Durasi Tidur (7 Hari Terakhir)</h2>
                    <canvas id="sleepChart"></canvas>
                </div>
                
                <div class="stats-summary">
                    <div class="summary-card">
                        <h3>Rata-rata Durasi Tidur</h3>
                        <p><?php echo number_format(array_sum($durations) / count($durations), 1); ?> jam</p>
                    </div>
                    <div class="summary-card">
                        <h3>Kualitas Tidur</h3>
                        <p><?php 
                            $normalCount = array_filter($durations, function($d) { return $d >= 7 && $d <= 9; });
                            echo number_format((count($normalCount) / count($durations)) * 100, 0) . '%';
                        ?></p>
                    </div>
                </div>
            </section>
        </main>
    </div>

    <script>
        const ctx = document.getElementById('sleepChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: <?php echo json_encode($dates); ?>,
                datasets: [{
                    label: 'Durasi Tidur (Jam)',
                    data: <?php echo json_encode($durations); ?>,
                    borderColor: '#4a90e2',
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: 'Pola Tidur 7 Hari Terakhir'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        suggestedMax: 12
                    }
                }
            }
        });
    </script>
</body>
</html> 