<?php
session_set_cookie_params(0);
session_start();
include('config/main.php');

$first_name = $last_name = $phoneNum = $email = $town = $dob = $pwd = '';
$errors = array('fnam'=>'', 'lnam'=>'', 'email'=>'','phone'=>'', 'home'=>'', 'dob'=>'', 'pwd'=>'', 'cpwd'=>'', 'exist' => '');

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
      $_SESSION['user_email'] = $email;
      header('location: login.php');
      exit();
    }else{
      echo 'query error:' . mysqli_error($conn);
    }
  }
}

mysqli_close($conn);

?>