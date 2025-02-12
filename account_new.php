<?php
session_set_cookie_params(0);
session_start();
include('config/main.php');

$first_name = $last_name = $phoneNum = $email = $town = $dob = $pwd = '';
$errors = array('fnam'=>'', 'lnam'=>'', 'email'=>'','phone'=>'', 'home'=>'', 'dob'=>'', 'pwd'=>'', 'cpwd'=>'', 'exist' => '');

if(isset($_POST['back'])){
  header('location: manage_accounts.php');
}

if(isset($_POST['submitbtn'])){

  //check first name
  if(empty($_POST['fnam'])){
    $errors['fnam'] = 'Required first name <br>';
  }else{
    $first_name = $_POST['fnam'];
    if (!preg_match('/[A-Za-z]+$/', $first_name)){
      $errors['fnam'] = 'Only alphabet allow and no spacing <br>';
    }
  }

  //check last name
  if(empty($_POST['lnam'])){
    $errors['lnam'] = 'Required last name <br>';
  }else{
    $last_name = $_POST['lnam'];
    if (!preg_match('/[A-Za-z]+$/', $last_name)){
      $errors['lnam'] = 'Only alphabet and spacing allow <br>';
    }
  }

  //check phone number
  if(empty($_POST['phone'])){
    $errors['phone'] = 'Required phone number <br>';
  }else{
    $phoneNum = $_POST['phone'];
    if (!preg_match('/\d{10,11}/', $phoneNum)){
      $errors['phone'] = 'Invalid phone number <br>';
    }
  }

  //check email
  if(empty($_POST['email'])){
    $errors['email'] = 'Required email <br>';
  }else{
    $email = $_POST['email'];
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
      $errors['email'] = 'Invalid email <br>';
    }
  }

  //check town
  if(empty($_POST['home'])){
    $errors['home'] = 'Required home town <br>';
  }else{
    $town = $_POST['home'];
  }

  //check DOB
  if(empty($_POST['dob'])){
    $errors['dob'] = 'Required DOB <br>';
  }else{
    $dob = $_POST['dob'];
  }

  //check password
  if(empty($_POST['pwd'])){
    $errors['pwd'] = 'Required password <br>';
  }else{
    $pwd = $_POST['pwd'];
    if (strlen($pwd) < 8){
      $errors['pwd'] = 'Required at least 8 characters <br>';
    } elseif(!preg_match("/[0-9]/", $pwd)){
      $errors['pwd'] = 'Required at lease 1 number <br>';
    } elseif (!preg_match("/[^\w]/", $pwd)) {
      $errors['pwd'] = 'Required at least 1 symbol <br>';
    }
  }

  //check comform password
  if(empty($_POST['cpwd'])){
    $errors['cpwd'] = 'Please comfirm your password <br>'; 
  }else{
    $cpwd = $_POST['cpwd'];
    if($pwd != $cpwd){
      $errors['cpwd'] = 'Password not the same <br>';
    }
  }

  //check gender
  if(empty($_POST['gender'])){
    $errors['gender'] = 'Choose gender';
  }else{
    $gender = $_POST['gender'];
  }

  //check if  email already exists
  $account = "SELECT * FROM user_table WHERE email='$email'";
  $result1 = $conn->query($account);

  if($result1->num_rows > 0){
    $errors['exist'] = 'There is an existing account';
  }elseif (!array_filter($errors)){

    $first_name = mysqli_real_escape_string($conn, $_POST['fnam']);
    $last_name = mysqli_real_escape_string($conn, $_POST['lnam']);
    $phoneNum = mysqli_real_escape_string($conn, $_POST['phone']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $town = mysqli_real_escape_string($conn, $_POST['home']);
    $password_hashed = mysqli_real_escape_string($conn, password_hash($_POST['pwd'], PASSWORD_BCRYPT));
    $cpwd = mysqli_real_escape_string($conn, $_POST['cpwd']);
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);

    $type = 'user';

    $sql1 = "INSERT INTO user_table(first_name, last_name, contact_number, email, hometown, dob, gender, profile_image) VALUES('$first_name', '$last_name', '$phoneNum', '$email', '$town', '$dob', '$gender', NULL)";

    $sql2 = "INSERT INTO account_table(email, password, type) VALUES ('$email', '$password_hashed', '$type')";

    // Save the user data
    $file = "data/user.txt";
      if (!is_dir('data')) {
        mkdir('data', 0777, true);
    }

    $file = fopen("data/user.txt", "a+");
    $user_data = "First Name:$first_name|Last Name:$last_name|DOB:$dob|Phone:$phoneNum|Gender:$gender|Email:$email|Hometown:$town|Password:$pwd\n";
    fwrite($file, $user_data);
    fclose($file);

    if (mysqli_query($conn, $sql1) && mysqli_query($conn, $sql2)){
      header('location: manage_accounts.php');
      exit();
    }else{
      echo 'query error:' . mysqli_error($conn);
    }
  }
}

