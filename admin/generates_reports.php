<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

// Connect to your database
$conn = new mysqli("localhost", "root", "", "entrep-dev"); // Update this!

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function getCount($conn, $role) {
    $sql = "SELECT COUNT(*) as total FROM users WHERE role = '$role'";
    $result = $conn->query($sql);
    return $result->fetch_assoc()['total'];
}

$donorCount = getCount($conn, 'donor');
$beneficiaryCount = getCount($conn, 'beneficiary');

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Generate Reports</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f4f4f4;
      padding: 20px;
    }
    .report-container {
      background: white;
      padding: 20px;
      max-width: 600px;
      margin: auto;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0,0,0,0.2);
    }
    h2 {
      text-align: center;
    }
    .report-section {
      margin-top: 20px;
    }
    .report-section p {
      font-size: 18px;
      margin: 10px 0;
    }
    .back-button {
      display: block;
      margin: 20px auto;
      padding: 10px 20px;
      background: #007bff;
      color: white;
      text-align: center;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      text-decoration: none;
    }
    .back-button:hover {
      background: #0056b3;
    }
  </style>
</head>
<body>
  <div class="report-container">
    <h2>Admin Report Summary</h2>

    <div class="report-section">
      <p><strong>Total Donors:</strong> <?= $donorCount ?></p>
      <p><strong>Total Beneficiaries:</strong> <?= $beneficiaryCount ?></p>
    </div>

    <a href="dashboard.php" class="back-button">Back to Dashboard</a>
  </div>
</body>
</html>
