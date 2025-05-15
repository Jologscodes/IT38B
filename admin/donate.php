<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'donor') {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conn = new mysqli('localhost', 'root', '', 'entrep-dev');
    if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

    $amount = $_POST['amount'];
    $description = $_POST['description'];
    $username = $_SESSION['username'];

    $stmt = $conn->prepare("INSERT INTO donations (donor_username, amount, description) VALUES (?, ?, ?)");
    $stmt->bind_param("sds", $username, $amount, $description);
    $stmt->execute();
    $stmt->close();
    $conn->close();

    echo "Donation submitted successfully!";
}
?>

<form method="POST">
  <h2>Make a Donation</h2>
  <label>Amount:</label>
  <input type="number" step="0.01" name="amount" required><br><br>
  <label>Description:</label>
  <textarea name="description" required></textarea><br><br>
  <button type="submit">Donate</button>
</form>
