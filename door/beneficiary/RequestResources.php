<?php
session_start();

if (!isset($_SESSION['beneficiary_id'])) {
    header("Location: beneficiary_login.php");
    exit();
}

require_once "../../data/config.php";  

$name = $_SESSION['beneficiary_name'] ?? "Beneficiary";

$requestSuccess = false;
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? "");
    $description = trim($_POST['description'] ?? "");

    if (empty($title) || empty($description)) {
        $error = "Please fill in all fields.";
    } else {
        try {
            $sql = "INSERT INTO request (beneficiary_id, title, description, request_date) VALUES (:beneficiary_id, :title, :description, NOW())";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':beneficiary_id', $_SESSION['beneficiary_id'], PDO::PARAM_INT);
            $stmt->bindParam(':title', $title, PDO::PARAM_STR);
            $stmt->bindParam(':description', $description, PDO::PARAM_STR);
            $stmt->execute();

            $requestSuccess = true;
        } catch (PDOException $e) {
            $error = "Failed to submit request: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Request Resources - Beneficiary Dashboard</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="rr.css" />
  <style>
    .request-container {
      background: #ffffff;
      padding: 30px;
      border-radius: 15px;
      box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
      max-width: 600px;
      margin: 20px auto;
      font-family: 'Poppins', sans-serif;
    }
    .request-container form label {
      display: block;
      margin-bottom: 8px;
      font-weight: 600;
    }
    .request-container form input,
    .request-container form textarea {
      width: 100%;
      padding: 12px;
      margin-bottom: 20px;
      border: 1px solid #ccc;
      border-radius: 8px;
      font-size: 1rem;
    }
    .request-container form button {
      background-color: #4CAF50;
      color: white;
      padding: 12px 20px;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      font-weight: bold;
      transition: background-color 0.3s ease;
    }
    .request-container form button:hover {
      background-color: #45a049;
    }
    .message {
      max-width: 600px;
      margin: 10px auto;
      padding: 15px;
      border-radius: 8px;
      font-weight: 600;
      text-align: center;
      font-family: 'Poppins', sans-serif;
    }
    .message.success {
      background-color: #d4edda;
      color: #155724;
      border: 1px solid #c3e6cb;
    }
    .message.error {
      background-color: #f8d7da;
      color: #721c24;
      border: 1px solid #f5c6cb;
    }
  </style>
</head>
<body>
  <nav class="sidebar">
    <h2>Dashboard</h2>
    <a href="beneficiary_dashboard.php">beneficiary dashboard</a>
    <a href="ReviewResources.php">Review Resources</a>
    <a href="RequestResources.php">Request Resources</a>
  </nav>

  <div class="main-content">
    <header class="topnav">
      <h1>Beneficiary Portal</h1>
      <a href="../logout.php" class="logout-btn">Logout</a>
    </header>

    <main class="dashboard">
      <h1>Request a Resource</h1>

      <?php if ($requestSuccess): ?>
        <div class="message success">Your request has been submitted successfully!</div>
      <?php elseif ($error): ?>
        <div class="message error"><?php echo htmlspecialchars($error); ?></div>
      <?php endif; ?>

      <div class="request-container">
        <form method="POST" action="RequestResources.php" novalidate>
          <label for="title">Resource Title</label>
          <input type="text" id="title" name="title" placeholder="Enter resource title" required maxlength="100" />

          <label for="description">Resource Description</label>
          <textarea id="description" name="description" placeholder="Describe the resource you need" rows="5" required maxlength="500"></textarea>

          <button type="submit">Submit Request</button>
        </form>
      </div>
    </main>
  </div>
</body>
</html>
