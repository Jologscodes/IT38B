<?php
session_start();
require_once '../data/config.php'; 
$email = $password = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($email)) {
        $error = "Please enter your email.";
    } elseif (empty($password)) {
        $error = "Please enter your password.";
    } else {
        $sql = "SELECT id, name, password FROM donors WHERE email = :email LIMIT 1";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();

        if ($stmt->rowCount() == 1) {
            $donor = $stmt->fetch(PDO::FETCH_ASSOC);
            if (password_verify($password, $donor['password'])) {
                $_SESSION['donor_id'] = $donor['id'];
                $_SESSION['donor_name'] = $donor['name'];
                header("Location: ./donor/donor_dashboard.php");
                exit();
            } else {
                $error = "Invalid password.";
            }
        } else {
            $error = "No donor found with that email.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Donor Login - Nonprofit Resource Management</title>
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
    background-color: #e0f0ff; 
    height: 100vh;
    display: flex;
    flex-direction: column;
  }

  .navbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem 2rem;
    background-color: rgba(0, 51, 102, 0.85); 
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
    background-color: #0077cc; 
    color: white;
    cursor: pointer;
    transition: background-color 0.3s;
  }

  .btn:hover {
    background-color: #005fa3; 
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
    color: #005fa3;
  }

  .form-group {
    margin-bottom: 15px;
  }

  .form-group label {
    display: block;
    font-weight: bold;
    color: #005fa3;
  }

  .form-group input {
    width: 100%;
    padding: 10px;
    margin-top: 5px;
    border-radius: 5px;
    border: 1px solid #ccc;
  }

  .error-msg {
    color: #004080;
    margin-bottom: 15px;
    text-align: center;
    font-weight: bold;
  }

  .btn-submit {
    width: 100%;
    padding: 10px;
    border: none;
    background: #0077cc;
    color: white;
    font-weight: bold;
    border-radius: 5px;
    cursor: pointer;
    margin-top: 10px;
    transition: background-color 0.3s;
  }

  .btn-submit:hover {
    background-color: #005fa3;
  }

  .text-muted {
    text-align: center;
    margin-top: 15px;
    font-size: 14px;
  }

  .text-muted a {
    color: #005fa3;
    text-decoration: none;
  }

  .text-muted a:hover {
    text-decoration: underline;
  }
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
      <div style="display: flex; align-items: center; gap: 60px; flex-wrap: wrap; justify-content: center;">
        <img src="../image/8620f2_b3b5145a6e3843c4a663bb57a0a785da~mv2.png" alt="Donor Image" 
          style="width: 500px; height: 500px; border-radius: 50%; object-fit: cover; box-shadow: 0 0 30px rgba(0,0,0,0.7); flex-shrink: 0;">

        <form class="login-box" action="" method="post" style="flex: 1; min-width: 320px; max-width: 450px;">
          <h2>Donor Login</h2>

          <?php if ($error): ?>
            <div class="error-msg"><?php echo htmlspecialchars($error); ?></div>
          <?php endif; ?>

          <div class="form-group">
            <label for="email">Email</label>
            <input 
              type="email" 
              name="email" 
              id="email" 
              value="<?php echo htmlspecialchars($email); ?>" 
              required 
            />
          </div>

          <div class="form-group">
            <label for="password">Password</label>
            <input type="password" name="password" id="password" required />
          </div>

          <button type="submit" class="btn-submit">Login</button>

          <p class="text-muted">Don't have an account? <a href="donor_register.php">Register</a></p>
          <p class="text-muted">Go back to <a href="../index.php">Home</a></p>
        </form>
      </div>
    </div>
  </div>
</body>
</html>
