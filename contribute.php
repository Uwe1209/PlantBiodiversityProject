<!DOCTYPE html>
<!--Description: Contribute page-->
<!--Author: Liew You Wee-->
<!--Date: 26/09/2024 -->
<!--Validated: OK 13/10/2024-->
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="description" content="Contribution Page">
  <meta name="keywords" content="Plant Biodiversity">
  <meta name="author" content="Liew You Wee">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Herbarium for Plant Biodiversity</title>
  <link rel="stylesheet" type="text/css" href="style/style.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body class="n">

<?php
session_set_cookie_params(0);
session_start();
include('config/main.php');

// Create directories if they don't exist
if (!is_dir('plants_description')) {
    mkdir('plants_description', 0777, true);
}
if (!is_dir('img/Contribute')) {
    mkdir('img/Contribute', 0777, true);
}

if (!isset($_SESSION['user_email'])) {
    echo '<div class="center-container">';
    echo "<h1>Please log in first.</h1>";
    echo '<a href="login.php"><button class="btn">Log In</button></a>';
    echo '</div>';   
    exit();
} else {
    $email = $_SESSION['user_email'];
    $sql = "SELECT user_table.*, account_table.type FROM user_table JOIN account_table ON user_table.email = account_table.email WHERE user_table.email='$email'";
    $result = mysqli_query($conn, $sql);
    $account = mysqli_fetch_assoc($result);

    if ($account['type'] != "user") {
        echo '<div class="center-container">';
        echo "<h1>Access denied. You are not authorized to view this page.</h1>";
        echo '<a href="login.php"><button class="btn">Log In</button></a>';
        echo '</div>';
        exit();
    }

    $scientific_name = $common_name = $term = $photo = $description_file_path = '';
    $errors = array('sname'=>'', 'cname'=>'', 'term'=>'', 'file'=>'', 'desc_file'=>'');
    $upload_success = false;

    if(isset($_POST['submit'])){
        // Input validation
        if (empty($_POST['sname'])) {
            $errors['sname'] = 'Scientific name is required';
        } else {
            $scientific_name = mysqli_real_escape_string($conn, $_POST['sname']);
        }

        if (empty($_POST['cname'])) {
            $errors['cname'] = 'Common name is required';
        } else {
            $common_name = mysqli_real_escape_string($conn, $_POST['cname']);
        }

        $term = mysqli_real_escape_string($conn, $_POST['term']);

        if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
            $target_dir = "img/Contribute/";
            $photo = basename($_FILES['file']['name']);
            $target_file = $target_dir . $photo;
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
            $allowed_types = array("jpg", "jpeg", "png", "gif");

            if (!in_array($imageFileType, $allowed_types)) {
                $errors['file'] = 'Only JPG, JPEG, PNG, and GIF files are allowed.';
            } elseif ($_FILES['file']['size'] > 5 * 1024 * 1024) {
                $errors['file'] = 'File size exceeds 5MB limit.';
            } elseif (!move_uploaded_file($_FILES['file']['tmp_name'], $target_file)) {
                $errors['file'] = 'Failed to upload image.';
            }
        } else {
            $errors['file'] = 'Photo is required.';
        }

        // Handle PDF file upload
        if (isset($_FILES['desc_file']) && $_FILES['desc_file']['error'] == 0) {
            $desc_dir = "plants_description/";
            $desc_file = basename($_FILES['desc_file']['name']);
            $desc_target_file = $desc_dir . $desc_file;
            $descFileType = strtolower(pathinfo($desc_target_file, PATHINFO_EXTENSION));

            if ($descFileType !== "pdf") {
                $errors['desc_file'] = 'Only PDF files are allowed.';
            } elseif ($_FILES['desc_file']['size'] > 7 * 1024 * 1024) {
                $errors['desc_file'] = 'File size exceeds 7MB limit.';
            } elseif (!move_uploaded_file($_FILES['desc_file']['tmp_name'], $desc_target_file)) {
                $errors['desc_file'] = 'Failed to upload PDF file.';
            } else {
                $description_file_path = $desc_target_file; 
            }
        } else {
            $errors['desc_file'] = 'PDF description file is required.';
        }

        if (!array_filter($errors)) {
            $family = $genus = $species = '';
            if ($term == 'Family') {
                $family = 'family';
            } elseif ($term == 'Genus') {
                $genus = 'genus';
            } elseif ($term == 'Species') {
                $species = 'species';
            }

            $sql1 = "INSERT INTO plant_table(scientific_name, common_name, plants_image, family, genus, species, description) 
                     VALUES('$scientific_name', '$common_name', '$photo', '$family', '$genus', '$species', '$description_file_path')";


            if (mysqli_query($conn, $sql1)){
                $upload_success = true; 
            } else {
                echo 'Query error: ' . mysqli_error($conn);
            }
        }
    }

    $sql2 = "SELECT * FROM plant_table";
    $result = mysqli_query($conn, $sql2);
    $contribute = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_free_result($result);
?>

<div class="contribution-con-h">
    <?php include("navigation.php"); ?>
    <div class="contribution-img">
      <div class="contribution-content">
        <p>Contribute</p>
        <h1>Contribute to the Herbarium</h1>
        <p>Share your plant data and help build our biodiversity database.</p>
      </div>
    </div>
</div>

<?php if ($upload_success): ?>
    <div class="center-container">
        <h2>Upload successful! Thank you for your contribution.</h2>
    </div>
<?php endif; ?>

<div>
    <div class="card-con">
        <?php foreach($contribute as $cont) { 
            if($cont['approve'] == "approve"){?>
          <div class="card-row">
            <a href="plant_detail.php?id=<?php echo htmlspecialchars($cont['id']) ?>">
              <div>
                <img src="img/Contribute/<?php echo htmlspecialchars($cont['plants_image']) ?>" alt="contribution">
              </div>
            </a>
            <div class="card-name">
              <p><?php echo htmlspecialchars($cont['scientific_name']) ?></p>
            </div>
          </div>
        <?php }} ?>
    </div>
</div>

<div class="contribution_form">
    <form class="ctb_form" action="contribute.php" method="POST" enctype="multipart/form-data">
        <div class="input-group">
            <label for="sname">Scientific Name</label>
            <input type="text" name="sname" id="sname" value="<?php echo htmlspecialchars($scientific_name) ?>">
            <div class="red-text"><?php echo $errors['sname']; ?></div>
        </div>

        <div class="input-group">
            <label for="cname">Common Name</label>
            <input type="text" name="cname" id="cname" value="<?php echo htmlspecialchars($common_name) ?>">
            <div class="red-text"><?php echo $errors['cname']; ?></div>
        </div>

        <div class="input-group">
            <label>The Term</label>
            <select name="term">
                <option value="Family" <?php if($term == 'Family') echo 'selected'; ?>>Family</option>
                <option value="Genus" <?php if($term == 'Genus') echo 'selected'; ?>>Genus</option>
                <option value="Species" <?php if($term == 'Species') echo 'selected'; ?>>Species</option>
            </select>
        </div>

        <div class="input-group">
            <label>Photo</label>
            <input type="file" name="file">
            <div class="red-text"><?php echo $errors['file']; ?></div>
        </div>

        <div class="input-group">
            <label>PDF Description File</label>
            <input type="file" name="desc_file">
            <div class="red-text"><?php echo $errors['desc_file']; ?></div>
        </div>

        <div class="btn-group">
            <button class="btn" name="submit">Submit</button>
        </div>
    </form>
</div>

<?php include("footer.php"); ?>
</body>
<?php } ?>
</html>
