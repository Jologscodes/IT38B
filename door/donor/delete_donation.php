<?php
session_start();
require '../../data/config.php';

if (!isset($_SESSION['donor_id'])) {
    header("Location: donor_login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['donation_id'])) {
    $donor_id = $_SESSION['donor_id'];
    $donation_id = (int)$_POST['donation_id'];

    $checkSql = "SELECT photo FROM item_donations WHERE id = ? AND donor_id = ?";
    $stmt = $pdo->prepare($checkSql);
    $stmt->execute([$donation_id, $donor_id]);
    $donation = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($donation) {
        // Delete related rows in resource_requests first
        $deleteRelatedSql = "DELETE FROM resource_requests WHERE item_donation_id = ?";
        $stmt = $pdo->prepare($deleteRelatedSql);
        $stmt->execute([$donation_id]);

        // Now delete from item_donations
        $deleteSql = "DELETE FROM item_donations WHERE id = ?";
        $stmt = $pdo->prepare($deleteSql);
        $stmt->execute([$donation_id]);

        if (!empty($donation['photo']) && $donation['photo'] !== 'default.png') {
            $photoPath = '../../uploads/donations/' . $donation['photo'];
            if (file_exists($photoPath)) {
                unlink($photoPath);
            }
        }

        $_SESSION['message'] = "Donation deleted successfully.";
    } else {
        $_SESSION['message'] = "Donation not found or permission denied.";
    }
} else {
    $_SESSION['message'] = "Invalid request.";
}

header("Location: donation_history.php");
exit();
