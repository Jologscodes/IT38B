<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Nonprofit Resource Management</title>
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
      background-color:rgb(232, 32, 32);
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
      align-items: center;
      justify-content: center;
      flex-direction: column;
      color: white;
      text-align: center;
      text-shadow: 1px 1px 3px rgba(0,0,0,0.7);
    }

    .center-image {
      width: 300px;
      height: 300px;
      object-fit: cover;
      border-radius: 50%;
      box-shadow: 0 0 20px rgba(0,0,0,0.5);
    }

    @media (max-width: 768px) {
      .navbar {
        flex-direction: column;
        align-items: flex-start;
      }

      .nav-buttons {
        margin-top: 10px;
      }

      .nav-buttons a {
        display: inline-block;
        margin: 5px 0;
      }

      .center-image {
        width: 200px;
        height: 200px;
      }
    }
  </style>
</head>
<body>

  <div class="background">
    <div class="navbar">
      <div class="navbar-title">Nonprofit Resource Management</div>
      <div class="nav-buttons">
        <a href="./door/admin_log.php"><button class="btn">Admin</button></a>
        <a href="./door/donor_log.php"><button class="btn">Donor</button></a>
        <a href="beneficiary_login.php"><button class="btn">Beneficiary</button></a>
      </div>
    </div>

    <div class="content">
      <img src="./image/bg.jpg" alt="Center Image" class="center-image">
    </div>
  </div>

</body>
</html>
