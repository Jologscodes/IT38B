<?php
session_start();

if (!isset($_SESSION['beneficiary_id'])) {
    header("Location: beneficiary_login.php");
    exit();
}

$name = $_SESSION['beneficiary_name'] ?? "Beneficiary";

$host = "localhost";
$db_name = "entrep-dev";
$username = "root";
$password = "";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db_name", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Use your actual table name here:
    $sql = "SELECT id, title, description, status, request_date FROM requests WHERE beneficiary_id = ? ORDER BY request_date DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$_SESSION['beneficiary_id']]);
    $requests = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>Track Request Status</title>
<style>
  body { font-family: Arial, sans-serif; background:#121212; color:#eee; padding:20px; }
  table { border-collapse: collapse; width: 100%; max-width: 700px; margin: auto; }
  th, td { border: 1px solid #444; padding: 10px; }
  th { background-color: #d32f2f; }
  .status { padding: 5px 10px; border-radius: 5px; color: white; }
  .pending { background-color: orange; }
  .approved { background-color: green; }
  .rejected { background-color: red; }
</style>
</head>
<body>

<h2>Hello, <?php echo htmlspecialchars($name); ?>! Your Requests</h2>

<?php if (empty($requests)): ?>
  <p>No requests found.</p>
<?php else: ?>
  <table>
    <thead>
      <tr>
        <th>Title</th>
        <th>Description</th>
        <th>Status</th>
        <th>Date</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($requests as $r): ?>
        <tr>
          <td><?php echo htmlspecialchars($r['title']); ?></td>
          <td><?php echo htmlspecialchars($r['description']); ?></td>
          <td>
            <span class="status <?php echo strtolower($r['status']); ?>">
              <?php echo ucfirst($r['status']); ?>
            </span>
          </td>
          <td><?php echo date("M d, Y", strtotime($r['request_date'])); ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
<?php endif; ?>

</body>
</html>
