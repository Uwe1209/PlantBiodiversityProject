<!DOCTYPE html>
<!--Description: Profile page-->
<!--Author: Liew You Wee-->
<!--Date: 06/09/2024 -->
<!--Validated: OK 13/10/2024-->
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="description" content="Profile Page">
	<meta name="keywords" content="Student, ID, Name">
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

if ($user_info['type'] !== 'user') {
  echo '<div class="center-container">';
  echo "<h1>Access denied. You are not authorized to view this page.</h1>";
  echo '<a href="login.php"><button class="btn">Log In</button></a>';
  echo '</div>';
  exit();
}
?>
<body>
    <div class="container con">

      <div class="profile-header">
        <div class="profile-image-container">
            <img src="img/profile.jpg" alt="Profile Image" title="Student Image" class="profile-img">
        </div>
        <div class="profile-info">
            <h1>You Wee Liew</h1>
            <p>Student ID: 102786467</p>
            <p>Email: 102786467@students.swinburne.edu.my</p>
        </div>
      </div>

      <div class="declaration">
        <p>
          I declare that this assignment is my individual work. I have not worked collaboratively nor have I copied from any other student's work or from any other source. I have not engaged another party to complete this assignment. I am aware of the University’s policy with regards to plagiarism. I have not allowed, and will not allow, anyone to copy my work with the intention of passing it off as his or her own work.
        </p>
      </div>

      <div class="button">
        <a href="index.php"><button class="buttons">Home Page</button></a>
        <a href="about.php"><button class="buttons">About this Assignment</button></a>
      </div>

    </div>
</body>
</html>
