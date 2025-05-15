<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'donor') {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Donor Dashboard</title>
  <style>
    /* Reuse admin styles, add donor specific styles if needed */
  </style>
</head>
<body>

  <!-- Sidebar -->
  <div class="sidebar">
    <img src="https://wallacefoundation.org/sites/default/files/2023-09/sfm-home-page-graphic.png" alt="Logo">
    <button onclick="location.href='donor_dashboard.php'">Home</button>
    <button onclick="location.href='donate.php'">Donate</button>
    <button onclick="location.href='view_donations.php'">View Donations</button>
    <button onclick="location.href='logout.php'">Logout</button>
  </div>

  <!-- Main Content -->
  <div class="main-content">
    <div class="page-title">Welcome, <span id="currentUser"><?= htmlspecialchars($_SESSION['username']) ?></span></div>

    <div class="dashboard-container">
      <h2>Donor Dashboard</h2>

      <div class="card-container">
        <div class="card" onclick="location.href='donate.php'">
          <i class="fa fa-donate"></i>
          <h3>Make a Donation</h3>
        </div>

        <div class="card" onclick="location.href='view_donations.php'">
          <i class="fa fa-eye"></i>
          <h3>View My Donations</h3>
        </div>
      </div>
    </div>
  </div>

</body>
</html>
