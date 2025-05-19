<?php
session_start();
require '../../data/config.php';

if (!isset($_SESSION['donor_id'])) {
    header("Location: donor_login.php");
    exit();
}

$donor_id = $_SESSION['donor_id'];
$donor_name = $_SESSION['donor_name'];
$donation_id = $_GET['id'] ?? null;

if (!$donation_id) {
    die("Invalid donation ID.");
}

$stmt = $pdo->prepare("SELECT * FROM item_donations WHERE id = :id AND donor_id = :donor_id");
$stmt->execute(['id' => $donation_id, 'donor_id' => $donor_id]);
$donation = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$donation) {
    die("Donation not found or access denied.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $item_type = $_POST['item_type'];
    $description = $_POST['description'];
    $quantity = (int)$_POST['quantity'];

    $update = $pdo->prepare("UPDATE item_donations SET item_type = :item_type, description = :description, quantity = :quantity WHERE id = :id AND donor_id = :donor_id");
    $update->execute([
        'item_type' => $item_type,
        'description' => $description,
        'quantity' => $quantity,
        'id' => $donation_id,
        'donor_id' => $donor_id
    ]);

    header("Location: donation_history.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit Donation</title>
<link rel="stylesheet" href="styles.css">
<style>


.form-container input[type="text"],
.form-container input[type="number"],
.form-container textarea {
  width: 100%;
  padding: 10px;
  margin-bottom: 15px;
  border-radius: 5px;
  border: 1px solid #ccc;
}


.form-container button:hover {
  background-color: #0056b3;
}

.back-link {
  margin-top: 20px;
  display: inline-block;
  text-decoration: none;
  color: #007bff;
}

.back-link:hover {
  text-decoration: underline;
}
</style>
</head>
<body>

<div class="dashboard-container">
  <div class="sidebar">
    <h2>Donor Panel</h2>
    <div class="nav-links">
        <a href="donor_dashboard.php">Dashboard</a>
        <a href="make_donation.php">Make a Donation</a>
        <a href="donation_history.php">Donation History</a>
        <a href="Message.php">Message</a>
        <a href="Reports.php">Reports</a>
      </div>
    <a class="logout-btn" href="../logout.php">Logout</a>
  </div>

  <div class="main-content">
    <div class="navbar">
      <h1>Nonprofit Resource Management</h1>
    </div>

    <h2>Edit Donation</h2>

    <div class="form-container">
      <form method="POST">
        <label>Item Type:</label>
        <input type="text" name="item_type" value="<?= htmlspecialchars($donation['item_type']) ?>" required>

        <label>Description:</label>
        <textarea name="description" required><?= htmlspecialchars($donation['description']) ?></textarea>

        <label>Quantity:</label>
        <input type="number" name="quantity" value="<?= (int)$donation['quantity'] ?>" required>

        <button type="submit">Update Donation</button>
      </form>

      <a href="donation_history.php" class="back-link">← Back to Donation History</a>
    </div>
  </div>
</div>

</body>
</html>
