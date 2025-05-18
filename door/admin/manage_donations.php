<?php
session_start();

if (!isset($_SESSION["admin_id"])) {
    header("Location: admin_log.php");
    exit;
}

$admin_email = $_SESSION["admin_email"];

require_once "../../data/config.php";  // this already creates $pdo

try {
    // Use the $pdo from your config.php directly, no need to recreate it here.
    $stmt = $pdo->query("SELECT * FROM donations ORDER BY donation_date DESC");
    $donations = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Database query failed: " . htmlspecialchars($e->getMessage());
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Manage Donations - Admin Dashboard</title>
  <style>
    /* your existing CSS here */
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }
    body, html {
      height: 100%;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: #f0f2f5;
    }
    .layout {
      display: flex;
      height: 100vh;
    }
    .sidebar {
      width: 240px;
      background: #1e1e2f;
      color: white;
      display: flex;
      flex-direction: column;
      padding: 30px 20px;
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
    .sidebar a:hover, .sidebar a.active {
      background: #e53935;
      transform: translateX(5px);
    }
    .main {
      flex: 1;
      padding: 0;
      display: flex;
      flex-direction: column;
    }
    .navbar {
      background: linear-gradient(to right, #b71c1c, #e53935);
      padding: 15px 25px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      color: white;
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
      background-color: #fdfdfd;
      margin: 30px;
      border-radius: 16px;
      box-shadow: 0 8px 16px rgba(0,0,0,0.1);
      animation: fadeIn 1s ease-in;
      overflow-x: auto;
    }
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }
    .content h2 {
      margin-bottom: 20px;
      color: #b71c1c;
      font-size: 2rem;
    }
    table {
      width: 100%;
      border-collapse: collapse;
    }
    table th, table td {
      padding: 12px 15px;
      border: 1px solid #ddd;
      text-align: left;
      font-size: 1rem;
      color: #333;
    }
    table th {
      background-color: #e53935;
      color: white;
    }
    table tr:nth-child(even) {
      background-color: #f9f9f9;
    }
    .no-data {
      font-size: 1.2rem;
      color: #999;
      text-align: center;
      padding: 40px 0;
    }
  </style>
</head>
<body>

<div class="layout">
  <!-- Sidebar -->
  <div class="sidebar">
    <h2>Admin Panel</h2>
    <a href="manage_users.php">Manage Users</a>
    <a href="manage_donations.php" class="active">Manage Donations</a>
    <a href="allocate_resources.php">Allocate Resources</a>
    <a href="generate_reports.php">Generate Reports</a>
    <a href="schedule_events.php">Schedule Events</a>
    <a href="track_inventory.php">Track Inventory</a>
  </div>

  <div class="main">
    <div class="navbar">
      <h1>Nonprofit Resource Management</h1>
      <a href="../logout.php" class="logout-btn">Logout</a>
    </div>

    <div class="content">
      <h2>Manage Donations</h2>

      <?php if (count($donations) > 0): ?>
        <table>
          <thead>
            <tr>
              <th>ID</th>
              <th>Donor Name</th>
              <th>Donation Amount</th>
              <th>Donation Date</th>
              <th>Notes</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($donations as $donation): ?>
              <tr>
                <td><?php echo htmlspecialchars($donation['id']); ?></td>
                <td><?php echo htmlspecialchars($donation['donor_name']); ?></td>
                <td><?php echo htmlspecialchars(number_format($donation['amount'], 2)); ?></td>
                <td><?php echo htmlspecialchars($donation['donation_date']); ?></td>
                <td><?php echo htmlspecialchars($donation['notes']); ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      <?php else: ?>
        <p class="no-data">No donations found.</p>
      <?php endif; ?>

    </div>
  </div>
</div>

</body>
</html>
