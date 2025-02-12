<!DOCTYPE html>
<!--Description: Update profile page-->
<!--Author: Liew You Wee-->
<!--Date: 29/09/2024 -->
<!--Validated: OK 13/10/2024-->
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="description" content="Update Profile Page">
<meta name="keywords" content="update profile">
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

$errors = array('fnam'=>'', 'lnam'=>'', 'email'=>'', 'phone'=>'', 'home'=>'', 'dob'=>'', 'pwd'=>'', 'file'=>'');
$profile_image = ""; 

$file_path = "data/user.txt";

    if (file_exists($file_path)) {
        $file = fopen($file_path, "r");
        $user_info1 = [];
        $email_found = false;

        while (($line = fgets($file)) !== false) {
            $user_data_parts = explode("|", $line);

            foreach ($user_data_parts as $part) {
                list($key, $value) = explode(":", $part);
                $user_info1[trim($key)] = trim($value);
            }

            if ($user_info1['Email'] == $_SESSION['user_email']) {
                $email_found = true;
                break;
            }
        }
        
        fclose($file);

        if (!$email_found) {
            echo "User data not found.";
            exit;
        }
    } else {
        echo "User data file not found.";
        exit;
    }

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


$photo = $user_info['profile_image'];
if (!empty($photo) && file_exists("img/ProfileImg/" . $photo)) {
    $profile_image = "img/ProfileImg/" . $photo; 
} else {
    $gender = strtolower($user_info['gender']); 
    if ($gender == 'male') {
        $profile_image = "img/ProfilePictures/male.jpg";
    } elseif ($gender == 'female') {
        $profile_image = "img/ProfilePictures/female.png";
    } else {
        $profile_image = "img/ProfilePictures/other.png";
    }
}

if (isset($_POST['update'])) {
    $first_name = $_POST['fnam'];
    $last_name = $_POST['lnam'];
    $phoneNum = $_POST['phone'];
    $town = $_POST['home'];
    $dob = $_POST['dob'];
    $gender = $_POST['gender'];
    $pwd = $_POST['pwd'];
    $new_email = $_POST['email'];
    $new_password_hash = $user_info['password']; 

    if (empty($first_name) || !preg_match('/^[A-Za-z]+$/', $first_name)) {
        $errors['fnam'] = 'First name required and must contain only letters.';
    }

    if (empty($last_name) || !preg_match('/^[A-Za-z]+$/', $last_name)) {
        $errors['lnam'] = 'Last name required and must contain only letters.';
    }

    if (empty($phoneNum) || !preg_match('/^\d{10,11}$/', $phoneNum)) {
        $errors['phone'] = 'Valid phone number required.';
    }

    if (empty($new_email) || !filter_var($new_email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Valid email required.';
    } elseif ($new_email !== $old_email) {
        $email_check_sql = "SELECT * FROM user_table WHERE email='$new_email'";
        $email_check_result = mysqli_query($conn, $email_check_sql);
        if (mysqli_num_rows($email_check_result) > 0) {
            $errors['email'] = 'Email is already in use.';
        }
    }

    if (empty($dob)) {
        $errors['dob'] = 'Date of Birth is required.';
    }

    
    if (!empty($pwd)) {  
        if (strlen($pwd) < 8 || !preg_match("/[0-9]/", $pwd) || !preg_match("/[^\\w]/", $pwd)) {
            $errors['pwd'] = 'Password must be at least 8 characters with at least 1 number and 1 symbol.';
        } else {
            $new_password_hash = password_hash($pwd, PASSWORD_BCRYPT); 
        }
    }

    if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
        $target_dir = "img/ProfileImg/";
        $photo = basename($_FILES['file']['name']);
        $target_file = $target_dir . $photo;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        if ($_FILES['file']['size'] > 5 * 1024 * 1024) {
            $errors['file'] = 'File size exceeds 5MB limit.';
        } elseif (!in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
            $errors['file'] = 'Only JPG, JPEG, PNG, and GIF files are allowed.';
        } elseif (!move_uploaded_file($_FILES['file']['tmp_name'], $target_file)) {
            $errors['file'] = 'Failed to upload image.';
        } else {
            $profile_image = $target_file; 
        }
    }

    
    if (!array_filter($errors)) {
        $sql1 = "UPDATE user_table SET first_name='$first_name', last_name='$last_name', contact_number='$phoneNum', hometown='$town', dob='$dob', gender='$gender', profile_image='$photo', email='$new_email' WHERE email='$old_email'";

        
        if ($new_password_hash !== $user_info['password']) {  
            $sql2 = "UPDATE account_table SET password='$new_password_hash', email='$new_email' WHERE email='$old_email'";
            mysqli_query($conn, $sql2);
        }

        if (mysqli_query($conn, $sql1)) {
            
            $file_path = "data/user.txt";
            $text_file_password = !empty($pwd) ? $pwd : $user_info['password']; 

            $updated_data = "First Name:$first_name|Last Name:$last_name|DOB:$dob|Phone:$phoneNum|Gender:$gender|Email:$new_email|Hometown:$town|Password:$text_file_password\n";

            $file_lines = file($file_path);
            $file = fopen($file_path, "w");

            foreach ($file_lines as $line) {
                $user_data_parts = explode("|", $line);
                $current_email = explode(":", $user_data_parts[5])[1] ?? ''; 

                if (trim($current_email) == $old_email) {
                    fwrite($file, $updated_data); 
                } else {
                    fwrite($file, $line); 
                }
            }

            fclose($file);

            $_SESSION['user_email'] = $new_email;
            header('Location: main_menu.php');
            exit();
        } else {
            echo "Error updating record: " . mysqli_error($conn);
        }
    }
}

