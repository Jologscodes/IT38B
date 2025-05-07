<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'beneficiary') {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conn = new mysqli('localhost', 'root', '', 'entrep-dev');
    if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

    $item = $_POST['item'];
    $quantity = $_POST['quantity'];
    $username = $_SESSION['username'];

    $stmt = $conn->prepare("INSERT INTO resource_requests (beneficiary_username, item, quantity, status) VALUES (?, ?, ?, 'Pending')");
    $stmt->bind_param("ssi", $username, $item, $quantity);
    $stmt->execute();
    $stmt->close();
    $conn->close();

    echo "Request submitted!";
}
?>

<form method="POST">
  <h2>Request Resources</h2>
  <label>Item:</label>
  <input type="text" name="item" required><br><br>
  <label>Quantity:</label>
  <input type="number" name="quantity" required><br><br>
  <button type="submit">Submit Request</button>
</form>