mysqli_close($conn);

?>
<!DOCTYPE html>
<!--Description: Admin Registration page-->
<!--Author: Liew You Wee-->
<!--Date: 03/11/2024 -->
<!--Validated: OK-->
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="description" content="Admin Registration Page">
	<meta name="keywords" content="Name, password">
	<meta name="author" content="Liew You Wee">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Herbarium for Plant Biodiversity</title>
  <link rel="stylesheet" type="text/css" href="style/style.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body class="r1">
    <div class="container">
        <div class="form-box">
            <h2>Add New Account</h2>
            <hr>
            <form action="account_new.php" method="POST">
                
                <div class="input-group">
                    <label for="fnam">First Name</label>
                    <input type="text" id="fnam" name="fnam" placeholder="Enter your first name" value="<?php echo htmlspecialchars($first_name)?>">
                    <div class="red-text"><?php echo $errors['fnam']; ?></div>
                </div>

                
                <div class="input-group">
                    <label for="lnam">Last name</label>
                    <input type="text" id="lnam" name="lnam" placeholder="Enter your last name" value="<?php echo htmlspecialchars($last_name)?>">
                    <div class="red-text"><?php echo $errors['lnam']; ?></div>
                </div>

                
                <div class="input-group">
                    <label for="email">Email</label>
                    <input type="text" id="email" name="email" placeholder="Enter your email" value="<?php echo htmlspecialchars($email)?>">
                    <div class="red-text"><?php echo $errors['email']; ?></div>
                </div>

                
                <div class="input-group">
                    <label for="phone">Phone Number</label>
                    <input type="text" id="phone" name="phone" placeholder="Enter your number" value="<?php echo htmlspecialchars($phoneNum)?>">
                    <div class="red-text"><?php echo $errors['phone']; ?></div>
                </div>

                <div class="input-group">
                  <label for="home">Home Town</label>
                  <input type="text" id="home" name="home" placeholder="Enter your home town" value="<?php echo htmlspecialchars($town)?>">
                  <div class="red-text"><?php echo $errors['home']; ?></div>
                </div>

                <div class="input-group">
                  <label for="dob">Date of Birth</label>
                  <input type="date" id="dob" name="dob" placeholder="Enter your date of birth" value="<?php echo htmlspecialchars($dob)?>">
                  <div class="red-text"><?php echo $errors['dob']; ?></div>
               </div>

                <div class="input-group">
                    <label for="pwd">Password</label>
                    <input type="text" id="pwd" name="pwd" placeholder="Enter your password" value="<?php echo htmlspecialchars($pwd)?>">
                    <div class="red-text"><?php echo $errors['pwd']; ?></div>
                </div>

                
                <div class="input-group">
                    <label for="cpwd">Confirm Password</label>
                    <input type="text" id="cpwd" name="cpwd" placeholder="Confirm your password">
                    <div class="red-text"><?php echo $errors['cpwd']; ?></div>
                </div>

                
                <div class="input-group">
                    <label>Gender</label>
                    <div class="radio-group">
                      <div class="input-group">
                        <input type="radio" id="male" name="gender" value="Male">
                        <label for="male">Male</label>
                      </div>
                        
                      <div class="input-group">
                        <input type="radio" id="female" name="gender" value="Female">
                        <label for="female">Female</label>
                      </div>
                        
                      <div class="input-group">
                        <input type="radio" id="other" name="gender" value="Other" checked>
                        <label for="other">Other</label>
                      </div>
                        
                    </div>
                </div>

                <div class="btn-group">
                  <button type="reset" name="submitbtn" class="btn" value="reset">Reset</button>
                  <button type="submit" name="submitbtn" class="btn" value="register">Register</button>
                  <button class="btn" name="back">Back</button>
                </div>

                <div class="red-texts"><?php echo $errors['exist']; ?></div>
                
            </form>
        </div>
    </div>
</body>
</html>