if (isset($_POST['cancel'])) {
    header("Location: main_menu.php");
    exit;
}

?>

<body class="r0">
<div class="container">
    <h1>Update Profile</h1>
    <img src="<?php echo $profile_image; ?>" alt="Profile Image" width="150" height="150">

    <form method="POST" action="update_profile.php" enctype="multipart/form-data" class="upd_f">
        <div class="input-group int-grp">
            <label>First Name: </label>
            <input type="text" name="fnam" value="<?php echo htmlspecialchars($user_info['first_name']); ?>">
            <div class="red-text"><?php echo $errors['fnam']; ?></div>
        </div>

        <div class="input-group int-grp">
            <label>Last Name: </label>
            <input type="text" name="lnam" value="<?php echo htmlspecialchars($user_info['last_name']); ?>">
            <div class="red-text"><?php echo $errors['lnam']; ?></div>
        </div>

        <div class="input-group int-grp">
            <label>Phone: </label>
            <input type="text" name="phone" value="<?php echo htmlspecialchars($user_info['contact_number']); ?>">
            <div class="red-text"><?php echo $errors['phone']; ?></div>
        </div>

        <div class="input-group int-grp">
            <label>Date of Birth: </label>
            <input type="date" name="dob" value="<?php echo htmlspecialchars($user_info['dob']); ?>">
            <div class="red-text"><?php echo $errors['dob']; ?></div>
        </div>

        <div class="input-group int-grp">
            <label>Gender: </label>
            <select name="gender">
                <option value="Male" <?php echo ($user_info['gender'] == 'Male') ? 'selected' : ''; ?>>Male</option>
                <option value="Female" <?php echo ($user_info['gender'] == 'Female') ? 'selected' : ''; ?>>Female</option>
                <option value="Other" <?php echo ($user_info['gender'] == 'Other') ? 'selected' : ''; ?>>Other</option>
            </select>
        </div>

        <div class="input-group int-grp">
            <label>Email: </label>
            <input type="email" name="email" value="<?php echo htmlspecialchars($user_info['email']); ?>">
            <div class="red-text"><?php echo $errors['email']; ?></div>
        </div>

        <div class="input-group int-grp">
            <label>Hometown: </label>
            <input type="text" name="home" value="<?php echo htmlspecialchars($user_info['hometown']); ?>">
            <div class="red-text"><?php echo $errors['home']; ?></div>
        </div>

        <div class="input-group int-grp">
            <label>Password: </label>
            <input type="text" name="pwd" value="<?php echo $user_info1['Password']?>">
            <div class="red-text"><?php echo $errors['pwd']; ?></div>
        </div>

        <div class="input-group int-grp">
            <label>Profile Picture</label>
            <input type="file" name="file">
            <div class="red-text"><?php echo $errors['file']; ?></div>
        </div>

        <div class="btn-group">
                <input type="submit" name="update" value="Update" class="btn">
                <input type="submit" name="cancel" value="Cancel" class="btn">
        </div>
    </form>
</div>
</body>
</html>
