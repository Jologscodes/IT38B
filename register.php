<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

$host = 'localhost';
$dbname = 'entrep-dev';
$db_user = 'root';
$db_pass = '';

$conn = new mysqli($host, $db_user, $db_pass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $fullname = $_POST['fullname'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        $error = "Passwords do not match!";
    } else {
        $check = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $check->bind_param("ss", $username, $email);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $error = "Username or email already exists!";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (fullname, username, email, password) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $fullname, $username, $email, $hashed_password);

            if ($stmt->execute()) {
                $stmt->close();
                $check->close();
                $conn->close();
                header("Location: http://localhost/entrep-dev/index.php");
                exit();
            } else {
                $error = "Error: " . $stmt->error;
            }
        }

        $check->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
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
                <h2>Create an Account</h2>
                <form action="register.php" method="POST">
                    <input type="text" name="fullname" placeholder="Full Name" required>
                    <input type="text" name="username" placeholder="Username" required>
                    <input type="email" name="email" placeholder="Email" required>
                    <input type="password" name="password" placeholder="Password" required>
                    <input type="password" name="confirm_password" placeholder="Confirm Password" required>
                    <button type="submit">Register</button>
                </form>
                <form action="index.php" method="GET">
                    <button type="submit" style="margin-top: 10px;">I already have an account</button>
                </form>
                <?php if (!empty($error)) echo '<p class="error">' . $error . '</p>'; ?>
            </div>
        </div>
    </div>
</body>
</html>
