<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

// Database connection
$conn = new mysqli("localhost", "root", "", "entrep-dev"); // Replace with your actual DB name

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch inventory data
$sql = "SELECT item_name, quantity, last_updated FROM inventory"; // Replace with your actual table name/columns
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Track Inventory</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      padding: 20px;
      background: #f0f0f0;
    }
    .container {
      max-width: 800px;
      margin: auto;
      background: white;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 0 8px rgba(0,0,0,0.2);
    }
    h2 {
      text-align: center;
      margin-bottom: 20px;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      text-align: left;
    }
    th, td {
      padding: 12px;
      border-bottom: 1px solid #ccc;
    }
    th {
      background: #007bff;
      color: white;
    }
    .back-btn {
      display: inline-block;
      margin-top: 20px;
      padding: 10px 15px;
      background: #007bff;
      color: white;
      text-decoration: none;
      border-radius: 5px;
    }
    .back-btn:hover {
      background: #0056b3;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>Inventory Tracking</h2>

    <table>
      <tr>
        <th>Item Name</th>
        <th>Quantity</th>
        <th>Last Updated</th>
      </tr>

      <?php if ($result->num_rows > 0): ?>
        <?php while($row = $result->fetch_assoc()): ?>
          <tr>
            <td><?= htmlspecialchars($row['item_name']) ?></td>
            <td><?= htmlspecialchars($row['quantity']) ?></td>
            <td><?= htmlspecialchars($row['last_updated']) ?></td>
          </tr>
        <?php endwhile; ?>
      <?php else: ?>
        <tr>
          <td colspan="3">No inventory data found.</td>
        </tr>
      <?php endif; ?>

    </table>

    <a class="back-btn" href="dashboard.php">← Back to Dashboard</a>
  </div>
</body>
</html>

<?php $conn->close(); ?>
