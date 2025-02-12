<!DOCTYPE html>
<!--Description: Main_menu page-->
<!--Author: Liew You Wee-->
<!--Date: 06/09/2024 -->
<!--Validated: OK 13/10/2024-->
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="description" content="Main Menu Page">
	<meta name="keywords" content="Plant Classification, Tutorial, Identify, Contribution">
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
  
}else{
  $email = $_SESSION['user_email'];

  $sql = "SELECT user_table.*, account_table.password, account_table.type 
        FROM user_table 
        JOIN account_table ON user_table.email = account_table.email 
        WHERE user_table.email='$email'";
$result = mysqli_query($conn, $sql);
$user_info = mysqli_fetch_assoc($result);

if ($user_info['type'] !== 'user') {
    echo '<div class="center-container">';
    echo "<h1>Access denied. You are not authorized to view this page.</h1>";
    echo '<a href="login.php"><button class="btn">Log In</button></a>';
    echo '</div>';
    exit();
}

    
  
?>
  <body>
  <?php include("navigation.php");?>
    <div class="main-con">

      <h1>Main Menu</h1>
      <p>Welcome to the Herbarium project. Choose an option below to explore or contribute.</p>

      <div class="menu-grid">
          <div class="card">
              <h2>Plant Classification</h2>
              <p>Learn about different plant families, genus, and species.</p>
              <a href="classify.php">Learn More</a>
          </div>

          <div class="card">
              <h2>Tutorial</h2>
              <p>Step-by-step guide on how to create herbarium specimens.</p>
              <a href="tutorial.php">View Tutorial</a>
          </div>

          <div class="card">
              <h2>Identify Plants</h2>
              <p>Upload photos to identify plants based on your contributions.</p>
              <a href="identify.php">Identify</a>
          </div>

          <div class="card">
            <h2>Contribute</h2>
            <p>Contribute your own plant specimen photos to the project.</p>
            <a href="contribute.php">Contribute Now</a>
          </div>
      </div>

      <div class="con_footer">
          <a href="index.php">Return to Home</a>
      </div>
    </div>
    <?php include("footer.php");?>
  </body>
<?php
}?>
</html>
