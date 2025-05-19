<?php
session_start();

if (!isset($_SESSION["admin_id"])) {
    header("Location: admin_log.php");
    exit;
}

$admin_email = $_SESSION["admin_email"];

require_once "../../data/config.php";

$message = "";

// Handle stock update submission
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['update_stock'])) {
    $item_id = intval($_POST['item_id']);
    $new_quantity = intval($_POST['stock_quantity']);

    if ($new_quantity >= 0) {
        $sql = "UPDATE inventory SET stock_quantity = :quantity, last_updated = NOW() WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':quantity' => $new_quantity,
            ':id' => $item_id
        ]);
        $message = "Stock updated successfully for item ID $item_id.";
    } else {
        $message = "Stock quantity cannot be negative.";
    }
}

// Fetch all inventory items
$items = [];
try {
    $stmt = $pdo->query("SELECT * FROM inventory ORDER BY item_name ASC");
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    // handle error appropriately
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Track Inventory - Admin Panel</title>
  <style>
    /* Reuse your admin panel style */
    body, html {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: #f0f2f5;
      margin: 0; padding: 0;
    }

    .layout {
      display: flex;
      height: 100vh;
    }

    .sidebar {
      width: 220px;
      background: #1e1e2f;
      color: white;
      padding: 30px 20px;
      display: flex;
      flex-direction: column;
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

    .sidebar a:hover,
    .sidebar a.active {
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
      color: white;
      display: flex;
      justify-content: space-between;
      align-items: center;
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
      background: #fff;
      margin: 30px;
      border-radius: 16px;
      box-shadow: 0 8px 16px rgba(0,0,0,0.1);
      overflow-y: auto;
    }

    h2 {
      color: #b71c1c;
      margin-bottom: 20px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 10px;
    }

    th, td {
      padding: 12px 15px;
      border-bottom: 1px solid #ddd;
      text-align: left;
      vertical-align: middle;
    }

    th {
      background-color: #f9f9f9;
      color: #b71c1c;
    }

    tr:hover {
      background-color: #ffeaea;
    }

    input[type="number"] {
      width: 70px;
      padding: 6px 8px;
      border-radius: 6px;
      border: 1px solid #ccc;
      font-size: 1rem;
    }

    button.update-btn {
      background-color: #e53935;
      color: white;
      border: none;
      padding: 7px 15px;
      border-radius: 8px;
      cursor: pointer;
      font-weight: bold;
      transition: background 0.3s;
    }

    button.update-btn:hover {
      background-color: #b71c1c;
    }

    .message {
      font-weight: 600;
      color: green;
      margin-bottom: 15px;
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
    <a href="track_inventory.php" class="active">Track Inventory</a>
  </div>

  <div class="main">
    <div class="navbar">
      <h1>Track Inventory</h1>
      <a href="../logout.php" class="logout-btn">Logout</a>
    </div>

    <div class="content">
      <h2>Inventory Items</h2>

      <?php if ($message): ?>
        <div class="message"><?php echo htmlspecialchars($message); ?></div>
      <?php endif; ?>

      <?php if (count($items) === 0): ?>
        <p>No inventory items found.</p>
      <?php else: ?>
        <table>
          <thead>
            <tr>
              <th>Item Name</th>
              <th>Description</th>
              <th>Stock Quantity</th>
              <th>Last Updated</th>
              <th>Update Stock</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($items as $item): ?>
              <tr>
                <td><?php echo htmlspecialchars($item['item_name']); ?></td>
                <td><?php echo nl2br(htmlspecialchars($item['description'])); ?></td>
                <td><?php echo (int)$item['stock_quantity']; ?></td>
                <td><?php echo $item['last_updated'] ?? '-'; ?></td>
                <td>
                  <form method="POST" style="display:inline-block;">
                    <input type="hidden" name="item_id" value="<?php echo $item['id']; ?>" />
                    <input
                      type="number"
                      name="stock_quantity"
                      min="0"
                      value="<?php echo (int)$item['stock_quantity']; ?>"
                      required
                    />
                    <button type="submit" name="update_stock" class="update-btn">Update</button>
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
