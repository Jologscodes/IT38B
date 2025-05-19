<?php
session_start();

if (!isset($_SESSION["admin_id"])) {
    header("Location: admin_log.php");
    exit;
}

require_once '../../data/config.php'; 

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $audience = $_POST["audience"];
    $message = $_POST["message"];
    $admin_id = $_SESSION["admin_id"];

    try {
        $stmt = $pdo->prepare("INSERT INTO reports (admin_id, audience, message, created_at) VALUES (?, ?, ?, NOW())");
        $stmt->execute([$admin_id, $audience, $message]);
        echo "<script>alert('Report successfully saved.'); window.location.href='generate_reports.php';</script>";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

?>
