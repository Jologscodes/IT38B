<?php
session_start();

if (!isset($_SESSION["admin_id"])) {
    header("Location: admin_log.php");
    exit;
}

$admin_email = $_SESSION["admin_email"];

require_once "../../data/config.php"; 
try {
    $stmt = $pdo->prepare("
        SELECT item_donations.*, donors.name AS donor_name 
        FROM item_donations
        JOIN donors ON item_donations.donor_id = donors.id
        ORDER BY donation_date DESC
    ");
    $stmt->execute();
    $donations = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("ERROR: Could not fetch donations. " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Manage Donations - Admin Dashboard</title>
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body, html { height: 100%; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f0f2f5; }
    .layout { display: flex; height: 100vh; }
    .sidebar {
      width: 240px;
      background: #1e1e2f;
      color: white;
      display: flex;
      flex-direction: column;
      padding: 30px 20px;
    }
    .sidebar h2 {
      margin-bottom: 30px;
      font-size: 1.7rem;
      text-align: center;
      color: #e53935;
    }
    .sidebar a {
      color: white;
      text-decoration: none;
      padding: 12px 15px;
      margin-bottom: 10px;
      border-radius: 8px;
      transition: background 0.3s, transform 0.2s;
      font-size: 1rem;
      display: block;
    }
    .sidebar a:hover {
      background: #e53935;
      transform: translateX(5px);
    }
    .main {
      flex: 1;
      display: flex;
      flex-direction: column;
    }
    .navbar {
      background: linear-gradient(to right, #b71c1c, #e53935);
      padding: 15px 25px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      color: white;
    }
    .navbar h1 {
      font-size: 1.5rem;
      font-weight: bold;
    }
    .logout-btn {
      background-color: white;
      color: #e53935;
      padding: 10px 20px;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      text-decoration: none;
      font-weight: bold;
      transition: background 0.3s;
    }
    .logout-btn:hover {
      background-color: #ffeaea;
    }
    .content {
      flex: 1;
      padding: 40px;
      background-color: #fdfdfd;
      margin: 30px;
      border-radius: 16px;
      box-shadow: 0 8px 16px rgba(0,0,0,0.1);
      animation: fadeIn 1s ease-in;
      overflow-x: auto;
    }
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }
    h2 {
      margin-bottom: 20px;
      color: #b71c1c;
      font-size: 2rem;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      font-size: 1rem;
    }
    th, td {
      padding: 12px 15px;
      border-bottom: 1px solid #ddd;
      text-align: left;
      vertical-align: middle;
    }
    th {
      background-color: #e53935;
      color: white;
      position: sticky;
      top: 0;
      z-index: 1;
    }
    tr:hover {
      background-color: #fce4e4;
    }
    .delete-btn {
      padding: 6px 12px;
      background-color: #c62828;
      color: white;
      border: none;
      border-radius: 4px;
      cursor: pointer;
    }
    .delete-btn:hover {
      background-color: #a81818;
    }
  </style>
</head>
<body>
<div class="layout">
  <div class="sidebar">
    <h2>Admin Panel</h2>
    <a href="admin_dashboard.php">Dashbaord</a>
    <a href="manage_users.php">Manage Users</a>
    <a href="manage_donations.php">Manage Donations</a>

    <a href="generate_reports.php">Generate Reports</a>
  
   
  </div>

  <div class="main">
    <div class="navbar">
      <h1>Nonprofit Resource Management</h1>
      <a href="../logout.php" class="logout-btn">Logout</a>
    </div>

    <div class="content">
      <h2>Manage Donations</h2>

      <?php if (count($donations) === 0): ?>
        <p>No donations found.</p>
      <?php else: ?>
        <table>
          <thead>
            <tr>
              <th>ID</th>
              <th>Donor Name</th>
              <th>Item Type</th>
              <th>Description</th>
              <th>Quantity</th>
              <th>Donation Date</th>
              <th>Created At</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($donations as $donation): ?>
              <tr>
                <td><?= htmlspecialchars($donation['id']); ?></td>
                <td><?= htmlspecialchars($donation['donor_name']); ?></td>
                <td><?= htmlspecialchars($donation['item_type']); ?></td>
                <td><?= htmlspecialchars($donation['description']); ?></td>
                <td><?= htmlspecialchars($donation['quantity']); ?></td>
                <td><?= htmlspecialchars($donation['donation_date']); ?></td>
                <td><?= htmlspecialchars($donation['created_at']); ?></td>
                <td>
                  <form method="POST" action="delete_donation.php" onsubmit="return confirm('Are you sure you want to delete this donation?');">
                    <input type="hidden" name="id" value="<?= $donation['id']; ?>">
                    <button type="submit" class="delete-btn">Delete</button>
                  </form>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      <?php endif; ?>
    </div>
  </div>
</div>
</body>
</html>
