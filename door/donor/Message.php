<?php
session_start();
require '../../data/config.php'; // Adjust the path as needed

if (!isset($_SESSION['donor_id'])) {
    header("Location: donor_login.php");
    exit();
}

$donor_id = $_SESSION['donor_id'];
$donor_name = $_SESSION['donor_name'] ?? 'Donor';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['request_id'], $_POST['action'])) {
    $request_id = (int)$_POST['request_id'];
    $action = $_POST['action'] === 'accept' ? 'accepted' : 'denied';

    $stmt = $pdo->prepare("UPDATE resource_requests SET status = ? WHERE id = ?");
    $stmt->execute([$action, $request_id]);

    $_SESSION['message'] = "Request has been " . strtoupper($action) . ".";
    header("Location: Message.php");
    exit();
}

$donationIdsStmt = $pdo->prepare("SELECT id FROM item_donations WHERE donor_id = ?");
$donationIdsStmt->execute([$donor_id]);
$donationIds = $donationIdsStmt->fetchAll(PDO::FETCH_COLUMN);

$placeholders = rtrim(str_repeat('?,', count($donationIds)), ',');

$requests = [];
if ($placeholders) {
    $requestStmt = $pdo->prepare("
        SELECT rr.*, b.name AS beneficiary_name, idn.description 
        FROM resource_requests rr
        JOIN beneficiaries b ON rr.beneficiary_id = b.id
        JOIN item_donations idn ON rr.item_donation_id = idn.id
        WHERE rr.item_donation_id IN ($placeholders)
        ORDER BY rr.request_date DESC
    ");
    $requestStmt->execute($donationIds);
    $requests = $requestStmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="stylesheet" href="styles.css" />
  <title>Messages</title>
  <style>
    .fade-out {
      opacity: 1;
      transition: opacity 1s ease-out;
    }
    .fade-out.hide {
      opacity: 0;
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
        <a href="Request_Report.php" class="active">Request Reports</a>
        <a href="Reports.php">Reports</a>
      </div>
    </div>
    <a class="logout-btn" href="../logout.php">Logout</a>
  </div>

  <main class="main-content">
    <div class="navbar">
      <h1>Donation Requests</h1>
    </div>

    <?php if (isset($_SESSION['message'])): ?>
      <div id="flash-message" class="fade-out" style="background:#d4edda; color:#155724; padding:10px; border-radius:5px; margin-bottom:10px;">
        <?= htmlspecialchars($_SESSION['message']) ?>
        <?php unset($_SESSION['message']); ?>
      </div>
      <script>
        setTimeout(() => {
          const msg = document.getElementById('flash-message');
          msg.classList.add('hide');
        }, 3000);
      </script>
    <?php endif; ?>

    <?php if (empty($requests)): ?>
      <p>No requests have been made for your donations yet.</p>
    <?php else: ?>
      <?php foreach ($requests as $req): ?>
        <div class="message-container" style="border:1px solid #ccc; padding:15px; margin-bottom:15px; border-radius:5px;">
          <p><strong>From:</strong> <?= htmlspecialchars($req['beneficiary_name']) ?></p>
          <p><strong>Message:</strong> <?= nl2br(htmlspecialchars($req['message'])) ?></p>
          <p><strong>Requested Quantity:</strong> <?= (int)$req['quantity_requested'] ?></p>
          <p><strong>Donation Item:</strong> <?= htmlspecialchars($req['description']) ?></p>
          <p><strong>Status:</strong> 
            <span style="font-weight:bold; color:
              <?= $req['status'] === 'accepted' ? 'green' : ($req['status'] === 'denied' ? 'red' : 'orange') ?>;">
              <?= strtoupper($req['status']) ?>
            </span>
          </p>

          <?php if ($req['status'] === 'pending'): ?>
            <form method="POST" style="margin-top:10px;">
              <input type="hidden" name="request_id" value="<?= (int)$req['id'] ?>">
              <button type="submit" name="action" value="accept" style="background:green; color:white; padding:6px 12px; margin-right:5px; border:none; border-radius:4px; cursor:pointer;">Accept</button>
              <button type="submit" name="action" value="deny" style="background:red; color:white; padding:6px 12px; border:none; border-radius:4px; cursor:pointer;">Deny</button>
            </form>
          <?php endif; ?>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </main>
</div>
</body>
</html>
