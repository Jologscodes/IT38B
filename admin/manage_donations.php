<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "entrep-dev"); // Replace with your DB name

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Delete donation
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM donations WHERE id = $id");
    header("Location: manage_donations.php");
    exit();
}

// Update donation
if (isset($_POST['update'])) {
    $id = intval($_POST['id']);
    $donation_type = $conn->real_escape_string($_POST['donation_type']);
    $quantity = $conn->real_escape_string($_POST['quantity']);
    $program_name = $conn->real_escape_string($_POST['program_name']);
    $amount = $conn->real_escape_string($_POST['amount']);

    $conn->query("UPDATE donations SET 
        donation_type='$donation_type',
        quantity='$quantity',
        program_name='$program_name',
        amount='$amount'
        WHERE id=$id
    ");
    header("Location: manage_donations.php");
    exit();
}

$result = $conn->query("SELECT * FROM donations");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Donations</title>
    <style>
        body { font-family: Arial; padding: 20px; background: #f9f9f9; }
        table { width: 100%; border-collapse: collapse; background: white; }
        th, td { border: 1px solid #ccc; padding: 10px; text-align: center; }
        th { background-color: #333; color: white; }
        a { color: red; text-decoration: none; }
        .edit-form { background: #eef; padding: 10px; margin-bottom: 20px; }
        .back-btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #2980b9;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .back-btn:hover {
            background-color: #1f6391;
        }
    </style>
</head>
<body>

<h2>Manage Donations</h2>

<a href="dashboard.php" class="back-btn">← Back to Dashboard</a>

<?php if (isset($_GET['edit'])): ?>
<?php
    $edit_id = intval($_GET['edit']);
    $edit_result = $conn->query("SELECT * FROM donations WHERE id = $edit_id");
    $edit_data = $edit_result->fetch_assoc();
?>
<div class="edit-form">
    <h3>Edit Donation (ID: <?= $edit_data['id'] ?>)</h3>
    <form method="post">
        <input type="hidden" name="id" value="<?= $edit_data['id'] ?>">
        <label>Donation Type: <input type="text" name="donation_type" value="<?= $edit_data['donation_type'] ?>"></label><br><br>
        <label>Quantity: <input type="text" name="quantity" value="<?= $edit_data['quantity'] ?>"></label><br><br>
        <label>Program Name: <input type="text" name="program_name" value="<?= $edit_data['program_name'] ?>"></label><br><br>
        <label>Amount: <input type="text" name="amount" value="<?= $edit_data['amount'] ?>"></label><br><br>
        <button type="submit" name="update">Update Donation</button>
    </form>
</div>
<?php endif; ?>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Donor Username</th>
            <th>Type</th>
            <th>Quantity</th>
            <th>Date</th>
            <th>Program</th>
            <th>Amount</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= htmlspecialchars($row['donor_username']) ?></td>
            <td><?= htmlspecialchars($row['donation_type']) ?></td>
            <td><?= htmlspecialchars($row['quantity']) ?></td>
            <td><?= htmlspecialchars($row['donation_date']) ?></td>
            <td><?= htmlspecialchars($row['program_name']) ?></td>
            <td><?= htmlspecialchars($row['amount']) ?></td>
            <td>
                <a href="?edit=<?= $row['id'] ?>">Edit</a> |
                <a href="?delete=<?= $row['id'] ?>" onclick="return confirm('Are you sure you want to delete this donation?')">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>

</body>
</html>
