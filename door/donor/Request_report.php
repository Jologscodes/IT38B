<?php
session_start();
require '../../data/config.php';

if (!isset($_SESSION['donor_id'])) {
    header("Location: donor_login.php");
    exit();
}

$stmt = $pdo->prepare("SELECT * FROM request WHERE status = 'Confirmed' ORDER BY request_date DESC");
$stmt->execute();
$requests = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="stylesheet" href="styles.css" />
  <title>Request_report</title>
  <style>
    main {
      flex-grow: 1;
      padding: 30px;
    }

    .request-card {
      background-color: #e0ffe0;
      padding: 15px;
      margin: 10px 0;
      border-radius: 8px;
      box-shadow: 0 0 10px #90ee90;
      font-family: Arial, sans-serif;
    }

    .request-card h3 {
      margin-top: 0;
      color: #2e7d32;
    }

    .request-card small {
      color: #555;
    }

    .dashboard-container {
      display: flex;
      min-height: 100vh;
    }

   
  </style>
</head>
<body>
<div class="dashboard-container">
  <div class="sidebar">
    <div>
      <h2>Donor Panel</h2>
      <div class="nav-links">
        <a href="donor_dashboard.php">Dashboard</a>
        <a href="make_donation.php">Make a Donation</a>
        <a href="donation_history.php">Donation History</a>
        <a href="Message.php" class="active">Message</a>
        <a href="Request_Report.php">Request Reports</a>
        <a href="Reports.php">Reports</a>
      </div>
    </div>
    <a class="logout-btn" href="../logout.php">Logout</a>
  </div>

  <main>
    <h1>Messages / Requests</h1>
    <?php if (count($requests) > 0): ?>
      <?php foreach ($requests as $request): ?>
        <div class="request-card">
          <h3><?= htmlspecialchars($request['title']) ?></h3>
          <p><?= nl2br(htmlspecialchars($request['description'])) ?></p>
          <small>Requested on: <?= date("F j, Y, g:i a", strtotime($request['request_date'])) ?></small><br>
          <small>Status: <strong><?= htmlspecialchars($request['status']) ?></strong></small>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <p>No approved requests found.</p>
    <?php endif; ?>
  </main>
</div>
</body>
</html>
