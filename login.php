<?php
session_set_cookie_params(0);
session_start();
include('config/main.php');

$sql = 'SELECT email, password, type FROM account_table';
$result = mysqli_query($conn, $sql);
$account = mysqli_fetch_all($result, MYSQLI_ASSOC);

$email = $pwd = '';
$errors = array('wrong' => '');

if (isset($_POST['submit'])) {
    $email = $_POST['email'];
    $pwd = $_POST['pwd'];
    $email_found = false;

    foreach ($account as $acc) {
        if ($acc['email'] == $email) {
            $email_found = true; // Email exists

            if (password_verify($pwd, $acc['password']) && $acc['type'] == "user") {
                $_SESSION['user_email'] = $email;
                header('Location: main_menu.php');
                exit();
            }elseif ($pwd == $acc['password'] && $acc['type'] == "admin"){
                $_SESSION['user_email'] = $email;
                header('Location: main_menu_admin.php');
                exit();
            }else {
                $errors['wrong'] = 'Login failed. Undefined password. Please try again';
            }
            break;
        }
    }

    if (!$email_found) {
        $errors['wrong'] = 'Login failed. Undefined email. Please try again';
    }
}


mysqli_free_result($result);
mysqli_close($conn);
?>

<!DOCTYPE html>
<!--Description: Login page-->
<!--Author: Liew You Wee-->
<!--Date: 09/09/2024 -->
<!--Validated: OK 13/10/2024-->
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="description" content="Login Page">
	<meta name="keywords" content="Name, password">
    <meta name="author" content="Liew You Wee">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Herbarium for Plant Biodiversity</title>
  <link rel="stylesheet" type="text/css" href="style/style.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body class="l0">
<div class="login-container">

    <div class="login-form">
        <h2>Welcome Back</h2>
            <p>Get started on your projects.</p>
                <form action="login.php" method="POST">

                    <div class="input-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" placeholder="Enter your email" value="<?php echo htmlspecialchars($email)?>">
                        </div>

                        <div class="input-group">
                            <label for="pwd">Password</label>
                            <input type="password" id="pwd" name="pwd" placeholder="Enter your password" value="<?php echo htmlspecialchars($pwd)?>">
                        </div>

                        <button type="submit" name="submit" value="submit" class="btn">Sign In</button>

                        <div class="extra-links">
                            <a href="#">Forgot Password?</a>
                            <p>Don’t have an account? <a href="registration.php">Sign up</a></p>
                        </div>
                        <div class="red-texts"><?php echo $errors['wrong']?></div>
                    </form>
                </div>

                <div class="login-image">
                <!--https://pikwizard.com/most-popular/plant-wallpaper-hd/ -->
                    <img src="img/plant.jpg" alt="Plant login">
                </div>
        </div>  
</body>
</html>