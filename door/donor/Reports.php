<?php
session_start();
require '../../data/config.php';

// Check if donor is logged in
if (!isset($_SESSION['donor_id'])) {
    header("Location: donor_login.php");
    exit();
}

$donor_id = $_SESSION['donor_id'];
$donor_name = $_SESSION['donor_name'] ?? 'Donor';

// Handle marking report as read
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['report_id'])) {
    $report_id = (int) $_POST['report_id'];
    // Insert read record if not exists
    $sqlInsert = "INSERT IGNORE INTO report_reads (report_id, donor_id) VALUES (:report_id, :donor_id)";
    $stmtInsert = $pdo->prepare($sqlInsert);
    $stmtInsert->execute(['report_id' => $report_id, 'donor_id' => $donor_id]);
    // Redirect to avoid form resubmission
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Fetch reports and whether donor has read them
$sql = "
SELECT 
    r.id, r.audience, r.message, r.created_at, a.email AS admin_name,
    rr.id AS read_id
FROM reports r
JOIN admins a ON r.admin_id = a.id
LEFT JOIN report_reads rr ON rr.report_id = r.id AND rr.donor_id = :donor_id
WHERE r.audience IN ('donor', 'all')
ORDER BY r.created_at DESC
";

$stmt = $pdo->prepare($sql);
$stmt->execute(['donor_id' => $donor_id]);
$reports = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="stylesheet" href="dashboard.css" />
  <title>Reports</title>
  <style>
    .report-container {
      border: 1px solid #ccc; 
      padding: 15px; 
      margin-bottom: 15px; 
      border-radius: 5px;
      background-color: #f9f9f9;
      position: relative;
    }
    .report-meta {
      font-size: 0.9em;
      color: #555;
      margin-bottom: 10px;
    }
    .read-button {
      position: absolute;
      top: 15px;
      right: 15px;
    }
    .read-button button[disabled] {
      background-color: #aaa;
      cursor: default;
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
        <a href="Message.php">Message</a>
        <a href="Request_Report.php" class="active">Request Reports</a>
        <a href="Reports.php" class="active">Reports</a>
        
      </div>
    </div>
    <a class="logout-btn" href="../logout.php">Logout</a>
  </div>

  <main class="main-content">
    <div class="navbar">
      <h1>Nonprofit Resource Management</h1>
    </div>

    <h1>Welcome, <?= htmlspecialchars($donor_name) ?>!</h1>
    <p>These are the latest updates and reports from administrators.</p>

    <section class="reports-section">
      <h2>Reports for You</h2>

      <?php if (empty($reports)): ?>
        <p>No reports available at the moment.</p>
      <?php else: ?>
        <?php foreach ($reports as $report): ?>
          <div class="report-container">
            <div class="report-meta">
              <strong>From:</strong> <?= htmlspecialchars($report['admin_name']) ?> |
              <strong>Audience:</strong> <?= htmlspecialchars($report['audience']) ?> |
              <strong>Date:</strong> <?= htmlspecialchars($report['created_at']) ?>
            </div>
            <p><?= nl2br(htmlspecialchars($report['message'])) ?></p>

            <div class="read-button">
              <?php if ($report['read_id']): ?>
                <button disabled>Read</button>
              <?php else: ?>
                <form method="POST" style="margin:0;">
                  <input type="hidden" name="report_id" value="<?= (int)$report['id'] ?>">
                  <button type="submit">Mark as Read</button>
                </form>
              <?php endif; ?>
            </div>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </section>

  </main>
</div>

</body>
</html>
