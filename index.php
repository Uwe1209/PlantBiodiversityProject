<?php
session_set_cookie_params(0);
session_start();
include('config/main.php');
include('config/popularity.php');

$img = "";

if (isset($_SESSION['user_email'])) {
  $login = "";
  $reg = "";
} else {
  $login = "<a href='registration.php'><button class='buttons'>Register</button></a>";
  $reg = "<a href='login.php'><button class='buttons'>Login</button></a>";
}

$random = rand(0,1);
if($random == 0){
  $img="index-photo.png";
}else{
  $img = "index-photo_1.png";
}
?>

<!DOCTYPE html>
<!--Description: Home page-->
<!--Author: Liew You Wee-->
<!--Date: 06/09/2024 -->
<!--Validated: OK 13/10/2024-->
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="description" content="Home Page">
	<meta name="keywords" content="Plant Biodiversity">
	<meta name="author" content="Liew You Wee">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Herbarium for Plant Biodiversity</title>
  <link rel="stylesheet" type="text/css" href="style/style.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
</head>

<body id="index">
  <div>
    <div class="index-con">
      <div class="index-row">
        <div class="index-row-con">
          <h1>Herbarium Project</h1>
          <p>Discover the rich diversity of plant species around the world. Join us in preserving plant biodiversity by contributing your own herbarium specimen photos.</p>
          <div class="button">
            <?php echo"$login"?>
            <?php echo"$reg"?>
          </div>
          <p class="rotated-text"><a href="about.php">About>></a></p>
        </div>
        <div>
          <img src="img/<?php echo "$img"?>" alt="herbarium" title="Herbarium">
        </div>
      </div>
      <div class="index-con_footer">
          <a href="main_menu.php">Go to Main Menu>></a>
      </div>
    </div>
  </div>
</body>
</html>
