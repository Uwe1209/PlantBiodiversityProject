<!DOCTYPE html>
<!--Description: Main_menu_admin page-->
<!--Author: Liew You Wee-->
<!--Date: 27/10/2024 -->
<!--Validated: OK-->
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="description" content="Main Menu Admin Page">
	<meta name="keywords" content="user account, manage plant">
	<meta name="author" content="Liew You Wee">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Herbarium for Plant Biodiversity</title>
  <link rel="stylesheet" type="text/css" href="style/style.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<?php
session_set_cookie_params(0);
session_start();
include('config/main.php');

  if (!isset($_SESSION['user_email'])) {
    echo '<div class="center-container">';
    echo "<h1>Please log in first.</h1>";
    echo '<a href="login.php"><button class="btn">Log In</button></a>';
    echo '</div>';
    exit();
  }

  $old_email = $_SESSION['user_email'];


  $sql = "SELECT user_table.*, account_table.password, account_table.type 
        FROM user_table 
        JOIN account_table ON user_table.email = account_table.email 
        WHERE user_table.email='$old_email'";
  $result = mysqli_query($conn, $sql);
  $user_info = mysqli_fetch_assoc($result);

  if ($user_info['type'] !== 'admin') {
    echo '<div class="center-container">';
    echo "<h1>Access denied. You are not authorized to view this page.</h1>";
    echo '<a href="login.php"><button class="btn">Log In</button></a>';
    echo '</div>';
    exit();
  }

  $sql1 = "SELECT COUNT(*) AS total_records1 FROM plant_table";
  $result1 = mysqli_query($conn, $sql1);

  if ($result1) {
    $data = mysqli_fetch_assoc($result1);
    $total_records1 = $data['total_records1'];
  } else {
    $total_records1 = 0; 
  }

  $sql2 = "SELECT COUNT(*) AS total_records2 FROM account_table";
  $result2 = mysqli_query($conn, $sql2);

  if ($result2) {
    $data = mysqli_fetch_assoc($result2);
    $total_records2 = $data['total_records2'];
  } else {
    $total_records2 = 0; 
  }

  $sql3 = "SELECT COUNT(*) AS total_records3 FROM user_table_restore";
  $result3 = mysqli_query($conn, $sql3);

  if ($result3) {
    $data = mysqli_fetch_assoc($result3);
    $total_records3 = $data['total_records3'];
  } else {
    $total_records3 = 0; 
  }

  $sql4 = "SELECT COUNT(*) AS total_records4 FROM plant_table_restore";
  $result4 = mysqli_query($conn, $sql4);

  if ($result4) {
    $data = mysqli_fetch_assoc($result4);
    $total_records4 = $data['total_records4'];
  } else {
    $total_records4 = 0; 
  }


?>

<body class="admin">
  <?php include('navigation_admin.php');?>
  <section>
    <h1>Admin Dashboard</h1>
    <div class="admin-con">
      <div class="div">
        <div class="admin-row">
          <div class="admin-manage_account">
            <div>
              <img src="img/account.png" alt="account">
            </div>
            <div>
              <p><?php echo $total_records2?></p>
              <p>Account's Manage</p>
            </div>
          </div>
          <div class="admin-manage_detail">
           <a href="manage_accounts.php">Click >></a>
          </div>
        </div>

        <div class="admin-row">
          <div class="admin-manage_account">
            <div>
              <img src="img/account_deleted.png" alt="account_deleted">
            </div>
            <div>
              <p><?php echo $total_records3?></p>
              <p>Account's Deleted</p>
            </div>
          </div>
          <div class="admin-manage_detail">
           <a href="account_deleted.php">Click >></a>
          </div>
        </div>

        <div class="admin-row">
          <div class="admin-manage_account">
            <div>
              <img src="img/plant.png" alt="account">
            </div>
            <div>
              <p><?php echo $total_records1?></p>
              <p>Plant's Manage</p>
            </div>
          </div>
          <div class="admin-manage_detail">
           <a href="manage_plants.php">Click >></a>
          </div>
        </div>

        <div class="admin-row">
          <div class="admin-manage_account">
            <div>
              <img src="img/plant_deleted.png" alt="account">
            </div>
            <div>
              <p><?php echo $total_records4?></p>
              <p>Plant's Deleted</p>
            </div>
          </div>
          <div class="admin-manage_detail">
           <a href="plant_deleted.php">Click >></a>
          </div>
        </div>
      </div>
      
      </div>
    </div>
  </section>
</body>
</html>