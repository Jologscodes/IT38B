<?php
session_start();

if (!isset($_SESSION['beneficiary_id'])) {
    header("Location: beneficiary_login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $beneficiary_id = $_SESSION['beneficiary_id'];
    $item_donation_id = intval($_POST['item_donation_id'] ?? 0);
    $quantity = intval($_POST['quantity'] ?? 0);
    $message = trim($_POST['message'] ?? '');

    // Basic validation
    if ($item_donation_id <= 0 || $quantity <= 0 || empty($message)) {
        die("Invalid input. Please fill all fields correctly.");
    }

    $host = "localhost";
    $dbname = "entrep-dev";
    $username = "root";
    $password = "";

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Start transaction to prevent race conditions
        $pdo->beginTransaction();

        // Lock the row for update
        $stmt = $pdo->prepare("SELECT quantity FROM item_donations WHERE id = :id FOR UPDATE");
        $stmt->execute([':id' => $item_donation_id]);
        $item = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$item) {
            $pdo->rollBack();
            die("Item not found.");
        }

        if ($item['quantity'] < $quantity) {
            $pdo->rollBack();
            die("Requested quantity exceeds available quantity.");
        }

        // Deduct quantity
        $new_qty = $item['quantity'] - $quantity;
        $updateStmt = $pdo->prepare("UPDATE item_donations SET quantity = :new_qty WHERE id = :id");
        $updateStmt->execute([
            ':new_qty' => $new_qty,
            ':id' => $item_donation_id,
        ]);

        // Insert request log
        $insertStmt = $pdo->prepare("INSERT INTO resource_requests (beneficiary_id, item_donation_id, quantity_requested, message) VALUES (:beneficiary_id, :item_id, :qty, :msg)");
        $insertStmt->execute([
            ':beneficiary_id' => $beneficiary_id,
            ':item_id' => $item_donation_id,
            ':qty' => $quantity,
            ':msg' => $message,
        ]);

        $pdo->commit();

        // Redirect back with success
        header("Location: ReviewResources.php?success=1");
        exit();

    } catch (PDOException $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        die("Database error: " . $e->getMessage());
    }
} else {
    header("Location: ReviewResources.php");
    exit();
}
