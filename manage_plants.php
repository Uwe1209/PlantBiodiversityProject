<?php
session_set_cookie_params(0);
session_start();
include('config/main.php');
require 'vendor/autoload.php'; 

use Smalot\PdfParser\Parser;
$sort_by = isset($_GET['sort_by']) ? $_GET['sort_by'] : '';
$order = isset($_GET['order']) ? $_GET['order'] : 'ASC';
$group_by = isset($_GET['group_by']) ? $_GET['group_by'] : '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && isset($_POST['plant_id'])) {
  $plant_id = mysqli_real_escape_string($conn, $_POST['plant_id']);
  $action = $_POST['action'];

  $reset_sql = "UPDATE plant_table SET approve = '', reject = '' WHERE id = '$plant_id'";
  mysqli_query($conn, $reset_sql);

  if ($action === 'approve') {
      $sql = "UPDATE plant_table SET approve = 'approve' WHERE id = '$plant_id'";
  } elseif ($action === 'reject') {
      $sql = "UPDATE plant_table SET reject = 'reject' WHERE id = '$plant_id'";
  }
  mysqli_query($conn, $sql);
}

$sql = "SELECT * FROM plant_table";

if ($group_by) {
  $sql .= " WHERE $group_by IS NOT NULL AND $group_by != ''";
  $sql .= " GROUP BY $group_by";
}

if ($sort_by) {
  $sql .= " ORDER BY $sort_by $order";
}

$result = mysqli_query($conn, $sql);
$account = mysqli_fetch_all($result, MYSQLI_ASSOC);

mysqli_free_result($result);
?>
<!DOCTYPE html>
<!--Description: Plant Manage page-->
<!--Author: Liew You Wee-->
<!--Date: 03/11/2024 -->
<!--Validated: -->
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="description" content="Plant Manage Page">
  <meta name="keywords" content="plant">
  <meta name="author" content="Liew You Wee">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Herbarium for Plant Biodiversity</title>
  <link rel="stylesheet" type="text/css" href="style/style.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>
<body class="admin">
<?php
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
?>
  <?php include('navigation_admin.php');?>
  <section>
    <h1>Plant's Management Dashboard</h1>

    <form method="GET" action="manage_plants.php">
      <div class="sort_con">
        <label for="sort_by">Sort by:</label>
        <select name="sort_by" id="sort_by">
            <option value="" <?php if ($sort_by == '') echo 'selected'; ?>>None</option>
            <option value="scientific_name" <?php if ($sort_by == 'scientific_name') echo 'selected'; ?>>Scientific Name</option>
            <option value="common_name" <?php if ($sort_by == 'common_name') echo 'selected'; ?>>Common Name</option>
        </select>

        <select name="order" id="order">
            <option value="ASC" <?php if ($order == 'ASC') echo 'selected'; ?>>Ascending</option>
            <option value="DESC" <?php if ($order == 'DESC') echo 'selected'; ?>>Descending</option>
        </select>

        <label for="group_by">Group by:</label>
        <select name="group_by" id="group_by">
            <option value="" <?php if ($group_by == '') echo 'selected'; ?>>None</option>
            <option value="scientific_name" <?php if ($group_by == 'scientific_name') echo 'selected'; ?>>Name</option>
            <option value="family" <?php if ($group_by == 'family') echo 'selected'; ?>>Family</option>
            <option value="genus" <?php if ($group_by == 'genus') echo 'selected'; ?>>Genus</option>
            <option value="species" <?php if ($group_by == 'species') echo 'selected'; ?>>Species</option>
        </select>

        <button type="submit" class="refresh_btn">Refresh</button>
      </div> 
    </form>

    <div class="admin-con">
      <div class="admin-flow">
      <table>
          <tr>
            <th>Plant Image</th>
            <th>Scientific Name</th>
            <th>Common Name</th>
            <th>Term</th>
            <th>Description</th>
            <th>Approve/Reject</th>
            <th><a href="plant_new.php">New</a></th>
          </tr>
          <div>
        <?php foreach ($account as $acc){?>
          
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
            <td class="butn-t">
              <form method="POST" action="manage_plants.php">
                <input type="hidden" name="plant_id" value="<?php echo $acc['id']; ?>">
                <input type="hidden" name="action" value="approve">
                <button type="submit" class="<?php echo ($acc['approve'] === 'approve') ? 'g_approve' : (($acc['reject'] === 'reject') ? 'default_btn' : 'g_approve'); ?>">Approve</button>
              </form>

              <form method="POST" action="manage_plants.php">
                <input type="hidden" name="plant_id" value="<?php echo $acc['id']; ?>">
                <input type="hidden" name="action" value="reject">
                <button type="submit" class="<?php echo ($acc['reject'] === 'reject') ? 'r_reject' : (($acc['approve'] === 'approve') ? 'default_btn' : 'r_reject'); ?>">Reject</button>
              </form>
            </td>
            <td>
              <div class="admin-dropdown">
                <a><i class="fa-solid fa-ellipsis-vertical"></i></a>
                <div class="dropdown-content">
                    <a href="plant_edit.php?id=<?php echo htmlspecialchars($acc['id']); ?>">Edit</a>
                    <a href="plant_delete.php?id=<?php echo htmlspecialchars($acc['id']); ?>">Delete</a>
                </div>
              </div>
            </td>
          </tr>
        <?php }?>
          </div>
      </table>
      </div>
    </div>
  </section>

</body>
</html>
