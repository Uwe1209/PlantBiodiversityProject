<?php include("process_registration.php")?>
<!DOCTYPE html>
<!--Description: Registration page-->
<!--Author: Liew You Wee-->
<!--Date: 09/09/2024 -->
<!--Validated: OK 13/10/2024-->
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="description" content="Registration Page">
	<meta name="keywords" content="Name, password">
	<meta name="author" content="Liew You Wee">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Herbarium for Plant Biodiversity</title>
  <link rel="stylesheet" type="text/css" href="style/style.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body class="r0">
    <div class="container">
        <div class="form-box">
            <h2>Registration</h2>
            <hr>
            <form action="registration.php" method="POST">
                
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
                  <button class="btn"><a href="login.php">Back to login</a></button> 
                </div>

                <div class="red-texts"><?php echo $errors['exist']; ?></div>
                
            </form>
        </div>
    </div>
</body>
</html>
