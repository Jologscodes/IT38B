<?php
session_start();

if (!isset($_SESSION['beneficiary_id'])) {
    header("Location: beneficiary_login.php");
    exit();
}

$name = $_SESSION['beneficiary_name'] ?? "Beneficiary";

$host = "localhost";
$dbname = "entrep-dev";
$username = "root";
$password = "";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->query("SELECT id, item_type, description, photo, quantity FROM item_donations");
    $resources = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Review Resources - Beneficiary Dashboard</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="rr.css" />
  <style>
    /* Your styles here */
    .take-form {
      margin-top: 10px;
      display: flex;
      flex-direction: column;
      max-width: 300px;
    }
    .take-form label {
      margin: 5px 0 2px;
      font-weight: 600;
    }
    .take-form input[type="number"],
    .take-form textarea {
      padding: 5px;
      font-size: 1rem;
      resize: vertical;
    }
    .take-form button {
      margin-top: 10px;
      padding: 8px 12px;
      background-color: #28a745;
      border: none;
      color: white;
      font-weight: 600;
      cursor: pointer;
      border-radius: 4px;
      transition: background-color 0.3s ease;
    }
    .take-form button:hover {
      background-color: #218838;
    }
    .success-message {
      color: green;
      font-weight: 600;
      margin-bottom: 15px;
      transition: opacity 1s ease;
    }
    .sold-out {
      color: red;
      font-weight: 700;
      margin-top: 10px;
    }
    .available-qty {
      font-weight: 600;
      margin-top: 8px;
      color: #333;
    }
    .resource-list {
  list-style-type: none;
  padding: 0;
  margin-top: 20px;
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
  gap: 20px;
}

.resource-item {
  background-color: #f9f9f9;
  border: 1px solid #ddd;
  padding: 15px;
  border-radius: 10px;
  box-shadow: 0 2px 5px rgba(0,0,0,0.1);
  display: flex;
  flex-direction: column;
  align-items: center;
  transition: transform 0.3s ease;
}

.resource-item:hover {
  transform: translateY(-5px);
}

.resource-item img {
  max-width: 100%;
  max-height: 180px;
  object-fit: contain;
  border-radius: 6px;
  margin-bottom: 10px;
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
      <h1>Available Resources for You, <?php echo htmlspecialchars($name); ?>!</h1>

      <?php if (isset($_GET['success'])): ?>
        <p id="success-message" class="success-message">Your request has been sent successfully!</p>
      <?php endif; ?>

      <ul class="resource-list">
        <?php if (!empty($resources)): ?>
          <?php foreach ($resources as $res): ?>
            <li class="resource-item">
              <?php if (!empty($res['photo'])): ?>
                <img src="../../uploads/donations/<?php echo htmlspecialchars($res['photo']); ?>" alt="<?php echo htmlspecialchars($res['item_type']); ?>" />
              <?php else: ?>
                <p><em>No image available</em></p>
              <?php endif; ?>

              <p><strong>Item Name:</strong> <?php echo htmlspecialchars($res['item_type']); ?></p>
              <p><strong>Description:</strong> <?php echo htmlspecialchars($res['description']); ?></p>
              <p class="available-qty">Available Quantity: <?php echo (int)$res['quantity']; ?></p>

              <?php if ((int)$res['quantity'] > 0): ?>
                <form method="POST" action="take_resource.php" class="take-form">
                  <input type="hidden" name="item_donation_id" value="<?php echo $res['id']; ?>" />
                  
                  <label for="quantity_<?php echo $res['id']; ?>">Quantity:</label>
                  <input type="number" id="quantity_<?php echo $res['id']; ?>" name="quantity" min="1" max="<?php echo (int)$res['quantity']; ?>" required />
                  
                  <label for="message_<?php echo $res['id']; ?>">Message to donor:</label>
                  <textarea id="message_<?php echo $res['id']; ?>" name="message" rows="3" placeholder="Write a message..." required></textarea>
                  
                  <button type="submit">Take</button>
                </form>
              <?php else: ?>
                <p class="sold-out">Sold Out</p>
              <?php endif; ?>
            </li>
          <?php endforeach; ?>
        <?php else: ?>
          <p>No resources available at the moment. Please check back later.</p>
        <?php endif; ?>
      </ul>
    </main>
  </div>

  <script>
    setTimeout(() => {
      const msg = document.getElementById('success-message');
      if (msg) {
        msg.style.opacity = '0';
        setTimeout(() => msg.remove(), 1000); // remove after fade out
      }
    }, 5000);
  </script>
</body>
</html>
