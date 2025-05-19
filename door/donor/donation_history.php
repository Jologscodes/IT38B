<?php
session_start();
require_once '../../data/config.php';

if (!isset($_SESSION['donor_id'])) {
    header("Location: login.php");
    exit();
}

$donor_id = $_SESSION['donor_id'];
$donor_name = $_SESSION['donor_name'];

try {
    $stmt = $pdo->prepare("
        SELECT d.amount, d.message, b.name AS beneficiary_name
        FROM donations d
        JOIN beneficiaries b ON d.beneficiary_id = b.id
        WHERE d.donor_id = ?
        ORDER BY d.id DESC
    ");
    $stmt->execute([$donor_id]);
    $donations = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Donation History</title>
    <style>
        /* Include the same style as Donor Dashboard */

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

        .dashboard-container {
            display: flex;
            height: 100vh;
            overflow: hidden;
        }

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

        .main-content {
            flex-grow: 1;
            padding: 50px 60px;
            background: #fff0f5;
            overflow-y: auto;
        }

        .main-content h1 {
            color: #880e4f;
            font-size: 36px;
            margin-bottom: 20px;
            text-align: center;
        }

        .donation {
            background-color: white;
            padding: 20px;
            margin-bottom: 20px;
            border-left: 6px solid #e91e63;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(233, 30, 99, 0.2);
        }

        .donation p {
            margin: 6px 0;
            font-size: 16px;
            color: #4a1a1a;
        }

        .no-record {
            text-align: center;
            font-size: 20px;
            color: #777;
        }

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
            .main-content h1 {
                font-size: 28px;
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
            <a href="donation_history.php">donation_history</a>
        </nav>
        <a href="../logout.php" class="logout-btn">Logout</a>
    </aside>

    <main class="main-content">
        <h1>Donation History</h1>

        <?php if (count($donations) > 0): ?>
            <?php foreach ($donations as $donation): ?>
                <div class="donation">
                    <p><strong>Beneficiary:</strong> <?= htmlspecialchars($donation['beneficiary_name']) ?></p>
                    <p><strong>Amount:</strong> ₱<?= number_format($donation['amount'], 2) ?></p>
                    <p><strong>Message:</strong> <?= htmlspecialchars($donation['message']) ?></p>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="no-record">No donation records found.</p>
        <?php endif; ?>
    </main>
</div>

</body>
</html>
