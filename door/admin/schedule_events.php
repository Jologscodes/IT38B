<?php
session_start();

if (!isset($_SESSION["admin_id"])) {
    header("Location: admin_log.php");
    exit;
}

$admin_email = $_SESSION["admin_email"];

require_once "../../data/config.php"; 

$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['event_name'] ?? '');
    $date = trim($_POST['event_date'] ?? '');
    $time = trim($_POST['event_time'] ?? '');
    $description = trim($_POST['event_description'] ?? '');

    if ($name && $date && $time) {
        $sql = "INSERT INTO events (event_name, event_date, event_time, event_description) VALUES (:name, :date, :time, :description)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':name' => $name,
            ':date' => $date,
            ':time' => $time,
            ':description' => $description
        ]);
        $message = "Event scheduled successfully!";
    } else {
        $message = "Please fill in all required fields (Name, Date, Time).";
    }
}

// Fetch all events ordered by date and time
$events = [];
try {
    $stmt = $pdo->query("SELECT * FROM events ORDER BY event_date ASC, event_time ASC");
    $events = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    // handle or log error
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Schedule Events - Admin Panel</title>
  <style>
    /* Reuse your dashboard styles */
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

    form {
      margin-bottom: 40px;
      max-width: 500px;
    }

    label {
      display: block;
      margin-bottom: 6px;
      font-weight: 600;
      color: #333;
    }

    input[type="text"],
    input[type="date"],
    input[type="time"],
    textarea {
      width: 100%;
      padding: 8px 10px;
      margin-bottom: 15px;
      border-radius: 6px;
      border: 1px solid #ccc;
      font-size: 1rem;
    }

    textarea {
      resize: vertical;
      min-height: 80px;
    }

    button {
      background-color: #e53935;
      color: white;
      border: none;
      padding: 12px 25px;
      border-radius: 8px;
      cursor: pointer;
      font-size: 1rem;
      font-weight: bold;
      transition: background 0.3s;
    }

    button:hover {
      background-color: #b71c1c;
    }

    .message {
      margin-bottom: 20px;
      font-weight: 600;
      color: green;
    }

    table {
      width: 100%;
      border-collapse: collapse;
    }

    th, td {
      text-align: left;
      padding: 10px;
      border-bottom: 1px solid #ddd;
    }

    th {
      background-color: #f9f9f9;
      color: #b71c1c;
    }

    tr:hover {
      background-color: #ffeaea;
    }

    .no-events {
      color: #666;
      font-style: italic;
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
    <a href="schedule_events.php" style="background:#e53935;">Schedule Events</a>
    <a href="track_inventory.php">Track Inventory</a>
  </div>

  <div class="main">
    <div class="navbar">
      <h1>Schedule Events</h1>
      <a href="../logout.php" class="logout-btn">Logout</a>
    </div>

    <div class="content">
      <h2>Schedule a New Event</h2>

      <?php if($message): ?>
        <div class="message"><?php echo htmlspecialchars($message); ?></div>
      <?php endif; ?>

      <form method="POST" action="">
        <label for="event_name">Event Name *</label>
        <input type="text" id="event_name" name="event_name" required />

        <label for="event_date">Date *</label>
        <input type="date" id="event_date" name="event_date" required />

        <label for="event_time">Time *</label>
        <input type="time" id="event_time" name="event_time" required />

        <label for="event_description">Description</label>
        <textarea id="event_description" name="event_description"></textarea>

        <button type="submit">Add Event</button>
      </form>

      <h2>Upcoming Events</h2>

      <?php if (count($events) === 0): ?>
        <p class="no-events">No events scheduled yet.</p>
      <?php else: ?>
        <table>
          <thead>
            <tr>
              <th>Event Name</th>
              <th>Date</th>
              <th>Time</th>
              <th>Description</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($events as $ev): ?>
            <tr>
              <td><?php echo htmlspecialchars($ev['event_name']); ?></td>
              <td><?php echo htmlspecialchars($ev['event_date']); ?></td>
              <td><?php echo htmlspecialchars($ev['event_time']); ?></td>
              <td><?php echo nl2br(htmlspecialchars($ev['event_description'])); ?></td>
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
