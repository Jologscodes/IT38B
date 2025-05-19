<?php
session_start();

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
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Admin Dashboard - Simple Panel</title>
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
      height: 100vh;
    }

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
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }

    .content h2 {
      margin-bottom: 10px;
      color: #b71c1c;
      font-size: 2rem;
    }

    .content p {
      font-size: 1.2rem;
      color: #444;
    }

    .highlight {
      color: #c62828;
      font-weight: bold;
    }

    .welcome-msg {
      margin-top: 20px;
      background: #fff1f1;
      padding: 15px 20px;
      border-left: 5px solid #e53935;
      border-radius: 10px;
      font-size: 1.1rem;
    }
  </style>
</head>
<body>

<div class="layout">

  <div class="sidebar">
    <h2>Admin Panel</h2>
    <a href="admin_dashboard.php">Dashbaord</a>
    <a href="manage_users.php">Manage Users</a>
    <a href="manage_donations.php">Manage Donations</a>
    <a href="Request.php">Request</a>
    <a href="generate_reports.php">Generate Reports</a>
  
   
  </div>

  <div class="main">
    <div class="navbar">
      <h1>Simple Admin Dashboard</h1>
      <a href="../logout.php" class="logout-btn">Logout</a>
    </div>
    <div class="content">
  <h2>Generate Report</h2>
  <p>Fill out the form below to create and send a report.</p>

  <form action="save_report.php" method="POST">
    <label for="audience">Report For:</label><br>
    <select name="audience" id="audience" required style="padding: 8px; margin: 10px 0; border-radius: 5px;">
      <option value="donor">Donor</option>
      <option value="beneficiary">Beneficiary</option>
      <option value="all">All</option>
    </select><br>

    <label for="message">Message:</label><br>
    <textarea name="message" id="message" rows="5" style="width: 100%; padding: 10px; margin-top: 10px; border-radius: 8px;" required></textarea><br>

    <button type="submit" style="margin-top: 15px; background: #e53935; color: white; padding: 10px 20px; border: none; border-radius: 6px; cursor: pointer;">Submit Report</button>
  </form>
</div>

    </div>
  </div>
</div>

</body>
</html>
