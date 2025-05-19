<?php
session_start();
require '../../data/config.php'; // your DB connection file

if (!isset($_SESSION['donor_id'])) {
    header("Location: ../donor_log.php");
    exit;
}

$donor_id = $_SESSION['donor_id'];
$donor_name = $_SESSION['donor_name'];
$message = "";
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $beneficiary_id = intval($_POST['beneficiary_id']);
    $amount = floatval($_POST['amount']);
    $donation_message = trim($_POST['message'] ?? '');

    if ($amount <= 0) {
        $error = "Please enter a valid donation amount.";
    } else {
        $stmt = $pdo->prepare("SELECT id FROM beneficiaries WHERE id = ?");
        $stmt->execute([$beneficiary_id]);
        if ($stmt->rowCount() === 0) {
            $error = "Selected beneficiary does not exist.";
        }
    }

    if (empty($error)) {
        $stmt = $pdo->prepare("INSERT INTO donations (donor_id, beneficiary_id, amount, message) VALUES (?, ?, ?, ?)");
        if ($stmt->execute([$donor_id, $beneficiary_id, $amount, $donation_message])) {
            $message = "Thank you for your generous donation!";
        } else {
            $error = "Error processing your donation. Please try again.";
        }
    }
}

$beneficiaries = $pdo->query("SELECT id, name, email FROM beneficiaries ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Make a Donation</title>
<style>
  * {
    box-sizing: border-box;
  }
  body, html {
    margin: 0;
    height: 100%;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: linear-gradient(135deg, #ffe6e6, #ffcccc);
    color: #4a1a1a;
  }

  .dashboard-container {
    display: flex;
    height: 100vh;
    overflow: hidden;
  }

  .sidebar {
    background: linear-gradient(180deg, #e91e63, #c2185b);
    color: #fff;
    width: 260px;
    padding: 30px 20px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    box-shadow: 4px 0 10px rgba(194, 0, 71, 0.3);
  }

  .sidebar h2 {
    margin-bottom: 40px;
    font-weight: 700;
    font-size: 28px;
    text-align: center;
    letter-spacing: 2px;
  }

  .nav-links a {
    display: block;
    color: #fff;
    text-decoration: none;
    padding: 14px 20px;
    margin-bottom: 15px;
    border-radius: 8px;
    font-weight: 600;
    font-size: 18px;
    transition: background 0.3s ease;
  }

  .nav-links a:hover {
    background: #ff4081;
  }

  .logout-btn {
    background: linear-gradient(45deg, #ff1744, #d50000);
    color: white;
    border: none;
    padding: 14px 20px;
    border-radius: 10px;
    cursor: pointer;
    font-size: 18px;
    text-align: center;
    text-decoration: none;
  }

  .logout-btn:hover {
    background: linear-gradient(45deg, #d50000, #ff1744);
  }

  .main-content {
    flex-grow: 1;
    padding: 50px 60px;
    background: #fff0f5;
    overflow-y: auto;
  }

  .navbar h1 {
    color: #c2185b;
    font-weight: 800;
    font-size: 32px;
  }

  .donation-form {
    background: rgba(255, 255, 255, 0.95);
    padding: 2rem;
    border-radius: 15px;
    width: 100%;
    max-width: 500px;
    box-shadow: 0 8px 32px rgba(31, 38, 135, 0.15);
    margin-top: 20px;
  }

  .donation-form h2 {
    text-align: center;
    margin-bottom: 1rem;
  }

  label {
    display: block;
    margin-top: 1rem;
    font-weight: bold;
  }

  input[type="number"], select, textarea {
    width: 100%;
    padding: 0.5rem;
    margin-top: 0.25rem;
    border-radius: 8px;
    border: 1px solid #ccc;
    outline: none;
  }

  textarea {
    resize: vertical;
    height: 80px;
  }

  button[type="submit"] {
    margin-top: 1.5rem;
    width: 100%;
    padding: 0.75rem;
    border: none;
    border-radius: 8px;
    background: linear-gradient(to right, #ff5f6d, #ffc371);
    color: #fff;
    font-weight: bold;
    font-size: 1.1rem;
    cursor: pointer;
  }

  button[type="submit"]:hover {
    background: #ffc371;
    color: #333;
  }

  .message {
    margin-top: 1rem;
    padding: 1rem;
    border-radius: 8px;
    text-align: center;
  }

  .success {
    background-color: #4BB543;
    color: white;
  }

  .error {
    background-color: #FF4136;
    color: white;
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
      </div>
    </div>
    <a class="logout-btn" href="../logout.php">Logout</a>
  </div>

  <div class="main-content">
    <div class="navbar">
      <h1>Welcome, <?= htmlspecialchars($donor_name) ?>!</h1>
    </div>

    <div class="donation-form">
      <h2>Make a Donation</h2>

      <?php if ($message): ?>
        <div class="message success"><?= htmlspecialchars($message) ?></div>
      <?php elseif ($error): ?>
        <div class="message error"><?= htmlspecialchars($error) ?></div>
      <?php endif; ?>

      <form method="POST" action="">
        <label for="beneficiary_id">Select Beneficiary</label>
        <select name="beneficiary_id" id="beneficiary_id" required>
          <option value="" disabled selected>Choose a beneficiary</option>
          <?php foreach ($beneficiaries as $b): ?>
            <option value="<?= $b['id'] ?>"><?= htmlspecialchars($b['name'] ?: $b['email']) ?></option>
          <?php endforeach; ?>
        </select>

        <label for="amount">Donation Amount (₱)</label>
        <input type="number" name="amount" id="amount" min="1" step="0.01" required>

        <label for="message">Message (Optional)</label>
        <textarea name="message" id="message" placeholder="Your kind words..."></textarea>

        <button type="submit">Donate Now</button>
      </form>
    </div>
  </div>
</div>

</body>
</html>
