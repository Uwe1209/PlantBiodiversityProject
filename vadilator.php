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

//check plant name
if(empty($_POST['pname'])){
  $errors['pname'] = 'Required plant name <br>';
}else{
  $plant_name = $_POST['pname'];
}

//check date
if(empty($_POST['date'])){
  $errors['date'] = 'Required date <br>';
}else{
  $date = $_POST['date'];
}

//check description
if(empty($_POST['des'])){
  $errors['des'] = 'Required description <br>';
}else{
  $description = $_POST['des'];
}

// Handle image file upload
if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
  $photo = $_FILES['file']['name'];
  $target_dir = "img/ProfileImg/";
  $target_file = $target_dir . basename($photo);
  $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
  
  $file_size_limit = 5 * 1024 * 1024;
  // Check file type
  $valid_extensions = array("jpg", "jpeg", "png", "gif");
  if (in_array($imageFileType, $valid_extensions)) {
    
      if($_FILES['file']['size'] <= $file_size_limit){
          if (move_uploaded_file($_FILES['file']['tmp_name'], $target_file)) {
              echo "File uploaded successfully.";
          }else {
              $errors['file'] = 'Failed to upload image.';
          }
      }else {
          $errors['file'] = 'File size exceeds the 5MB limit.';
      }
    
  }else{
      $errors['file'] = 'Only JPG, JPEG, PNG, and GIF files are allowed.';
  }
}

