<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

$host = 'localhost';
$dbname = 'entrep-dev';
$db_user = 'root';
$db_pass = '';

// Database connection
$conn = new mysqli($host, $db_user, $db_pass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch login history
$sql = "SELECT username, login_time FROM login_history ORDER BY login_time DESC";
$result = $conn->query($sql);

$history = [];
while ($row = $result->fetch_assoc()) {
    $history[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Admin Dashboard</title>
  <style>
    /* Reset and Base Styles */
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: Arial, sans-serif;
      background-color: #f4f4f9;
      color: #333;
    }

    /* Sidebar Styles */
    .sidebar {
      position: fixed;
      top: 0;
      left: 0;
      width: 250px;
      height: 100%;
      background-color: #2f3b52;
      color: white;
      padding-top: 20px;
      padding-left: 20px;
      box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
    }

    .sidebar img {
      width: 100%;
      max-width: 150px;
      margin-bottom: 20px;
    }

    .sidebar button {
      width: 100%;
      padding: 12px;
      background-color: #3b4c72;
      border: none;
      border-radius: 5px;
      color: white;
      font-size: 16px;
      margin-bottom: 10px;
      cursor: pointer;
      text-align: left;
    }

    .sidebar button:hover {
      background-color: #2c3a56;
    }

    /* Main Content Styles */
    .main-content {
      margin-left: 270px;
      padding: 20px;
    }

    .page-title {
      font-size: 28px;
      font-weight: bold;
      color: #2f3b52;
      margin-bottom: 20px;
    }

    .dashboard-container {
      background-color: white;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
    }

    .dashboard-container h2 {
      font-size: 24px;
      margin-bottom: 20px;
    }

    /* Card Layout for Features */
    .card-container {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 20px;
      margin-top: 20px;
    }

    .card {
      background-color: #ffffff;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      text-align: center;
      cursor: pointer;
      transition: all 0.3s ease;
    }

    .card:hover {
      transform: scale(1.05);
      box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
    }

    .card i {
      font-size: 40px;
      color: #2f3b52;
      margin-bottom: 10px;
    }

    .card h3 {
      font-size: 18px;
      color: #333;
    }

    /* Logout Button */
    .button.logout-btn {
      background-color: #e74c3c;
      color: white;
      padding: 10px 20px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      text-align: center;
      width: 100%;
      margin-top: 30px;
    }

    .button.logout-btn:hover {
      background-color: #c0392b;
    }

    /* Responsive Design */
    @media screen and (max-width: 768px) {
      .sidebar {
        width: 200px;
      }

      .main-content {
        margin-left: 220px;
      }

      .card-container {
        grid-template-columns: repeat(2, 1fr);
      }
    }

    @media screen and (max-width: 480px) {
      .card-container {
        grid-template-columns: 1fr;
      }
    }
  </style>
</head>
<body>

  <!-- Sidebar -->
  <div class="sidebar">
    <img src="https://wallacefoundation.org/sites/default/files/2023-09/sfm-home-page-graphic.png" alt="Logo">
    <button onclick="location.href='dashboard.php'">Home</button>
    <button onclick="location.href='file.php'">File</button>
    <button onclick="location.href='message.php'">Message</button>
    <button onclick="location.href='location.php'">Location</button>
    <button onclick="location.href='graph.php'">Graph</button>
  </div>

  <!-- Main Content -->
  <div class="main-content">
    <div class="page-title">Nonprofit Resource Management</div>

    <div class="dashboard-container">
      <h2>Admin Dashboard</h2>
      <h3>Welcome, <span id="currentUser"><?= htmlspecialchars($_SESSION['username']) ?></span></h3>

      <div class="card-container">
        <!-- Manage Donations -->
        <div class="card" onclick="location.href='manage_donations.php'">
          <i class="fa fa-donate"></i>
          <h3>Manage Donations</h3>
        </div>

        <!-- Allocate Resources -->
        <div class="card" onclick="location.href='allocate_resources.php'">
          <i class="fa fa-cogs"></i>
          <h3>Allocate Resources</h3>
        </div>

        <!-- Generate Reports -->
        <div class="card" onclick="location.href='generate_reports.php'">
          <i class="fa fa-chart-bar"></i>
          <h3>Generate Reports</h3>
        </div>

        <!-- Schedule Events -->
        <div class="card" onclick="location.href='schedule_events.php'">
          <i class="fa fa-calendar-check"></i>
          <h3>Schedule Events</h3>
        </div>

        <!-- Track Inventory -->
        <div class="card" onclick="location.href='track_inventory.php'">
          <i class="fa fa-boxes"></i>
          <h3>Track Inventory</h3>
        </div>
      </div>

      <!-- Logout Button -->
      <form method="post" action="logout.php">
        <button class="button logout-btn" type="submit">Logout</button>
      </form>
    </div>
  </div>

</body>
</html>
