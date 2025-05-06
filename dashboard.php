<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

$host = 'localhost';
$dbname = 'entrep-dev';
$db_user = 'root';
$db_pass = '';

// Database connection
$conn = new mysqli($host, $db_user, $db_pass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch login history
$sql = "SELECT username, login_time FROM login_history ORDER BY login_time DESC";
$result = $conn->query($sql);

$history = [];
while ($row = $result->fetch_assoc()) {
    $history[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Admin Dashboard</title>
  <style>
    /* Reset and Base Styles */
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: Arial, sans-serif;
      background-color: #f4f4f9;
      color: #333;
    }

    /* Sidebar Styles */
    .sidebar {
      position: fixed;
      top: 0;
      left: 0;
      width: 250px;
      height: 100%;
      background-color: #2f3b52;
      color: white;
      padding-top: 20px;
      padding-left: 20px;
      box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
    }

    .sidebar img {
      width: 100%;
      max-width: 150px;
      margin-bottom: 20px;
    }

    .sidebar button {
      width: 100%;
      padding: 12px;
      background-color: #3b4c72;
      border: none;
      border-radius: 5px;
      color: white;
      font-size: 16px;
      margin-bottom: 10px;
      cursor: pointer;
      text-align: left;
    }

    .sidebar button:hover {
      background-color: #2c3a56;
    }

    /* Main Content Styles */
    .main-content {
      margin-left: 270px;
      padding: 20px;
    }

    .page-title {
      font-size: 28px;
      font-weight: bold;
      color: #2f3b52;
      margin-bottom: 20px;
    }

    .dashboard-container {
      background-color: white;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
    }

    .dashboard-container h2 {
      font-size: 24px;
      margin-bottom: 20px;
    }

    .search-bar {
      margin-bottom: 20px;
      display: flex;
      justify-content: flex-end;
    }

    .search-bar input {
      padding: 10px;
      font-size: 16px;
      width: 250px;
      border-radius: 5px;
      border: 1px solid #ddd;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }

    table th, table td {
      padding: 12px;
      text-align: left;
      border-bottom: 1px solid #ddd;
    }

    table th {
      background-color: #f1f1f1;
    }

    table tr:hover {
      background-color: #f9f9f9;
    }

    .button.logout-btn {
      background-color: #e74c3c;
      color: white;
      padding: 10px 20px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      text-align: center;
      width: 100%;
      margin-top: 30px;
    }

    .button.logout-btn:hover {
      background-color: #c0392b;
    }

    /* Responsive Design */
    @media screen and (max-width: 768px) {
      .sidebar {
        width: 200px;
      }

      .main-content {
        margin-left: 220px;
      }

      .search-bar input {
        width: 200px;
      }
    }
  </style>
</head>
<body>

  <!-- Sidebar -->
  <div class="sidebar">
    <img src="https://wallacefoundation.org/sites/default/files/2023-09/sfm-home-page-graphic.png" alt="Logo">
    <button onclick="location.href='dashboard.php'">Home</button>
    <button onclick="location.href='file.php'">File</button>
    <button onclick="location.href='message.php'">Message</button>
    <button onclick="location.href='location.php'">Location</button>
    <button onclick="location.href='graph.php'">Graph</button>
  </div>

  <!-- Main Content -->
  <div class="main-content">
    <div class="page-title">Nonprofit Resource Management</div>

    <div class="dashboard-container">
      <h2>Admin Dashboard</h2>
      <div class="search-bar">
        <input type="text" id="searchInput" placeholder="Search by username..." onkeyup="filterHistory()">
      </div>
      
      <h3>Welcome, <span id="currentUser"><?= htmlspecialchars($_SESSION['username']) ?></span></h3>
      <h3>Login History</h3>
      <table>
        <thead>
          <tr>
            <th>Username</th>
            <th>Login Time</th>
          </tr>
        </thead>
        <tbody id="historyTable">
          <?php foreach ($history as $entry): ?>
            <tr>
              <td><?= htmlspecialchars($entry['username']) ?></td>
              <td><?= htmlspecialchars($entry['login_time']) ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>

      <!-- Logout Button -->
      <form method="post" action="logout.php">
        <button class="button logout-btn" type="submit">Logout</button>
      </form>
    </div>
  </div>

  <script>
    function filterHistory() {
      let input = document.getElementById("searchInput").value.toLowerCase();
      let rows = document.querySelectorAll("#historyTable tr");
      rows.forEach(row => {
        const username = row.children[0].textContent.toLowerCase();
        row.style.display = username.includes(input) ? "" : "none";
      });
    }
  </script>

</body>
</html>
