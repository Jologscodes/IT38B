<?php
session_start();

if (!isset($_SESSION['beneficiary_id'])) {
    header("Location: beneficiary_login.php");
    exit();
}

$name = $_SESSION['beneficiary_name'] ?? "Beneficiary";
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Beneficiary Dashboard</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet" />
  <style>
    /* Reset */
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    html, body {
      height: 100%;
      width: 100%;
      font-family: 'Poppins', sans-serif;
      background: #1a1a1a;
      color: #fff;
      overflow: hidden;
    }

    body {
      display: flex;
      height: 100vh;
      width: 100vw;
    }

    /* Sidebar */
    .sidebar {
      width: 240px;
      background: linear-gradient(135deg, #d32f2f, #b71c1c);
      box-shadow: 4px 0 12px rgba(0,0,0,0.6);
      display: flex;
      flex-direction: column;
      padding-top: 40px;
      height: 100vh;
      position: fixed;
      top: 0;
      left: 0;
      overflow-y: auto;
      border-top-right-radius: 20px;
      border-bottom-right-radius: 20px;
      user-select: none;
    }

    .sidebar h2 {
      text-align: center;
      margin-bottom: 40px;
      font-weight: 700;
      font-size: 28px;
      letter-spacing: 3px;
      text-shadow: 1px 1px 5px rgba(0,0,0,0.7);
      cursor: default;
    }

    .sidebar a {
      color: #fff;
      text-decoration: none;
      padding: 18px 28px;
      display: block;
      font-weight: 600;
      font-size: 18px;
      border-left: 6px solid transparent;
      transition: all 0.3s ease;
      margin: 0 20px 10px 20px;
      border-radius: 8px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.3);
      backdrop-filter: blur(5px);
      background: rgba(255, 255, 255, 0.05);
    }
    .sidebar a:hover {
      background: rgba(255, 255, 255, 0.15);
      border-left-color: #fff;
      transform: scale(1.05);
      box-shadow: 0 4px 12px rgba(255, 255, 255, 0.3);
    }
    .sidebar a.active {
      background: rgba(255, 255, 255, 0.25);
      border-left-color: #fff;
      box-shadow: 0 0 15px #fff;
    }

    /* Main content wrapper */
    .main-content {
      margin-left: 240px;
      flex-grow: 1;
      display: flex;
      flex-direction: column;
      height: 100vh;
      overflow: hidden;
      width: calc(100vw - 240px);
      background: linear-gradient(135deg, #000000, #2e2e2e);
      padding: 30px 40px;
    }

    /* Top Navigation Bar */
    .topnav {
      background-color: #000000cc;
      color: white;
      padding: 18px 30px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      box-shadow: 0 4px 10px rgba(0,0,0,0.8);
      border-radius: 12px;
      margin-bottom: 30px;
      user-select: none;
    }

    .topnav h1 {
      font-size: 24px;
      font-weight: 600;
      letter-spacing: 1.2px;
      text-shadow: 1px 1px 4px rgba(255, 255, 255, 0.3);
    }

    .topnav a.logout-btn {
      background: #d32f2f;
      padding: 12px 28px;
      border-radius: 30px;
      font-weight: 700;
      color: white;
      text-decoration: none;
      box-shadow: 0 4px 10px rgba(211, 47, 47, 0.7);
      transition: background 0.3s ease, box-shadow 0.3s ease;
    }
    .topnav a.logout-btn:hover {
      background: #b71c1c;
      box-shadow: 0 6px 14px rgba(183, 28, 28, 0.9);
      transform: scale(1.05);
    }

    /* Dashboard content */
    .dashboard {
      background: #121212cc;
      padding: 40px;
      border-radius: 20px;
      box-shadow: 0 0 30px rgba(255, 255, 255, 0.1);
      flex-grow: 1;
      overflow-y: auto;
      user-select: none;
    }

    .dashboard h1 {
      color: #ff5252;
      margin-bottom: 20px;
      font-size: 36px;
      font-weight: 700;
      letter-spacing: 2px;
      text-shadow: 0 0 8px #ff5252;
    }

    .dashboard p {
      font-size: 20px;
      color: #ddd;
      line-height: 1.5;
    }
  </style>
</head>
<body>
  <nav class="sidebar">
    <h2>Dashboard</h2>
    <a href="#" class="active">Review Resources</a>
    <a href="#">Request Resources</a>
    <a href="#">Track Request Status</a>
  </nav>

  <div class="main-content">
    <header class="topnav">
      <h1>Beneficiary Portal</h1>
      <a href="../logout.php" class="logout-btn">Logout</a>
    </header>

    <main class="dashboard">
      <h1>Welcome, <?php echo htmlspecialchars($name); ?>!</h1>
      <p>You have successfully logged in as a beneficiary.</p>
    </main>
  </div>
</body>
</html>
