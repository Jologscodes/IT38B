<?php
session_start();

if (!isset($_SESSION["admin_id"])) {
    header("Location: admin_log.php");
    exit;
}

$admin_email = $_SESSION["admin_email"];

$host = "localhost";
$dbname = "entrep-dev";
$username = "root";
$password = "";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("DB connection failed: " . $e->getMessage());
}

$stmt = $pdo->query("SELECT id, email, name, created_at FROM beneficiaries ORDER BY created_at DESC");
$beneficiaries = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt2 = $pdo->query("SELECT id, name, email, created_at FROM donors ORDER BY created_at DESC");
$donors = $stmt2->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Manage Users - Admin Dashboard</title>
  <style>
    /* Include your existing styles here (copied from the dashboard) */
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body, html {
      height: 100%;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: #f0f2f5;
    }

    .layout {
      display: flex;
      height: 100vh;
    }

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
    }

    .sidebar a:hover {
      background: #e53935;
      transform: translateX(5px);
    }

    .main {
      flex: 1;
      padding: 0;
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
      overflow-y: auto;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }

    .content h2 {
      margin-bottom: 20px;
      color: #b71c1c;
      font-size: 2rem;
      border-bottom: 2px solid #e53935;
      padding-bottom: 10px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 40px;
      font-size: 1rem;
    }

    th, td {
      text-align: left;
      padding: 12px 15px;
      border-bottom: 1px solid #ddd;
    }

    th {
      background-color: #e53935;
      color: white;
    }

    tr:hover {
      background-color: #ffeaea;
    }

    .no-data {
      font-style: italic;
      color: #888;
      padding: 10px 0;
    }
  </style>
</head>
<body>

<div class="layout">
  <div class="sidebar">
    <h2>Admin Panel</h2>
    <a href="manage_users.php">Manage Users</a>
    <a href="manage_donations.php">Manage Donations</a>
    <a href="allocate_resources.php">Allocate Resources</a>
    <a href="generate_reports.php">Generate Reports</a>
    <a href="schedule_events.php">Schedule Events</a>
    <a href="track_inventory.php">Track Inventory</a>
  </div>

  <div class="main">
    <div class="navbar">
      <h1>Nonprofit Resource Management</h1>
      <a href="../logout.php" class="logout-btn">Logout</a>
    </div>

    <div class="content">
      <h2>Manage Beneficiaries</h2>
      <?php if (count($beneficiaries) > 0): ?>
      <table>
        <thead>
          <tr>
            <th>ID</th>
            <th>Email</th>
            <th>Name</th>
            <th>Joined At</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($beneficiaries as $b): ?>
          <tr>
            <td><?= htmlspecialchars($b['id']) ?></td>
            <td><?= htmlspecialchars($b['email']) ?></td>
            <td><?= htmlspecialchars($b['name'] ?? '-') ?></td>
            <td><?= htmlspecialchars($b['created_at']) ?></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
      <?php else: ?>
        <p class="no-data">No beneficiaries found.</p>
      <?php endif; ?>

      <h2>Manage Donors</h2>
      <?php if (count($donors) > 0): ?>
      <table>
        <thead>
          <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Joined At</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($donors as $d): ?>
          <tr>
            <td><?= htmlspecialchars($d['id']) ?></td>
            <td><?= htmlspecialchars($d['name']) ?></td>
            <td><?= htmlspecialchars($d['email']) ?></td>
            <td><?= htmlspecialchars($d['created_at']) ?></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
      <?php else: ?>
        <p class="no-data">No donors found.</p>
      <?php endif; ?>
    </div>
  </div>
</div>

</body>
</html>
