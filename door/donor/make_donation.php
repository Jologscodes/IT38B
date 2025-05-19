<?php
session_start();
require '../../data/config.php'; 

if (!isset($_SESSION['donor_id'])) {
    header("Location: ../donor_log.php");
    exit;
}

$donor_id = $_SESSION['donor_id'];
$donor_name = $_SESSION['donor_name'];
$message = "";
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $item_type = $_POST['item_type'] ?? '';
    $description = trim($_POST['description'] ?? '');
    $quantity = intval($_POST['quantity'] ?? 0);

    if ($quantity <= 0) {
        $error = "Please enter a valid quantity.";
    } elseif (!in_array($item_type, ['Foods', 'Clothes', 'appliances', 'Gadgets'])) {
        $error = "Invalid item type selected.";
    }

    if (empty($error) && isset($_FILES['photo']) && $_FILES['photo']['error'] !== UPLOAD_ERR_NO_FILE) {
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($_FILES['photo']['type'], $allowed_types)) {
            $error = "Only JPG, PNG, and GIF files are allowed.";
        } elseif ($_FILES['photo']['size'] > 5 * 1024 * 1024) {
            $error = "File size must be less than 5MB.";
        } else {
            $ext = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
            $new_filename = uniqid('donation_', true) . "." . $ext;
            $upload_dir = '../../uploads/donations/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }
            $upload_path = $upload_dir . $new_filename;

            if (!move_uploaded_file($_FILES['photo']['tmp_name'], $upload_path)) {
                $error = "Failed to upload the photo.";
            }
        }
    }
    if (empty($error)) {
        $stmt = $pdo->prepare("INSERT INTO item_donations (donor_id, item_type, description, quantity, photo) VALUES (?, ?, ?, ?, ?)");
        $photo_db = $new_filename ?? null;
        if ($stmt->execute([$donor_id, $item_type, $description, $quantity, $photo_db])) {
            $message = "Thank you for your generous item donation!";
        } else {
            $error = "Error processing your donation. Please try again.";
        }
    }
}

$stmt = $pdo->prepare("SELECT * FROM item_donations WHERE donor_id = ? ORDER BY id DESC");
$stmt->execute([$donor_id]);
$donations = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<link rel="stylesheet" href="styles.css" />
<title>Make a Donation</title>
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

  <div class="main-content">
    <div class="navbar">
      <h1>Welcome, <?= htmlspecialchars($donor_name) ?>!</h1>
    </div>

    <div class="donation-form">
      <h2>Make an Item Donation</h2>

      <?php if ($message): ?>
        <div class="message success"><?= htmlspecialchars($message) ?></div>
      <?php elseif ($error): ?>
        <div class="message error"><?= htmlspecialchars($error) ?></div>
      <?php endif; ?>

      <form method="POST" action="" enctype="multipart/form-data">
        <label for="item_type">Type of Item</label>
        <select name="item_type" id="item_type" required>
          <option value="" disabled selected>Select item type</option>
          <option value="Foods">Foods</option>
          <option value="Clothes">Clothes</option>
          <option value="appliances">appliances</option>
          <option value="Gadgets">Gadgets</option>
         
        </select>

        <label for="description">Description</label>
        <textarea name="description" id="description" placeholder="Describe the item..." required></textarea>

        <label for="quantity">Quantity</label>
        <input type="number" name="quantity" id="quantity" min="1" required>

        <label for="photo">Upload Photo (optional)</label>
        <input type="file" name="photo" id="photo" accept="image/*">

        <button type="submit">Donate Item</button>
      </form>
    </div>

    <hr>

    <div class="donations-display">
      <h2>Your Donations</h2>

      <?php if (count($donations) === 0): ?>
        <p>You haven't made any donations yet.</p>
      <?php else: ?>
        <?php foreach ($donations as $donation): ?>
          <div class="donation-container">
            <?php if (!empty($donation['photo']) && file_exists('../../uploads/donations/' . $donation['photo'])): ?>
              <img src="../../uploads/donations/<?= htmlspecialchars($donation['photo']) ?>" alt="Donation photo" class="donation-photo" />
            <?php else: ?>
              <img src="../../uploads/donations/default.png" alt="No photo" class="donation-photo" />
            <?php endif; ?>

            <div class="donation-details">
              <strong>Type:</strong> <?= htmlspecialchars($donation['item_type']) ?><br>
              <strong>Description:</strong> <?= nl2br(htmlspecialchars($donation['description'])) ?><br>
              <strong>Quantity:</strong> <?= (int)$donation['quantity'] ?><br>
              <small><em>Donated on: <?= htmlspecialchars($donation['created_at'] ?? 'N/A') ?></em></small>
            </div>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>

  </div>
</div>

</body>
</html>
