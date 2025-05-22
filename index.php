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
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: linear-gradient(135deg, #1e3c72, #2a5298);
    color: #f0f0f0;
  }

  .background {
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
    background: rgba(0, 0, 50, 0.6);
    backdrop-filter: blur(5px);
    box-shadow: 0 2px 10px rgba(0,0,0,0.2);
  }

  .navbar-title {
    font-size: 1.8rem;
    font-weight: bold;
    color: #ffffff;
  }

  .nav-buttons a {
    text-decoration: none;
    margin-left: 15px;
    color: #f0f0f0;
    font-weight: 500;
    transition: color 0.3s;
  }

  .nav-buttons a:hover {
    color: #66ccff;
  }

  .btn {
    padding: 10px 20px;
    font-size: 1rem;
    border: none;
    border-radius: 8px;
    background-color: #3399ff;
    color: white;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
  }

  .btn:hover {
    background-color: #007acc;
    transform: translateY(-2px);
  }

  .content {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
    text-align: center;
    text-shadow: 1px 1px 5px rgba(0,0,0,0.8);
  }

  .center-image {
    width: 300px;
    height: 300px;
    object-fit: cover;
    border-radius: 50%;
    box-shadow: 0 0 30px rgba(0,0,0,0.5);
    transition: transform 0.3s ease;
  }

  .center-image:hover {
    transform: scale(1.05);
  }

  @media (max-width: 768px) {
    .navbar {
      flex-direction: column;
      align-items: flex-start;
    }

    .nav-buttons {
      margin-top: 10px;
      display: flex;
      flex-wrap: wrap;
    }

    .nav-buttons a {
      margin: 5px 10px 0 0;
    }

    .center-image {
      width: 200px;
      height: 200px;
    }

    .navbar-title {
      font-size: 1.5rem;
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
        <a href="./door/beneficiary_login.php"><button class="btn">Beneficiary</button></a>
      </div>
    </div>

    <div class="content">
      <img src="./image/8620f2_b3b5145a6e3843c4a663bb57a0a785da~mv2.png" alt="Center Image" class="center-image">
    </div>
  </div>

</body>
</html>
