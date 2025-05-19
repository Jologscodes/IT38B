<?php
session_start();

if (!isset($_SESSION['beneficiary_id'])) {
    header("Location: beneficiary_login.php");
    exit();
}

$name = $_SESSION['beneficiary_name'] ?? "Beneficiary";

require '../../data/config.php'; // Adjust path if needed

// Fetch reports for beneficiaries or all
$sql = "SELECT r.id, r.message, r.created_at, a.email AS admin_email
        FROM reports r
        JOIN admins a ON r.admin_id = a.id
        WHERE r.audience IN ('beneficiary', 'all')
        ORDER BY r.created_at DESC";

$stmt = $pdo->query($sql);
$reports = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Beneficiary Dashboard</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="rr.css" />
  <style>
    .reports-container {
      margin-top: 30px;
      padding: 15px;
      background-color: #f0f4f8;
      border-radius: 8px;
    }

    .report-box {
      background: white;
      border: 1px solid #ccc;
      border-radius: 6px;
      padding: 12px 15px;
      margin-bottom: 15px;
      box-shadow: 1px 1px 4px rgba(0,0,0,0.05);
    }

    .report-box small {
      color: #555;
      display: block;
      margin-bottom: 8px;
      font-size: 0.9em;
    }
  </style>
</head>
<body>
  <nav class="sidebar">
    <h2>Dashboard</h2>

    <a href="beneficiary_dashboard.php">beneficiary dashboard</a>
    <a href="ReviewResources.php">Review Resources</a>
    <a href="RequestResources.php" class="active">Request Resources</a>
  </nav>

  <div class="main-content">
    <header class="topnav">
      <h1>Beneficiary Portal</h1>
      <a href="../logout.php" class="logout-btn">Logout</a>
    </header>

    <main class="dashboard">
      <h1>Welcome, <?php echo htmlspecialchars($name); ?>!</h1>
      <p>You have successfully logged in as a beneficiary.</p>

      <section class="reports-container">
        <h2>Reports for You</h2>

        <?php if (empty($reports)): ?>
          <p>No reports available at the moment.</p>
        <?php else: ?>
          <?php foreach ($reports as $report): ?>
            <div class="report-box">
              <small>
                <strong>From:</strong> <?= htmlspecialchars($report['admin_email']) ?> | 
                <strong>Date:</strong> <?= htmlspecialchars($report['created_at']) ?>
              </small>
              <p><?= nl2br(htmlspecialchars($report['message'])) ?></p>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </section>
    </main>
  </div>
</body>
</html>
