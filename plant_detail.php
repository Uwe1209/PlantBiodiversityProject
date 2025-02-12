<!DOCTYPE html>
<!--Description: Plant Detail Page-->
<!--Author: Liew You Wee-->
<!--Date: 26/09/2024 -->
<!--Validated: OK 13/10/2024-->
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="description" content="Plant Detail Page">
  <meta name="keywords" content="Plant Biodiversity">
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
require 'vendor/autoload.php'; 
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

use Smalot\PdfParser\Parser;

if (isset($_GET['id'])) {
    $plant_id = mysqli_real_escape_string($conn, $_GET['id']);

    $sql = "SELECT * FROM plant_table WHERE id = '$plant_id'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $plant = mysqli_fetch_assoc($result);
    } else {
        echo "Plant not found!";
        exit();
    }

    mysqli_free_result($result);
    mysqli_close($conn);

    $descriptionText = '';
    if (!empty($plant['description'])) {
        $descriptionFilePath = $plant['description'];
        if (file_exists($descriptionFilePath)) {
            try {
                $parser = new Parser();
                $pdf = $parser->parseFile($descriptionFilePath);
                $descriptionText = $pdf->getText();
            } catch (Exception $e) {
                $descriptionText = "Error reading the PDF file: " . $e->getMessage();
            }
        } else {
            $descriptionText = "Description file not found.";
        }
    }
} else {
    header('Location: contribute.php');
    exit();
}
?>
<body>
    <div class="container1">
        <?php if ($plant): ?>
          <h1><?php echo htmlspecialchars($plant['scientific_name']); ?></h1>
          <img src="img/Contribute/<?php echo htmlspecialchars($plant['plants_image']); ?>" alt="<?php echo htmlspecialchars($plant['scientific_name']); ?>" style="max-width: 500px;">
          <div class="plant-detail-con">
            <p><strong>Common Name:</strong> <?php echo htmlspecialchars($plant['common_name']); ?></p>
            
            <p><strong>Term:</strong> 
                <?php 
                    if (!empty($plant['family'])) {
                        echo "Family ";
                    } elseif (!empty($plant['genus'])) {
                        echo "Genus " ;
                    } elseif (!empty($plant['species'])) {
                        echo "Species" ;
                    } else {
                        echo "No specific term available.";
                    }
                ?>
            </p>
          </div>

          <?php if (!empty($descriptionText)): ?>
              <h2>Description:</h2>
              <p><?php echo nl2br(htmlspecialchars($descriptionText)); ?></p>
          <?php endif; ?>

          <a href="download_pdf.php?id=<?php echo htmlspecialchars($plant['id']); ?>" class="btn">Download PDF</a>

          <a href="contribute.php" class="btn">Back to Contributions</a>
      <?php else: ?>
          <p>Plant not found.</p>
      <?php endif; ?>
    </div>
</body>
</html>
