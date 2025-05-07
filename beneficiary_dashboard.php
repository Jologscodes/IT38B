<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'beneficiary') {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Beneficiary Dashboard</title>
  <style>
    /* Reuse admin styles, add beneficiary-specific styles if needed */
  </style>
</head>
<body>

  <!-- Sidebar -->
  <div class="sidebar">
    <img src="https://wallacefoundation.org/sites/default/files/2023-09/sfm-home-page-graphic.png" alt="Logo">
    <button onclick="location.href='beneficiary_dashboard.php'">Home</button>
    <button onclick="location.href='request_resources.php'">Request Resources</button>
    <button onclick="location.href='receive_resources.php'">Received Resources</button>
    <button onclick="location.href='track_status.php'">Track Request Status</button>
    <button onclick="location.href='logout.php'">Logout</button>
  </div>

  <!-- Main Content -->
  <div class="main-content">
    <div class="page-title">Welcome, <span id="currentUser"><?= htmlspecialchars($_SESSION['username']) ?></span></div>

    <div class="dashboard-container">
      <h2>Beneficiary Dashboard</h2>

      <div class="card-container">
        <div class="card" onclick="location.href='request_resources.php'">
          <i class="fa fa-hand-paper"></i>
          <h3>Request Resources</h3>
        </div>

        <div class="card" onclick="location.href='receive_resources.php'">
          <i class="fa fa-box"></i>
          <h3>Received Resources</h3>
        </div>

        <div class="card" onclick="location.href='track_status.php'">
          <i class="fa fa-search"></i>
          <h3>Track Status</h3>
        </div>
      </div>
    </div>
  </div>

</body>
</html>
