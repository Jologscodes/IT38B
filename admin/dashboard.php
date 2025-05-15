<?php
session_start();

// Check if the user is logged in and has the 'admin' role
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}
?>




          <!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Admin Dashboard</title>
  <style>
    body {
      margin: 0;
      padding: 0;
      background: red;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      font-family: Arial, sans-serif;
    }
    .container {
      text-align: center;
      width: 90%;
      max-width: 1000px;
    }
    .header {
      color: white;
      font-weight: bold;
      font-size: 24px;
      text-shadow: 1px 1px 2px black;
      margin-bottom: 20px;
    }
    .main-content {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 20px;
    }
    .circle-section {
      flex: 1;
      display: flex;
      justify-content: center;
      align-items: center;
    }
    .circle {
      background: white;
      width: 350px;
      height: 350px;
      border-radius: 50%;
      display: flex;
      justify-content: center;
      align-items: center;
      box-shadow: 3px 3px 10px rgba(0, 0, 0, 0.3);
      overflow: hidden;
    }
    .circle img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }
    .dashboard-box {
      flex: 1;
      background: #333;
      padding: 30px;
      border-radius: 8px;
      width: 100%;
      max-width: 400px;
      box-shadow: 3px 3px 10px rgba(0, 0, 0, 0.3);
      color: white;
      text-align: left;
    }
    .dashboard-box h2 {
      text-align: center;
      margin-bottom: 20px;
    }
    .welcome {
      text-align: center;
      margin-bottom: 20px;
    }
    .dashboard-links {
      display: flex;
      flex-direction: column;
      gap: 10px;
    }
    .dashboard-links button {
      padding: 12px;
      background: blue;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      width: 100%;
    }
    .dashboard-links button:hover {
      background: darkblue;
    }
    .logout-btn {
      margin-top: 20px;
      background: #e74c3c;
    }
    .logout-btn:hover {
      background: #c0392b;
    }
  </style>
</head>
<body>

  <div class="container">
    <h1 class="header">Nonprofit Resource Management</h1>
    <div class="main-content">
      <div class="circle-section">
        <div class="circle">
          <img src="https://wallacefoundation.org/sites/default/files/2023-09/sfm-home-page-graphic.png" alt="Dashboard Image">
        </div>
      </div>

      <div class="dashboard-box">
        <h2>Admin Dashboard</h2>
        <div class="welcome">Welcome, <strong><?= htmlspecialchars($_SESSION['username']) ?></strong></div>
        
        <div class="dashboard-links">
          <button onclick="location.href='file.php'">File</button>
          <button onclick="location.href='message.php'">Message</button>
          <button onclick="location.href='location.php'">Location</button>
          <button onclick="location.href='graph.php'">Graph</button>
          <button onclick="location.href='manage_donations.php'">Manage Donations</button>
          <button onclick="location.href='allocate_resources.php'">Allocate Resources</button>
          <button onclick="location.href='generate_reports.php'">Generate Reports</button>
          <button onclick="location.href='schedule_events.php'">Schedule Events</button>
          <button onclick="location.href='track_inventory.php'">Track Inventory</button>
            
          </body>
          </html>