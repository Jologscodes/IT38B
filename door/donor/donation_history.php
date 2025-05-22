<?php
session_start();
require '../../data/config.php'; 

if (!isset($_SESSION['donor_id'])) {
    header("Location: donor_login.php");
    exit();
}

$donor_id = $_SESSION['donor_id'];
$donor_name = $_SESSION['donor_name'];

try {
    $stmt = $pdo->prepare("SELECT id, item_type, description, quantity, photo, donation_date 
                           FROM item_donations 
                           WHERE donor_id = :donor_id 
                           ORDER BY donation_date DESC");
    $stmt->execute(['donor_id' => $donor_id]);
    $donations = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error retrieving donation history: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Donation History</title>
<link rel="stylesheet" href="dashboard.css" />
<style>
.donation-grid {
  display: flex;
  flex-wrap: wrap;
  gap: 20px;
}
.donation-card {
  background-color: white;
  border: 1px solid #ddd;
  border-radius: 10px;
  width: 300px;
  padding: 15px;
  box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}
.donation-card img {
  width: 100%;
  height: 180px;
  object-fit: cover;
  border-radius: 8px;
}
.donation-info {
  margin-top: 10px;
}
.no-donations {
  font-style: italic;
  color: #777;
}
.edit-btn {
  display: inline-block;
  margin-top: 10px;
  padding: 8px 12px;
  background-color: #007bff;
  color: white;
  text-decoration: none;
  border-radius: 5px;
  font-size: 14px;
}
.edit-btn:hover {
  background-color: #0056b3;
}
</style>
</head>
<body>

<div class="dashboard-container">
  <div class="sidebar">
    <div>
      <h2>Donor Panel</h2>
      <div class="nav-links">
        <a href="donor_dashboard.php">Dashboard</a>
        <a href="make_donation.php">Make a Donation</a>
        <a href="donation_history.php">Donation History</a>
        <a href="Message.php">Message</a>
        <a href="Request_Report.php" class="active">Request Reports</a>
        <a href="Reports.php">Reports</a>
      </div>
    </div>
    <a class="logout-btn" href="../logout.php">Logout</a>
  </div>

  <main class="main-content">
    <div class="navbar">
      <h1>Nonprofit Resource Management</h1>
    </div>

    <h2>Welcome, <?= htmlspecialchars($donor_name) ?>!</h2>
    <p>Here is a record of all your past donations.</p>

    <?php if (count($donations) > 0): ?>
      <div class="donation-grid">
        <?php foreach ($donations as $donation): ?>
          <?php
          $photoPath = '../../uploads/donations/' . $donation['photo'];
          $photoSrc = (!empty($donation['photo']) && file_exists($photoPath)) ? $photoPath : '../../uploads/donations/default.png';
          ?>
          <div class="donation-card">
            <img src="<?= htmlspecialchars($photoSrc) ?>" alt="Donation Photo" />
            <div class="donation-info">
              <p><strong>Item:</strong> <?= htmlspecialchars($donation['item_type']) ?></p>
              <p><strong>Description:</strong> <?= htmlspecialchars($donation['description']) ?></p>
              <p><strong>Quantity:</strong> <?= (int)$donation['quantity'] ?></p>
              <p><strong>Date:</strong> <?= date("F j, Y, g:i a", strtotime($donation['donation_date'])) ?></p>
              <a href="edit_donation.php?id=<?= $donation['id'] ?>" class="edit-btn">Edit</a>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <p class="no-donations">You have not made any donations yet.</p>
    <?php endif; ?>
  </main>
</div>

</body>
</html>
