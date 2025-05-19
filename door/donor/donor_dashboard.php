<?php
session_start();
require '../../data/config.php'; // Adjust path to your DB config file

// Check if donor is logged in
if (!isset($_SESSION['donor_id'])) {
    header("Location: donor_login.php"); // redirect to login if not logged in
    exit();
}

$donor_id = $_SESSION['donor_id'];
$donor_name = $_SESSION['donor_name'] ?? 'Donor';

// Fetch donations grouped by item type, using 'id' as primary key
$sql = "SELECT id, item_type, description, quantity, photo, donation_date 
        FROM item_donations 
        WHERE donor_id = ? 
        ORDER BY item_type, donation_date DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute([$donor_id]);
$donations = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Group donations by item_type
$groupedDonations = [];
foreach ($donations as $donation) {
    $groupedDonations[$donation['item_type']][] = $donation;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<link rel="stylesheet" href="dashboard.css" />
<title>Donor Dashboard</title>
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

    <?php if (isset($_SESSION['message'])): ?>
      <div class="message" style="background-color:#d4edda; color:#155724; padding:10px; border-radius:5px; margin-bottom:10px;">
        <?= htmlspecialchars($_SESSION['message']) ?>
      </div>
      <?php unset($_SESSION['message']); ?>
    <?php endif; ?>

    <h1>Welcome, <?= htmlspecialchars($donor_name) ?>!</h1>
    <p>This is your donor dashboard.</p>

    <section class="donations-section">
      <h2>Your Donations by Item Type</h2>

      <?php if (empty($groupedDonations)): ?>
        <p>You have not made any donations yet.</p>
      <?php else: ?>
        <?php foreach ($groupedDonations as $itemType => $donationsList): ?>
          <div class="donation-group">
            <h3><?= htmlspecialchars($itemType) ?></h3>

            <?php foreach ($donationsList as $donation): ?>
              <div class="donation-container" style="border: 1px solid #ccc; padding: 10px; margin-bottom: 10px; display:flex; gap: 15px;">
                <?php
                $photoPath = '../../uploads/donations/' . $donation['photo'];
                if (!empty($donation['photo']) && file_exists($photoPath)) {
                    $photoSrc = $photoPath;
                } else {
                    $photoSrc = '../../uploads/donations/default.png';
                }
                ?>
                <img src="<?= htmlspecialchars($photoSrc) ?>" alt="Donation Photo" class="donation-photo" style="width:120px; height:auto; object-fit:cover; border-radius:5px;" />

                <div class="donation-details" style="flex-grow:1;">
                  <strong>Description:</strong> <?= nl2br(htmlspecialchars($donation['description'])) ?><br>
                  <strong>Quantity:</strong> <?= (int)$donation['quantity'] ?><br>
                  <small>Donated on: <?= htmlspecialchars($donation['donation_date']) ?></small><br><br>

                  <!-- Delete form -->
                  <form method="POST" action="delete_donation.php" onsubmit="return confirm('Are you sure you want to delete this donation?');">
                    <input type="hidden" name="donation_id" value="<?= (int)$donation['id'] ?>" />
                    <button type="submit" class="delete-btn" style="
                      background-color: #e74c3c;
                      color: white;
                      border: none;
                      padding: 6px 12px;
                      cursor: pointer;
                      border-radius: 3px;
                      font-size: 0.9rem;
                      transition: background-color 0.3s ease;
                    ">Delete</button>
                  </form>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </section>

  </main>
</div>

</body>
</html>
