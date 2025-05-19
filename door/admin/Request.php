<?php
session_start();
if (!isset($_SESSION["admin_id"])) {
    header("Location: admin_log.php");
    exit;
}

require_once "../../data/config.php";
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["request_id"]) && isset($_POST["status"])) {
    $request_id = $_POST["request_id"];
    $status = $_POST["status"];

    $stmt = $pdo->prepare("UPDATE request SET status = ? WHERE id = ?");
    $stmt->execute([$status, $request_id]);
}

$stmt = $pdo->query("SELECT * FROM request ORDER BY request_date DESC");
$requests = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Admin Dashboard - Beneficiary Requests</title>
  <style>
    * {
      margin: 0; padding: 0; box-sizing: border-box;
    }
    body, html {
      height: 100%;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: #f0f2f5;
    }
    .layout {
      display: flex;
      min-height: 100vh;
    }
    /* Sidebar */
    .sidebar {
      width: 220px;
      background: #1e1e2f;
      color: white;
      padding: 30px 20px;
      display: flex;
      flex-direction: column;
    }
    .sidebar h2 {
      margin-bottom: 30px;
      font-size: 1.7rem;
      text-align: center;
      color: #e53935;
    }
    .sidebar a {
      color: white;
      text-decoration: none;
      padding: 12px 15px;
      margin-bottom: 10px;
      border-radius: 8px;
      transition: background 0.3s, transform 0.2s;
      font-size: 1rem;
    }
    .sidebar a:hover {
      background: #e53935;
      transform: translateX(5px);
    }

    /* Main content */
    .main {
      flex: 1;
      display: flex;
      flex-direction: column;
    }
    .navbar {
      background: linear-gradient(to right, #b71c1c, #e53935);
      padding: 15px 25px;
      color: white;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    .navbar h1 {
      font-size: 1.5rem;
      font-weight: bold;
    }
    .logout-btn {
      background-color: white;
      color: #e53935;
      padding: 10px 20px;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      text-decoration: none;
      font-weight: bold;
      transition: background 0.3s;
    }
    .logout-btn:hover {
      background-color: #ffeaea;
    }
    .content {
      flex: 1;
      padding: 40px;
      background: #fff;
      margin: 30px;
      border-radius: 16px;
      box-shadow: 0 8px 16px rgba(0,0,0,0.1);
      animation: fadeIn 1s ease-in;
      display: flex;
      flex-wrap: wrap;
      gap: 20px;
      justify-content: center;
    }
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }

    /* Request cards */
    .request-card {
      background: white;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
      border-radius: 12px;
      padding: 20px;
      width: 320px;
      display: flex;
      flex-direction: column;
      gap: 12px;
      transition: box-shadow 0.3s ease;
    }
    .request-card:hover {
      box-shadow: 0 6px 18px rgba(0,0,0,0.15);
    }
    .request-title {
      font-size: 1.3rem;
      font-weight: bold;
      color: #b71c1c;
    }
    .request-desc {
      font-size: 1rem;
      color: #444;
      white-space: pre-wrap;
      min-height: 70px;
    }
    .request-info {
      font-size: 0.9rem;
      color: #777;
    }
    .status {
      font-weight: bold;
      color: #e53935;
    }
    form {
      margin-top: 10px;
      display: flex;
      gap: 10px;
    }
    .btn {
      flex: 1;
      padding: 8px 0;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      font-weight: bold;
      color: white;
      transition: background-color 0.3s ease;
    }
    .confirm-btn {
      background-color: #4caf50;
    }
    .confirm-btn:hover {
      background-color: #388e3c;
    }
    .deny-btn {
      background-color: #f44336;
    }
    .deny-btn:hover {
      background-color: #d32f2f;
    }
    .no-requests {
      font-size: 1.2rem;
      color: #999;
      margin-top: 40px;
      text-align: center;
      width: 100%;
    }
  </style>
</head>
<body>

<div class="layout">
  <div class="sidebar">
    <h2>Admin Panel</h2>
    <a href="admin_dashboard.php">Dashboard</a>
    <a href="manage_users.php">Manage Users</a>
    <a href="manage_donations.php">Manage Donations</a>
    <a href="Request.php">Request</a>
    <a href="generate_reports.php">Generate Reports</a>
  </div>

  <div class="main">
    <div class="navbar">
      <h1>Beneficiary Requests</h1>
      <a href="../logout.php" class="logout-btn">Logout</a>
    </div>

    <div class="content">
      <?php if (empty($requests)): ?>
        <p class="no-requests">No beneficiary requests found.</p>
      <?php else: ?>
        <?php foreach ($requests as $row): ?>
          <div class="request-card">
            <div class="request-title"><?= htmlspecialchars($row['title']) ?></div>
            <div class="request-desc"><?= htmlspecialchars($row['description']) ?></div>
            <div class="request-info">Request Date: <?= htmlspecialchars($row['request_date']) ?></div>
            <div class="request-info">Beneficiary ID: <?= htmlspecialchars($row['beneficiary_id']) ?></div>
            <div class="status">Status: <?= htmlspecialchars($row['status']) ?></div>

            <?php if ($row['status'] === 'Pending'): ?>
              <form method="POST" action="">
                <input type="hidden" name="request_id" value="<?= $row['id'] ?>">
                <button class="btn confirm-btn" type="submit" name="status" value="Confirmed">Confirm</button>
                <button class="btn deny-btn" type="submit" name="status" value="Denied">Deny</button>
              </form>
            <?php endif; ?>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </div>
</div>

</body>
</html>
