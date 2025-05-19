<?php
session_start();

// Check if donor is logged in
if (!isset($_SESSION['donor_id'])) {
    header("Location: donor_login.php"); // redirect to login if not logged in
    exit();
}

$donor_name = $_SESSION['donor_name'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Donor Dashboard</title>
<style>
  /* Reset and base */
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
  
  /* Layout */
  .dashboard-container {
    display: flex;
    height: 100vh;
    overflow: hidden;
  }
  
  /* Sidebar */
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
    text-shadow: 0 0 8px rgba(255, 255, 255, 0.6);
  }
  
  .nav-links {
    flex-grow: 1;
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
    box-shadow: 0 4px 6px rgba(255, 255, 255, 0.15);
    transition: background 0.4s ease, box-shadow 0.3s ease;
  }
  
  .nav-links a:hover {
    background: #ff4081;
    box-shadow: 0 6px 15px rgba(255, 64, 129, 0.7);
  }
  
  /* Logout button */
  .logout-btn {
    background: linear-gradient(45deg, #ff1744, #d50000);
    color: white;
    border: none;
    padding: 14px 20px;
    border-radius: 10px;
    cursor: pointer;
    font-size: 18px;
    font-weight: 700;
    letter-spacing: 1px;
    box-shadow: 0 5px 15px rgba(213, 0, 0, 0.7);
    transition: background 0.3s ease, transform 0.2s ease;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    width: 100%;
  }
  
  .logout-btn:hover {
    background: linear-gradient(45deg, #d50000, #ff1744);
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(255, 23, 68, 0.9);
  }
  
  /* Main content */
  .main-content {
    flex-grow: 1;
    padding: 50px 60px;
    background: #fff0f5;
    box-shadow: inset 0 0 25px rgba(255, 23, 68, 0.1);
    overflow-y: auto;
  }
  
  .navbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 50px;
  }
  
  .navbar h1 {
    color: #c2185b;
    font-weight: 800;
    font-size: 32px;
    letter-spacing: 2px;
    text-shadow: 0 1px 3px rgba(194, 24, 91, 0.6);
  }
  
  .main-content h1 {
    color: #880e4f;
    font-size: 36px;
    margin-bottom: 15px;
  }
  
  .main-content p {
    font-size: 20px;
    color: #6a1b4d;
    line-height: 1.5;
    max-width: 600px;
  }
  
  /* Responsive adjustments */
  @media (max-width: 600px) {
    .dashboard-container {
      flex-direction: column;
    }
    .sidebar {
      width: 100%;
      flex-direction: row;
      padding: 15px;
      justify-content: space-around;
      align-items: center;
      box-shadow: none;
    }
    .sidebar h2 {
      display: none;
    }
    .nav-links {
      display: flex;
      flex-grow: 0;
      margin: 0;
    }
    .nav-links a {
      margin: 0 8px;
      padding: 10px 14px;
      font-size: 16px;
      border-radius: 6px;
    }
    .logout-btn {
      width: auto;
      padding: 10px 16px;
      font-size: 16px;
      box-shadow: none;
    }
    .main-content {
      padding: 20px 25px;
    }
    .navbar h1 {
      font-size: 24px;
    }
    .main-content h1 {
      font-size: 28px;
    }
    .main-content p {
      font-size: 16px;
      max-width: 100%;
    }
  }
</style>
</head>
<body>

<div class="dashboard-container">
  <aside class="sidebar">
    <h2>Donor Panel</h2>
    <nav class="nav-links">
      <a href="make_donation.php">Make Donation</a>
      <a href="view_donations.php">View Donations</a>
    </nav>
    <a href="../logout.php" class="logout-btn">Logout</a>
  </aside>

  <main class="main-content">
    <div class="navbar">
      <h1>Nonprofit Resource Management</h1>
    </div>
    <h1>Welcome, <?php echo htmlspecialchars($donor_name); ?>!</h1>
    <p>This is your donor dashboard.</p>
  </main>
</div>

</body>
</html>
