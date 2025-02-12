<?php
session_set_cookie_params(0);
session_start();
include('config/main.php');

$sql = "SELECT * FROM user_table_restore ORDER BY email";
$result = mysqli_query($conn, $sql);
$deleted_accounts = mysqli_fetch_all($result, MYSQLI_ASSOC);

mysqli_free_result($result);
?>

<!DOCTYPE html>
<!--Description: account deleted page-->
<!--Author: Liew You Wee-->
<!--Date: 03/11/2024 -->
<!--Validated:-->
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="description" content="deleted accounts">
    <meta name="keywords" content="account, deleted">
    <meta name="author" content="Liew You Wee">
    <title>Deleted Accounts</title>
    <link rel="stylesheet" type="text/css" href="style/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>
<body class="admin">
  <?php include('navigation_admin.php'); ?>
  <section>
    <h1>Deleted Accounts</h1>
    
    <div class="admin-con">
      <div class="admin-flow">
        <table>
          <tr class="t1">
            <th>First Name</th>
            <th>Last Name</th>
            <th>Email</th>
            <th>DOB</th>
            <th>Gender</th>
            <th>Contact Number</th>
            <th>Home Town</th>
            <th></th>
          </tr>
          <?php foreach ($deleted_accounts as $acc) { ?>
          <tr class="t2">
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
                      <a href="account_restore.php?email=<?php echo htmlspecialchars($acc['email']); ?>">Restore</a>
                  </div>
              </div>
            </td>
          </tr>
          <?php } ?>
        </table>
      </div>
    </div>
    
  </section>
</body>
</html>
