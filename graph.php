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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donations by Program</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <h2>Total Donations by Program</h2>
    <div>
        <canvas id="donationChart"></canvas>
    </div>

    <script>
        const ctx = document.getElementById('donationChart').getContext('2d');
        const donationChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?= json_encode($programs); ?>,
                datasets: [{
                    label: '₱ Donations',
                    data: <?= json_encode($totals); ?>,
                    backgroundColor: '#4e73df', // Simple color
                    borderRadius: 5
                }]
            },
            options: {
                indexAxis: 'y', // makes it horizontal
                responsive: true,
                plugins: {
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
                            color: '#333'
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>
