<?php
session_start();

$host = "localhost";
$user = "root";
$pass = "";
$db = "entrep-dev";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$loginError = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if (!empty($username) && !empty($password)) {
        $sql = "SELECT * FROM users WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $row = $result->fetch_assoc();

            if (password_verify($password, $row['password'])) {
                $_SESSION['username'] = $username;
                $_SESSION['role'] = $row['role'];

                if ($row['role'] === 'admin') {
                    header("Location: admin_dashboard.php");
                } elseif ($row['role'] === 'donor') {
                    header("Location: donor_dashboard.php");
                } elseif ($row['role'] === 'beneficiary') {
                    header("Location: beneficiary_dashboard.php");
                }
                exit();
            } else {
                $loginError = "Incorrect password.";
            }
        } else {
            $loginError = "User not found.";
        }
    } else {
        $loginError = "Please enter both username and password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background: red;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            font-family: Arial, sans-serif;
        }
        .container {
            text-align: center;
            width: 90%;
            max-width: 1000px;
        }
        .header {
            color: white;
            font-weight: bold;
            font-size: 24px;
            text-shadow: 1px 1px 2px black;
            margin-bottom: 20px;
        }
        .main-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
        }
        .circle-section {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .circle {
            background: white;
            width: 350px;
            height: 350px;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            box-shadow: 3px 3px 10px rgba(0, 0, 0, 0.3);
            overflow: hidden;
        }
        .circle img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .login-box {
            flex: 1;
            background: #333;
            padding: 30px;
            border-radius: 8px;
            width: 320px;
            box-shadow: 3px 3px 10px rgba(0, 0, 0, 0.3);
            color: white;
            text-align: left;
        }
        .login-box h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: none;
            border-radius: 5px;
        }
        button {
            width: 100%;
            padding: 10px;
            background: blue;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background: darkblue;
        }
        p {
            text-align: center;
            margin-top: 10px;
        }
        a {
            color: lightblue;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
        .error {
            color: red;
            text-align: center;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="header">Nonprofit Resource Management</h1>
        <div class="main-content">
            <div class="circle-section">
                <div class="circle">
                    <img src="https://wallacefoundation.org/sites/default/files/2023-09/sfm-home-page-graphic.png" alt="Image">
                </div>
            </div>
            <div class="login-box">
                <h2>Login</h2>
                <form method="POST">
                    <input type="text" name="username" placeholder="Username" required>
                    <input type="password" name="password" placeholder="Password" required>
                    <button type="submit">LOG IN</button>
                </form>
                <form action="register.php" method="GET">
                    <button type="submit" style="margin-top: 10px;">Create an account</button>
                </form>
                <?php if (!empty($loginError)): ?>
                    <p class="error"><?= htmlspecialchars($loginError) ?></p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>

<?php if (isset($_GET['registered']) && $_GET['registered'] == 1): ?>
    <p style="color:lightgreen; text-align:center;">Registration successful! Please log in.</p>
<?php endif; ?>

