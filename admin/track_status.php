<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'beneficiary') {
    header("Location: index.php");
    exit();
}

$conn = new mysqli('localhost', 'root', '', 'entrep-dev');
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$username = $_SESSION['username'];
$result = $conn->query("SELECT * FROM resource_requests WHERE beneficiary_username = '$username'");
?>

<h2>Track Request Status</h2>
<table border="1">
  <tr><th>Item</th><th>Quantity</th><th>Status</th></tr>
  <?php while($row = $result->fetch_assoc()): ?>
    <tr>
      <td><?= htmlspecialchars($row['item']) ?></td>
      <td><?= $row['quantity'] ?></td>
      <td><?= $row['status'] ?></td>
    </tr>
  <?php endwhile; ?>
</table>
