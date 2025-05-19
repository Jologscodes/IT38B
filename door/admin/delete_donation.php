<?php
session_start();
if (!isset($_SESSION["admin_id"])) {
    header("Location: admin_log.php");
    exit;
}

require_once "../../data/config.php";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['id'])) {
    $donation_id = $_POST['id'];

    try {
        $stmt = $pdo->prepare("DELETE FROM item_donations WHERE id = ?");
        $stmt->execute([$donation_id]);

        header("Location: manage_donations.php?deleted=1");
        exit;
    } catch (PDOException $e) {
        die("ERROR: Could not delete donation. " . $e->getMessage());
    }
} else {
    header("Location: manage_donations.php");
    exit;
}
