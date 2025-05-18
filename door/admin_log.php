<?php
session_start();

$valid_email = "admin@gmail.com";
$valid_password = "admin09";

$email = $password = "";
$email_err = $password_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
   
    if (empty(trim($_POST["email"]))) {
        $email_err = "Please enter email.";
    } else {
        $email = trim($_POST["email"]);
    }

    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter your password.";
    } else {
        $password = trim($_POST["password"]);
    }

    if (empty($email_err) && empty($password_err)) {
        if ($email === $valid_email && $password === $valid_password) {
      
            $_SESSION["admin_id"] = 1;
            $_SESSION["admin_email"] = $valid_email;

            header("Location: admin_dashboard.php");
            exit;
        } else {
            $password_err = "Incorrect email or password.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Admin Login - Nonprofit Resource Management</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body, html {
      height: 100%;
      font-family: Arial, sans-serif;
    }

    .background {
      background-color: red;
      height: 100vh;
      width: 100%;
      display: flex;
      flex-direction: column;
    }

    .navbar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 1rem 2rem;
      background-color: rgba(0, 0, 0, 0.7);
      color: white;
    }

    .navbar-title {
      font-size: 1.5rem;
      font-weight: bold;
    }

    .nav-buttons a {
      text-decoration: none;
      margin-left: 15px;
    }

    .btn {
      padding: 10px 20px;
      font-size: 1rem;
      border: none;
      border-radius: 8px;
      background-color: rgb(232, 32, 32);
      color: white;
      cursor: pointer;
      transition: background-color 0.3s;
    }

    .btn:hover {
      background-color: #218838;
    }

    .content {
      flex: 1;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .login-box {
      background: white;
      padding: 30px;
      border-radius: 10px;
      width: 100%;
      max-width: 400px;
      box-shadow: 0px 4px 10px rgba(0,0,0,0.2);
    }

    .login-box h2 {
      text-align: center;
      margin-bottom: 20px;
      color: red;
    }

    .form-group {
      margin-bottom: 15px;
    }

    .form-group label {
      display: block;
      font-weight: bold;
    }

    .form-group input {
      width: 100%;
      padding: 10px;
      margin-top: 5px;
      border-radius: 5px;
      border: 1px solid #ccc;
    }

    .form-group span {
      color: red;
      font-size: 0.9em;
    }

    .login-box .btn-submit {
      width: 100%;
      padding: 10px;
      border: none;
      background: red;
      color: white;
      font-weight: bold;
      border-radius: 5px;
      cursor: pointer;
      margin-top: 10px;
    }

    .login-box .btn-submit:hover {
      background-color: #b30000;
    }

    .text-muted {
      text-align: center;
      margin-top: 15px;
      font-size: 14px;
    }

    .text-muted a {
      color: red;
    }

  </style>
</head>
<body>
  <div class="background">
    <div class="navbar">
      <div class="navbar-title">Nonprofit Resource Management</div>
      <div class="nav-buttons">
        <a href="./admin_log.php"><button class="btn">Admin</button></a>
        <a href="donor_log.php"><button class="btn">Donor</button></a>
        <a href="beneficiary_login.php"><button class="btn">Beneficiary</button></a>
      </div>
    </div>

  <div class="content">
  <div style="display: flex; align-items: center; gap: 60px; flex-wrap: wrap; justify-content: center;">
    
    <img src="../image/bg.jpg" alt="Admin Image" 
      style="
        width: 500px; 
        height: 500px; 
        border-radius: 50%; 
        object-fit: cover; 
        box-shadow: 0 0 30px rgba(0,0,0,0.7);
        flex-shrink: 0;
      ">

    <form class="login-box" action="admin_log.php" method="post" style="flex: 1; min-width: 320px; max-width: 450px;">
      <h2>Admin Login</h2>

      <div class="form-group">
        <label for="email">Email</label>
        <input type="text" name="email" id="email" value="<?php echo htmlspecialchars($email); ?>" required>
        <span><?php echo $email_err; ?></span>
      </div>

      <div class="form-group">
        <label for="password">Password</label>
        <input type="password" name="password" id="password" required>
        <span><?php echo $password_err; ?></span>
      </div>

      <button type="submit" class="btn-submit">Login</button>

      <p class="text-muted">Go back to <a href="../index.php">Home</a></p>
    </form>

  </div>
</div>

</body>
</html>
