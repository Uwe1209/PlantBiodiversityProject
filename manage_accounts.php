<?php
session_set_cookie_params(0);
session_start();
include('config/main.php');

$sort_by = isset($_GET['sort_by']) ? $_GET['sort_by'] : '';
$order = isset($_GET['order']) ? $_GET['order'] : 'ASC';
$group_by = isset($_GET['group_by']) ? $_GET['group_by'] : '';

$sql = "SELECT * FROM user_table WHERE email != 'admin@swin.edu.my'";

if ($group_by) {
    $sql .= " GROUP BY $group_by";
}

if ($sort_by === 'gender_male_first') {
    $sql .= " ORDER BY CASE WHEN gender = 'Male' THEN 1 ELSE 2 END, first_name $order";
} elseif ($sort_by === 'gender_female_first') {
    $sql .= " ORDER BY CASE WHEN gender = 'Female' THEN 1 ELSE 2 END, first_name $order";
} elseif ($sort_by) {
    $sql .= " ORDER BY $sort_by $order";
}

$result = mysqli_query($conn, $sql);
$account = mysqli_fetch_all($result, MYSQLI_ASSOC);

mysqli_free_result($result);
?>
<!DOCTYPE html>
<!--Description: Account Manage page-->
<!--Author: Liew You Wee-->
<!--Date: 03/11/2024 -->
<!--Validated: -->
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="description" content="Account Manage Page">
  <meta name="keywords" content="user account">
  <meta name="author" content="Liew You Wee">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Herbarium for Plant Biodiversity</title>
  <link rel="stylesheet" type="text/css" href="style/style.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>
<body class="admin">
  <?php if (!isset($_SESSION['user_email'])) {
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
}?>
  <?php include('navigation_admin.php');?>
  <section>
    <h1>Account's Management Dashboard</h1>

    <form method="GET" action="manage_accounts.php">
      <div class="sort_con">
        <label for="sort_by">Sort by:</label>
        <select name="sort_by" id="sort_by">
            <option value="" <?php if ($sort_by == '') echo 'selected'; ?>>None</option>
            <option value="first_name" <?php if ($sort_by == 'first_name') echo 'selected'; ?>>First Name</option>
            <option value="email" <?php if ($sort_by == 'email') echo 'selected'; ?>>Email</option>
            <option value="gender_male_first" <?php if ($sort_by == 'gender_male_first') echo 'selected'; ?>>Male</option>
            <option value="gender_female_first" <?php if ($sort_by == 'gender_female_first') echo 'selected'; ?>>Female</option>
        </select>
        <select name="order" id="order">
            <option value="ASC" <?php if ($order == 'ASC') echo 'selected'; ?>>Ascending</option>
            <option value="DESC" <?php if ($order == 'DESC') echo 'selected'; ?>>Descending</option>
        </select>
        <label for="group_by">Group by:</label>
        <select name="group_by" id="group_by">
            <option value="" <?php if ($group_by == '') echo 'selected'; ?>>None</option>
            <option value="first_name" <?php if ($group_by == 'first_name') echo 'selected'; ?>>Name</option>
            <option value="email" <?php if ($group_by == 'email') echo 'selected'; ?>>Email</option>
            <option value="gender" <?php if ($group_by == 'gender') echo 'selected'; ?>>Gender</option>
        </select>
        <button type="submit" class="refresh_btn">Refresh</button>
      </div> 
    </form>

    <div class="admin-con">
      <div class="admin-flow">
        <table>
          <tr>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Email</th>
            <th>DOB</th>
            <th>Gender</th>
            <th>Contact Number</th>
            <th>Home Town</th>
            <th><a href="account_new.php">New</a></th>
          </tr>

          <?php foreach ($account as $acc){?>
          <tr>
            <td><?php echo htmlspecialchars($acc['first_name']); ?></td>
            <td><?php echo htmlspecialchars($acc['last_name']); ?></td>
            <td><?php echo htmlspecialchars($acc['email']); ?></td>
            <td><?php echo htmlspecialchars($acc['dob']); ?></td>
            <td><?php echo htmlspecialchars($acc['gender']); ?></td>
            <td><?php echo htmlspecialchars($acc['contact_number']); ?></td>
            <td><?php echo htmlspecialchars($acc['hometown']); ?></td>
            <td>
              <div class="admin-dropdown">
                <a><i class="fa-solid fa-ellipsis-vertical"></i></a>
                <div class="dropdown-content">
                    <a href="account_edit.php?email=<?php echo htmlspecialchars($acc['email']); ?>">Edit</a>
                    <a href="account_delete.php?email=<?php echo htmlspecialchars($acc['email']); ?>">Delete</a>
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
