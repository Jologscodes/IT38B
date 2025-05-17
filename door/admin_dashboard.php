<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION["admin_id"])) {
    header("Location: admin_log.php");
    exit;
}

$admin_email = $_SESSION["admin_email"];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin Dashboard - Nonprofit Resource Management</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body, html {
      height: 100%;
      font-family: Arial, sans-serif;
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
      font-size: 1.5rem;
      text-align: center;
      color: #f44336;
    }

    .sidebar a {
      color: white;
      text-decoration: none;
      padding: 12px 15px;
      margin-bottom: 10px;
      border-radius: 8px;
      transition: background 0.3s;
      font-size: 1rem;
    }

    .sidebar a:hover {
      background: #f44336;
    }

    .main {
      flex: 1;
      background:rgb(249, 244, 244);
      padding: 20px;
      display: flex;
      flex-direction: column;
    }

    .navbar {
      background: red;
      padding: 15px 25px;
      border-bottom: 1px solid #ddd;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .navbar h1 {
      font-size: 1.4rem;
      color: #333;
    }

    .navbar .logout-btn {
      background-color: #f44336;
      color: white;
      padding: 10px 20px;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      text-decoration: none;
    }

    .navbar .logout-btn:hover {
      background-color: #c0392b;
    }

    .content {
      flex: 1;
      padding: 30px;
      background-color: #ffffff;
      margin-top: 20px;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }

    .content h2 {
      margin-bottom: 10px;
      color: #444;
    }

    .content p {
      font-size: 1.1rem;
    }
  </style>
</head>
<body>

<div class="layout">
  <!-- Sidebar -->
  <div class="sidebar">
    <h2>Admin Panel</h2>
    <a href="manage_users.php">Manage Users</a>
    <a href="manage_donations.php">Manage Donations</a>
    <a href="allocate_resources.php">Allocate Resources</a>
    <a href="generate_reports.php">Generate Reports</a>
    <a href="schedule_events.php">Schedule Events</a>
    <a href="track_inventory.php">Track Inventory</a>
  </div>

  <!-- Main content -->
  <div class="main">
    <div class="navbar">
      <h1>Nonprofit Resource Management</h1>
      <a href="logout.php" class="logout-btn">Logout</a>
    </div>

    <div class="content">
      <h2>Welcome, Admin!</h2>
      <p>You are logged in as: <strong><?php echo htmlspecialchars($admin_email); ?></strong></p>
    </div>
  </div>
</div>

</body>
</html>
