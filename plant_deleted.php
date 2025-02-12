<?php
session_set_cookie_params(0);
session_start();
include('config/main.php');
require 'vendor/autoload.php'; 
use Smalot\PdfParser\Parser;

$sql = "SELECT * FROM plant_table_restore ORDER BY id";
$result = mysqli_query($conn, $sql);
$deleted_plant = mysqli_fetch_all($result, MYSQLI_ASSOC);

mysqli_free_result($result);
?>

<!DOCTYPE html>
<!--Description: plant deleted page-->
<!--Author: Liew You Wee-->
<!--Date: 20/11/2024 -->
<!--Validated:-->
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="description" content="deleted plant">
    <meta name="keywords" content="plant, deleted">
    <meta name="author" content="Liew You Wee">
    <title>Deleted Plants</title>
    <link rel="stylesheet" type="text/css" href="style/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>
<body class="admin">
  <?php include('navigation_admin.php'); ?>
  <section>
    <h1>Deleted Plants</h1>
    
    <div class="admin-con">
      <div class="admin-flow">
        <table>
          <tr class="t1">
            <th>Plant Image</th>
            <th>Scientific Name</th>
            <th>Common Name</th>
            <th>Term</th>
            <th>Description</th>
            <th></th>
          </tr>
          <?php foreach ($deleted_plant as $acc){?>
          
          <tr>
            <td><img src="img/Contribute/<?php echo htmlspecialchars($acc['plants_image']); ?>" class="img"></td>
            <td><?php echo htmlspecialchars($acc['scientific_name']); ?></td>
            <td><?php echo htmlspecialchars($acc['common_name']); ?></td>
            <td>
              <?php 
                if (!empty($acc['family'])) {
                  echo "Family ";
                } elseif (!empty($acc['genus'])) {
                  echo "Genus " ;
                } elseif (!empty($acc['species'])) {
                  echo "Species" ;
                } else {
                  echo "No specific term available.";
                }
              ?>
            </td>
            <td>
              <?php 
                $descriptionText = '';
                $descriptionFilePath = $acc['description'];
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
                echo nl2br(htmlspecialchars($descriptionText)); 
              ?>
            </td>
            <td>
              <div class="admin-dropdown">
                  <a><i class="fa-solid fa-ellipsis-vertical"></i></a>
                  <div class="dropdown-content">
                      <a href="plant_restore.php?id=<?php echo htmlspecialchars($acc['id']); ?>">Restore</a>
                  </div>
              </div>
            </td>
          </tr>
        <?php }?>
        </table>
      </div>
    </div>
    
  </section>
</body>
</html>
