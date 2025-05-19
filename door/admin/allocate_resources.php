<?php
session_start();

if (!isset($_SESSION["admin_id"])) {
    header("Location: admin_log.php");
    exit;
}

require_once "../../data/config.php";  // Your DB connection and $pdo

// Handle form submission to add a new resource allocation
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $resource_name = $_POST['resource_name'] ?? '';
    $allocated_to = $_POST['allocated_to'] ?? '';
    $allocation_date = $_POST['allocation_date'] ?? '';
    $notes = $_POST['notes'] ?? '';

    if ($resource_name && $allocated_to && $allocation_date) {
        try {
            $stmt = $pdo->prepare("INSERT INTO resource_allocations (resource_name, allocated_to, allocation_date, notes) VALUES (?, ?, ?, ?)");
            $stmt->execute([$resource_name, $allocated_to, $allocation_date, $notes]);
            header("Location: allocate_resources.php"); // redirect to avoid resubmission
            exit;
        } catch (PDOException $e) {
            $error = "Failed to allocate resource: " . $e->getMessage();
        }
    } else {
        $error = "Please fill in all required fields.";
    }
}

// Fetch existing resource allocations
try {
    $stmt = $pdo->query("SELECT * FROM resource_allocations ORDER BY allocation_date DESC");
    $allocations = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching resource allocations: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Allocate Resources - Admin Dashboard</title>
  <style>
    /* Use the same styles as your donations page for consistency */
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
    .sidebar a:hover, .sidebar a.active {
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
      overflow-x: auto;
    }
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }
    .content h2 {
      margin-bottom: 20px;
      color: #b71c1c;
      font-size: 2rem;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 30px;
    }
    table th, table td {
      padding: 12px 15px;
      border: 1px solid #ddd;
      text-align: left;
      font-size: 1rem;
      color: #333;
    }
    table th {
      background-color: #e53935;
      color: white;
    }
    table tr:nth-child(even) {
      background-color: #f9f9f9;
    }
    form {
      max-width: 600px;
      background: #fff;
      padding: 25px;
      border-radius: 12px;
      box-shadow: 0 6px 12px rgba(0,0,0,0.1);
    }
    form label {
      display: block;
      margin-bottom: 8px;
      font-weight: 600;
      color: #b71c1c;
    }
    form input[type="text"],
    form input[type="date"],
    form textarea {
      width: 100%;
      padding: 10px 12px;
      margin-bottom: 18px;
      border-radius: 8px;
      border: 1px solid #ccc;
      font-size: 1rem;
      font-family: inherit;
    }
    form textarea {
      resize: vertical;
      min-height: 80px;
    }
    form button {
      background-color: #e53935;
      color: white;
      padding: 12px 20px;
      border: none;
      border-radius: 10px;
      font-weight: 700;
      font-size: 1rem;
      cursor: pointer;
      transition: background-color 0.3s;
    }
    form button:hover {
      background-color: #b71c1c;
    }
    .error {
      background: #ffdddd;
      color: #d8000c;
      border: 1px solid #d8000c;
      padding: 10px 15px;
      border-radius: 8px;
      margin-bottom: 15px;
      max-width: 600px;
    }
  </style>
</head>
<body>

<div class="layout">
  <!-- Sidebar -->
  <div class="sidebar">
    <h2>Admin Panel</h2>
    <a href="manage_users.php">Manage Users</a>
    <a href="manage_donations.php">Manage Donations</a>
    <a href="allocate_resources.php" class="active">Allocate Resources</a>
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
      <h2>Allocate Resources</h2>

      <?php if (!empty($error)): ?>
        <div class="error"><?php echo htmlspecialchars($error); ?></div>
      <?php endif; ?>

      <form method="post" action="allocate_resources.php">
        <label for="resource_name">Resource Name *</label>
        <input type="text" id="resource_name" name="resource_name" required />

        <label for="allocated_to">Allocated To *</label>
        <input type="text" id="allocated_to" name="allocated_to" required />

        <label for="allocation_date">Allocation Date *</label>
        <input type="date" id="allocation_date" name="allocation_date" required />

        <label for="notes">Notes</label>
        <textarea id="notes" name="notes" placeholder="Optional additional information"></textarea>

        <button type="submit">Allocate Resource</button>
      </form>

      <h3 style="margin-top: 40px; color:#b71c1c;">Current Resource Allocations</h3>

      <?php if (count($allocations) > 0): ?>
        <table>
          <thead>
            <tr>
              <th>ID</th>
              <th>Resource Name</th>
              <th>Allocated To</th>
              <th>Allocation Date</th>
              <th>Notes</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($allocations as $alloc): ?>
              <tr>
                <td><?php echo htmlspecialchars($alloc['id']); ?></td>
                <td><?php echo htmlspecialchars($alloc['resource_name']); ?></td>
                <td><?php echo htmlspecialchars($alloc['allocated_to']); ?></td>
                <td><?php echo htmlspecialchars($alloc['allocation_date']); ?></td>
                <td><?php echo htmlspecialchars($alloc['notes']); ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      <?php else: ?>
        <p>No resource allocations found.</p>
      <?php endif; ?>
    </div>
  </div>
</div>

</body>
</html>
