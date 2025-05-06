<?php
// Database connection
$host = "localhost";
$user = "root";
$password = "";
$database = "entrep-dev";

$conn = new mysqli($host, $user, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch total donations per program
$sql = "SELECT program_name, SUM(amount) as total_amount 
        FROM donations 
        GROUP BY program_name";
$result = $conn->query($sql);

$programs = [];
$totals = [];

while ($row = $result->fetch_assoc()) {
    $programs[] = $row['program_name'];
    $totals[] = $row['total_amount'];
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Donations by Program</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            background: #f5f7fa;
        }
        h2 {
            text-align: center;
            color: #333;
        }
        .chart-container {
            width: 90%;
            max-width: 700px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <h2>"Total Donations by Program"</h2>
    <div class="chart-container">
        <canvas id="donationChart"></canvas>
    </div>

    <script>
        const ctx = document.getElementById('donationChart').getContext('2d');
        const donationChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?= json_encode($programs); ?>,
                datasets: [{
                    label: '₱ Donations Received',
                    data: <?= json_encode($totals); ?>,
                    backgroundColor: [
                        '#a0d8ef', '#ffb6b9', '#c1f0c1', '#ffd6a5', '#b5ead7', '#fcd5ce'
                    ],
                    borderRadius: 8,
                    borderSkipped: false
                }]
            },
            options: {
                indexAxis: 'y', // makes it horizontal
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: (ctx) => `₱ ${ctx.parsed.x.toLocaleString()}`
                        }
                    }
                },
                scales: {
                    x: {
                        beginAtZero: true,
                        ticks: {
                            callback: (value) => '₱ ' + value.toLocaleString()
                        }
                    },
                    y: {
                        ticks: {
                            color: '#333',
                            font: {
                                weight: 'bold'
                            }
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>
