<?php
session_set_cookie_params(0);
session_start();
include('config/main.php');
require 'vendor/autoload.php';
require('fpdf/fpdf.php');

use Smalot\PdfParser\Parser;

// Get plant ID from the URL
$id = mysqli_real_escape_string($conn, $_GET['id']);

// Fetch plant data from the database
$sql = "SELECT * FROM plant_table WHERE id = '$id'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    $plant = mysqli_fetch_assoc($result);
    // Determine which term has data
    if (!empty($plant['family'])) {
        $term = 'Family';
    } elseif (!empty($plant['genus'])) {
        $term = 'Genus';
    } elseif (!empty($plant['species'])) {
        $term = 'Species';
    } else {
        $term = '';
    }

    // Convert PDF description to text
    $descriptionText = '';
    if (!empty($plant['description'])) {
        $descriptionFilePath = $plant['description'];
        if (file_exists($descriptionFilePath)) {
            try {
                $parser = new Parser();
                $pdf = $parser->parseFile($descriptionFilePath);
                $descriptionText = $pdf->getText();
            } catch (Exception $e) {
                $descriptionText = "Error reading the PDF file: " . $e->getMessage();
            }
        } else {
            $descriptionText = "Description file not found.";
        }
    }
} else {
    echo "Plant not found.";
    exit();
}

$errors = [];
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and validate form inputs
    $scientific_name = trim($_POST['scientific_name']);
    $common_name = trim($_POST['common_name']);
    $term = $_POST['term'] ?? '';
    $descriptionText = trim($_POST['description']);
    $plants_image = $plant['plants_image']; // Preserve current image unless updated

    // Validation for required fields
    if (empty($scientific_name)) {
        $errors['scientific_name'] = 'Scientific name is required.';
    }
    if (empty($common_name)) {
        $errors['common_name'] = 'Common name is required.';
    }
    if (empty($term)) {
        $errors['term'] = 'Term is required.';
    }
    if (empty($descriptionText)) {
        $errors['description'] = 'Description is required.';
    }

    // Handle file upload (optional update)
    if (isset($_FILES['plants_image']) && $_FILES['plants_image']['error'] == 0) {
        $target_dir = "img/Contribute/";
        $photo = basename($_FILES['plants_image']['name']);
        $target_file = $target_dir . $photo;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $allowed_types = ["jpg", "jpeg", "png", "gif"];

        if (!in_array($imageFileType, $allowed_types)) {
            $errors['plants_image'] = 'Only JPG, JPEG, PNG, and GIF files are allowed.';
        } elseif ($_FILES['plants_image']['size'] > 5 * 1024 * 1024) {
            $errors['plants_image'] = 'File size exceeds 5MB limit.';
        } elseif (!move_uploaded_file($_FILES['plants_image']['tmp_name'], $target_file)) {
            $errors['plants_image'] = 'Failed to upload the image.';
        } else {
            $plants_image = $photo; // Use the new uploaded image
        }
    }

    // If no errors, update the plant record
    if (empty($errors)) {
        // Clear previous term values and set the selected term
        $family = $genus = $species = '';
        if ($term == 'Family') {
            $family = $scientific_name;
        } elseif ($term == 'Genus') {
            $genus = $scientific_name;
        } elseif ($term == 'Species') {
            $species = $scientific_name;
        }

        // Create a new PDF from the updated description using FPDF
        if (!empty($plant['description'])) {
            $descriptionFilePath = $plant['description'];

            $pdf = new FPDF();
            $pdf->AddPage();
            $pdf->SetFont('Arial', '', 12);

            // Handle line breaks in descriptionText
            $descriptionLines = explode("\n", $descriptionText);
            foreach ($descriptionLines as $line) {
                $pdf->MultiCell(0, 10, $line);
            }

            $pdf->Output('F', $descriptionFilePath);
        }

        $update_sql = "UPDATE plant_table SET 
            scientific_name = '" . mysqli_real_escape_string($conn, $scientific_name) . "', 
            common_name = '" . mysqli_real_escape_string($conn, $common_name) . "', 
            family = '" . mysqli_real_escape_string($conn, $family) . "', 
            genus = '" . mysqli_real_escape_string($conn, $genus) . "', 
            species = '" . mysqli_real_escape_string($conn, $species) . "', 
            plants_image = '" . mysqli_real_escape_string($conn, $plants_image) . "' 
            WHERE id = '$id'";

        if (mysqli_query($conn, $update_sql)) {
            header("Location: manage_plants.php");
            exit();
        } else {
            echo "Error updating plant: " . mysqli_error($conn);
        }
    }
}

mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="description" content="Edit Plant Page">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Plant</title>
    <link rel="stylesheet" href="style/style.css">
</head>
<body class="r1">
    <div class="container">
        <div class="form-box">
            <h1>Edit Plant: <?php echo htmlspecialchars($plant['scientific_name']); ?></h1>
            <form action="plant_edit.php?id=<?php echo htmlspecialchars($plant['id']); ?>" method="POST" enctype="multipart/form-data">
                <div class="input-group">
                    <label for="scientific_name">Scientific Name:</label>
                    <input type="text" name="scientific_name" id="scientific_name" value="<?php echo htmlspecialchars($plant['scientific_name']); ?>" required>
                    <div class="red-text"><?php echo $errors['scientific_name'] ?? ''; ?></div>
                </div>
                <div class="input-group">
                    <label for="common_name">Common Name:</label>
                    <input type="text" name="common_name" id="common_name" value="<?php echo htmlspecialchars($plant['common_name']); ?>" required>
                    <div class="red-text"><?php echo $errors['common_name'] ?? ''; ?></div>
                </div>
                <div class="input-group">
                    <label for="term">The Term:</label>
                    <select name="term" id="term">
                        <option value="Family" <?php if ($term == 'Family') echo 'selected'; ?>>Family</option>
                        <option value="Genus" <?php if ($term == 'Genus') echo 'selected'; ?>>Genus</option>
                        <option value="Species" <?php if ($term == 'Species') echo 'selected'; ?>>Species</option>
                    </select>
                    <div class="red-text"><?php echo $errors['term'] ?? ''; ?></div>
                </div>
                
                <div class="input-group">
                    <label for="plants_image">Plant Image:</label>
                    <input type="file" name="plants_image" id="plants_image">
                    <div class="red-text"><?php echo $errors['plants_image'] ?? ''; ?></div>
                </div>
                <div class="input-group">
                    <label for="description">Description:</label>
                    <input type="text" name="description" id="description" value="<?php echo htmlspecialchars($descriptionText); ?>" >
                    <div class="red-text"><?php echo $errors['description'] ?? ''; ?></div>
                </div>
                <div class="btn-group">
                    <button type="submit" class="btn">Update</button>
                    <a href="manage_plants.php" class="btn">Back</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
