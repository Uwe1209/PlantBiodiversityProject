<?php
session_set_cookie_params(0);
session_start();
include('config/main.php');

$email = mysqli_real_escape_string($conn, $_GET['email']);

$sql = "SELECT * FROM user_table WHERE email = '$email'";
$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) > 0) {
    $user = mysqli_fetch_assoc($result);
} else {
    echo "User not found.";
    exit();
}

$errors = [];
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $dob = trim($_POST['dob']);
    $gender = trim($_POST['gender']);
    $contact_number = trim($_POST['contact_number']);
    $hometown = trim($_POST['hometown']);

    if (empty($first_name)) {
        $errors['first_name'] = "Required first name";
    }
    if (empty($last_name)) {
        $errors['last_name'] = "Required last name";
    }
    if (empty($dob)) {
        $errors['dob'] = "Required DOB";
    }
    if (empty($gender) || !in_array($gender, ['Male', 'Female', 'Other'])) {
        $errors['gender'] = "Valid gender selection is required.";
    }
    if (empty($contact_number) || !preg_match("/^\d{10,15}$/", $contact_number)) {
        $errors['contact_number'] = "Invalid number";
    }
    if (empty($hometown)) {
        $errors['hometown'] = "Required home town";
    }

    if (empty($errors)) {
        $first_name = mysqli_real_escape_string($conn, $first_name);
        $last_name = mysqli_real_escape_string($conn, $last_name);
        $dob = mysqli_real_escape_string($conn, $dob);
        $gender = mysqli_real_escape_string($conn, $gender);
        $contact_number = mysqli_real_escape_string($conn, $contact_number);
        $hometown = mysqli_real_escape_string($conn, $hometown);

        $update_sql = "UPDATE user_table SET 
            first_name = '$first_name', 
            last_name = '$last_name', 
            dob = '$dob', 
            gender = '$gender', 
            contact_number = '$contact_number', 
            hometown = '$hometown' 
            WHERE email = '$email'";

        if (mysqli_query($conn, $update_sql)) {
            header("Location: manage_accounts.php"); 
            exit();
        } else {
            echo "Error updating user: " . mysqli_error($conn);
        }
    }
}

mysqli_free_result($result);
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="description" content="Edit Account Page">
  <meta name="keywords" content="user account edit">
  <meta name="author" content="Liew You Wee">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit User Account</title>
  <link rel="stylesheet" type="text/css" href="style/style.css">
</head>
<body class="r1">

  <div class="container">
    <div class="form-box">
    <h1>Edit Account for <?php echo htmlspecialchars($user['email']); ?></h1>
    <form action="account_edit.php?email=<?php echo htmlspecialchars($user['email']); ?>" method="POST">
      
      <div class="input-group">
        <label for="first_name">First Name:</label>
        <input type="text" name="first_name" id="first_name" value="<?php echo htmlspecialchars($user['first_name']); ?>" >
        <div class="red-text"><?php echo $errors['first_name'] ?? ''; ?></div>
      </div>
      
      <div class="input-group">
        <label for="last_name">Last Name:</label>
        <input type="text" name="last_name" id="last_name" value="<?php echo htmlspecialchars($user['last_name']); ?>" >
        <div class="red-text"><?php echo $errors['last_name'] ?? ''; ?></div>
      </div>

      
      <div class="input-group">
        <label for="dob">Date of Birth:</label>
        <input type="date" name="dob" id="dob" value="<?php echo htmlspecialchars($user['dob']); ?>" >
        <div class="red-text"><?php echo $errors['dob'] ?? ''; ?></div>
      </div>
      
      
      <div class="input-group">
        <label for="gender">Gender:</label>
        <select name="gender" id="gender">
          <option value="Male" <?php if ($user['gender'] == 'Male') echo 'selected'; ?>>Male</option>
          <option value="Female" <?php if ($user['gender'] == 'Female') echo 'selected'; ?>>Female</option>
          <option value="Other" <?php if ($user['gender'] == 'Other') echo 'selected'; ?>>Other</option>
        </select>
        <div class="red-text"><?php echo $errors['gender'] ?? ''; ?></div>
      </div>
      
      
      <div class="input-group">
        <label for="contact_number">Contact Number:</label>
        <input type="text" name="contact_number" id="contact_number" value="<?php echo htmlspecialchars($user['contact_number']); ?>" >
        <div class="red-text"><?php echo $errors['contact_number'] ?? ''; ?></div>
      </div>
     
      
      <div class="input-group">
        <label for="hometown">Home Town:</label>
        <input type="text" name="hometown" id="hometown" value="<?php echo htmlspecialchars($user['hometown']); ?>" >
        <div class="red-text"><?php echo $errors['hometown'] ?? ''; ?></div>
      </div>
      
      <div class="btn-group">
        <button type="submit" class="btn">Update</button>
        <button class="btn">
          <a href="manage_accounts.php">Back</a>
        </button>
      </div>
    </form>
    </div>
  </div>
</body>
</html>
