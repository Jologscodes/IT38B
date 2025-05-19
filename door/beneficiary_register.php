<?php
require_once "../data/config.php";

$name = $email = $password = $confirm_password = "";
$name_err = $email_err = $password_err = $confirm_password_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
   
    if (empty(trim($_POST["name"]))) {
        $name_err = "Please enter your name.";
    } else {
        $name = trim($_POST["name"]);
    }

    if (empty(trim($_POST["email"]))) {
        $email_err = "Please enter your email.";
    } else {
        $sql = "SELECT id FROM beneficiaries WHERE email = :email";
        if ($stmt = $pdo->prepare($sql)) {
            $stmt->bindParam(":email", trim($_POST["email"]), PDO::PARAM_STR);
            $stmt->execute();
            if ($stmt->rowCount() == 1) {
                $email_err = "This email is already taken.";
            } else {
                $email = trim($_POST["email"]);
            }
        }
    }

    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter a password.";
    } elseif (strlen(trim($_POST["password"])) < 6) {
        $password_err = "Password must have at least 6 characters.";
    } else {
        $password = trim($_POST["password"]);
    }

    if (empty(trim($_POST["confirm_password"]))) {
        $confirm_password_err = "Please confirm password.";
    } else {
        $confirm_password = trim($_POST["confirm_password"]);
        if ($password != $confirm_password) {
            $confirm_password_err = "Password did not match.";
        }
    }

    if (empty($name_err) && empty($email_err) && empty($password_err) && empty($confirm_password_err)) {
        $sql = "INSERT INTO beneficiaries (name, email, password) VALUES (:name, :email, :password)";
        if ($stmt = $pdo->prepare($sql)) {
            $stmt->bindParam(":name", $name, PDO::PARAM_STR);
            $stmt->bindParam(":email", $email, PDO::PARAM_STR);
            $stmt->bindParam(":password", password_hash($password, PASSWORD_DEFAULT), PDO::PARAM_STR);

            if ($stmt->execute()) {
                header("Location: beneficiary_login.php");
                exit;
            } else {
                echo "Something went wrong. Please try again later.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Beneficiary Registration</title>
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body, html { height: 100%; font-family: Arial, sans-serif; }
    .background {
      background-color: red;
      height: 100vh;
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

    .navbar-title { font-size: 1.5rem; font-weight: bold; }
    .nav-buttons a { text-decoration: none; margin-left: 15px; }

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

    .btn:hover { background-color: #218838; }

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

    .form-group { margin-bottom: 15px; }
    .form-group label { display: block; font-weight: bold; }

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

    .login-box .btn-submit:hover { background-color: #b30000; }

    .text-muted {
      text-align: center;
      margin-top: 15px;
      font-size: 14px;
    }

    .text-muted a { color: red; }
  </style>
</head>
<body>
  <div class="background">
    <div class="navbar">
      <div class="navbar-title">Nonprofit Resource Management</div>
      <div class="nav-buttons">
        <a href="admin_log.php"><button class="btn">Admin</button></a>
        <a href="donor_log.php"><button class="btn">Donor</button></a>
        <a href="beneficiary_login.php"><button class="btn">Beneficiary</button></a>
      </div>
    </div>

    <div class="content">
      <form class="login-box" action="beneficiary_register.php" method="post">
        <h2>Beneficiary Registration</h2>

        <div class="form-group">
          <label for="name">Full Name</label>
          <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($name); ?>" required>
          <span><?php echo $name_err; ?></span>
        </div>

        <div class="form-group">
          <label for="email">Email</label>
          <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($email); ?>" required>
          <span><?php echo $email_err; ?></span>
        </div>

        <div class="form-group">
          <label for="password">Password</label>
          <input type="password" name="password" id="password" required>
          <span><?php echo $password_err; ?></span>
        </div>

        <div class="form-group">
          <label for="confirm_password">Confirm Password</label>
          <input type="password" name="confirm_password" id="confirm_password" required>
          <span><?php echo $confirm_password_err; ?></span>
        </div>

        <button type="submit" class="btn-submit">Register</button>

        <p class="text-muted">Already have an account? <a href="beneficiary_login.php">Login</a></p>
        <p class="text-muted">Go back to <a href="index.php">Home</a></p>
      </form>
    </div>
  </div>
</body>
</html>
