<?php
// location.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Location Page</title>
  <style>
    :root {
      --primary-color: #00bcd4;
      --secondary-color: #008ba3;
      --glow-color: #4dd0e1;
      --background-gradient: linear-gradient(135deg, #e0f7fa, #ffffff);
    }

    body {
      font-family: Arial, sans-serif;
      margin: 0;
      height: 100vh;
      display: flex;
      overflow: hidden;
      background: var(--background-gradient);
      background-size: 400% 400%;
      animation: gradientBG 15s ease infinite;
    }

    @keyframes gradientBG {
      0% { background-position: 0% 50%; }
      50% { background-position: 100% 50%; }
      100% { background-position: 0% 50%; }
    }

    .sidebar {
      width: 220px;
      background: var(--primary-color);
      color: white;
      display: flex;
      flex-direction: column;
      align-items: center;
      padding-top: 30px;
      box-shadow: 4px 0 20px var(--glow-color);
    }

    .sidebar img {
      width: 160px;
      margin-bottom: 30px;
      border-radius: 15px;
      box-shadow: 0 0 20px var(--glow-color);
    }

    .sidebar button {
      background: none;
      border: none;
      color: white;
      padding: 15px 25px;
      width: 100%;
      font-size: 18px;
      cursor: pointer;
      transition: background 0.3s, transform 0.3s;
    }

    .sidebar button:hover {
      background-color: var(--secondary-color);
      transform: scale(1.05);
    }

    .main-content {
      flex: 1;
      padding: 40px;
      overflow-y: auto;
      display: flex;
      flex-direction: column;
      align-items: center;
    }

    .page-title {
      font-size: 36px;
      font-weight: bold;
      margin-bottom: 30px;
      color: var(--primary-color);
      text-align: center;
      text-shadow: 0 0 8px var(--glow-color);
    }

    .location-container {
      background: var(--background-gradient);
      padding: 40px;
      border-radius: 20px;
      width: 800px;
      text-align: center;
      box-shadow: 0 0 30px var(--glow-color);
    }

    .location-container h2 {
      margin-bottom: 20px;
      color: var(--secondary-color);
    }

    .location-container p {
      font-size: 20px;
      color: #555;
    }
  </style>
</head>

<body>

<div class="sidebar">
  <img src="https://wallacefoundation.org/sites/default/files/2023-09/sfm-home-page-graphic.png" alt="Logo">
  <button onclick="location.href='dashboard.php'">Home</button>
  <button onclick="location.href='file.php'">File</button>
  <button onclick="location.href='message.php'">Message</button>
  <button onclick="location.href='location.php'">Location</button>
  <button onclick="location.href='graph.php'">Graph</button>
</div>

<div class="main-content">
  <div class="page-title">Location Details</div>

  <div class="location-container">
    <h2>Our Office Locations</h2>
    <p>Main Office: 123 Manolo Fortich, Bukidnon, Phillipines</p>
    <p>Support Center: 456 Los Santos, boulevard, UNITED KINGDOM</p>
    <p>Field Office: 789 Sankanan, Manila, Japan</p>
  </div>
</div>

</body>
</html>
